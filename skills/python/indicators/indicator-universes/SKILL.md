---
name: indicator-universes
description: Use when selecting a QuantConnect/LEAN universe based on per-symbol indicators. Triggers — code uses `add_universe(...)` with a selection callback that builds per-symbol `SimpleMovingAverage`/`ExponentialMovingAverage`/`BollingerBands`/`RSI`/`ATR` etc., often via a `SelectionData` class kept in a per-symbol dict; questions like "rank stocks by 21-day SMA", "top N most volatile crypto pairs", "fundamentals universe with momentum filter", "why does my SMA spike around splits/dividends", "why does my universe shrink during warm-up", "how do I avoid history calls in universe selection". Skip when — universe doesn't need per-symbol indicators (use plain fundamentals/ETF/CryptoUniverse selection).
---

# Indicator-Based Universe Selection in QuantConnect / LEAN

The pattern: stream the universe's daily data through one indicator instance per symbol, then filter or rank the universe by the indicator's value. Wrap the per-symbol state in a `SelectionData` class kept in a per-symbol dict on the algorithm.

## Basic pattern (Equity / Fundamentals)

US Equities only support **data-point indicators** for universe selection (SMA, EMA, RSI, StandardDeviation, BollingerBands, etc. — anything that updates from `(time, value)`), not bar indicators (ATR, candle patterns).

```python
class EquityIndicatorUniverseSelectionAlgorithm(QCAlgorithm):

    def initialize(self):
        self.settings.seed_initial_prices = True
        self._selection_data_by_symbol = {}
        self._universe = self.add_universe(self._select_assets)
        self.set_warm_up(21, Resolution.DAILY)

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

1. **One `SelectionData` per symbol, kept in a dict on the algorithm.** Indicators are stateful — you cannot recompute them from scratch each selection or you'll just be re-doing history calls. The dict lives across selection callbacks. Lazy-create the entry on first sight of a symbol (`setdefault(...)` + assign idiom).
2. **Lifecycle cleanup.** Symbols leave the universe dataset (delisting, fundamentals coverage drop, exchange removal). Without removing entries for absent symbols, the dict grows unbounded over a long backtest.
3. **Warm-up.** `set_warm_up(N, Resolution.DAILY)` makes LEAN replay `N` daily bars into the universe selection callback before the live start, so the indicators are ready by the first real selection. **Set `N` to the longest indicator's warm-up period in bars** — `SimpleMovingAverage(21)` needs `N = 21`. Use this bar-count overload (not `set_warm_up(timedelta(N))`): counting trading bars handles weekends and holidays without padding, and daily resolution is all the indicator needs since the universe feeds it daily bars at runtime. Return an empty list while `self.is_warming_up` so the universe stays empty (selection on un-ready indicators is meaningless and would also subscribe to assets you don't actually want yet).
4. **The per-symbol update method returns the indicator's readiness.** This is the filter for "do I have enough data to use this indicator now?" — pairing it with the comprehension / LINQ `Where` makes it one expression.

## Leave universe selection on its daily schedule

The indicator only sees one new bar per universe selection call. Default Fundamentals and CryptoUniverse selection runs **once per trading day** — that's what makes `SimpleMovingAverage(21)` actually mean "21-day SMA."

This collides with the usual cross-skill advice ("match the universe schedule to the rebalance schedule to avoid wasted work"). For indicator universes, **don't do that.** If you set `self.universe_settings.schedule.on(self.date_rules.month_start())` to align with a monthly rebalance, the indicator only receives one bar per month, and `SimpleMovingAverage(21)` silently becomes "21-month SMA" — almost never what was intended. Rebalance on whatever cadence you want, but leave universe selection daily.

When can you legitimately slow it down?

- **Data-point indicators (SMA, EMA, RSI, …):** OK if the slower cadence _is_ the indicator semantic you want — e.g., a deliberate "21-week SMA of weekly closes," scheduled weekly and sized in weeks (`SimpleMovingAverage(21)` with a weekly schedule). Each close stands on its own, so sampling them at the slower cadence is coherent.
- **Crypto bar indicators (ATR, candle patterns, anything that needs OHLC):** never. The CryptoUniverse data point is always a daily bar regardless of how often selection runs. Sampling only Friday's daily bar at a weekly cadence throws away Mon–Thu's high/low/range — the resulting ATR isn't "weekly true range," it's "daily true range computed from one day per week," which is meaningless. Keep selection daily.

## Equity-only: handle splits and dividends

`Fundamental.price` is the previous trading day's **raw** close — actual unadjusted trading price. (`Fundamental.adjusted_price` is the split- and dividend-adjusted version.) Streaming raw prices into an indicator works fine until a split or dividend, at which point the raw price drops abruptly — a 2-for-1 split halves it overnight; a dividend knocks it down by the dividend amount on ex-date. An SMA fed across that boundary mixes pre-action and post-action prices on different scales and produces a spurious dip.

`Fundamental.price_scale_factor` is the cumulative scaling that maps historical raw prices onto today's session. Cache it on the `SelectionData`; if it changes between bars, **reset the indicator and rewarm from `SCALED_RAW` history** — historical raw prices scaled to today's session, so they compose coherently with the next `f.price`. The example above is the canonical implementation — don't omit it.

This is Equity-specific. Crypto doesn't have corporate actions, so the price-scale-factor check doesn't apply.

## Crypto pattern

Same shape, different universe constructor and different bar fields:

```python
class CryptoIndicatorUniverseSelectionAlgorithm(QCAlgorithm):

    def initialize(self):
        self.settings.seed_initial_prices = True
        self._selection_data_by_symbol = {}
        self._universe = self.add_universe(CryptoUniverse.coinbase(self._select_assets))
        self.set_warm_up(30, Resolution.DAILY)

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

Notes specific to Crypto universe data:

- `CryptoUniverse.coinbase`, `CryptoUniverse.binance`, etc. — pick the exchange that matches the brokerage you're modeling.
- The data point has `open`, `high`, `low`, `close`, `volume`, and `volume_in_usd` (volume × close in USD). For a USD-volume filter use the USD volume, not the base-asset `volume`.
- For bar indicators (ATR, candle patterns — anything that genuinely needs OHLC, _not_ BollingerBands or other indicators that compute on closes), construct a `TradeBar` from the universe data point — the indicator API takes `IBaseDataBar`, not the raw universe selection type.
- Use the non-short-circuit operator `&` (not `and`) between two update calls when you need both to advance every bar regardless of readiness. The short-circuit form silently desyncs the indicators. Note this is specifically about update calls (which have the side effect of advancing the indicator). Plain readiness checks like `ind1.is_ready and ind2.is_ready` are pure boolean reads with no side effect — short-circuit is correct there, and `&` adds nothing.

## Rebalancing

These universes are almost always paired with a `self.schedule.on(...)` rebalance. **If your implementation includes one, load the `scheduled-events` skill before writing it** — the rules for picking a fire time that doesn't land inside a data bar (and the 8AM ET convention for dynamic daily Equity universes) live there. Don't re-derive them.

## Common mistakes

- **Calling `self.history(...)` per symbol inside the selection callback.** The entire reason this pattern exists is to avoid computationally expensive history calls. The only legitimate history call is the post-split rewarm in the Equity `SelectionData` update, which fires once per affected symbol per corporate action.
- **Splitting update from readiness, then writing `update(...) & update(...)` followed by a `&`-joined readiness check.** Once the updates have run, the readiness check is a bool read with no side effect — short-circuit (`and`) is correct there, and `&` is just noise. The "non-short-circuit" rule is for the side-effecting update calls only.
- **Putting the selection logic on intraday data.** Universe selection callbacks usually fire once per day. The indicators are seeing daily bars regardless of `universe_settings.resolution` — `SimpleMovingAverage(21)` means 21 days, not 21 minutes.
