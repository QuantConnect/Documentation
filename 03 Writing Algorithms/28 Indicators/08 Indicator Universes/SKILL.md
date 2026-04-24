---
name: indicator-universe
description: Use when selecting a QuantConnect/LEAN universe based on per-symbol indicators (SMA, BollingerBands, RSI, ranking by indicator value, etc.) for US Equities (Fundamentals) or Crypto (CryptoUniverse). Covers the SelectionData class pattern, lifecycle cleanup, warm-up, and the Equity-specific split/dividend handling that quietly breaks indicators if ignored.
---

# Indicator-Based Universe Selection in QuantConnect / LEAN

The pattern: stream the universe's daily data through one indicator instance per symbol, then filter or rank the universe by the indicator's value. Wrap the per-symbol state in a `SelectionData` class kept in a dict on the algorithm.

**Do not call `self.history(...)` inside the universe selection callback to compute indicators.** It runs every selection step for every symbol — for a 8000-symbol Equity fundamentals universe that's thousands of history requests per trading day. Stream the data through the indicator instead; that's what the universe data is for.

**Match the project's language before writing.** Check the project files: `.cs` files mean a C# project, `.py` files mean Python. The Python form is shown first in each section below; the C# form is in the "C# implementation" section near the bottom. Both are equally canonical — never default to Python in a C# project.

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

## The four pieces, why each one matters

1. **One `SelectionData` per symbol, kept in a dict on the algorithm.** Indicators are stateful — you cannot recompute them from scratch each selection or you'll just be re-doing history calls. The dict lives across selection callbacks.
2. **Lifecycle cleanup.** Symbols leave the universe dataset (delisting, fundamentals coverage drop, exchange removal). Without `del self._selection_data_by_symbol[symbol]` for absent symbols, the dict grows unbounded over a long backtest.
3. **Warm-up.** `set_warm_up(timedelta(N))` makes LEAN replay `N` calendar days into the universe selection callback before the live start. That's how the indicators get fed enough bars to be ready by the first real selection. **`N` must be ≥ the indicator's warm-up period in trading days, with slack for weekends/holidays** — for a 21-day SMA, 60 calendar days is comfortable. Return `[]` while `self.is_warming_up` so the universe stays empty (selection on un-ready indicators is meaningless and would also subscribe to assets you don't actually want yet).
4. **`update()` returns `is_ready`.** This is the filter for "do I have enough data to use this indicator now?" — list-comprehension form makes it one line.

## Leave universe selection on its daily schedule

The indicator only sees one new bar per universe selection call. Default Fundamentals and CryptoUniverse selection runs **once per trading day** — that's what makes `SimpleMovingAverage(21)` actually mean "21-day SMA."

This collides with the usual cross-skill advice ("match the universe schedule to the rebalance schedule to avoid wasted work"). For indicator universes, **don't do that.** If you set `self.universe_settings.schedule.on(self.date_rules.month_start())` to align with a monthly rebalance, the indicator only receives one bar per month, and `SimpleMovingAverage(21)` silently becomes "21-month SMA" — almost never what was intended. Rebalance on whatever cadence you want, but leave universe selection daily.

When can you legitimately slow it down?

- **Data-point indicators (SMA, EMA, RSI, …):** OK if the slower cadence _is_ the indicator semantic you want — e.g., a deliberate "21-week SMA of weekly closes," scheduled weekly and sized in weeks (`SimpleMovingAverage(21)` with a weekly schedule). Each close stands on its own, so sampling them at the slower cadence is coherent.
- **Crypto bar indicators (ATR, candle patterns, anything that needs OHLC):** never. The CryptoUniverse data point is always a daily bar regardless of how often selection runs. Sampling only Friday's daily bar at a weekly cadence throws away Mon–Thu's high/low/range — the resulting ATR isn't "weekly true range," it's "daily true range computed from one day per week," which is meaningless. Keep selection daily.

## Equity-only: handle splits and dividends or your indicator is wrong

`Fundamental.price` is the previous trading day's **raw** close — actual unadjusted trading price. (`Fundamental.adjusted_price` is the split- and dividend-adjusted version.) Streaming raw prices into an indicator works fine until a split or dividend, at which point the raw price drops abruptly — a 2-for-1 split halves it overnight; a dividend knocks it down by the dividend amount on ex-date. An SMA fed across that boundary mixes pre-action and post-action prices on different scales and produces a spurious dip.

`Fundamental.price_scale_factor` is the cumulative scaling that maps historical raw prices onto today's session. Cache it on the `SelectionData`; if it changes between bars, **reset the indicator and rewarm from `SCALED_RAW` history** — historical raw prices scaled to today's session, so they compose coherently with the next `f.price`. The example above is the canonical implementation — don't omit it.

This is Equity-specific. Crypto doesn't have corporate actions, so the `price_scale_factor` check doesn't apply.

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
        # `update()` returns is_ready and has the side effect of advancing the indicator.
        # Use `&` (non-short-circuiting) so both indicators advance every bar even when
        # the first isn't ready. The expression doubles as the joint readiness check.
        # ATR genuinely needs OHLC, so wrap the universe data point in a TradeBar.
        return (
            self.mean_daily_usd_volume.update(c.end_time, c.volume_in_usd) &
            self.atr.update(TradeBar(c.end_time, c.symbol, c.open, c.high, c.low, c.close, c.volume_in_usd))
        )
```

Notes specific to Crypto universe data:

- `CryptoUniverse.coinbase`, `CryptoUniverse.binance`, etc. — pick the exchange that matches the brokerage you're modeling.
- The data point has `open`, `high`, `low`, `close`, `volume`, and `volume_in_usd` (volume × close in USD). For a USD-volume filter use `volume_in_usd`, not `volume` (which is in the base asset).
- For bar indicators (ATR, candle patterns — anything that genuinely needs OHLC, _not_ BollingerBands or other indicators that compute on closes), construct a `TradeBar` from the universe data point — the indicator API takes `IBaseDataBar`, not the raw universe selection type.
- Use `&` not `and` between two `update()` calls when you need both to advance every bar regardless of readiness. `and` short-circuits and silently desyncs the indicators. Note this is specifically about `update()` calls (which have the side effect of advancing the indicator). Plain readiness checks like `ind1.is_ready and ind2.is_ready` are pure boolean reads with no side effect — `and` is correct there, and `&` adds nothing.

## Rebalancing

These universes are almost always paired with a `self.schedule.on(...)` rebalance. **If your implementation includes one, load the `scheduled-events` skill before writing it** — the rules for picking a fire time that doesn't land inside a data bar (and the 8AM ET convention for dynamic daily Equity universes) live there. Don't re-derive them.

## Common mistakes

- **Calling `self.history(...)` per symbol inside the selection callback.** Doesn't scale and is the entire reason this pattern exists. The only legitimate `history` call is the post-split rewarm in the Equity `SelectionData.update`, which fires once per affected symbol per corporate action.
- **Forgetting to clean up `SelectionData` for departed symbols.** The dict grows forever. Use the `keys() - {symbol set in current data}` set difference.
- **Returning a populated list during `self.is_warming_up`.** Selection happens on un-ready indicators.
- **Warm-up shorter than the indicator's warm-up period.** `set_warm_up(timedelta(N))` is **calendar days**, not trading days. For a 21-bar daily indicator, 21 days isn't enough for US Equities — weekends and holidays mean ~30 calendar days is the floor; pad to 1.5–2× the indicator length to be safe.
- **Ignoring `price_scale_factor` on Equity.** Raw `f.price` drops abruptly through splits and dividends; the indicator's window straddles the boundary and produces a spurious value. Symptoms are subtle: rankings spike around heavily-dividend-paying or recently-split stocks.
- **Using `and` instead of `&` between two `update()` calls.** `and` short-circuits — if the first indicator isn't ready, the second never updates that bar, and the two indicators become permanently out of sync. The canonical pattern is to _combine_ the side-effecting update with the joint readiness check in one expression: `return ind1.update(...) & ind2.update(...)`. Each `update()` returns the indicator's new `is_ready`, so the `&`-joined expression doubles as the row's readiness.
- **Splitting update from readiness, then writing `update(...) & update(...)` followed by `is_ready & is_ready`.** Once the updates have run, the readiness check is just a bool read with no side effect — `and` is fine, `&` is just noise. Don't carry the "use & not and" rule into pure boolean expressions.
- **Putting the selection logic on intraday data.** Universe selection callbacks usually fire once per day. The indicators are seeing daily bars regardless of `universe_settings.resolution` — `SimpleMovingAverage(21)` means 21 days, not 21 minutes.
- **Trying to use a bar indicator on an Equity universe.** `Fundamental` has no OHLCV — only the previous day's close in `f.price`. `ATR`, candle patterns, and other indicators that genuinely need high/low/open cannot be driven from `Fundamental`. (`BollingerBands` and similar that compute on closes are _data-point_ indicators — they're fine.) On Crypto, the data point does have OHLCV, so wrap it in a `TradeBar` before passing to a true bar indicator.

## Checklist when writing one of these

1. Is there a `SelectionData` class with the indicator(s) as instance attributes?
2. Is there a `_selection_data_by_symbol` dict on the algorithm, with `setdefault(...)` to lazy-create entries?
3. Is there a cleanup step removing entries for symbols no longer in the current data?
4. Does `update(...)` return the indicator's `is_ready` so the comprehension can filter to ready symbols?
5. Is `set_warm_up(timedelta(N))` set, with `N` ≥ ~1.5× the longest indicator's warm-up period in calendar days?
6. Does the callback `return []` while `self.is_warming_up`?
7. **Equity only:** is `price_scale_factor` cached and checked, with reset + `SCALED_RAW` rewarm on change?
8. **Crypto only:** for bar indicators, is the universe data point being wrapped in a `TradeBar` before `update`? **Equity only:** are all indicators data-point indicators (no bar indicators)?
9. Between multiple `update()` calls in `SelectionData.update`, is the operator `&` (not `and`)? And conversely, are pure `is_ready` boolean reads using `and`, not `&`?
10. Is universe selection running on its default daily schedule (no `universe_settings.schedule.on(...)` slowing it down)? The exception is a data-point indicator where the slower cadence _is_ the indicator semantic (e.g., a 21-week SMA on weekly closes). Crypto bar indicators always need the daily cadence — never slow them down.

## C# implementation

The pattern is identical to the Python version above; only the syntax changes. **Use this form when the project is C# (`.cs` files).**

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
        SetWarmUp(TimeSpan.FromDays(60));
    }

    private IEnumerable<Symbol> SelectAssets(IEnumerable<Fundamental> fundamentals)
    {
        var readyStocks = new List<Fundamental>();
        foreach (var f in fundamentals)
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
        var activeStocks = fundamentals.Select(f => f.Symbol).ToHashSet();
        foreach (var symbol in _selectionDataBySymbol.Keys.Where(s => !activeStocks.Contains(s)).ToList())
        {
            _selectionDataBySymbol.Remove(symbol);
        }
        if (IsWarmingUp)
        {
            return Enumerable.Empty<Symbol>();
        }

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
        if (f.PriceScaleFactor == _priceScaleFactor)
        {
            return Indicator.Update(f.EndTime, f.Price);
        }
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

| Python                                                     | C#                                                                    |
| ---------------------------------------------------------- | --------------------------------------------------------------------- |
| `self.add_universe(self._select_assets)`                   | `AddUniverse(SelectAssets)`                                           |
| `self.add_universe(CryptoUniverse.coinbase(...))`          | `AddUniverse(CryptoUniverse.Coinbase(...))`                           |
| `self.set_warm_up(timedelta(60))`                          | `SetWarmUp(TimeSpan.FromDays(60))`                                    |
| `self.is_warming_up`                                       | `IsWarmingUp`                                                         |
| `f.price_scale_factor`                                     | `f.PriceScaleFactor`                                                  |
| `DataNormalizationMode.SCALED_RAW`                         | `DataNormalizationMode.ScaledRaw`                                     |
| `self.history[TradeBar](symbol, n, Resolution.DAILY, ...)` | `History<TradeBar>(symbol, n, Resolution.Daily, ...)`                 |
| `indicator.update(time, value)` / `indicator.update(bar)`  | `indicator.Update(time, value)` / `indicator.Update(bar)`             |
| `indicator.is_ready` / `indicator.warm_up_period`          | `indicator.IsReady` / `indicator.WarmUpPeriod`                        |
| `setdefault(symbol, SelectionData(...))`                   | `TryGetValue` + assign idiom                                          |
| `dict.keys() - {symbols}` set difference                   | `_dict.Keys.Where(k => !set.Contains(k)).ToList()`                    |
| `&` between `update` calls                                 | `&` between `Update` calls (`bool & bool` is non-short-circuit in C#) |

C#-only gotchas:

- `IEnumerable<Fundamental>` from the callback may be enumerated more than once in the body — materialize to a list first if you iterate it twice (the example does this).
- Modify `_selectionDataBySymbol` only after taking a `.ToList()` snapshot of the keys to remove — removing while iterating throws.
- Pass a method group (`SelectAssets`) to `AddUniverse`, not `SelectAssets()`.
- On Crypto, `c.VolumeInUsd` is a `double` but `TradeBar`'s volume parameter is `decimal` — cast explicitly when wrapping a universe data point for a bar indicator:
  ```csharp
  new TradeBar(c.EndTime, c.Symbol, c.Open, c.High, c.Low, c.Close, (decimal)c.VolumeInUsd)
  ```
