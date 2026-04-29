---
name: scheduled-events
description: Use when adding or reviewing Scheduled Events in a QuantConnect/LEAN algorithm. Triggers — code uses `Schedule.On(...)`, `DateRules.*`, `TimeRules.*`; questions like "rebalance weekly/monthly", "fire at 8am ET", "why is my rebalance using yesterday's universe", "schedule 15 min before close", "daily Crypto rebalance at midnight UTC", "why does my hourly schedule fire at the wrong time", "multi-asset rebalance across time zones". Skip when — scheduling indicator updates (those route through universe selection or `PlotIndicator`, not `Schedule.On`).
---

# Scheduled Events in QuantConnect / LEAN

Scheduled Events are the classic way to run rebalancing or periodic logic on a recurring schedule.

## Basic rebalance pattern

```csharp
// Weekly Equity rebalance at 08:00 ET (dynamic-universe convention when using daily resolution)
Schedule.On(DateRules.WeekStart("SPY"), TimeRules.At(8, 0), WeeklyRebalance);

// Monthly, first trading day of the month, at a safe hourly boundary
Schedule.On(DateRules.MonthStart("SPY"), TimeRules.At(10, 0), MonthlyCheck);

// Every trading day, 15 minutes before SPY's session close — minute or second resolution only
Schedule.On(DateRules.EveryDay("SPY"), TimeRules.BeforeMarketClose("SPY", 15), EndOfDay);

// One-off event on a specific date
Schedule.On(DateRules.On(2024, 6, 3), TimeRules.At(9, 0), OneShot);

// Daily Crypto at midnight UTC (requires the UTC time-zone override).
// Pass a method group (`CryptoRebalance`), not `CryptoRebalance()` — that would invoke immediately.
TimeRules.SetDefaultTimeZone(TimeZones.Utc);
Schedule.On(DateRules.EveryDay("BTCUSD"), TimeRules.Midnight, CryptoRebalance);
```

## Pick a fire time outside every bar

A Scheduled Event that fires while a bar is still forming reads stale data (the previous bar) and may place orders that fill at prices inconsistent with what the logic "saw." Pick a fire time that lands _between_ bars (or outside the asset's trading session). The valid windows depend on the data resolution.

### Minute data

Fire at the top of any minute — every minute boundary is a bar boundary.

### Hour data

**Hourly bars are wall-clock aligned**, not aligned to session start. They roll at the **top of each hour** (10:00, 11:00, 12:00, …), regardless of when the market opens.

- **24-hour asset (e.g. Crypto):** bars run 00:00–01:00, 01:00–02:00, …, continuously. Fire only at the top of an hour.
- **Non-24-hour asset (e.g. US Equities):** the session opens at 09:30 ET, but that does **not** shift the hourly grid. Bars for a regular session are:
  - 09:30–10:00 (partial first bar, 30 minutes)
  - 10:00–11:00, 11:00–12:00, 12:00–13:00, 13:00–14:00, 14:00–15:00
  - 15:00–16:00 (last bar, closes at the session close)

  Valid fire times _during_ regular trading hours are therefore **10:00, 11:00, 12:00, 13:00, 14:00, or 15:00** only. `10:30`, `11:30`, etc. are all mid-bar — the session starting at 09:30 does **not** make 10:30 a boundary. If the user asks for an intraday time that isn't a top-of-hour (e.g. 10:30), either switch to minute resolution or surface the conflict to the user rather than silently firing mid-bar.

  _Outside_ regular trading hours: no hourly bars are being generated, so **any minute is fine** (e.g. `08:37` or `17:45`). If the subscription enables extended market hours, the "no bars" window shrinks to times outside regular **and** extended hours.

### Daily data

- **24-hour asset (e.g. Crypto):** fire only at midnight.
- **Non-24-hour asset (e.g. US Equities):** fire any time **outside** the daily bar's start and end (before 09:30 ET or after 16:00 ET, extended appropriately if extended hours are subscribed).

## Dynamic US Equity universes on daily data: use 08:00 ET

```csharp
Schedule.On(DateRules.WeekStart("SPY"), TimeRules.At(8, 0), Rebalance);
```

**Why 08:00:** in live trading, universe selection runs between 07:00 and 08:00 ET. Scheduling the rebalance at 08:00 guarantees `_universe.Selected` reflects the **current day's** universe constituents, not the previous day's.

## String tickers work

Use a plain string ticker in the date/time rules to create the schedule for US Equities without subscribing to an extra security you don't want to trade.

```csharp
DateRules.WeekStart("SPY");
TimeRules.AfterMarketOpen("SPY", 1);
```

## Align universe selection with the rebalance schedule

If the rebalance doesn't run every trading day (e.g. weekly or monthly), match the universe selection schedule to the same date rule. Otherwise, universe selection fires daily and the results are discarded on non-rebalance days — wasted computation in backtests and unnecessary data fetches in live.

```csharp
var dateRule = DateRules.WeekStart("SPY");
UniverseSettings.Schedule.On(dateRule);
UniverseSettings.Resolution = Resolution.Daily;
_universe = AddUniverse(Universe.ETF("SPY"));
Schedule.On(dateRule, TimeRules.At(8, 0), Rebalance);
```

Using the **same** `WeekStart("SPY")` date rule on both ensures that on every day the rebalance runs, `_universe.Selected` reflects a selection run earlier the same day (between 07:00 and 08:00 ET in live). On every other day, neither runs.

## Time zones

`TimeRules.At(hour, minute)` interprets the hour/minute in the **time-rule default time zone**, which defaults to the algorithm time zone (New York / ET unless overridden). The "bar boundary" you're dodging is in the **data's** time zone — so these have to match, or the event will run mid-bar.

### Crypto (UTC-based data)

Crypto price datasets are in UTC. If the event reads or trades Crypto, match the time rule to UTC:

```csharp
TimeRules.SetDefaultTimeZone(TimeZones.Utc);

Schedule.On(
    DateRules.EveryDay("BTCUSD"),
    TimeRules.Midnight,   // interpreted as 00:00 UTC — top of the daily Crypto bar
    Rebalance);
```

Without the time-zone override, `Midnight` means midnight ET, which lands mid-bar in UTC.

### Assets across multiple time zones (e.g. US Equities + Crypto)

The typical pattern is **one** rebalance function that trades every asset class at once — not one Scheduled Event per asset class. That means picking a single fire time that's valid for _all_ assets simultaneously.

General approach:

1. For each asset class, write down its "valid fire" windows in a common time zone (UTC is usually easiest). Include any extra constraints like the 08:00 ET universe selection cutoff for dynamic US Equity universes.
2. Pick a time in the intersection of all the windows.
3. Set the time-rule default time zone to that common zone so `At(...)` reads naturally, or pass it to `At(hour, minute, timeZone)` explicitly.
4. **If the intersection is empty, change a data resolution.** A narrower bar gives wider valid-fire windows — e.g. dropping daily Crypto to hourly turns "only 00:00 UTC is valid" into "any top-of-hour is valid."

Worked example: daily dynamic US Equity universe (must fire at 08:00 ET, which is 12:00 or 13:00 UTC) + daily Crypto (must fire at 00:00 UTC). The valid-fire windows don't overlap, so either use hourly Crypto (then the Equity slot at 08:00 ET / 13:00 UTC is also a top-of-hour Crypto slot and works), or accept separate Scheduled Events as a last resort.

## Common mistakes

- **Calendar ticker that doesn't match the traded asset** — pass the asset's own ticker to `DateRules.WeekStart("SPY")` so the weekend / holiday calendar matches the market the asset trades on.
