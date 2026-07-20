---
name: indicators
description: Use whenever a signal needs a technical indicator or rolling statistic (moving average, momentum/rate-of-change, RSI, ATR, rolling volatility, etc.) — OR any custom per-symbol statistic computed from a trailing window of prices/returns (weighted sums of past log-prices, custom formulas). Covers automatic vs manual indicators and when to use each, `attaching indicators to their Security via duck typing`, warming them up (including dynamic universes), reading current/previous/historical values, combining indicators, and writing custom indicators — instead of recomputing a rolling statistic with a `history()` request every bar or rebalance.
---

# Technical indicators — QuantConnect / LEAN

If a signal is a rolling statistic over time — a moving average, an N-period return/momentum, RSI, ATR, rolling volatility, **or any custom statistic over a trailing window (a weighted sum of past log-prices, a custom formula over N past bars)** — register an **indicator once** (or maintain per-symbol rolling state) and read its value; do not recompute it with a `history()` request on every bar: that is slow and easy to get wrong (off-by-one on the as-of bar). **Cadence decides the pattern.** In a **daily (or intraday) universe-selection/rebalance loop over many names**, a `history(symbols, N, ...)` re-downloads N days × the whole universe every single day — the #1 backtest-speed killer; there, seed each symbol's rolling window ONCE when it first appears and append the new bar each day (per-symbol SelectionData state — note incremental updates only work when the selection/rebalance actually fires at the data frequency the statistic needs). For an **infrequent cadence (weekly/monthly rebalances)**, one batched history request per rebalance is fine — don't add rolling-state machinery the cadence doesn't need; optimize for speed only when the algorithm is actually slow. LEAN indicators update "on-line" — each new data point updates the indicator's internal state.

**Default to the built-ins.** Use the built-in indicators and `IndicatorExtensions` (e.g. `self.vwap`, `self.roc`, `self.sma`, `self.std`, `self.atr`, `IndicatorExtensions.of`) — do NOT hand-roll a rolling statistic with manual accumulators, `deques`, or running sum/variance loops when a built-in (or a composition of them) computes it. Hand-rolled indicator math is more code, more bug surface, and harder to review; only write it yourself when no built-in covers it, and even then prefer a custom `PythonIndicator` over inline loops. **The goal is a SMALL algorithm — push the work onto LEAN's helpers, not into hand-written code.** This extends past plain price indicators: a rolling mean/std of a value YOU compute each period belongs in a manual-update indicator (feed it `.update(time, your_value)` — see Custom indicators), and a fixed-cadence intraday decision belongs in a consolidator's handler (see "Acting on a fixed intraday bar"), never a `deque`/accumulator loop or an `on_data` clock-time filter.

## Automatic vs manual — choose by where the input comes from

Both are valid; pick by the input the indicator needs.

- **Automatic — the default when the input is the security's standard data.** Call the QCAlgorithm helper method, which creates the indicator AND registers it for automatic updates from the security's bars. Create it in `initialize`. The indicator resolution must be **≥** the security's subscription resolution.

```python
roc = self.roc(symbol, 252, Resolution.DAILY)   # 252-bar rate of change, auto-updated
```

  Common helpers (each is the constructor name lower-cased): `self.sma`, `self.ema`, `self.roc` (RateOfChange, a *fraction*), `self.rocp` (RateOfChangePercent, ×100 — same sign), `self.rsi`, `self.atr`, `self.bb` (BollingerBands), `self.std` (StandardDeviation), `self.mom` (Momentum), `self.macd`, `self.max` (Maximum), `self.min` (Minimum). Each takes `(symbol, period[, resolution])`; a few take extra args (e.g. `self.bb(symbol, period, k)`).
  - **Do NOT also `register_indicator(...)` or call `.update(...)` on an automatic (helper-created) indicator** — it would then receive each data point twice per cycle and compute wrong values. The helper already wired the updates.
  - **Never assign a helper indicator to `self.<helpername>`** (e.g. `self.roc = self.roc(...)`) — it shadows the factory method. Use another name (or attach it to the Security, below).

- **Manual — when the input is non-standard.** Construct the indicator directly and drive it yourself — when you need a **custom field** (not the default close), **consolidated / Renko bars**, a **custom data source**, or values you compute:

```python
roc = RateOfChange(252)
self.register_indicator(symbol, roc, Resolution.DAILY)   # feed it a chosen subscription/consolidator
# ...or feed values yourself: roc.update(bar.end_time, bar.close)
```

Litmus: *"does the indicator just need this security's normal bars/price?"* → automatic helper. *"do I need to feed it a custom field, custom bar, or my own values?"* → manual.

**Custom timeframe:** to drive an indicator on weekly/monthly/custom bars instead of the security's native resolution, register it against a consolidator — `self.register_indicator(symbol, indicator, consolidator)` updates it from each consolidated bar.

**Two-symbol indicators:** some indicators take a pair (e.g. Beta, correlation). Register them once per symbol, and warm them up by passing a symbol list — `self.warm_up_indicator([symbol_a, symbol_b], beta, Resolution.DAILY)`.

## Acting on a fixed intraday bar — consolidators vs scheduled events
If you only need to ACT at a fixed time (no bar or indicator data required), a **Scheduled Event** is simplest — see the scheduled-events skill. But when the decision happens on a fixed intraday bar cadence and uses that bar — read the price at each half-hour mark, then act — **drive it off a consolidator**: do NOT replicate the cadence by filtering minute bars in `on_data` (`if bar.end_time.time() in MARKS: ...`), which is hand-written plumbing for what a consolidator does natively. Register the consolidator and put the per-bar logic in its handler — either you read the consolidated bar's OHLCV directly, or the bar updates an indicator you trade off. A **market-hour-aware** consolidator anchors intraday bars to the market open (the first 30-min bar ends 30 min after the open) and respects early-close/holiday sessions:

```python
self._symbol = self.add_equity("SPY", Resolution.MINUTE).symbol
# 30-min TradeBars anchored to the market open (args: daily_strict_end_time, period, data_type, tick_type, extended_hours):
self._consolidator = MarketHourAwareConsolidator(True, timedelta(minutes=30), TradeBar, TickType.TRADE, False)
self._consolidator.data_consolidated += self._consolidation_handler
self.subscription_manager.add_consolidator(self._symbol, self._consolidator)
...
# The handler takes THREE parameters — (self, sender, bar). A 2-arg def (self, bar)
# raises "takes 2 positional arguments but 3 were given" on the first bar. Type it:
def _consolidation_handler(self, sender: object, consolidated_bar: TradeBar) -> None:
    # consolidated_bar is the 30-min TradeBar — read consolidated_bar.close, decide here.
    ...
```

Put the trading logic where the data lands: if you read the bar directly, the `data_consolidated` handler is the home; if you trade off an indicator fed by the consolidator (`self.register_indicator(symbol, indicator, self._consolidator)`), put the logic in the indicator's own `updated` event handler instead (see "React per update" under Reading values). Sub-bar statistics need the underlying minute subscription, not the consolidated bar — and a built-in usually already exists. A session VWAP is `self.vwap(symbol)` (a built-in `IntradayVwap` that updates off the minute bars and resets each session) — use it. **Never** accumulate a VWAP (or any sub-bar statistic) from the 30-min consolidated bars: those are ~6 points per half-hour, not the full minute series, so the value is wrong.

The handler fires once per consolidated bar, so **that bar is your cadence** — don't ALSO keep a parallel set of target times (or re-check the clock), neither to decide whether to act (the consolidator already only emits at those marks, plus the session-close bar) nor as the keys for per-time-of-day state. When you need a separate statistic per intra-session slot (a band/mean/stat keyed by time-of-day), do NOT enumerate the slot times in `initialize`: derive each bar's key from its own `bar.end_time.time()` and look it up lazily, so the key set builds itself from the bars that actually arrive — automatically matching early-close/holiday/DST sessions, where an enumerated list would be stale. Each slot's rolling stat **accumulates across days under its own key**, so you never need the list to pre-build or backfill the slots:

```python
slot = bar.end_time.time()                     # key derived from the bar, not declared in initialize
if slot not in self._slot:
    self._slot[slot] = SimpleMovingAverage(N)  # one rolling stat per slot, created on first sighting
self._slot[slot].update(bar.end_time, value)   # one bar per slot per day accumulates here
```

If a particular emitted bar must be skipped — e.g. a market-hour-aware consolidator emits a final bar at the session close that you don't want to trade on — gate the handler on the session-close test `security.exchange.hours.is_open(bar.end_time, False)` (`True` for every intraday mark, `False` only for that session-close bar — on normal and early-close days alike; `security.exchange.exchange_open` and `self.is_market_open(symbol)` agree), not on an enumerated list of bars. To turn a value you compute each bar into a rolling statistic (a mean/std over the last N of those bars), feed it to an indicator in the handler — see "Rolling mean / std of a value you compute".

**Seeding handler-built state — replay history through the consolidator, not a parallel loop.** When per-asset state is built inside a `data_consolidated` handler and must be ready before live trading (so the strategy doesn't idle), seed it by feeding that asset's history **through the same consolidator**, so the same handler builds the same state from the same bars:

```python
self._consolidator.data_consolidated += self._on_bar
self.subscription_manager.add_consolidator(symbol, self._consolidator)
self._seeding = True
for bar in self.history[TradeBar](symbol, timedelta(days=N), Resolution.MINUTE):
    self._consolidator.update(bar)   # drives _on_bar with the SAME bars/marks it sees live
self._seeding = False
```

Gate order placement on `self._seeding` (always build state; trade only when not seeding). Do this **per asset, wherever the asset is added** — in `initialize` for a fixed universe, or in `on_securities_changed` as each asset enters a dynamic universe. Never hand-roll a separate `history()` loop that re-derives the bar cadence (e.g. selecting bars by an enumerated clock-time list) — replaying through the consolidator removes that whole parallel path. (`set_warm_up` is a fixed-universe shortcut that feeds only the consolidators registered at start; it does **not** warm consolidators for assets that join a dynamic universe later, so prefer the explicit `consolidator.update(...)` replay, which works in both cases.)

## Attach the indicator to its Security (duck typing)

In Python you can attach arbitrary attributes to a `Security` object, so keep a per-symbol indicator **on its Security** instead of in a parallel dict you have to maintain and prune:
```python
equity = self.add_equity("SPY", Resolution.DAILY)
equity.roc = self.roc(equity.symbol, 252, Resolution.DAILY)   # attach
# read it back anywhere:
value = self.securities["SPY"].roc.current.value
```
This shines for **dynamic universes**: the indicator's lifetime is tied to the Security, so when a symbol leaves the universe the Security is dropped and the attached indicator goes with it — no manual bookkeeping. (Verified: `sec.roc = r` attaches and `self.securities[sym].roc is r`.)

(Selecting universe *membership* by an indicator — e.g. only assets above their 200-day SMA — is the separate indicator-universe / cross-sectional pattern; this skill is about computing and reading the indicators themselves.)

## Warm up so it is ready at the start

A fresh indicator is not `is_ready` until it has received `period` data points. Seed it rather than idling. **The right mechanism depends on whether your asset set is fixed or dynamic:**

- **Auto warm-up — simplest, and works for both fixed and dynamic sets.** Set this ONCE, **before** creating any helper indicator:

```python
self.settings.automatic_indicator_warm_up = True
roc = self.roc(symbol, 252, Resolution.DAILY)   # warmed from history AT CREATION; is_ready immediately
```

  Because it warms at *creation* time, an indicator you create when a security joins a dynamic universe is warmed too. (Verified: `is_ready=True`, 253 samples right after creation, including on `TOTAL_RETURN`-normalized equities.)
- **`set_warm_up(n, Resolution.DAILY)` — only for a FIXED asset set / fixed universe.** It replays `n` historical bars through the algorithm before the start, so it only warms indicators that **already exist at the start**. In a **dynamic universe it will NOT warm indicators of assets added later.** Size `n` to the longest indicator period (a fixed length, not a fraction of the backtest).
- **Dynamic universe — warm each indicator as the asset joins.** In `on_securities_changed`, create the indicator for each added security and warm it explicitly; deregister automatic indicators on removal so their update wiring is freed:

```python
def on_securities_changed(self, changes):
    for added in changes.added_securities:
        added.roc = self.roc(added.symbol, 252, Resolution.DAILY)
        self.warm_up_indicator(added.symbol, added.roc, Resolution.DAILY)
    for removed in changes.removed_securities:
        self.deregister_indicator(removed.roc)
```

  (Or just rely on `automatic_indicator_warm_up = True`, which warms each `self.roc(...)` at creation here too.)

Always guard reads with `if indicator.is_ready:` — values before warm-up are inaccurate.

## Splits & dividends — reset indicators fed unadjusted prices

If an indicator is fed **adjusted** prices (the equity default) or total-return prices, splits and dividends are already baked in — nothing to do. But if you feed it **Raw / unadjusted** prices (or you live-trade equities), a split or dividend invalidates the indicator's accumulated window (the old prices are on a different scale). On the corporate action, `reset()` the indicator and re-warm it with `SCALED_RAW` history:

```python
if data.splits.contains_key(symbol) or data.dividends.contains_key(symbol):
    ind.reset()
    bars = self.history[TradeBar](symbol, ind.warm_up_period, Resolution.DAILY,
                                  data_normalization_mode=DataNormalizationMode.SCALED_RAW)
    for bar in bars:
        ind.update(bar.end_time, bar.close)
```

## Reading values

- **Current value:** `indicator.current.value` — e.g. `roc.current.value > 0` to test a positive trailing return / up-trend.
- **Previous value:** `indicator.previous.value` — the prior point. Equivalent to `indicator[1].value` and `indicator.window[1].value` (`indicator[i]` is sugar for `indicator.window[i]`; index 0 = current, 1 = previous).
- **Deeper trailing history:** the rolling window keeps only a short history by default — set its length explicitly to index back further: `indicator.window.size = 10`, then `indicator[5].value`. (Verified writable.)
- **A historical series:** `self.indicator_history(indicator, symbol, period_or_timedelta)` (it resets the indicator, requests history, and updates it).
- **Multi-output indicators:** some indicators have more than one output, each a named sub-property with its own `.current.value` — e.g. BollingerBands → `bb.upper_band.current.value`, `bb.middle_band.current.value`, `bb.lower_band.current.value`; MACD → `macd.current.value`, `macd.signal.current.value`, `macd.histogram.current.value`. Check the specific indicator for its output names.
- **React per update (optional):** `indicator.updated += handler` fires every time the indicator produces a new value; the handler receives `(indicator, IndicatorDataPoint)`. Use it to act or plot on each update instead of polling in `on_data`.

## Combining indicators

To build a spread, ratio, weighted blend, or an indicator-of-an-indicator, use `IndicatorExtensions` (in Python these are **static** calls). They return a new composite indicator:

```python
sma_fast = self.sma(symbol, 14)
sma_slow = self.sma(symbol, 21)
spread   = IndicatorExtensions.minus(sma_fast, sma_slow)          # also plus / times / over
ratio    = IndicatorExtensions.over(self.rsi(symbol, 14), 2)      # indicator and a constant
blended  = IndicatorExtensions.weighted_by(sma_fast, sma_slow, 3) # lookback-weighted average
```

- Chaining feeds the source's `current.value` only; to chain on another field, write a custom indicator.
- For an indicator-of-an-indicator, the **target `(first arg)` of `IndicatorExtensions.of(...)` must be a MANUAL indicator** (e.g. `SimpleMovingAverage(10)`), never an auto helper like `self.sma(...)`, or it gets double-updated:

```python
rsi = self.rsi(symbol, 14)
rsi_sma = IndicatorExtensions.of(SimpleMovingAverage(10), rsi)   # 10-period SMA of the 14-period RSI
```

## Rolling mean / std of a value you compute — feed an indicator, don't loop
A rolling mean, std, or vol is a rolling statistic even when its INPUT is a value YOU compute each period (not a raw bar field). Create the indicator once and feed it with `.update(time, your_value)`; read `.current.value`. Do NOT keep a `deque` of records and recompute the mean/variance in a loop.

```python
# 14-day mean of a per-day, per-time-of-day metric (e.g. |close_at_mark / open - 1|) — one SMA per mark:
self._mark_sma = {mark: SimpleMovingAverage(14) for mark in marks}
# ...each day, once you have today's value for `mark`:
self._mark_sma[mark].update(self.time, todays_move)   # feeds the indicator
sigma = self._mark_sma[mark].current.value             # the 14-day mean — no loop
```

Warm it up by feeding the historical values the same way. For the std of **returns**, compose `roc`→`StandardDeviation`:

```python
daily_ret = self.roc(symbol, 1, Resolution.DAILY)               # 1-day return
vol = IndicatorExtensions.of(StandardDeviation(14), daily_ret)  # 14-day rolling std of daily returns
```

`StandardDeviation` (and `Variance`) compute the **population** statistic (÷n). That ÷n vs ÷(n−1) difference is immaterial for a rolling estimate — **use the indicator even when a spec writes "sample std (÷n−1)"**; do NOT hand-roll a variance loop to match the divisor.
Reach for a DataFrame/Series `.std()` (sample, ddof=1) only for a one-shot vectorized computation, never a per-bar rolling value.

## Custom indicators

When LEAN doesn't ship the indicator you need, subclass `PythonIndicator`: provide `name`, `time`, `value` attributes, an `update(self, input) -> bool` that mutates `self.value`/`self.time` and **returns the readiness bool**, and an `is_ready` property:
```python
class CustomSMA(PythonIndicator):
    def __init__(self, name, period):
        self.name = name
        self.warm_up_period = period
        self.time = datetime.min
        self.value = 0
        self.queue = deque(maxlen=period)

    def update(self, input):
        self.queue.appendleft(input.value)
        self.time = input.time
        self.value = sum(self.queue) / len(self.queue)
        return len(self.queue) == self.queue.maxlen

    @property
    def is_ready(self):
        return len(self.queue) == self.queue.maxlen
```
- **Automatic updates:** `self.register_indicator(symbol, custom, Resolution.DAILY)` — the engine feeds it; the template above is complete.
- **Manual updates:** if you call `custom.update(...)` yourself, also set `self.current = IndicatorDataPoint(input.end_time, self.value)` and call `self.on_updated(self.current)` at the end of `update`. These two are manual-only — LEAN sets them for you in automatic mode, and `on_updated` must NOT be called in automatic mode.
- **Warm-up:** loop a history request and call `.update(bar.end_time, bar.close)`, or — if it exposes a `warm_up_period` `attribute` — `self.warm_up_indicator(symbol, custom, Resolution.DAILY)`.

## Don't hand-roll a rolling statistic with repeated `history()`

Recomputing an N-period return / moving average / volatility with a `history()` request on every rebalance is slow and off-by-one-prone. Register the indicator once and read it. A one-off startup seed or a single lookback is a fine use of `history()`; a per-bar or per-rebalance recompute of a rolling statistic is the smell that an indicator belongs there instead.
