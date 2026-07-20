---
name: calendar
description: Use whenever a strategy depends on the market calendar — market closures/holidays, trading-day counting, the Nth trading day before/after a date, or whether a date is a trading day. Use QuantConnect's built-in calendar; do not hand-roll a holiday calendar or hardcode dates.
---

# Market calendar — use QC's built-in calendar, don't reinvent it

Don't hand-roll a trading-day loop or hardcode dates. QuantConnect exposes the real historical calendar for each security's exchange. But it does NOT hand you a clean "named holidays" list, so read this carefully — the obvious-looking APIs do something different from what their names suggest (all behaviour below is verified on the cloud).

## Stepping / counting trading days — THIS is the reliable primitive
On the exchange hours object:
```python
hours = self.securities[symbol].exchange.hours
prev = hours.get_previous_trading_day(d)   # -> datetime, trading day strictly before d
nxt  = hours.get_next_trading_day(d)        # -> datetime, trading day strictly after d
open_today = hours.is_date_open(d)          # -> bool, True iff the exchange trades on date d
```

These skip weekends **and** all closures, so no manual weekend/holiday checks are needed.
- "Last trading day before date H" → `hours.get_previous_trading_day(H)`.
- "Nth trading day before H" → start at H and call `get_previous_trading_day` N times in a loop.
- Verified: before Memorial Day 2025 (Mon 5/26), one step → 5/23, five steps → 5/19; `is_date_open(5/26)` → `False`.

## The session open/close TIME on a date (early-close aware)
For the actual TIME the exchange opens/closes on a date — not just *whether* it trades — use the exchange-hours helpers, instead of reading `.early_closes` or hand-rolling the session segments:
```python
hours = self.securities[symbol].exchange.hours
close_dt = hours.get_next_market_close(self.time, False)   # next close at/after self.time; extended_hours=False
open_dt  = hours.get_next_market_open(self.time, False)
```

These return the REAL boundary for that date, so they already account for early-close half-days (e.g. a 13:00 close) and holidays. Use them for time-to-close logic, or to find when "the close" actually is, rather than hardcoding 16:00 or computing it from the hours segments yourself.

## Full-day market closures — `exchange.hours.holidays`
`self.securities[symbol].exchange.hours.holidays` → a `set` of `datetime` (midnight) of EVERY full-day closure for that exchange. Compare on the date component: `d.date() in {h.date() for h in hours.holidays}`.

This is NOT a list of the named federal holidays. It is the union of ALL full-day closures, which includes:
- Good Friday (a market closure but not a federal holiday), and
- ad-hoc closures — e.g. 2025-01-09 was closed for a national day of mourning; historically also hurricanes, 9/11, etc.

So you cannot get "the nine federal holidays" by taking `.holidays` and dropping Good Friday — you would still pick up the ad-hoc days. Use `.holidays` / `is_date_open` to answer "is the market closed on date D," not to enumerate named holidays.
Half-days are separate: `.early_closes` / `.late_opens` (dicts) — a half-day is not in `.holidays`.

## Pitfall — `get_days_by_type` does NOT return holidays
`self.trading_calendar.get_days_by_type(TradingDayType.PUBLIC_HOLIDAY, start, end)` returns ALL non-business days (weekends + closures) — ~117 for a single year, not ~10. The `TradingDay.public_holiday` flag is likewise `True` for weekends (a Saturday comes back with `public_holiday=True, weekend=True`). Do not use this method, or that flag, to find holidays.
- `self.trading_calendar.get_trading_days(start, end)` is fine for enumerating days; identify a weekday closure as `business_day == False and weekend == False` (this still includes Good Friday + ad-hoc closures, same caveat as `.holidays`).

## When a strategy targets SPECIFIC NAMED holidays
QC's calendar can tell you whether a day is closed and can count trading days, but it cannot name a closure. If the method needs particular named holidays (e.g. the nine U.S. federal holidays, excluding Good Friday), compute each holiday's NOMINAL calendar date by its rule (a fixed date like `datetime(y, 7, 4)`, or an Nth-weekday like the 3rd Monday of January; honor start years such as Juneteenth from 2022). Then ANCHOR on that nominal date and let the calendar API map it to trading days:
- "Last trading day before holiday H" → `hours.get_previous_trading_day(nominal_H)`. It already skips the weekend, the observed closure, and any adjacent closures, so it returns the correct day in every case.
- Do NOT hand-roll observed-date shifts (Saturday→Friday / Sunday→Monday). The generic shift is WRONG for New Year's: NYSE does not close the preceding Friday when Jan 1 is a Saturday (verified — Dec 31 2021 was a normal trading day, with `is_date_open` `True` and not in `.holidays`), so a hand-rolled shift mis-dates the window by a day. The nominal-anchor approach is correct for all of them, New Year included, with no special cases.

Do not try to reverse-engineer names out of `.holidays`.
