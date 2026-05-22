---
name: debug-performance
description: >
  Diagnoses slow QuantConnect Python (.py) and C# (.cs) backtests using the
  Performance Chart first and Python cProfile only when the chart does not
  pinpoint the hot function. Trigger phrases: "slow backtest", "high CPU", "algorithm slow", "CPU usage", "RAM usage", "memory usage", "performance chart", "profiling", "bottleneck", "debug performance", "taking too long", "optimize algorithm".
---

# /debug-performance - QuantConnect Performance Debugging
Invoked on a slow backtest. Work top to bottom. Find the dominant cost before
changing code. Same Performance Chart rules for `main.py` and `main.cs`; Python
can add cProfile only when Section 4 says to.

## 0. Never add error-hiding patterns
Never introduce py`try`/`except`cs`try`/`catch` or
py`hasattr`/`getattr`/`setattr`/`isinstance`cs`reflection`. They hide the exact
failure or slow path. A profiler wrapper measures work; it is not a catch. If
existing code wraps the slow region in one, remove it, re-run, read the real
behavior, then continue.

## 1. Enable Performance Chart first
Record the original py`set_start_date`/`set_end_date`cs`SetStartDate`/`SetEndDate`
and universe, then shrink both: usually 1 to 3 months and the smallest universe
that still reproduces the slowdown. Set
py`self.settings.performance_sample_period = timedelta(7)`cs`Settings.PerformanceSamplePeriod = TimeSpan.FromDays(7)`
in py`initialize`cs`Initialize`, then run the short backtest.
Open the results and inspect the Performance Chart. Record the dominant series:
the tallest spike, or the sustained plateau if there is no single spike. Do not
optimize before naming the series.

## 2. Route by dominant series
Read which series dominates:
- Selection, Consolidators, OnData, Schedule, Subscriptions, Securities,
  Transactions, SplitsDividendsDelisting, Slice, HistoryDataPoints, or
  ActiveSecurities -> Section 3.
- CPU, ManagedRAM, or TotalRAM high with no single time-series dominant ->
  Section 4 for Python, or keep using the chart for C#.
- DataPoints low -> Section 3, Subscriptions row.
If multiple series spike together, start with the earliest causal source:
Subscriptions before Consolidators, Consolidators before OnData, Selection
before Securities or Transactions. Treat CPU and RAM as symptoms until the chart
or profiler names the code path.

## 3. Fix by bottleneck
Change only the code path that corresponds to the dominant series. Re-run the
same short backtest after each change and compare the same chart region.
For rolling calculations, use this order: built-in indicator first, indicator
extension second, `Security.session` for cached OHLCV/session data third, and
manual `RollingWindow`/`deque` only when LEAN has no built-in equivalent. Example:
a trailing mean of closes is an SMA, so use py`self.sma(security, period)` instead
of storing closes and averaging them manually.
| Series | Meaning | Fix |
| --- | --- | --- |
| Selection | Universe add/remove and selection functions. | Reduce coarse universe size; move expensive fundamental queries out of the filter; cache results across calls. |
| Consolidators | Consolidation, indicator updates, consolidator handlers. | Reduce universe size; consolidate at a coarser resolution; share one consolidator where logic allows. |
| OnData | py`on_data`cs`OnData` and alpha update time. | Move heavy logic to a scheduled event; replace manual series calculations with a built-in indicator or indicator extension before considering a manual window. |
| Schedule | Scheduled event handler time. | Reduce event frequency; cache intermediate results computed in the handler. |
| Subscriptions | Time reading subscribed data. | Reduce data resolution or universe size; confirm only needed resolutions are subscribed. |
| Securities | Security updates, security changes, symbol changes. | Reduce py`active_securities`cs`ActiveSecurities` count, usually fewer holdings or open orders. |
| Transactions | Order event processing. | Batch orders via portfolio targets; reduce rebalance frequency. |
| SplitsDividendsDelisting | Corporate action events. | Reduce universe size or move corporate-action logic out of the handler. |
| Slice | Time creating the py`slice`cs`Slice` object. | Reduce subscription count, resolution, and custom data fields before optimizing handlers. |
| HistoryDataPoints | History provider data points. | Reduce py`history`cs`History` calls; replace rolling calculations with indicators/extensions; use py`Security.session`cs`Security.Session` for cached OHLCV/session data; request fewer symbols, fields, or bars. |
| ActiveSecurities | Selected securities plus holdings and open orders. | Prune stale symbols; liquidate or cancel old positions/orders before broadening the universe. |
Logging rules: measure with py`self.log(...)`cs`Log(...)` in the narrow handler
being debugged. Never use the Object Store for profiler output. Never log inside
py`on_data`cs`OnData` or an often-firing scheduled event without a counter limit.
Remove diagnostic logs after the fix.

## 4. Python profiler with logs
Use cProfile only when CPU, ManagedRAM, or TotalRAM is high and the Performance
Chart does not isolate the expensive function. This is Python only. C# does not
have a built-in cProfile equivalent; use the chart series exclusively.
1. At the top of `main.py`, before the class:
```python
from cProfile import Profile
from pstats import Stats
from io import StringIO

profile = Profile()
profile.enable()
```
2. In py`on_end_of_algorithm`cs`OnEndOfAlgorithm`, disable the profiler and log
   the top cumulative-time lines. Do not save the report to the Object Store:

```python
def on_end_of_algorithm(self):
    profile.disable()
    stream = StringIO()
    Stats(profile, stream=stream).sort_stats('cumulative').print_stats(20)
    lines = stream.getvalue().splitlines()
    for line in lines[:40]:
        self.log(f"PROFILE {line}")
```

3. Read the cumulative time column in the backtest logs. The top function is the
   bottleneck; map it back to a Section 3 series and apply the matching fix.
Remove the profiler after measurement unless the user explicitly wants another
profiling run.

## 5. Checklist
1. No banned error-hiding pattern (Section 0).
2. Original dates + universe recorded before shrinking.
3. Performance Chart enabled; short backtest run to expose the dominant series.
4. Dominant series identified before applying any fix (Section 2).
5. Fix targets the real bottleneck series, not a guess (Section 3).
6. Built-in indicators/extensions considered before adding py`RollingWindow`/`deque`cs`RollingWindow`.
7. Profiler used only for Python when chart alone does not pinpoint the function.
8. Profiler output logged with py`self.log`cs`Log`; Object Store not used.
9. Original period + universe restored; final backtest confirms the spike is gone.
