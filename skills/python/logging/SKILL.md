---
name: logging
description: Use when adding or reviewing logging in a QuantConnect/LEAN algorithm. Triggers — code uses `self.log`, `self.debug`, `self.error`, `self.quit`; questions like "why are my logs missing", "log quota exceeded", "too many log lines per bar", "how do I log every fill", "where do my prints go", "save backtest data for Research analysis", "log spam in `on_data` / inside loops". Skip when — goal is sending alerts out of the algo (email/SMS/webhook → `notifications` skill).
---

# Logging in QuantConnect / LEAN

The default mode of an algorithm is **silent**. Logs are quota-bounded with a silent failure: once the budget runs out, no further lines are written — *including* the lines you'd actually need when something breaks. This skill is about keeping log volume low enough that the diagnostic trail survives the run.

## Quotas (and why every line costs)

| Tier | Logs per backtest | Logs per day |
| --- | --- | --- |
| Free | 10 KB | 3 MB |
| Quant Researcher | 100 KB | 3 MB |
| Team | 1 MB | 10 MB |
| Trading Firm | 5 MB | 50 MB |
| Institution | ∞ | ∞ |

Daily quotas restore on a **24-hour rolling window**, not at midnight, and deleting a backtest or project does **not** refund the quota. On Free / Quant Researcher tiers, a chatty algorithm on minute or tick resolution can fill the per-backtest budget in a few simulated days. Live trading has its own limit: **100,000 lines per project, retained for up to one year.**

## Six rules for keeping logs useful

1. **Only log significant events** — trades executed, errors, major state changes (warm-up finished, signal flipped, scheduled rebalance fired). If a reader of the log would skim past the line, it shouldn't be there.
2. **Never log routine HOLD / no-action decisions in loops.** If the algorithm decides "do nothing" for 4900 of 5000 symbols, that's 4900 useless lines per bar. Log only the active subset, or one summary count.
3. **Use summary logging.** Once per day, write one line listing only the active positions and their P&L. Don't write a status line per bar.
4. **Avoid logging inside tight loops or per iteration.** Aggregate inside the loop, emit one line after.
5. **Log at key decision points, not every evaluation.** The values that *led* to a decision are useful; every intermediate computation is noise.
6. **Keep logs actionable and signal-focused.** "Sold 100 SPY at 612.34 on EMA cross down" is actionable. "Bar received, EMA=608.11" is not — it tells you nothing the algorithm did.

## The four logging functions

```python
self.log("My log message")       # log file only — quiet
self.debug("My debug message")   # Cloud Terminal (orange) + log — 1/sec, 200-char cap
self.error("My error message")   # Cloud Terminal (red) + log — 1/sec
self.quit("My quit message")     # log + stops the algorithm (follow with `return`)
```

Default to `log`. Reach for the others only when their specific behavior is needed: `debug` / `error` for a *handful* of events per day you want to see live in the Cloud Terminal, `quit` only on unrecoverable state.

## Patterns

### Log on events, not on bars

A logger in `on_data` writes ~390 lines per simulated trading day at minute resolution. Move it to `on_order_event` (or another event handler) so it fires only when something happened:

```python
def on_order_event(self, order_event):
    if order_event.status == OrderStatus.FILLED:
        self.log(f"{self.time}: filled {order_event.symbol} qty={order_event.fill_quantity} @ {order_event.fill_price:.2f}")
```

### Aggregate inside loops; log once after

A per-symbol log inside the rebalance loop is N lines per rebalance — for a 5000-symbol universe, that's 5000 lines. Compute first, log a summary outside the loop:

```python
targets = [(s, compute_weight(s)) for s in selected]
top = sorted(targets, key=lambda t: -abs(t[1]))[:5]
self.log(f"{self.time}: rebalanced {len(targets)}, top: {top}")
for s, w in targets:
    self.set_holdings(s, w)
```

### Daily summary — only the active positions

`on_end_of_day` fires once per traded symbol per day, so a universe of 5000 symbols would call it 5000 times. Drive the summary from a Scheduled Event instead — once per trading day, at a time outside any data bar (5 minutes after the US Equity close works on every resolution; see the `scheduled-events` skill for the rules).

```python
def initialize(self):
    self.schedule.on(
        self.date_rules.every_day('SPY'),
        self.time_rules.at(16, 5),
        self._daily_summary,
    )

def _daily_summary(self):
    active = [(s, h) for s, h in self.portfolio.items() if h.invested]
    self.log(f"{self.time.date()}: {len(active)} positions, total ${self.portfolio.total_portfolio_value:.2f}")
    for s, h in active:
        self.log(f"  {s}: qty={h.quantity} unrealized={h.unrealized_profit:.2f}")
```

The summary records the *useful* state — only positions actually held, not zeroes for every symbol in the universe.

### End-of-algorithm summary

`on_end_of_algorithm` fires exactly once. Put final stats here.

```python
def on_end_of_algorithm(self):
    self.log(f"Final portfolio value: {self.portfolio.total_portfolio_value:.2f}")
    self.log(f"Wins: {self._wins}, losses: {self._losses}")
```

### Log values *before* the branch, not after

The point of a diagnostic log is to answer "why did (or didn't) this branch fire?" — which requires logging the inputs **before** the `if`, regardless of outcome. Logging only inside the taken branch tells you nothing about the cases where it wasn't.

```python
# Bad — only the days you traded leave a trail.
if bar.close > self._ema.current.value and self.portfolio[self.spy].quantity == 0:
    self._safe_log(f"{self.time}: entering long")
    self.set_holdings(self.spy, 1)

# Good — every decision day has the inputs recorded.
self._safe_log(
    f"{self.time}: close={bar.close:.2f} ema={self._ema.current.value:.2f} qty={self.portfolio[self.spy].quantity}"
)
if bar.close > self._ema.current.value and self.portfolio[self.spy].quantity == 0:
    self.set_holdings(self.spy, 1)
```

If the `if` checks three things, log all three. When an order doesn't happen, the line shows which term blocked it.

### `safe_log` wrapper for live-only diagnostics

In live trading the volume is naturally capped by wall-clock time, so chatty diagnostics that would blow the backtest quota are fine. Route them through a wrapper that's a no-op in backtests:

```python
def _safe_log(self, message):
    if self.live_mode:
        self.log(message)
```

Use it for verbose diagnostics you'd only need *if* something went wrong in live — backtests stay clean, and when an incident happens the log already contains the trail.

### Prefer order tags over a `log` call after the order

Put the *why* on the order's `tag=` rather than a separate `log` line — it travels with the order, shows on the ticket and trade list, and costs nothing against the log quota. Include the values that drove the decision, not just a label.

```python
self.market_order("SPY", 1, tag=f"{self.time}: close={bar.close:.2f} ema={self._ema.current.value:.2f}")
```

### Object Store for structured Research data

For structured rows you'll analyze in a Research notebook (e.g. a CSV of every signal evaluation), the log file is the wrong tool — size-capped and awkward to parse. Accumulate rows in memory and save once in `on_end_of_algorithm`:

```python
def initialize(self):
    self._content = "time,symbol,price,tag\n"

def on_order_event(self, order_event):
    if order_event.status == OrderStatus.FILLED:
        self._content += f"{order_event.utc_time},{order_event.symbol},{order_event.fill_price},{order_event.ticket.tag}\n"

def on_end_of_algorithm(self):
    self.object_store.save(f"{self.project_id}-{self.algorithm_id}.txt", self._content)
```

Read back in Research with `qb.object_store.read(key)`. Use a stable, unique key like `{project_id}-{algorithm_id}.txt` so runs don't clobber each other. Backtests only — in live, the 100K-line log file is the right place for diagnostics.

## Silent footgun: duplicate-message suppression

If you log the **exact same string** more than once, only the first call is written — the rest are dropped silently. A per-bar log like `self.log("Portfolio value below threshold")` looks fine on bar one and then disappears. Always include a changing value (timestamp, price, symbol) so each line is distinct:

```python
self.log(f"{self.time}: portfolio below threshold ({self.portfolio.total_portfolio_value:.2f})")
```

Embedding the actual value being logged usually makes the message unique on its own, removing the need for an explicit timestamp.

## Common mistakes

- **Logging raw market data** (bars, trades, quotes from subscribed datasets). Not permitted *and* the highest-volume content possible. Log the derived quantity (signal value, portfolio value, decision).
- **End-of-run state logged from `on_data`** — belongs in `on_end_of_algorithm` so it fires exactly once.
- **No logging at all in live.** When something goes wrong you'll have nothing to diagnose against. Always log fills and key state transitions; route verbose diagnostics through `safe_log`.
- **`debug` / `error` from `on_data` on minute/tick data.** Rate-limited to ~1/second, so most calls vanish. For high-frequency diagnostics use `log`; reserve `debug`/`error` for a handful of notable events per day.
- **`quit` without a following `return`.** The algorithm *will* stop, but the current method keeps executing until it returns naturally; orders placed after the quit call still go through.
