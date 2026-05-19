---
name: debug-algorithm
description: >
  Diagnoses QuantConnect Python (.py) and C# (.cs) algorithm failures: runtime
  exceptions and zero-trade backtests. Invoked by other agents the moment an
  algorithm throws a runtime error or completes a backtest with 0 orders. Walks
  an ordered checklist to the root cause without hiding it. Trigger phrases:
  "runtime error", "stack trace", "0 orders", "no trades", "flat equity curve",
  "unknown property", "AttributeError", "KeyNotFoundException", "wasn't found in
  the DataDictionary", "insufficient buying power", "indicator not ready",
  "debug the algorithm".
---

# /debug-algorithm - QuantConnect Cross-Language Debugging

Invoked on a runtime error or a 0-order backtest. Work top to bottom. Find the
real cause; never hide it. Same rules for `main.py` and `main.cs`.

## 0. Never add error-hiding patterns

Never introduce py`try`/`except`cs`try`/`catch` or
py`hasattr`/`getattr`/`setattr`/`isinstance`cs`reflection`. They hide the exact
failure. If existing code wraps the failing region in one, remove it, re-run,
read the real exception, then continue. Only add an `if` check to skip a known,
expected case (such as a header line) by testing that exact condition. Adding a
check just to make a crash or 0 trades go away is not a fix; fix the cause.

## 1. Shorten the backtest, then find the failure type

Record the original py`set_start_date`/`set_end_date`cs`SetStartDate`/`SetEndDate`
and universe, then shrink both: the smallest window that still reproduces the
failure (runtime error: a span around the error date; 0 trades: 3 to 6 months),
and the universe to a small fixed symbol list or tight filter. Only after the
fix gives non-zero orders with no runtime error, restore the original dates and
universe and run ONE final full-period backtest to confirm.
Match the failure type:
- Runtime exception -> Section 2.
- 0 orders / flat equity -> Section 3.

## 2. Runtime exception

Read the actual message and line; never guess.
- Unknown property or method / AttributeError / "no attribute" -> Section 4.
- KeyNotFound / "wasn't found in the DataDictionary": find WHY the key is
  absent first. Custom/alt data (py`add_data`cs`AddData`) has its OWN symbol
  (the return value), not the equity symbol; reading alt data with the equity
  symbol is the usual cause. Key by py`add_data(...).symbol`cs`AddData(...).Symbol`;
  field names from the QC docs page (Section 4), never py`getattr`cs`reflection`.
  Skipping a wrong key with a check just turns the crash into 0 trades
  (Section 3). py`symbol in data`cs`ContainsKey` is right only for a real
  fillforward gap on a correct key.
- None / null price: py`seed_initial_prices`cs`SeedInitialPrices` only looks
  back 3 days; keep only securities where py`security.price`cs`security.Price`
  is set.
- Insufficient buying power: reduce exposure, then derivative sizing, then raise
  py`free_portfolio_value_percentage`cs`FreePortfolioValuePercentage`; add
  py`liquidate_existing_holdings=True`cs`liquidateExistingHoldings: true`; if a
  9:00 schedule still fails, move it to `9, 31` (sizes on intraday price).

## 3. 0 trades / 0 orders

Stop at the first that applies. NEVER add or tighten a check here.
1. Selection too strict OR too loose (the usual flat equity / silent-fail
   cause).
   - Too strict: a long chain of py`if ...: continue`cs`if (...) continue;`
     checks drops every candidate, so py`set_holdings`cs`SetHoldings` runs with
     an empty list -> 0 orders / flat equity.
   - Too loose: the filter passes a very large set (e.g. 1000+) into an
     equal-weight py`set_holdings`cs`SetHoldings`; LEAN cannot size that many
     positions and silently places no orders.
   Measure with py`self.log(...)`cs`Log(...)` in the rebalance/filter: log the
   count passing each check and the final selected count. Find the one check
   responsible, then loosen or tighten just it. Common cause: data an earlier
   bug left empty (e.g. a signal dict from the Section 2 wrong-symbol bug), or
   thresholds too strict to ever happen together. Logging rules: never
   py`set_runtime_statistic`cs`SetRuntimeStatistic` or the Object Store; never
   py`self.log`cs`Log` in py`on_data`cs`OnData` or an often-firing scheduled
   event (it floods); if unavoidable there, gate it to the first N calls with a
   counter.
2. Schedule / frequency mismatch:
   - Daily-resolution data with an intraday TimeRule (e.g.
     py`time_rules.after_market_open`cs`TimeRules.AfterMarketOpen`) fires before
     the daily bar is delivered, so the rebalance reads stale/empty data. With
     daily data schedule via py`self.date_rules...`cs`DateRules...` +
     py`self.time_rules.at(8, 0)`cs`TimeRules.At(8, 0)`; use an intraday
     TimeRule only with minute/hour data.
   - Rebalance reads py`self._universe.selected`cs`Universe.Selected` on a
     different/earlier frequency than the universe updates, so it sees an empty
     set. Align both to the same DateRule, or drive the rebalance from
     py`on_securities_changed`cs`OnSecuritiesChanged`.
3. Universe empty / wrong `Universe.UNCHANGED` return (only a filter that just
   stores symbols and selects nothing returns it; a real selection returns a
   list).
4. Securities dropped for missing price (see Section 2).
5. Indicators never ready: py`automatic_indicator_warm_up = True`cs`AutomaticIndicatorWarmUp = true`
   set BEFORE any factory call; feed manual indicators/consolidators in
   py`initialize`cs`Initialize` via a history loop; warm-up length fits the
   rebalance frequency (weekly 14d, monthly or slower 45d). Futures/continuous
   contracts need this.

## 4. Unknown property or method

Do not find a property or method by py`hasattr`/`getattr`cs`reflection`. Find
the real property/field name on the dataset's or type's QC docs page, then use
it directly.
Quick rules:
- Alt-data fields (headline, body, sentiment, etc.) come from that dataset's
  page, never from py`getattr`cs`reflection` guessing.
- Comparing indicators: use py`indicator.current.value`cs`indicator.Current.Value`.
  Direct object comparison only works when both are the same indicator type;
  different types (or an indicator vs a sub-indicator) need the explicit
  `.current.value`. Previous value:
  py`indicator.previous.value`cs`indicator.Previous.Value`, not `.window[0]`.
- Fundamental data missing: py`np.isnan(value)`cs`double.IsNaN(value)`,
  never `!= 0`.
- Yesterday's daily OHLC: py`security.session`cs`security.Session`
  (set `.size`; index `[1]` is yesterday).
Change one access at a time, then re-run.

## 5. Checklist

1. No banned pattern (Section 0).
2. Original dates + universe recorded before shrinking.
3. Fix targets the real exception line, not a guess.
4. Alt/custom data keyed by the py`add_data`cs`AddData` symbol; presence checks
   only for real fillforward gaps.
5. Unknown properties/fields taken from the QC docs page, not guessed.
6. No check added/tightened to mask 0 trades; cause found via
   py`self.log`cs`Log`; selection not so large py`set_holdings`cs`SetHoldings`
   silently no-ops (Section 3).
7. Universe and rebalance same frequency; schedule matches data (daily not on an
   intraday TimeRule, DateRule symbol subscribed); indicators warmed; no
   buying-power rejection.
8. Original period + universe restored; ONE final backtest confirms non-zero
   orders and no error.
