---
name: indicator-universe
description: Use when selecting a QuantConnect/LEAN universe based on per-symbol indicators. Triggers — code uses py`add_universe(...)`cs`AddUniverse(...)` with a selection callback that builds per-symbol `SimpleMovingAverage`/`ExponentialMovingAverage`/`BollingerBands`/`RSI`/`ATR` etc., often via a `SelectionData` class kept in a per-symbol dict; questions like "rank stocks by 21-day SMA", "top N most volatile crypto pairs", "fundamentals universe with momentum filter", "why does my SMA spike around splits/dividends", "why does my universe shrink during warm-up", "how do I avoid history calls in universe selection". Skip when — universe doesn't need per-symbol indicators (use plain fundamentals/ETF/CryptoUniverse selection).
---

# Indicator-Based Universe Selection in QuantConnect / LEAN

The pattern: stream the universe's daily data through one indicator instance per symbol, then filter or rank the universe by the indicator's value. Wrap the per-symbol state in a `SelectionData` class kept in a per-symbol dict on the algorithm.

**Do not call py`self.history(...)`cs`History(...)` inside the universe selection callback to compute indicators.** It runs every selection step for every symbol — for an 8000-symbol Equity fundamentals universe that's thousands of history requests per trading day. Stream the data through the indicator instead; that's what the universe data is for.

## Basic pattern (Equity / Fundamentals)

US Equities only support **data-point indicators** for universe selection (SMA, EMA, RSI, StandardDeviation, BollingerBands, etc. — anything that updates from `(time, value)`), not bar indicators (ATR, candle patterns).

```python
class EquityIndicatorUniverseSelectionAlgorithm(QCAlgorithm):

    def initialize(self):
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.settings.seed_initial_prices = True
        self._selection_data_by_symbol = {}
        self._universe = self.add_universe(self._select_assets)
        # Warm-up must cover the indicator's warm-up period (in trading days).
        self.set_warm_up(timedelta(60))

    def _select_assets(self, fundamentals):
        # 1. Update each symbol's indicator and keep only those that are ready.
        ready_stocks = [
            f for f in fundamentals
            if self._selection_data_by_symbol.setdefault(f.symbol, SelectionData(self, f)).update(f)
        ]
        # 2. Drop SelectionData for symbols no longer in the dataset.
        for symbol in self._selection_data_by_symbol.keys() - {f.symbol for f in fundamentals}:
            del self._selection_data_by_symbol[symbol]
        # 3. Skip selection during warm-up — indicators aren't ready yet.
        if self.is_warming_up:
            return []
        # 4. Filter / rank by the indicator value.
        factor_by_symbol = {
            f.symbol: f.price / self._selection_data_by_symbol[f.symbol].indicator.current.value
            for f in ready_stocks
        }
        return sorted(
            {k: v for k, v in factor_by_symbol.items() if v > 0},
            key=lambda symbol: factor_by_symbol[symbol]
        )[-100:]


class SelectionData:

    def __init__(self, algorithm, f):
        self._algorithm = algorithm
        self._price_scale_factor = f.price_scale_factor
        self.indicator = SimpleMovingAverage(21)

    def update(self, f):
        # No split/dividend since last bar — normal update.
        if f.price_scale_factor == self._price_scale_factor:
            return self.indicator.update(f.end_time, f.price)
        # Otherwise, history is invalidated — reset and rewarm with adjusted history.
        self._price_scale_factor = f.price_scale_factor
        self.indicator.reset()
        history = self._algorithm.history[TradeBar](
            f.symbol,
            self.indicator.warm_up_period,
            Resolution.DAILY,
            data_normalization_mode=DataNormalizationMode.SCALED_RAW
        )
        for bar in history:
            self.indicator.update(bar)
        return self.indicator.is_ready
```

```csharp
public class EquityIndicatorUniverseSelectionAlgorithm : QCAlgorithm
{
    private readonly Dictionary<Symbol, SelectionData> _selectionDataBySymbol = new();
    private Universe _universe;

    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 12, 31);
        Settings.SeedInitialPrices = true;
        _universe = AddUniverse(SelectAssets);
        // Warm-up must cover the indicator's warm-up period (in trading days).
        SetWarmUp(TimeSpan.FromDays(60));
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

1. **One `SelectionData` per symbol, kept in a dict on the algorithm.** Indicators are stateful — you cannot recompute them from scratch each selection or you'll just be re-doing history calls. The dict lives across selection callbacks. Lazy-create the entry on first sight of a symbol (py`setdefault(...)`cs`TryGetValue` + assign idiom).
2. **Lifecycle cleanup.** Symbols leave the universe dataset (delisting, fundamentals coverage drop, exchange removal). Without removing entries for absent symbols, the dict grows unbounded over a long backtest.
3. **Warm-up.** py`set_warm_up(timedelta(N))`cs`SetWarmUp(TimeSpan.FromDays(N))` makes LEAN replay `N` calendar days into the universe selection callback before the live start. That's how the indicators get fed enough bars to be ready by the first real selection. **`N` must be ≥ the indicator's warm-up period in trading days, with slack for weekends/holidays** — for a 21-day SMA, 60 calendar days is comfortable. Return an empty list while py`self.is_warming_up`cs`IsWarmingUp` so the universe stays empty (selection on un-ready indicators is meaningless and would also subscribe to assets you don't actually want yet).
4. **The per-symbol update method returns the indicator's readiness.** This is the filter for "do I have enough data to use this indicator now?" — pairing it with the comprehension / LINQ `Where` makes it one expression.

## Leave universe selection on its daily schedule

The indicator only sees one new bar per universe selection call. Default Fundamentals and CryptoUniverse selection runs **once per trading day** — that's what makes `SimpleMovingAverage(21)` actually mean "21-day SMA."

This collides with the usual cross-skill advice ("match the universe schedule to the rebalance schedule to avoid wasted work"). For indicator universes, **don't do that.** If you set py`self.universe_settings.schedule.on(self.date_rules.month_start())`cs`UniverseSettings.Schedule.On(DateRules.MonthStart())` to align with a monthly rebalance, the indicator only receives one bar per month, and `SimpleMovingAverage(21)` silently becomes "21-month SMA" — almost never what was intended. Rebalance on whatever cadence you want, but leave universe selection daily.

When can you legitimately slow it down?

- **Data-point indicators (SMA, EMA, RSI, …):** OK if the slower cadence _is_ the indicator semantic you want — e.g., a deliberate "21-week SMA of weekly closes," scheduled weekly and sized in weeks (`SimpleMovingAverage(21)` with a weekly schedule). Each close stands on its own, so sampling them at the slower cadence is coherent.
- **Crypto bar indicators (ATR, candle patterns, anything that needs OHLC):** never. The CryptoUniverse data point is always a daily bar regardless of how often selection runs. Sampling only Friday's daily bar at a weekly cadence throws away Mon–Thu's high/low/range — the resulting ATR isn't "weekly true range," it's "daily true range computed from one day per week," which is meaningless. Keep selection daily.

## Equity-only: handle splits and dividends or your indicator is wrong

py`Fundamental.price`cs`Fundamental.Price` is the previous trading day's **raw** close — actual unadjusted trading price. (py`Fundamental.adjusted_price`cs`Fundamental.AdjustedPrice` is the split- and dividend-adjusted version.) Streaming raw prices into an indicator works fine until a split or dividend, at which point the raw price drops abruptly — a 2-for-1 split halves it overnight; a dividend knocks it down by the dividend amount on ex-date. An SMA fed across that boundary mixes pre-action and post-action prices on different scales and produces a spurious dip.

py`Fundamental.price_scale_factor`cs`Fundamental.PriceScaleFactor` is the cumulative scaling that maps historical raw prices onto today's session. Cache it on the `SelectionData`; if it changes between bars, **reset the indicator and rewarm from py`SCALED_RAW`cs`ScaledRaw` history** — historical raw prices scaled to today's session, so they compose coherently with the next py`f.price`cs`f.Price`. The example above is the canonical implementation — don't omit it.

This is Equity-specific. Crypto doesn't have corporate actions, so the price-scale-factor check doesn't apply.

## Crypto pattern

Same shape, different universe constructor and different bar fields:

```python
class CryptoIndicatorUniverseSelectionAlgorithm(QCAlgorithm):

    def initialize(self):
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.settings.seed_initial_prices = True
        self._selection_data_by_symbol = {}
        self._universe = self.add_universe(CryptoUniverse.coinbase(self._select_assets))
        self.set_warm_up(timedelta(60))

    def _select_assets(self, data):
        ready_pairs = [
            c for c in data
            if self._selection_data_by_symbol.setdefault(c.symbol, SelectionData()).update(c)
        ]
        for symbol in self._selection_data_by_symbol.keys() - {c.symbol for c in data}:
            del self._selection_data_by_symbol[symbol]
        if self.is_warming_up:
            return []
        # Take the 50 highest-volume pairs, then the 10 most volatile of those (by ATR / close).
        top_volume = sorted(ready_pairs, key=lambda c: self._selection_data_by_symbol[c.symbol].mean_daily_usd_volume.current.value)[-50:]
        top_volatility = sorted(top_volume, key=lambda c: self._selection_data_by_symbol[c.symbol].atr.current.value / c.close)[-10:]
        return [c.symbol for c in top_volatility]


class SelectionData:

    def __init__(self):
        self.mean_daily_usd_volume = SimpleMovingAverage(30)
        self.atr = AverageTrueRange(14)

    def update(self, c):
        # Use & (non-short-circuit) so both indicators advance every bar even when
        # the first isn't ready. The expression doubles as the joint readiness check.
        # ATR genuinely needs OHLC, so wrap the universe data point in a TradeBar.
        return (
            self.mean_daily_usd_volume.update(c.end_time, c.volume_in_usd) &
            self.atr.update(TradeBar(c.end_time, c.symbol, c.open, c.high, c.low, c.close, c.volume_in_usd))
        )
```

```csharp
public class CryptoIndicatorUniverseSelectionAlgorithm : QCAlgorithm
{
    private readonly Dictionary<Symbol, SelectionData> _selectionDataBySymbol = new();
    private Universe _universe;

    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 12, 31);
        Settings.SeedInitialPrices = true;
        _universe = AddUniverse(CryptoUniverse.Coinbase(SelectAssets));
        SetWarmUp(TimeSpan.FromDays(60));
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

- py`CryptoUniverse.coinbase`cs`CryptoUniverse.Coinbase`, py`CryptoUniverse.binance`cs`CryptoUniverse.Binance`, etc. — pick the exchange that matches the brokerage you're modeling.
- The data point has `open`, `high`, `low`, `close`, `volume`, and py`volume_in_usd`cs`VolumeInUsd` (volume × close in USD). For a USD-volume filter use the USD volume, not the base-asset `volume`.
- For bar indicators (ATR, candle patterns — anything that genuinely needs OHLC, _not_ BollingerBands or other indicators that compute on closes), construct a `TradeBar` from the universe data point — the indicator API takes `IBaseDataBar`, not the raw universe selection type.
- Use the non-short-circuit operator `&` (not py`and`cs`&&`) between two update calls when you need both to advance every bar regardless of readiness. The short-circuit form silently desyncs the indicators. Note this is specifically about update calls (which have the side effect of advancing the indicator). Plain readiness checks like py`ind1.is_ready and ind2.is_ready`cs`ind1.IsReady && ind2.IsReady` are pure boolean reads with no side effect — short-circuit is correct there, and `&` adds nothing.

## Rebalancing

These universes are almost always paired with a py`self.schedule.on(...)`cs`Schedule.On(...)` rebalance. **If your implementation includes one, load the `scheduled-events` skill before writing it** — the rules for picking a fire time that doesn't land inside a data bar (and the 8AM ET convention for dynamic daily Equity universes) live there. Don't re-derive them.

## Common mistakes

- **Calling py`self.history(...)`cs`History(...)` per symbol inside the selection callback.** Doesn't scale and is the entire reason this pattern exists. The only legitimate history call is the post-split rewarm in the Equity `SelectionData` update, which fires once per affected symbol per corporate action.
- **Forgetting to clean up `SelectionData` for departed symbols.** The dict grows forever. Use the set difference between the dict's keys and the symbols in the current data.
- **Returning a populated list during py`self.is_warming_up`cs`IsWarmingUp`.** Selection happens on un-ready indicators.
- **Warm-up shorter than the indicator's warm-up period.** py`set_warm_up(timedelta(N))`cs`SetWarmUp(TimeSpan.FromDays(N))` is **calendar days**, not trading days. For a 21-bar daily indicator, 21 days isn't enough for US Equities — weekends and holidays mean ~30 calendar days is the floor; pad to 1.5–2× the indicator length to be safe.
- **Ignoring py`price_scale_factor`cs`PriceScaleFactor` on Equity.** Raw py`f.price`cs`f.Price` drops abruptly through splits and dividends; the indicator's window straddles the boundary and produces a spurious value. Symptoms are subtle: rankings spike around heavily-dividend-paying or recently-split stocks.
- **Using a short-circuit operator (py`and`cs`&&`) instead of `&` between two update calls.** The short-circuit form means if the first indicator isn't ready, the second never updates that bar, and the two indicators become permanently out of sync. The canonical pattern is to _combine_ the side-effecting update with the joint readiness check in one expression: py`return ind1.update(...) & ind2.update(...)`cs`return ind1.Update(...) & ind2.Update(...)`. Each update returns the indicator's new readiness, so the `&`-joined expression doubles as the row's readiness.
- **Splitting update from readiness, then writing `update(...) & update(...)` followed by a `&`-joined readiness check.** Once the updates have run, the readiness check is just a bool read with no side effect — short-circuit (py`and`cs`&&`) is fine there, and `&` is just noise. Don't carry the "non-short-circuit" rule into pure boolean expressions.
- **Putting the selection logic on intraday data.** Universe selection callbacks usually fire once per day. The indicators are seeing daily bars regardless of py`universe_settings.resolution`cs`UniverseSettings.Resolution` — `SimpleMovingAverage(21)` means 21 days, not 21 minutes.
- **Trying to use a bar indicator on an Equity universe.** `Fundamental` has no OHLCV — only the previous day's close in py`f.price`cs`f.Price`. `ATR`, candle patterns, and other indicators that genuinely need high/low/open cannot be driven from `Fundamental`. (`BollingerBands` and similar that compute on closes are _data-point_ indicators — they're fine.) On Crypto, the data point does have OHLCV, so wrap it in a `TradeBar` before passing to a true bar indicator.

## Checklist when writing one of these

1. Is there a `SelectionData` class with the indicator(s) as instance attributes / properties?
2. Is there a per-symbol dict on the algorithm, with lazy-create on first sight of a symbol (py`setdefault`cs`TryGetValue` + assign)?
3. Is there a cleanup step removing entries for symbols no longer in the current data?
4. Does the per-symbol update method return the indicator's readiness so the comprehension / LINQ filter can keep only ready symbols?
5. Is py`set_warm_up(timedelta(N))`cs`SetWarmUp(TimeSpan.FromDays(N))` set, with `N` ≥ ~1.5× the longest indicator's warm-up period in calendar days?
6. Does the callback return an empty list while py`self.is_warming_up`cs`IsWarmingUp`?
7. **Equity only:** is py`price_scale_factor`cs`PriceScaleFactor` cached and checked, with reset + py`SCALED_RAW`cs`ScaledRaw` rewarm on change?
8. **Crypto only:** for bar indicators, is the universe data point being wrapped in a `TradeBar` before update? **Equity only:** are all indicators data-point indicators (no bar indicators)?
9. Between multiple update calls in the per-symbol update method, is the operator `&` (non-short-circuit), not py`and`cs`&&`? And conversely, are pure readiness reads using py`and`cs`&&`, not `&`?
10. Is universe selection running on its default daily schedule (no py`universe_settings.schedule.on(...)`cs`UniverseSettings.Schedule.On(...)` slowing it down)? The exception is a data-point indicator where the slower cadence _is_ the indicator semantic (e.g., a 21-week SMA on weekly closes). Crypto bar indicators always need the daily cadence — never slow them down.
