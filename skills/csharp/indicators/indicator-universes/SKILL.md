---
name: indicator-universes
description: Use when selecting a QuantConnect/LEAN universe based on per-symbol indicators. Triggers — code uses `AddUniverse(...)` with a selection callback that builds per-symbol `SimpleMovingAverage`/`ExponentialMovingAverage`/`BollingerBands`/`RSI`/`ATR` etc., often via a `SelectionData` class kept in a per-symbol dict; questions like "rank stocks by 21-day SMA", "top N most volatile crypto pairs", "fundamentals universe with momentum filter", "why does my SMA spike around splits/dividends", "why does my universe shrink during warm-up", "how do I avoid history calls in universe selection". Skip when — universe doesn't need per-symbol indicators (use plain fundamentals/ETF/CryptoUniverse selection).
---

# Indicator-Based Universe Selection in QuantConnect / LEAN

The pattern: stream the universe's daily data through one indicator instance per symbol, then filter or rank the universe by the indicator's value. Wrap the per-symbol state in a `SelectionData` class kept in a per-symbol dict on the algorithm.

## Basic pattern (Equity / Fundamentals)

US Equities only support **data-point indicators** for universe selection (SMA, EMA, RSI, StandardDeviation, BollingerBands, etc. — anything that updates from `(time, value)`), not bar indicators (ATR, candle patterns).

```csharp
public class EquityIndicatorUniverseSelectionAlgorithm : QCAlgorithm
{
    private readonly Dictionary<Symbol, SelectionData> _selectionDataBySymbol = new();
    private Universe _universe;

    public override void Initialize()
    {
        Settings.SeedInitialPrices = true;
        _universe = AddUniverse(SelectAssets);
        SetWarmUp(21, Resolution.Daily);
    }

    private IEnumerable<Symbol> SelectAssets(IEnumerable<Fundamental> fundamentals)
    {
        // Materialize once so we can iterate the input twice below.
        var fundamentalsList = fundamentals.ToList();

        // 1. Update each symbol's indicator and keep only those that are ready.
        var readyStocks = new List<Fundamental>();
        foreach (var f in fundamentalsList)
        {
            if (!_selectionDataBySymbol.TryGetValue(f.Symbol, out var sd))
            {
                sd = new SelectionData(this, f);
                _selectionDataBySymbol[f.Symbol] = sd;
            }
            if (sd.Update(f))
            {
                readyStocks.Add(f);
            }
        }
        // 2. Drop SelectionData for symbols no longer in the dataset.
        //    Snapshot the keys with .ToList() — can't modify the dict while enumerating it.
        var activeSymbols = fundamentalsList.Select(f => f.Symbol).ToHashSet();
        foreach (var symbol in _selectionDataBySymbol.Keys.Where(s => !activeSymbols.Contains(s)).ToList())
        {
            _selectionDataBySymbol.Remove(symbol);
        }
        // 3. Skip selection during warm-up — indicators aren't ready yet.
        if (IsWarmingUp)
        {
            return Enumerable.Empty<Symbol>();
        }
        // 4. Filter / rank by the indicator value.
        return readyStocks
            .Select(f => (f.Symbol, Factor: f.Price / _selectionDataBySymbol[f.Symbol].Indicator.Current.Value))
            .Where(t => t.Factor > 0)
            .OrderBy(t => t.Factor)
            .TakeLast(100)
            .Select(t => t.Symbol);
    }
}

public class SelectionData
{
    private readonly QCAlgorithm _algorithm;
    private decimal _priceScaleFactor;
    public SimpleMovingAverage Indicator { get; }

    public SelectionData(QCAlgorithm algorithm, Fundamental f)
    {
        _algorithm = algorithm;
        _priceScaleFactor = f.PriceScaleFactor;
        Indicator = new SimpleMovingAverage(21);
    }

    public bool Update(Fundamental f)
    {
        // No split/dividend since last bar — normal update.
        if (f.PriceScaleFactor == _priceScaleFactor)
        {
            return Indicator.Update(f.EndTime, f.Price);
        }
        // Otherwise, history is invalidated — reset and rewarm with adjusted history.
        _priceScaleFactor = f.PriceScaleFactor;
        Indicator.Reset();
        var history = _algorithm.History<TradeBar>(
            f.Symbol,
            Indicator.WarmUpPeriod,
            Resolution.Daily,
            dataNormalizationMode: DataNormalizationMode.ScaledRaw);
        foreach (var bar in history)
        {
            Indicator.Update(bar);
        }
        return Indicator.IsReady;
    }
}
```

## The four pieces, why each one matters

1. **One `SelectionData` per symbol, kept in a dict on the algorithm.** Indicators are stateful — you cannot recompute them from scratch each selection or you'll just be re-doing history calls. The dict lives across selection callbacks. Lazy-create the entry on first sight of a symbol (`TryGetValue` + assign idiom).
2. **Lifecycle cleanup.** Symbols leave the universe dataset (delisting, fundamentals coverage drop, exchange removal). Without removing entries for absent symbols, the dict grows unbounded over a long backtest.
3. **Warm-up.** `SetWarmUp(N, Resolution.Daily)` makes LEAN replay `N` daily bars into the universe selection callback before the live start, so the indicators are ready by the first real selection. **Set `N` to the longest indicator's warm-up period in bars** — `SimpleMovingAverage(21)` needs `N = 21`. Use this bar-count overload (not `SetWarmUp(TimeSpan.FromDays(N))`): counting trading bars handles weekends and holidays without padding, and daily resolution is all the indicator needs since the universe feeds it daily bars at runtime. Return an empty list while `IsWarmingUp` so the universe stays empty (selection on un-ready indicators is meaningless and would also subscribe to assets you don't actually want yet).
4. **The per-symbol update method returns the indicator's readiness.** This is the filter for "do I have enough data to use this indicator now?" — pairing it with the comprehension / LINQ `Where` makes it one expression.

## Leave universe selection on its daily schedule

The indicator only sees one new bar per universe selection call. Default Fundamentals and CryptoUniverse selection runs **once per trading day** — that's what makes `SimpleMovingAverage(21)` actually mean "21-day SMA."

This collides with the usual cross-skill advice ("match the universe schedule to the rebalance schedule to avoid wasted work"). For indicator universes, **don't do that.** If you set `UniverseSettings.Schedule.On(DateRules.MonthStart())` to align with a monthly rebalance, the indicator only receives one bar per month, and `SimpleMovingAverage(21)` silently becomes "21-month SMA" — almost never what was intended. Rebalance on whatever cadence you want, but leave universe selection daily.

When can you legitimately slow it down?

- **Data-point indicators (SMA, EMA, RSI, …):** OK if the slower cadence _is_ the indicator semantic you want — e.g., a deliberate "21-week SMA of weekly closes," scheduled weekly and sized in weeks (`SimpleMovingAverage(21)` with a weekly schedule). Each close stands on its own, so sampling them at the slower cadence is coherent.
- **Crypto bar indicators (ATR, candle patterns, anything that needs OHLC):** never. The CryptoUniverse data point is always a daily bar regardless of how often selection runs. Sampling only Friday's daily bar at a weekly cadence throws away Mon–Thu's high/low/range — the resulting ATR isn't "weekly true range," it's "daily true range computed from one day per week," which is meaningless. Keep selection daily.

## Equity-only: handle splits and dividends

`Fundamental.Price` is the previous trading day's **raw** close — actual unadjusted trading price. (`Fundamental.AdjustedPrice` is the split- and dividend-adjusted version.) Streaming raw prices into an indicator works fine until a split or dividend, at which point the raw price drops abruptly — a 2-for-1 split halves it overnight; a dividend knocks it down by the dividend amount on ex-date. An SMA fed across that boundary mixes pre-action and post-action prices on different scales and produces a spurious dip.

`Fundamental.PriceScaleFactor` is the cumulative scaling that maps historical raw prices onto today's session. Cache it on the `SelectionData`; if it changes between bars, **reset the indicator and rewarm from `ScaledRaw` history** — historical raw prices scaled to today's session, so they compose coherently with the next `f.Price`. The example above is the canonical implementation — don't omit it.

This is Equity-specific. Crypto doesn't have corporate actions, so the price-scale-factor check doesn't apply.

## Crypto pattern

Same shape, different universe constructor and different bar fields:

```csharp
public class CryptoIndicatorUniverseSelectionAlgorithm : QCAlgorithm
{
    private readonly Dictionary<Symbol, SelectionData> _selectionDataBySymbol = new();
    private Universe _universe;

    public override void Initialize()
    {
        Settings.SeedInitialPrices = true;
        _universe = AddUniverse(CryptoUniverse.Coinbase(SelectAssets));
        SetWarmUp(30, Resolution.Daily);
    }

    private IEnumerable<Symbol> SelectAssets(IEnumerable<CryptoUniverse> data)
    {
        var dataList = data.ToList();
        var readyPairs = new List<CryptoUniverse>();
        foreach (var c in dataList)
        {
            if (!_selectionDataBySymbol.TryGetValue(c.Symbol, out var sd))
            {
                sd = new SelectionData();
                _selectionDataBySymbol[c.Symbol] = sd;
            }
            if (sd.Update(c))
            {
                readyPairs.Add(c);
            }
        }
        var activeSymbols = dataList.Select(c => c.Symbol).ToHashSet();
        foreach (var symbol in _selectionDataBySymbol.Keys.Where(s => !activeSymbols.Contains(s)).ToList())
        {
            _selectionDataBySymbol.Remove(symbol);
        }
        if (IsWarmingUp)
        {
            return Enumerable.Empty<Symbol>();
        }

        // Take the 50 highest-volume pairs, then the 10 most volatile of those (by ATR / close).
        var topVolume = readyPairs
            .OrderBy(c => _selectionDataBySymbol[c.Symbol].MeanDailyUsdVolume.Current.Value)
            .TakeLast(50);
        var topVolatility = topVolume
            .OrderBy(c => _selectionDataBySymbol[c.Symbol].Atr.Current.Value / c.Close)
            .TakeLast(10);
        return topVolatility.Select(c => c.Symbol);
    }
}

public class SelectionData
{
    public SimpleMovingAverage MeanDailyUsdVolume { get; }
    public AverageTrueRange Atr { get; }

    public SelectionData()
    {
        MeanDailyUsdVolume = new SimpleMovingAverage(30);
        Atr = new AverageTrueRange(14);
    }

    public bool Update(CryptoUniverse c)
    {
        // Use & (non-short-circuit, not &&) so both indicators advance every bar even
        // when the first isn't ready. The expression doubles as the joint readiness check.
        // ATR genuinely needs OHLC, so wrap the universe data point in a TradeBar.
        // VolumeInUsd is double; cast to decimal for both the SMA update and the TradeBar ctor.
        return MeanDailyUsdVolume.Update(c.EndTime, (decimal)c.VolumeInUsd)
             & Atr.Update(new TradeBar(c.EndTime, c.Symbol, c.Open, c.High, c.Low, c.Close, (decimal)c.VolumeInUsd));
    }
}
```

Notes specific to Crypto universe data:

- `CryptoUniverse.Coinbase`, `CryptoUniverse.Binance`, etc. — pick the exchange that matches the brokerage you're modeling.
- The data point has `open`, `high`, `low`, `close`, `volume`, and `VolumeInUsd` (volume × close in USD). For a USD-volume filter use the USD volume, not the base-asset `volume`.
- For bar indicators (ATR, candle patterns — anything that genuinely needs OHLC, _not_ BollingerBands or other indicators that compute on closes), construct a `TradeBar` from the universe data point — the indicator API takes `IBaseDataBar`, not the raw universe selection type.
- Use the non-short-circuit operator `&` (not `&&`) between two update calls when you need both to advance every bar regardless of readiness. The short-circuit form silently desyncs the indicators. Note this is specifically about update calls (which have the side effect of advancing the indicator). Plain readiness checks like `ind1.IsReady && ind2.IsReady` are pure boolean reads with no side effect — short-circuit is correct there, and `&` adds nothing.

## Rebalancing

These universes are almost always paired with a `Schedule.On(...)` rebalance. **If your implementation includes one, load the `scheduled-events` skill before writing it** — the rules for picking a fire time that doesn't land inside a data bar (and the 8AM ET convention for dynamic daily Equity universes) live there. Don't re-derive them.

## Common mistakes

- **Calling `History(...)` per symbol inside the selection callback.** The entire reason this pattern exists is to avoid computationally expensive history calls. The only legitimate history call is the post-split rewarm in the Equity `SelectionData` update, which fires once per affected symbol per corporate action.
- **Splitting update from readiness, then writing `update(...) & update(...)` followed by a `&`-joined readiness check.** Once the updates have run, the readiness check is a bool read with no side effect — short-circuit (`&&`) is correct there, and `&` is just noise. The "non-short-circuit" rule is for the side-effecting update calls only.
- **Putting the selection logic on intraday data.** Universe selection callbacks usually fire once per day. The indicators are seeing daily bars regardless of `UniverseSettings.Resolution` — `SimpleMovingAverage(21)` means 21 days, not 21 minutes.
