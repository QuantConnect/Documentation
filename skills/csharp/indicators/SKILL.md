---
name: indicators
description: Use whenever a signal needs a technical indicator or rolling statistic (moving average, momentum/rate-of-change, RSI, ATR, rolling volatility, etc.) — OR any custom per-symbol statistic computed from a trailing window of prices/returns (weighted sums of past log-prices, custom formulas). Covers automatic vs manual indicators and when to use each, `keeping per-symbol indicators in a Symbol-keyed dictionary`, warming them up (including dynamic universes), reading current/previous/historical values, combining indicators, and writing custom indicators — instead of recomputing a rolling statistic with a `History()` request every bar or rebalance.
---

# Technical indicators — QuantConnect / LEAN

If a signal is a rolling statistic over time — a moving average, an N-period return/momentum, RSI, ATR, rolling volatility, **or any custom statistic over a trailing window (a weighted sum of past log-prices, a custom formula over N past bars)** — register an **indicator once** (or maintain per-symbol rolling state) and read its value; do not recompute it with a `History()` request on every bar: that is slow and easy to get wrong (off-by-one on the as-of bar). **Cadence decides the pattern.** In a **daily (or intraday) universe-selection/rebalance loop over many names**, a `History(symbols, N, ...)` re-downloads N days × the whole universe every single day — the #1 backtest-speed killer; there, seed each symbol's rolling window ONCE when it first appears and append the new bar each day (per-symbol SelectionData state — note incremental updates only work when the selection/rebalance actually fires at the data frequency the statistic needs). For an **infrequent cadence (weekly/monthly rebalances)**, one batched history request per rebalance is fine — don't add rolling-state machinery the cadence doesn't need; optimize for speed only when the algorithm is actually slow. LEAN indicators update "on-line" — each new data point updates the indicator's internal state.

**Default to the built-ins.** Use the built-in indicators and `IndicatorExtensions` (e.g. `VWAP`, `ROC`, `SMA`, `STD`, `ATR`, `IndicatorExtensions.Of`) — do NOT hand-roll a rolling statistic with manual accumulators, `queues`, or running sum/variance loops when a built-in (or a composition of them) computes it. Hand-rolled indicator math is more code, more bug surface, and harder to review; only write it yourself when no built-in covers it, and even then prefer a custom `Indicator` over inline loops. **The goal is a SMALL algorithm — push the work onto LEAN's helpers, not into hand-written code.** This extends past plain price indicators: a rolling mean/std of a value YOU compute each period belongs in a manual-update indicator (feed it `.Update(time, yourValue)` — see Custom indicators), and a fixed-cadence intraday decision belongs in a consolidator's handler (see "Acting on a fixed intraday bar"), never a `Queue`/accumulator loop or an `OnData` clock-time filter.

## Automatic vs manual — choose by where the input comes from

Both are valid; pick by the input the indicator needs.

- **Automatic — the default when the input is the security's standard data.** Call the QCAlgorithm helper method, which creates the indicator AND registers it for automatic updates from the security's bars. Create it in `Initialize`. The indicator resolution must be **≥** the security's subscription resolution.

```csharp
var roc = ROC(symbol, 252, Resolution.Daily);   // 252-bar rate of change, auto-updated
```

  Common helpers (each is the indicator abbreviation): `SMA`, `EMA`, `ROC` (RateOfChange, a *fraction*), `ROCP` (RateOfChangePercent, ×100 — same sign), `RSI`, `ATR`, `BB` (BollingerBands), `STD` (StandardDeviation), `MOM` (Momentum), `MACD`, `MAX` (Maximum), `MIN` (Minimum). Each takes `(symbol, period[, resolution])`; a few take extra args (e.g. `BB(symbol, period, k)`).
  - **Do NOT also `RegisterIndicator(...)` or call `.Update(...)` on an automatic (helper-created) indicator** — it would then receive each data point twice per cycle and compute wrong values. The helper already wired the updates.

- **Manual — when the input is non-standard.** Construct the indicator directly and drive it yourself — when you need a **custom field** (not the default close), **consolidated / Renko bars**, a **custom data source**, or values you compute:

```csharp
var roc = new RateOfChange(252);
RegisterIndicator(symbol, roc, Resolution.Daily);   // feed it a chosen subscription/consolidator
// ...or feed values yourself: roc.Update(bar.EndTime, bar.Close);
```

Litmus: *"does the indicator just need this security's normal bars/price?"* → automatic helper. *"do I need to feed it a custom field, custom bar, or my own values?"* → manual.

**Custom timeframe:** to drive an indicator on weekly/monthly/custom bars instead of the security's native resolution, register it against a consolidator — `RegisterIndicator(symbol, indicator, consolidator)` updates it from each consolidated bar.

**Two-symbol indicators:** some indicators take a pair (e.g. Beta, correlation). Register them once per symbol, and warm them up by passing a symbol list — `WarmUpIndicator(new[] {symbolA, symbolB}, beta, Resolution.Daily)`.

## Acting on a fixed intraday bar — consolidators vs scheduled events
If you only need to ACT at a fixed time (no bar or indicator data required), a **Scheduled Event** is simplest — see the scheduled-events skill. But when the decision happens on a fixed intraday bar cadence and uses that bar — read the price at each half-hour mark, then act — **drive it off a consolidator**: do NOT replicate the cadence by filtering minute bars in `OnData` (`if (MARKS.Contains(bar.EndTime.TimeOfDay)) ...`), which is hand-written plumbing for what a consolidator does natively. Register the consolidator and put the per-bar logic in its handler — either you read the consolidated bar's OHLCV directly, or the bar updates an indicator you trade off. A **market-hour-aware** consolidator anchors intraday bars to the market open (the first 30-min bar ends 30 min after the open) and respects early-close/holiday sessions:

```csharp
_symbol = AddEquity("SPY", Resolution.Minute).Symbol;
// 30-min TradeBars anchored to the market open (args: dailyStrictEndTime, period, dataType, tickType, extendedHours):
_consolidator = new MarketHourAwareConsolidator(true, TimeSpan.FromMinutes(30), typeof(TradeBar), TickType.Trade, false);
// The DataConsolidated event delivers the bar as an IBaseData — cast it to a TradeBar for the handler:
_consolidator.DataConsolidated += (sender, consolidated) => ConsolidationHandler(sender, (TradeBar)consolidated);
SubscriptionManager.AddConsolidator(_symbol, _consolidator);
...
private void ConsolidationHandler(object sender, TradeBar consolidatedBar)
{
    // consolidatedBar is the 30-min TradeBar — read consolidatedBar.Close, decide here.
    ...
}
```

Put the trading logic where the data lands: if you read the bar directly, the `DataConsolidated` handler is the home; if you trade off an indicator fed by the consolidator (`RegisterIndicator(symbol, indicator, _consolidator)`), put the logic in the indicator's own `Updated` event handler instead (see "React per update" under Reading values). Sub-bar statistics need the underlying minute subscription, not the consolidated bar — and a built-in usually already exists. A session VWAP is `VWAP(symbol)` (a built-in `IntradayVwap` that updates off the minute bars and resets each session) — use it. **Never** accumulate a VWAP (or any sub-bar statistic) from the 30-min consolidated bars: those are ~6 points per half-hour, not the full minute series, so the value is wrong.

The handler fires once per consolidated bar, so **that bar is your cadence** — don't ALSO keep a parallel set of target times (or re-check the clock), neither to decide whether to act (the consolidator already only emits at those marks, plus the session-close bar) nor as the keys for per-time-of-day state. When you need a separate statistic per intra-session slot (a band/mean/stat keyed by time-of-day), do NOT enumerate the slot times in `Initialize`: derive each bar's key from its own `bar.EndTime.TimeOfDay` and look it up lazily, so the key set builds itself from the bars that actually arrive — automatically matching early-close/holiday/DST sessions, where an enumerated list would be stale. Each slot's rolling stat **accumulates across days under its own key**, so you never need the list to pre-build or backfill the slots:

```csharp
// _slot is a Dictionary<TimeSpan, SimpleMovingAverage> field
var slot = bar.EndTime.TimeOfDay;              // key derived from the bar, not declared in Initialize
if (!_slot.ContainsKey(slot))
    _slot[slot] = new SimpleMovingAverage(N);  // one rolling stat per slot, created on first sighting
_slot[slot].Update(bar.EndTime, value);        // one bar per slot per day accumulates here
```

If a particular emitted bar must be skipped — e.g. a market-hour-aware consolidator emits a final bar at the session close that you don't want to trade on — gate the handler on the session-close test `security.Exchange.Hours.IsOpen(bar.EndTime, false)` (`true` for every intraday mark, `false` only for that session-close bar — on normal and early-close days alike; `security.Exchange.ExchangeOpen` and `IsMarketOpen(symbol)` agree), not on an enumerated list of bars. To turn a value you compute each bar into a rolling statistic (a mean/std over the last N of those bars), feed it to an indicator in the handler — see "Rolling mean / std of a value you compute".

**Seeding handler-built state — replay history through the consolidator, not a parallel loop.** When per-asset state is built inside a `DataConsolidated` handler and must be ready before live trading (so the strategy doesn't idle), seed it by feeding that asset's history **through the same consolidator**, so the same handler builds the same state from the same bars:

```csharp
_consolidator.DataConsolidated += (sender, consolidated) => OnBar(sender, (TradeBar)consolidated);
SubscriptionManager.AddConsolidator(symbol, _consolidator);
_seeding = true;
foreach (var bar in History<TradeBar>(symbol, TimeSpan.FromDays(N), Resolution.Minute))
    _consolidator.Update(bar);   // drives OnBar with the SAME bars/marks it sees live
_seeding = false;
```

Gate order placement on `_seeding` (always build state; trade only when not seeding). Do this **per asset, wherever the asset is added** — in `Initialize` for a fixed universe, or in `OnSecuritiesChanged` as each asset enters a dynamic universe. Never hand-roll a separate `History()` loop that re-derives the bar cadence (e.g. selecting bars by an enumerated clock-time list) — replaying through the consolidator removes that whole parallel path. (`SetWarmUp` is a fixed-universe shortcut that feeds only the consolidators registered at start; it does **not** warm consolidators for assets that join a dynamic universe later, so prefer the explicit `consolidator.Update(...)` replay, which works in both cases.)

## Keep per-symbol indicators in a Symbol-keyed dictionary

In C#, keep each per-symbol indicator in a `Dictionary<Symbol, RateOfChange>` field (or group several per-symbol indicators in a `SymbolData` class), so you can read it back anywhere by symbol:
```csharp
// _roc is a Dictionary<Symbol, RateOfChange> field
var equity = AddEquity("SPY", Resolution.Daily);
_roc[equity.Symbol] = ROC(equity.Symbol, 252, Resolution.Daily);
// read it back anywhere:
var value = _roc[equity.Symbol].Current.Value;
```
In a **dynamic universe**, create the entry in `OnSecuritiesChanged` as each security is added, and prune it on removal — call `DeregisterIndicator` and remove the dictionary entry, since the indicator's lifetime is not tied to the `Security` object (see the `OnSecuritiesChanged` example under Warm up).

(Selecting universe *membership* by an indicator — e.g. only assets above their 200-day SMA — is the separate indicator-universe / cross-sectional pattern; this skill is about computing and reading the indicators themselves.)

## Warm up so it is ready at the start

A fresh indicator is not `IsReady` until it has received `period` data points. Seed it rather than idling. **The right mechanism depends on whether your asset set is fixed or dynamic:**

- **Auto warm-up — simplest, and works for both fixed and dynamic sets.** Set this ONCE, **before** creating any helper indicator:

```csharp
Settings.AutomaticIndicatorWarmUp = true;
var roc = ROC(symbol, 252, Resolution.Daily);   // warmed from history AT CREATION; IsReady immediately
```

  Because it warms at *creation* time, an indicator you create when a security joins a dynamic universe is warmed too. (Verified: `IsReady=true`, 253 samples right after creation, including on `TotalReturn`-normalized equities.)
- **`SetWarmUp(n, Resolution.Daily)` — only for a FIXED asset set / fixed universe.** It replays `n` historical bars through the algorithm before the start, so it only warms indicators that **already exist at the start**. In a **dynamic universe it will NOT warm indicators of assets added later.** Size `n` to the longest indicator period (a fixed length, not a fraction of the backtest).
- **Dynamic universe — warm each indicator as the asset joins.** In `OnSecuritiesChanged`, create the indicator for each added security and warm it explicitly; deregister automatic indicators on removal so their update wiring is freed:

```csharp
// _roc is a Dictionary<Symbol, RateOfChange> field
public override void OnSecuritiesChanged(SecurityChanges changes)
{
    foreach (var added in changes.AddedSecurities)
    {
        _roc[added.Symbol] = ROC(added.Symbol, 252, Resolution.Daily);
        WarmUpIndicator(added.Symbol, _roc[added.Symbol], Resolution.Daily);
    }
    foreach (var removed in changes.RemovedSecurities)
    {
        DeregisterIndicator(_roc[removed.Symbol]);
        _roc.Remove(removed.Symbol);
    }
}
```

  (Or just rely on `AutomaticIndicatorWarmUp = true`, which warms each `ROC(...)` at creation here too.)

Always guard reads with `if (indicator.IsReady)` — values before warm-up are inaccurate.

## Splits & dividends — reset indicators fed unadjusted prices

If an indicator is fed **adjusted** prices (the equity default) or total-return prices, splits and dividends are already baked in — nothing to do. But if you feed it **Raw / unadjusted** prices (or you live-trade equities), a split or dividend invalidates the indicator's accumulated window (the old prices are on a different scale). On the corporate action, `Reset()` the indicator and re-warm it with `ScaledRaw` history:

```csharp
if (data.Splits.ContainsKey(symbol) || data.Dividends.ContainsKey(symbol))
{
    ind.Reset();
    var bars = History<TradeBar>(symbol, ind.WarmUpPeriod, Resolution.Daily,
                                 dataNormalizationMode: DataNormalizationMode.ScaledRaw);
    foreach (var bar in bars)
        ind.Update(bar.EndTime, bar.Close);
}
```

## Reading values

- **Current value:** `indicator.Current.Value` — e.g. `roc.Current.Value > 0` to test a positive trailing return / up-trend.
- **Previous value:** `indicator.Previous.Value` — the prior point. Equivalent to `indicator[1].Value` and `indicator.Window[1].Value` (`indicator[i]` is sugar for `indicator.Window[i]`; index 0 = current, 1 = previous).
- **Deeper trailing history:** the rolling window keeps only a short history by default — set its length explicitly to index back further: `indicator.Window.Size = 10`, then `indicator[5].Value`. (Verified writable.)
- **A historical series:** `IndicatorHistory(indicator, symbol, periodOrTimeSpan)` (it resets the indicator, requests history, and updates it).
- **Multi-output indicators:** some indicators have more than one output, each a named sub-property with its own `.Current.Value` — e.g. BollingerBands → `bb.UpperBand.Current.Value`, `bb.MiddleBand.Current.Value`, `bb.LowerBand.Current.Value`; MACD → `macd.Current.Value`, `macd.Signal.Current.Value`, `macd.Histogram.Current.Value`. Check the specific indicator for its output names.
- **React per update (optional):** `indicator.Updated += Handler` fires every time the indicator produces a new value; the handler receives `(indicator, IndicatorDataPoint)`. Use it to act or plot on each update instead of polling in `OnData`.

## Combining indicators

To build a spread, ratio, weighted blend, or an indicator-of-an-indicator, use the `IndicatorExtensions` extension methods. They return a new composite indicator:

```csharp
var smaFast = SMA(symbol, 14);
var smaSlow = SMA(symbol, 21);
var spread  = smaFast.Minus(smaSlow);           // also Plus / Times / Over
var ratio   = RSI(symbol, 14).Over(2);          // indicator and a constant
var blended = smaFast.WeightedBy(smaSlow, 3);   // lookback-weighted average
```

- Chaining feeds the source's `Current.Value` only; to chain on another field, write a custom indicator.
- For an indicator-of-an-indicator, the **target `(receiver)` of `.Of(...)` must be a MANUAL indicator** (e.g. `new SimpleMovingAverage(10)`), never an auto helper like `SMA(...)`, or it gets double-updated:

```csharp
var rsi = RSI(symbol, 14);
var rsiSma = new SimpleMovingAverage(10).Of(rsi);   // 10-period SMA of the 14-period RSI
```

## Rolling mean / std of a value you compute — feed an indicator, don't loop
A rolling mean, std, or vol is a rolling statistic even when its INPUT is a value YOU compute each period (not a raw bar field). Create the indicator once and feed it with `.Update(time, yourValue)`; read `.Current.Value`. Do NOT keep a `Queue` of records and recompute the mean/variance in a loop.

```csharp
// 14-day mean of a per-day, per-time-of-day metric (e.g. |close_at_mark / open - 1|) — one SMA per mark:
_markSma = marks.ToDictionary(mark => mark, mark => new SimpleMovingAverage(14));
// ...each day, once you have today's value for `mark`:
_markSma[mark].Update(Time, todaysMove);    // feeds the indicator
var sigma = _markSma[mark].Current.Value;   // the 14-day mean — no loop
```

Warm it up by feeding the historical values the same way. For the std of **returns**, compose `ROC`→`StandardDeviation`:

```csharp
var dailyRet = ROC(symbol, 1, Resolution.Daily);    // 1-day return
var vol = new StandardDeviation(14).Of(dailyRet);   // 14-day rolling std of daily returns
```

`StandardDeviation` (and `Variance`) compute the **population** statistic (÷n). That ÷n vs ÷(n−1) difference is immaterial for a rolling estimate — **use the indicator even when a spec writes "sample std (÷n−1)"**; do NOT hand-roll a variance loop to match the divisor.

## Custom indicators

When LEAN doesn't ship the indicator you need, subclass `Indicator` (or `BarIndicator` for OHLCV input): override `ComputeNextValue` (return the new value) and `IsReady`, and declare a `WarmUpPeriod` property (implementing `IIndicatorWarmUpPeriodProvider`):
```csharp
public class CustomSMA : Indicator, IIndicatorWarmUpPeriodProvider
{
    private readonly RollingWindow<decimal> _queue;

    public int WarmUpPeriod => _queue.Size;

    public CustomSMA(string name, int period) : base(name)
    {
        _queue = new RollingWindow<decimal>(period);
    }

    public override bool IsReady => _queue.IsReady;

    protected override decimal ComputeNextValue(IndicatorDataPoint input)
    {
        _queue.Add(input.Value);
        return _queue.Sum() / _queue.Count;
    }
}
```
- **Automatic updates:** `RegisterIndicator(symbol, custom, Resolution.Daily)` — the engine feeds it; the template above is complete.
- **Manual updates:** calling `custom.Update(...)` yourself just works — the `Indicator` base class maintains `Current` and raises `Updated` for you.
- **Warm-up:** loop a history request and call `.Update(bar.EndTime, bar.Close)`, or — if it exposes a `WarmUpPeriod` `property` — `WarmUpIndicator(symbol, custom, Resolution.Daily)`.

## Don't hand-roll a rolling statistic with repeated `History()`

Recomputing an N-period return / moving average / volatility with a `History()` request on every rebalance is slow and off-by-one-prone. Register the indicator once and read it. A one-off startup seed or a single lookback is a fine use of `History()`; a per-bar or per-rebalance recompute of a rolling statistic is the smell that an indicator belongs there instead.
