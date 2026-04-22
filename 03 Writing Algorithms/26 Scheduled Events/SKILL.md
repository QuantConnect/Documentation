---
name: scheduled-events
description: Use when adding or reviewing Scheduled Events in a QuantConnect/LEAN algorithm (portfolio rebalancing, periodic signal checks, anything scheduled via self.schedule.on). Ensures the time rule does not fire inside a data bar's time period, covers the 8AM convention for dynamic US Equity universes on daily data, and notes that string tickers work in date/time rules.
---

# Scheduled Events in QuantConnect / LEAN

Scheduled Events are the classic way to run rebalancing or periodic logic on a recurring schedule.

## Basic rebalance pattern

```python
class DailyUSEquityRebalanceExampleAlgorithm(QCAlgorithm):

    def initialize(self):
        self.set_start_date(2026, 1, 1)
        self.settings.seed_initial_prices = True
        self.universe_settings.resolution = Resolution.DAILY
        self._universe = self.add_universe(self.universe.etf('QQQ'))
        self.schedule.on(
            self.date_rules.week_start('QQQ'),
            self.time_rules.at(8, 0),
            self._rebalance,
        )

    def _rebalance(self):
        if not self._universe.selected:
            return
        symbols = list(self._universe.selected)
        weight = 1 / len(symbols)
        targets = [PortfolioTarget(symbol, weight) for symbol in symbols]
        self.set_holdings(targets, True)
```

```csharp
public class DailyUSEquityRebalanceExampleAlgorithm : QCAlgorithm
{
    private Universe _universe;

    public override void Initialize()
    {
        SetStartDate(2026, 1, 1);
        Settings.SeedInitialPrices = true;
        UniverseSettings.Resolution = Resolution.Daily;
        _universe = AddUniverse(Universe.ETF("QQQ"));
        Schedule.On(DateRules.WeekStart("QQQ"), TimeRules.At(8, 0), Rebalance);
    }

    private void Rebalance()
    {
        if (_universe.Selected == null) 
        {
            return;
        }
        var symbols = _universe.Selected.ToList();
        var weight = 1m / symbols.Count;
        var targets = symbols.Select(s => new PortfolioTarget(s, weight)).ToList();
        SetHoldings(targets, true);
    }
}
```

## Critical rule: never fire inside a data bar

Every asset's data is delivered in bars that cover a time period (see https://www.quantconnect.com/docs/v2/writing-algorithms/key-concepts/time-modeling/periods). A Scheduled Event that fires in the middle of a bar will read stale data (the previous bar) and may place orders that fill at prices inconsistent with what the logic "saw."

**The single rule: don't fire during a bar that's still forming.** Everything below is an application of that rule — work out when the asset's bars are being generated at your resolution, and pick a fire time that lands *between* bars (or outside the asset's trading session).

### Minute data
Fire at the top of any minute — every minute boundary is a bar boundary.

### Hour data

**Hourly bars are wall-clock aligned**, not aligned to session start. They roll at the **top of each hour** (10:00, 11:00, 12:00, …), regardless of when the market opens.

- **24-hour asset (e.g. Crypto):** bars run 00:00–01:00, 01:00–02:00, …, continuously. Fire only at the top of an hour.
- **Non-24-hour asset (e.g. US Equities):** the session opens at 09:30 ET, but that does **not** shift the hourly grid. Bars for a regular session are:
  - 09:30–10:00 (partial first bar, 30 minutes)
  - 10:00–11:00, 11:00–12:00, 12:00–13:00, 13:00–14:00, 14:00–15:00
  - 15:00–16:00 (last bar, closes at the session close)

  Valid fire times *during* regular trading hours are therefore **10:00, 11:00, 12:00, 13:00, 14:00, or 15:00** only. `10:30`, `11:30`, etc. are all mid-bar — the session starting at 09:30 does **not** make 10:30 a boundary. If the user asks for an intraday time that isn't a top-of-hour (e.g. 10:30), either switch to minute resolution or surface the conflict to the user rather than silently firing mid-bar.

  *Outside* regular trading hours: no hourly bars are being generated, so **any minute is fine** (e.g. `08:37` or `17:45`). If the subscription enables extended market hours, the "no bars" window shrinks to times outside regular **and** extended hours.

### Daily data
- **24-hour asset (e.g. Crypto):** fire only at midnight.
- **Non-24-hour asset (e.g. US Equities):** fire any time **outside** the daily bar's start and end (before 09:30 ET or after 16:00 ET, extended appropriately if extended hours are subscribed).

## Dynamic US Equity universes on daily data: use 08:00 ET

```python
self.schedule.on(
    self.date_rules.week_start('SPY'),
    self.time_rules.at(8, 0),
    self._rebalance,
)
```

**Why 08:00:** in live trading, universe selection runs between 07:00 and 08:00 ET. Scheduling the rebalance at 08:00 guarantees `self._universe.selected` reflects the **current day's** universe constituents, not the previous day's.

## Align universe selection with the rebalance schedule

If the rebalance doesn't run every trading day (e.g. weekly or monthly), match the universe selection schedule to the same date rule. Otherwise, universe selection fires daily and the results are discarded on non-rebalance days — wasted computation in backtests and unnecessary data fetches in live.

```python
date_rule = self.date_rules.week_start('SPY')
self.universe_settings.schedule.on(date_rule)
self.universe_settings.resolution = Resolution.DAILY
self._universe = self.add_universe(self.universe.etf('SPY'))
self.schedule.on(date_rule, self.time_rules.at(8, 0), self._rebalance)
```

Using the **same** `date_rules.week_start('SPY')` on both ensures that on every day the rebalance runs, `self._universe.selected` reflects a selection run earlier the same day (between 07:00 and 08:00 ET in live). On every other day, neither runs.

## Time zones

`time_rules.at(hour, minute)` interprets the hour/minute in the **time-rule default time zone**, which defaults to the algorithm time zone (New York / ET unless overridden). The "bar boundary" you're dodging is in the **data's** time zone — so these have to match, or the event will run mid-bar.

### Crypto (UTC-based data)

Crypto price datasets are in UTC. If the event reads or trades Crypto, match the time rule to UTC:

```python
self.time_rules.set_default_time_zone(TimeZones.UTC)

self.schedule.on(
    self.date_rules.every_day('BTCUSD'),
    self.time_rules.midnight,   # interpreted as 00:00 UTC — top of the daily Crypto bar
    self._rebalance,
)
```

Without the time-zone override, `midnight` means midnight ET, which lands mid-bar in UTC.

### Assets across multiple time zones (e.g. US Equities + Crypto)

The typical pattern is **one** rebalance function that trades every asset class at once — not one Scheduled Event per asset class. That means picking a single fire time that's valid for *all* assets simultaneously.

General approach:
1. For each asset class, write down its "valid fire" windows in a common time zone (UTC is usually easiest). Include any extra constraints like the 08:00 ET universe selection cutoff for dynamic US Equity universes.
2. Pick a time in the intersection of all the windows.
3. Set the time-rule default time zone to that common zone so `at(...)` reads naturally, or pass it to `at(hour, minute, time_zone)` explicitly.
4. **If the intersection is empty, change a data resolution.** A narrower bar gives wider valid-fire windows — e.g. dropping daily Crypto to hourly turns "only 00:00 UTC is valid" into "any top-of-hour is valid."

Worked example: daily dynamic US Equity universe (must fire at 08:00 ET, which is 12:00 or 13:00 UTC) + daily Crypto (must fire at 00:00 UTC). The valid-fire windows don't overlap, so either use hourly Crypto (then the Equity slot at 08:00 ET / 13:00 UTC is also a top-of-hour Crypto slot and works), or accept separate Scheduled Events as a last resort.

## String tickers work — don't build a Symbol just for the rule

`date_rules` and `time_rules` methods accept a plain string ticker. The ticker does **not** need to have been added to the algorithm:

```python
# Good — concise, no Symbol boilerplate
self.date_rules.week_start('SPY')
self.time_rules.after_market_open('SPY', 1)
```

## Common mistakes to avoid

- **`after_market_open('SPY', 1)` with daily data** — fires at 09:31 ET, which is inside the daily bar. Use `time_rules.at(8, 0)` instead.
- **`time_rules.every(timedelta(minutes=30))` with hourly data during a trading session** — the `X:30` fires land mid-bar. Either fire only at the top of the hour, or only outside the asset's trading session.
- **Assuming hourly US Equity bars align to the 09:30 open** — they don't. Hourly bars are wall-clock aligned: 10:00, 11:00, 12:00, … are boundaries; 10:30, 11:30, … are not. The first bar of the session is a 30-minute 09:30–10:00 partial, then the grid is whole-hour from there. So `at(10, 30)` with hourly US Equity data fires inside the 10:00–11:00 bar, not on a boundary. If the user specifies a non-top-of-hour intraday time, switch to minute resolution or raise the conflict — don't silently fire mid-bar.
- **Rebalancing a dynamic Equity universe before universe selection finishes** — in live, universe selection runs between 07:00 and 08:00 ET, so firing earlier than 08:00 means `self._universe.selected` still holds the previous day's constituents. If you use daily data, run at 08:00 ET. If you use intraday data, run at the top of the hour or minute, but not before 08:00 ET.
- **Building a `Symbol` just to pass to `date_rules`/`time_rules`** — a string works.
- **Firing on a weekend / holiday calendar that doesn't match the asset** — use the asset's own ticker in `date_rules.week_start('SPY')` so the calendar matches the market the asset trades on.
- **Time-zone mismatch between the time rule and the data** — e.g. scheduling Crypto at `at(0, 0)` without setting UTC, so the event fires at 00:00 ET (05:00 UTC), five hours into the daily Crypto bar. Either call `time_rules.set_default_time_zone(TimeZones.UTC)` or pass the time zone to `at(...)` explicitly.
- **Splitting a multi-asset-class rebalance into one Scheduled Event per asset class** — prefer a single rebalance function firing at one time valid for every asset. If no such time exists, drop a data resolution to widen the valid-fire windows before resorting to multiple events.
- **Running universe selection daily when the rebalance is weekly/monthly** — wastes work. Pass the same date rule to `self.universe_settings.schedule.on(...)` so universe selection and rebalance only run on the same days.

## Quick checklist when writing a Scheduled Event

1. What is the coarsest data resolution of the assets this event reads or trades?
2. Does the chosen time fall **outside** every bar of that resolution for those assets?
3. If the event depends on `self._universe.selected` for a dynamic US Equity universe, is it at/after 08:00 ET?
4. Is the calendar (`date_rules.week_start(...)`) anchored to an asset that trades on the relevant market?
5. Do you know which time zone `time_rules.at(...)` is being interpreted in (algorithm zone by default, or the time-rule default zone)? Use that to verify the fire time lands outside every asset's bar boundaries.
6. If the algorithm trades multiple asset classes, is there a single fire time valid for all of them? If not, widen the windows by dropping a data resolution before splitting into multiple events.
7. If the rebalance runs less often than every trading day, is the dynamic universe on the same `date_rules` via `self.universe_settings.schedule.on(...)`?

## C# equivalents

Every concept above applies identically to C# — only the syntax differs. Mapping:

| Python | C# |
| --- | --- |
| `self.schedule.on(...)` | `Schedule.On(...)` |
| `self.date_rules.week_start('SPY')` | `DateRules.WeekStart("SPY")` |
| `self.time_rules.at(8, 0)` | `TimeRules.At(8, 0)` |
| `self.time_rules.midnight` | `TimeRules.Midnight` |
| `self.time_rules.after_market_open('SPY', 1)` | `TimeRules.AfterMarketOpen("SPY", 1)` |
| `self.time_rules.every(timedelta(hours=1))` | `TimeRules.Every(TimeSpan.FromHours(1))` |
| `self.time_rules.set_default_time_zone(TimeZones.UTC)` | `TimeRules.SetDefaultTimeZone(TimeZones.Utc)` |
| `self.universe_settings.schedule.on(date_rule)` | `UniverseSettings.Schedule.On(dateRule)` |
| `self.add_universe(self.universe.etf('SPY'))` | `AddUniverse(Universe.ETF("SPY"))` |
| `self._universe.selected` | `_universe.Selected` |
| `PortfolioTarget(symbol, weight)` | `new PortfolioTarget(symbol, weight)` |
| `self.set_holdings(targets, liquidate_existing_holdings=True)` | `SetHoldings(targets, liquidateExistingHoldings: true)` |
| `TimeZones.UTC` / `TimeZones.NEW_YORK` | `TimeZones.Utc` / `TimeZones.NewYork` |

C#-only gotchas:
- Pass a **method group** (`Rebalance`) to `Schedule.On`, not `Rebalance()` — the parentheses would invoke it immediately.
- `TimeZones` members use PascalCase (`Utc`, `NewYork`), not the Python `UTC`/`NEW_YORK`.
- Decimal literals for weights (`1m / members.Count`) — `PortfolioTarget` takes `decimal`, and integer division would truncate.
- `_universe.Selected` is an `IEnumerable<Symbol>`; materialize it (`.ToList()`) before indexing or taking the count twice.
