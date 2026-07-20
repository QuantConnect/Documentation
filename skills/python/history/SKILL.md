---
name: history
description: Use whenever you reach for a `history()` request — a startup seed, a one-off lookback, or a recent past value like the previous daily close. For a rolling statistic recomputed every bar, prefer a streaming indicator (see indicators).
---

# History requests — seeds, lookbacks, and recent past values

Use `history()` for a one-off need: seeding a signal at startup, a single lookback, or reading a recent past value (like yesterday's close). If instead you need a rolling statistic recomputed on every bar or scheduled event — a moving average, momentum, rolling volatility — register a streaming indicator once and read it (see the **indicators** skill); don't call `history()` every time to recompute it (slow, and easy to get the as-of bar wrong).

## Two return shapes: typed bars vs the DataFrame — pick by how you'll use it
`history()` returns different shapes depending on the overload; choosing the right one avoids a lot of DataFrame wrangling.
- **Iterating bar-by-bar → use the TYPED overload** `self.history[TradeBar](symbol, period, resolution)` (or `[QuoteBar]`). It yields `TradeBar` objects you read directly — `bar.end_time`, `bar.open`, `bar.high`, `bar.low`, `bar.close`, `bar.volume` — with no `reset_index`, no MultiIndex, no `.dt` accessor. This is the right tool for building per-day/per-bar records or finding the bar at a specific time.
- **Vectorized column math → use the DataFrame overload** `self.history(symbol, period, resolution)` and operate on columns, e.g. `df["close"].pct_change().std()`.
- **DataFrame gotcha:** the index is a MultiIndex `(symbol, time)`, and the `time` level is the bar's **END** time (every LEAN bar is timestamped at its end). For **US Equities** specifically, the first regular-hours minute bar is stamped 09:31 (the 09:30→09:31 bar), not 09:30 — so matching a target time to the bar's *start* silently yields empty results. (Treating the symbol level as the time column is the other common slip.) The typed overload sidesteps all of this — just read `bar.end_time`.

## A prior daily value — `Identity` on daily resolution
`ind = self.identity(symbol, Resolution.DAILY)` gives an indicator whose `ind.current.value` is the close of the last COMPLETED daily bar — a clean way to reference "the previous day's close" each day without a per-event history request.
- Read intraday (e.g. a few minutes before today's close), it holds the PRIOR trading day's close — today's daily bar is not built yet. Verified: on Monday 2024-05-20 at 15:44 ET, `ind.current.value` = 68.40 = Friday 2024-05-17's close (matching a daily-history last close), while the live intraday price was 68.38.
- Warm it up so `ind.is_ready` is `True` at the start; for the warm-up options and the full automatic/manual indicator toolkit, see **indicators**.

## Current session OHLC + recent daily bars — `security.session`
`security.session` is a built-in `RollingWindow` of daily session bars — reach for it instead of a hand-rolled per-day tracker (a record class / `deque` you update yourself) or repeated daily `history()` calls. It defaults to size 0; set the size, then read:

```python
session = self.securities[symbol].session
session.size = 252                        # keep the last 252 sessions (default 0 = off)
today_open  = session.open                # current session open (also .high/.low/.close/.volume, live/cumulative)
prev_close  = session[1].close            # previous session's close (index 1) — guard with session.is_ready
closes      = [x.close for x in session]  # the last N session closes — no history() request
```

`session.open` is the running open from the day's first bar, so it is the clean way to read "today's open" intraday.

**Index [0] is the CURRENT, still-forming session** — intraday its `.close` is the live price, not a completed daily close (verified: at 15:00 `session[0].close` equals the live price while `session[1].close` equals the prior day's official close). For any *previous* daily value use `session[1]`, never `session[0]`.

**`security.session` starts empty — warm it up before you read `session[1]`.**
- **Static universe:** `set_warm_up(n, Resolution.DAILY)` warms it.
- **Dynamic universe:** `set_warm_up` does NOT warm sessions for assets that join later — in `on_securities_changed`, replay daily history through `session.update(bar)`:

```python
def on_securities_changed(self, changes):
    history = self.history[TradeBar]([s.symbol for s in changes.added_securities], 2, Resolution.DAILY)
    for security in changes.added_securities:
        security.session.size = 2
        for bars in history:
            bar = bars.get(security.symbol, None)
            if bar:
                security.session.update(bar)
```

- **Alternative (intraday data):** set `self.settings.automatic_indicator_warm_up = True`, then register a daily `Identity` per asset as its previous close: `security.previous_close = self.identity(security.symbol, Resolution.DAILY)` — its `.current.value` is the last completed daily close.

## When you do legitimately need `history()`
A startup seed or a one-off lookback is a fine use of `history()`. It returns data from before the start date, so a single request can seed a signal with no warm-up. Request it ONCE for all the symbols and the full window you need — do not loop a separate request per symbol or per bar. One request per genuine need is fine; what's wasteful is using a history request to check whether names have *enough* history — pulling N bars for every candidate in a universe screen just to count them stalls the algorithm. For a listing-age / minimum-history filter, read the Fundamental data point's `f.security_reference.ipo_date` instead.

## Start date placement — seed the lookback, don't idle into it
Because `history()` and `set_warm_up` read from BEFORE the start date, set the backtest start to the first date you intend to **trade**, with the lookback seeded from earlier data — not to the data's inception. If you start at the inception, there is no earlier data to seed from, so the strategy spends its first lookback-worth of bars accumulating the window in-sample: a flat warm-up stretch at the start of the equity curve (which also understates CAGR/Sharpe). When the data itself begins partway in (e.g. an ETF that incepts mid-history), set the start to roughly (inception + the longest lookback) so bar 1 already has its window.

This is about being **eligible** to trade from bar 1 — NOT about being always invested. A strategy whose signal legitimately holds cash and moves in/out at precise moments will still show flat stretches (possibly including the start), and those are correct — only a flat stretch caused by an unseeded lookback is the defect.
