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

## If the event rebalances the portfolio: two extra constraints

Scheduled events are used for lots of things — logging, signal checks, closing trailing positions, periodic reporting. This section applies **only** if the event places `PortfolioTarget`s (i.e. a rebalance).

1. **Enable `Settings.SeedInitialPrices = true` in `Initialize`.** Without it, securities that join the universe may have no price on the first tick of the rebalance — `PortfolioTarget(symbol, weight)` has nothing to size against and the rebalance errors or silently skips. With it, LEAN seeds each new security with its last known price the moment it enters the universe.
2. **Filter out zero-price securities before building targets.** Even with `SeedInitialPrices`, a freshly-added symbol can still have `Price == 0` briefly (delisting day, data gap, no bar for the asset since it entered the universe). Orders at zero throw errors and leave the portfolio under-invested.

```csharp
private void Rebalance()
{
    if (_universe.Selected == null)
    {
        return;
    }
    // Materialize Selected before counting + iterating; it's IEnumerable<Symbol>.
    var securities = _universe.Selected.Select(s => Securities[s]).Where(s => s.Price > 0).ToList();
    if (!securities.Any())
    {
        return;
    }
    // 1m forces decimal division — integer division would truncate to 0.
    var weight = 1m / securities.Count;
    var targets = securities.Select(s => new PortfolioTarget(s, weight)).ToList();
    SetHoldings(targets, true);
}
```

Neither constraint applies to non-rebalancing scheduled events.

## Critical rule: never fire inside a data bar

Every asset's data is delivered in bars that cover a time period (see https://www.quantconnect.com/docs/v2/writing-algorithms/key-concepts/time-modeling/periods). A Scheduled Event that fires in the middle of a bar will read stale data (the previous bar) and may place orders that fill at prices inconsistent with what the logic "saw."

**The single rule: don't fire during a bar that's still forming.** Everything below is an application of that rule — work out when the asset's bars are being generated at your resolution, and pick a fire time that lands _between_ bars (or outside the asset's trading session).

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

## String tickers work — don't build a Symbol just for the rule

The date/time rule methods accept a plain string ticker (or a `Symbol`). The string ticker does **not** need to have been added to the algorithm:

```csharp
// Good — concise, no Symbol boilerplate
DateRules.WeekStart("SPY");
TimeRules.AfterMarketOpen("SPY", 1);
```

**Do not pass a `Universe` object.** It looks reasonable — you've added a universe, and it feels like the rule should anchor to "that universe's calendar" — but the date/time rule methods don't accept a universe and the runtime error is opaque. Pass the ticker of an asset that trades on the relevant market (e.g. `'SPY'` for a US Equity universe):

```csharp
// WRONG — Universe object is not accepted by DateRules / TimeRules
_universe = AddUniverse(SelectAssets);
Schedule.On(
    DateRules.EveryDay(_universe),
    TimeRules.AfterMarketOpen(_universe, 1),
    Rebalance);

// Right — anchor the calendar to a representative ticker for the market
Schedule.On(
    DateRules.EveryDay("SPY"),
    TimeRules.AfterMarketOpen("SPY", 1),
    Rebalance);
```

## Common mistakes to avoid

- **`AfterMarketOpen("SPY", 1)` with daily data** — fires at 09:31 ET, which is inside the daily bar. Use `TimeRules.At(8, 0)` instead.
- **`TimeRules.Every(TimeSpan.FromMinutes(30))` with hourly data during a trading session** — the `X:30` fires land mid-bar. Either fire only at the top of the hour, or only outside the asset's trading session.
- **Assuming hourly US Equity bars align to the 09:30 open** — they don't. Hourly bars are wall-clock aligned: 10:00, 11:00, 12:00, … are boundaries; 10:30, 11:30, … are not. The first bar of the session is a 30-minute 09:30–10:00 partial, then the grid is whole-hour from there. So `At(10, 30)` with hourly US Equity data fires inside the 10:00–11:00 bar, not on a boundary. If the user specifies a non-top-of-hour intraday time, switch to minute resolution or raise the conflict — don't silently fire mid-bar.
- **Rebalancing a dynamic Equity universe before universe selection finishes** — in live, universe selection runs between 07:00 and 08:00 ET, so firing earlier than 08:00 means `_universe.Selected` still holds the previous day's constituents. If you use daily data, run at 08:00 ET. If you use intraday data, run at the top of the hour or minute, but not before 08:00 ET.
- **Building a `Symbol` just to pass to the date/time rules** — a string works.
- **Passing a `Universe` object to the date/time rules** — e.g. `DateRules.EveryDay(_universe)` or `TimeRules.AfterMarketOpen(_universe, 1)`. These methods take a string ticker or a `Symbol`, not a universe. Pass a representative ticker for the relevant market (`'SPY'`, `'BTCUSD'`, etc.).
- **Firing on a weekend / holiday calendar that doesn't match the asset** — use the asset's own ticker in `DateRules.WeekStart("SPY")` so the calendar matches the market the asset trades on.
- **Time-zone mismatch between the time rule and the data** — e.g. scheduling Crypto at `At(0, 0)` without setting UTC, so the event fires at 00:00 ET (05:00 UTC), five hours into the daily Crypto bar. Either call `TimeRules.SetDefaultTimeZone(TimeZones.Utc)` or pass the time zone to `At(...)` explicitly.
- **Splitting a multi-asset-class rebalance into one Scheduled Event per asset class** — prefer a single rebalance function firing at one time valid for every asset. If no such time exists, drop a data resolution to widen the valid-fire windows before resorting to multiple events.
- **Running universe selection daily when the rebalance is weekly/monthly** — wastes work. Pass the same date rule to `UniverseSettings.Schedule.On(...)` so universe selection and rebalance only run on the same days.
