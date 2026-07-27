---
name: universes
description: Base mechanics shared by EVERY dynamic universe in QuantConnect/LEAN — what universe selection returns is what QC subscribes to, the MINUTE resolution default, scheduling selection to the strategy's rebalance calendar, and what selection should return during warm-up. Load alongside the specific universe skill (fundamental-universes, indicator-universes, cross-sectional, alternative-data-universes) whenever an algorithm calls `add_universe(...)`. Skip when — the algorithm subscribes a fixed asset list directly (no universe selection).
---

# Dynamic universes — the mechanics every universe shares

Whatever your universe selection function returns is what QC subscribes to and streams every bar. Deselection does not always end the subscription: LEAN keeps a security that you still hold, that has an open order, or that has not yet met the `minimum_time_in_universe` setting — so held names keep streaming after they leave the selection. Selection itself is where you filter, rank, and maintain per-symbol state; subscriptions are only for the names the algorithm actually trades or reads bar data for. The topical skills (`fundamental-universes`, `indicator-universes`, `cross-sectional`) cover what to compute in selection; the rules below apply regardless.

## Set the universe resolution — the default is MINUTE
`universe_settings.resolution` defaults to minute resolution: every selected name streams a full session of minute bars every day — ~390 for US Equities, up to ~1,440 for 24-hour asset classes. Match the resolution to where decisions and fills actually happen. A method that decides after the close and fills at the next open needs daily data only — the minute default streams hundreds of times the data for zero benefit and dominates backtest runtime. But a method that decides or fills DURING the session — an intraday signal time, market orders meant to fill immediately, an at-the-close entry — needs intraday data: daily bars only arrive after the session ends, so on daily data an intraday order cannot fill at the intended time (see the scheduled-events skill for the timing mechanics). Set it explicitly, before `add_universe`:

```python
self.universe_settings.resolution = Resolution.DAILY   # coarsest resolution the method needs
self._universe = self.add_universe(self._select_assets)
```
## Schedule selection to the strategy's rebalance calendar
Unscheduled universes re-select EVERY day; a calendar-rebalanced strategy (weekly/monthly/quarterly formations) then discards almost every selection — wasted computation in backtests and unnecessary data fetches live. Anchor selection to the same date rule the rebalance uses:

```python
date_rule = self.date_rules.month_end("SPY")           # match the strategy's formation calendar
self.universe_settings.schedule.on(date_rule)
self.universe_settings.resolution = Resolution.DAILY
self._universe = self.add_universe(self._select_assets)
```
Two qualifications:
- **Per-symbol indicators are denominated in selection fires** — an N-period indicator updated by the selection function means "N selection periods," so match the selection cadence to the indicator's intended unit. A 21-DAY SMA or a 252-day return window needs daily selection (scheduling it monthly silently turns it into a 21-month SMA); a 12-MONTH rate-of-change is correctly — and most cheaply — fed by month-start selections. The unit MISMATCH is the bug, not the slower cadence itself; see the `indicator-universes` skill's cadence section, including its bar-indicator caveats.
- **A strategy that must sample data between rebalances** (e.g. catching newly filed fundamentals) can schedule selection at the finer sampling cadence (e.g. monthly) and gate the heavy formation work inside the selection function to formation dates only.

## Warm-up: return no symbols — except a sparse schedule's final pre-start selection
During `set_warm_up`, selection functions still run and per-symbol state should accumulate — but returning symbols subscribes them, and there is nothing to trade yet. Return an empty list during warm-up. One exception, for SCHEDULED (sparse) universes: the final scheduled selection before warm-up ends must return the tradable set — otherwise the universe stays empty until the next scheduled fire after the start date, and the strategy sits flat for up to a full schedule period (a defect, not a warm-up artifact). Daily-cadence universes need no exception: the first live-day selection populates the universe immediately.

```python
def _select_assets(self, fundamentals):
    # ...update per-symbol state on every fire (warm-up included)...
    if self.is_warming_up and not final_prestart_fire:   # e.g. next scheduled fire is still before start_date
        return []                                        # accumulate state; subscribe nothing
    # ...final pre-start fire and all live fires: return the traded/held set...
```
## Per-symbol state lives in the selection function
State that outlives one selection (rolling price windows, indicators, accumulated fundamental snapshots) belongs in a per-symbol dict maintained inside the selection function: seed a symbol once on first sight, update it every fire from the data the callback hands you, and prune it when the symbol leaves the passed collection. Do not re-seed with `history()` on every appearance, and do not keep names subscribed merely to feed state — the selection callback sees the whole cross-section without subscribing any of it.
