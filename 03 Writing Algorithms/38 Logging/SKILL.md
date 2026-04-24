---
name: logging
description: Use when adding or reviewing logging statements in a QuantConnect/LEAN algorithm. Covers the four channels (log/debug/error/quit), per-tier log quotas, `log`'s duplicate suppression, the 1/sec + 200-char limits on `debug`/`error`, why `quit()` needs a following `return`, the live-only `safe_log` wrapper, preferring order `tag=` over a trailing `log`, and Object Store for structured backtest diagnostics.
---

# Logging in QuantConnect / LEAN

Algorithms have four logging channels — `log`, `debug`, `error`, and `quit` — each with different visibility, rate-limits, and side effects. Picking the wrong channel either spams the Cloud Terminal (and triggers rate-limiting) or hides important information in the log file where nobody will see it.

## The four channels

```python
self.log("My log message")       # log file only — quiet, high volume OK
self.debug("My debug message")   # orange in Cloud Terminal + log file
self.error("My error message")   # red in Cloud Terminal + log file
self.quit("My quit message")     # orange in Cloud Terminal, then stop the algorithm
```

```csharp
Log("My log message");
Debug("My debug message");
Error("My error message");
Quit("My quit message");
```

| Channel | Where it shows | Rate-limit | Use it for |
| --- | --- | --- | --- |
| `log` | Log file only | Duplicate suppression (see below) | High-volume diagnostics, order fills, per-bar state, end-of-algorithm summaries |
| `debug` | Cloud Terminal (orange) + log file | 1 per second, 200-char cap | A handful of notable events you want to *see* while the algorithm runs |
| `error` | Cloud Terminal (red) + log file | 1 per second | Caught exceptions, invariant violations, "this should never happen" branches |
| `quit` | Cloud Terminal (orange) + log file, then **stops the algorithm** | — | Unrecoverable state where the algorithm must not continue |

## Critical rule: be parsimonious — log lines are a scarce resource

Logs are quota-bounded, and the failure mode is silent: once the quota is exhausted the remainder of the run produces no logs at all, including the lines you'd actually need when something breaks. "Log it all and filter later" is not a viable default in this environment — every `log`/`debug`/`error` call has to justify its slot.

Quotas are set per organization tier:

| Tier | Logs per backtest | Logs per day |
| --- | --- | --- |
| Free | 10 KB | 3 MB |
| Quant Researcher | 100 KB | 3 MB |
| Team | 1 MB | 10 MB |
| Trading Firm | 5 MB | 50 MB |
| Institution | ∞ | ∞ |

Daily quotas restore on a **24-hour rolling window**, not at midnight, and deleting a backtest or project does **not** refund the quota. On the Free and Quant Researcher tiers in particular, a chatty algorithm on minute or tick resolution can fill the per-backtest budget in a few simulated days.

Live trading has its own limit: **100,000 lines per project, retained for up to one year.** Overflow lines are trimmed from the oldest end.

Rules for staying under:

1. **Log on events, not on bars.** Order fills, signal changes, scheduled rebalances, warm-up finished, end-of-algorithm — yes. Per-bar state at minute/tick resolution — no, unless the backtest window is short *or* the line is gated by a real event condition.
2. **Prefer alternatives to `log` when one fits.** Order `tag=` for order-placement context (see below); Object Store for structured rows you'll analyze in Research (see below); `safe_log` (live-only, see below) for verbose diagnostics that would overwhelm a backtest.
3. **Logging raw dataset content is not permitted** — and bars, trades, and quotes from subscribed datasets are exactly the high-volume content that drains the quota fastest. Log derived quantities (your signal value, your portfolio value, your decision), not the market data that produced them.
4. **Before adding any `log` / `debug` / `error` call, ask: "is this line worth one of a finite number of slots?"** If the answer is unclear, it probably isn't — route it through `safe_log`, put it on the order as a tag, or write it to the Object Store instead.

When reviewing or generating algorithm code, treat log density the same way you'd treat allocations in a hot loop: the default should be silence, and each line should earn its place.

## Critical rule: `quit()` does not stop execution immediately

`quit()` schedules the algorithm to stop — but the current method keeps running to its end. Any code after the `quit()` call still executes, which means orders can still be placed, state can still be mutated, and later exceptions can still be raised. If the intent is to stop *now*, `return` immediately after the quit call.

```python
if portfolio_is_corrupt:
    self.quit("Portfolio state is inconsistent — bailing out")
    return   # <-- required; without this the method keeps running
```

```csharp
if (portfolioIsCorrupt)
{
    Quit("Portfolio state is inconsistent — bailing out");
    return;  // <-- required
}
```

This applies inside every method, including `on_data`, event handlers, and scheduled functions.

## Duplicate-message rate-limit on `log`

If you log the **exact same string** more than once, only the first instance is written to the log file. This is a common foot-gun: a per-bar log like `self.log("Portfolio value below threshold")` looks fine in the first bar and then silently disappears.

Bypass it by making each message unique — the simplest way is to include the algorithm time:

```python
# Bad — every call after the first is dropped
self.log("Portfolio value below threshold")

# Good — the timestamp makes each message distinct
self.log(f"{self.time}: Portfolio value below threshold ({self.portfolio.total_portfolio_value:.2f})")
```

```csharp
// Good
Log($"{Time}: Portfolio value below threshold ({Portfolio.TotalPortfolioValue:F2})");
```

Embedding the actual value being logged (price, portfolio value, symbol) usually makes the message unique on its own and removes the need for a manual timestamp.

## `debug` and `error` rate-limit + 200-char cap

`debug` and `error` are throttled to roughly 1 message per second — extra messages in the same second are dropped to avoid crashing the browser-rendered Cloud Terminal. `debug` additionally truncates at 200 characters.

Implication: **don't call `debug`/`error` from `on_data` on minute or tick resolution** without guarding it, or from a tight loop. For high-volume diagnostics use `log`; reserve `debug`/`error` for a small number of notable events per day.

```python
# Bad — fires every minute bar, 99% of messages dropped, and 200 chars isn't enough
self.debug(f"Bar {bar}, EMA {self._ema.current.value}, portfolio {self.portfolio.total_portfolio_value}, ...")

# Good — log file for volume, debug for the signal change
self.log(f"{self.time}: bar={bar.close:.2f} ema={self._ema.current.value:.2f}")
if crossed_up and not was_crossed_up:
    self.debug(f"{self.time}: EMA cross up, entering long")
```

## End-of-algorithm summary

To record final state (win rate, custom stats, ending positions), put log statements in `on_end_of_algorithm` — the log file is what survives after the run ends, and this handler fires exactly once at the end.

```python
def on_end_of_algorithm(self) -> None:
    self.log(f"Final portfolio value: {self.portfolio.total_portfolio_value:.2f}")
    self.log(f"Wins: {self._wins}, losses: {self._losses}")
```

```csharp
public override void OnEndOfAlgorithm()
{
    Log($"Final portfolio value: {Portfolio.TotalPortfolioValue:F2}");
    Log($"Wins: {_wins}, losses: {_losses}");
}
```

## Debugging in live mode

In live trading you can't attach a debugger, set breakpoints, or inspect state interactively — **logs are the only debugging tool.** The typical question ("why didn't my algorithm place an order yesterday?") is answerable only if the values that drove the decision were logged *at the time the decision was made*.

Two practices make this tractable:

### 1. A `safe_log` wrapper that only fires in live mode

Verbose diagnostic logging would blow past the per-backtest log quota in seconds, but in live mode the volume is naturally capped by wall-clock time. Route diagnostic logs through a wrapper that is a no-op in backtests:

```python
def safe_log(self, message: str) -> None:
    if self.live_mode:
        self.log(message)
```

```csharp
private void SafeLog(string message)
{
    if (LiveMode) Log(message);
}
```

Use `safe_log` for everything you'd want to inspect *if* something went wrong in live — ordinary backtests stay clean, and when a live incident happens the log already contains the trail.

### 2. Log the values *before* the branch, not after

The point of a debug log is to answer "why did (or didn't) this branch fire?" — which requires logging the values the branch evaluates **before** the `if`, regardless of the outcome. Logging only inside the taken branch tells you nothing about the cases where the branch wasn't taken.

```python
# Bad — only tells you about the days you did trade
if bar.close > self._ema.current.value and self.portfolio[self.spy].quantity == 0:
    self.safe_log(f"{self.time}: entering long")
    self.set_holdings(self.spy, 1)

# Good — records the values every time, so "no order today" is explainable
self.safe_log(
    f"{self.time}: close={bar.close:.2f} ema={self._ema.current.value:.2f} "
    f"qty={self.portfolio[self.spy].quantity}"
)
if bar.close > self._ema.current.value and self.portfolio[self.spy].quantity == 0:
    self.set_holdings(self.spy, 1)
```

Log every value that appears in the condition — if the `if` checks three things, log all three. When an order doesn't happen, the log line shows which of the three was the blocker.

This pairs naturally with `log` (not `debug`/`error`): the volume is too high for the Cloud Terminal, and the duplicate-suppression is not a problem here because each line already contains a changing timestamp and changing values.

## Prefer order tags over `log` for order context

When the thing you want to record is *why this order was placed*, put the context on the order itself via the `tag` parameter rather than calling `log` right after `market_order`. The tag travels with the order, shows up on the order ticket, the order events, the trade list, and — crucially — the Object Store CSV in the pattern below. A separate `log` line is unanchored from the order and is subject to log's duplicate-suppression and log-quota.

```python
# Bad — the "Buy SPY" line is detached from the order and adds a log-quota line
self.market_order("SPY", 1)
self.log("Buy SPY")

# Good — the context lives on the order
self.market_order(
    "SPY", 1,
    tag=f"{self.time}: close={bar.close:.2f} ema={self._ema.current.value:.2f}",
)
```

```csharp
MarketOrder("SPY", 1,
    tag: $"{Time}: close={bar.Close:F2} ema={_ema.Current.Value:F2}");
```

Include the values that drove the decision, not just a human-readable label — `close=612.34 ema=608.11` explains the fill; "Buy SPY" doesn't.

## Object Store logging for Research analysis

For structured diagnostic data you want to analyze in Research (e.g. a CSV of every signal evaluation), the log file is the wrong tool — size-capped and awkward to parse. Accumulate rows in memory and save once in `on_end_of_algorithm`:

```python
def initialize(self) -> None:
    self._content = "time,symbol,price,tag\n"   # CSV header

def on_order_event(self, order_event: OrderEvent) -> None:
    if order_event.status == OrderStatus.FILLED:
        self._content += f"{order_event.utc_time},{order_event.symbol},{order_event.fill_price},{order_event.ticket.tag}\n"

def on_end_of_algorithm(self) -> None:
    self.object_store.save(f"{self.project_id}-{self.algorithm_id}.txt", self._content)
```

C# follows the same pattern with the PascalCase APIs from the equivalents table below. Read back in a research notebook with `qb.object_store.read(key)`.

- **Accumulate in memory, save once** — writing every bar is wasteful.
- **Use a stable, unique key** like `{project_id}-{algorithm_id}.txt` so runs don't clobber each other.
- **Backtests only** — in live, the 100K-line log file remains the right place for diagnostics.

## Picking the right channel

Decision order:

1. **Does the algorithm need to stop?** → `quit(...)` followed by `return`.
2. **Is this something the user needs to *see* in the Cloud Terminal while the algorithm runs?**
   - Error-level (caught exception, invariant violation) → `error`
   - Notable-but-normal (signal change, daily summary) → `debug`
3. **Everything else** → `log`. This is the default. Order fills, per-event diagnostics, end-of-algorithm summaries, live-trading audit trails all belong in `log`.

## Common mistakes to avoid

- **`quit()` without a following `return`** — the algorithm *will* stop, but the current method keeps executing until it returns naturally. Orders placed after the `quit` call still go through.
- **Identical `log` strings in a loop** — everything after the first call is silently suppressed. Include the timestamp or a changing value (symbol, price, portfolio value) so each line is unique.
- **`debug`/`error` in `on_data` on minute/tick data** — rate-limited to ~1/second, so most calls vanish. Use `log` for high-frequency diagnostics and reserve `debug`/`error` for notable events.
- **Logging raw dataset content** — not permitted and eats the log quota. Log derived quantities (your signal, your portfolio value, your order), not the subscribed market data that produced them.
- **No logging in a live algorithm** — if a live algorithm misbehaves and there are no logs, there is nothing to diagnose against. Always log order fills and key state transitions, and route "why did this branch fire?" diagnostics through a live-only `safe_log` wrapper.
- **Diagnostic logs only inside the taken branch** — they can't explain the *missing* orders. Log the values *before* the `if`, covering every term in the condition.
- **`log` right after `market_order`/`limit_order`/etc.** — context detached from the order, eats quota, and is duplicate-suppressed. Put the reasoning on the order via `tag=...`.
- **Using `log` to accumulate structured diagnostics in a backtest** — write rows to an in-memory buffer and `object_store.save` once in `on_end_of_algorithm` instead.
- **End-of-run state logged from `on_data`** — belongs in `on_end_of_algorithm` so it fires exactly once.
- **Using `debug` as the default channel** — floods the Cloud Terminal, hits the 1/second rate-limit, and the actually-important debug messages get dropped with the noise.

## Quick checklist when adding logging

1. Is this a stopping condition? → `quit(...)` then `return`.
2. Does a human need to see it live? → `debug` (notable) or `error` (problem). Otherwise `log`.
3. Will this line run every bar at minute/tick resolution? **First** ask whether it needs to run every bar at all — per-bar logs are the fastest way to exhaust the quota, and an event-gated log (fire, order, signal change) almost always covers the same diagnostic need. If it genuinely has to run every bar, it must be `log` (never `debug`/`error` — they're rate-limited to ~1/second and the vast majority of calls will be dropped) **and** include a changing value so it isn't duplicate-suppressed.
4. Is the message unique each time? If the content is static, add `self.time` or the relevant value so `log`'s duplicate-suppression doesn't drop it.
5. Is any part of the message verbatim market data from a subscribed dataset? If yes, log the derived quantity instead.
6. For final/summary output, is it in `on_end_of_algorithm`?
7. For verbose live-only debugging, is it routed through `safe_log` (gated on `self.live_mode`) and placed *before* the branching `if`, with every term of the condition included in the message?
8. If the message is explaining *why an order was placed*, is it on the order as `tag=...` rather than a separate `log` call?
9. If the goal is structured data to analyze in Research (not just runtime visibility), is it being accumulated in memory and saved to the Object Store in `on_end_of_algorithm`?

## C# equivalents

Every concept above applies identically to C# — only the syntax differs. Mapping:

| Python | C# |
| --- | --- |
| `self.log(msg)` | `Log(msg)` |
| `self.debug(msg)` | `Debug(msg)` |
| `self.error(msg)` | `Error(msg)` |
| `self.quit(msg)` | `Quit(msg)` |
| `self.time` | `Time` |
| `self.portfolio.total_portfolio_value` | `Portfolio.TotalPortfolioValue` |
| `self.live_mode` | `LiveMode` |
| `on_end_of_algorithm` | `OnEndOfAlgorithm` |
| `self.market_order("SPY", 1, tag="...")` | `MarketOrder("SPY", 1, tag: "...")` |
| `self.object_store.save(key, content)` | `ObjectStore.Save(key, content)` |
| `order_event.ticket.tag` | `orderEvent.Ticket.Tag` |
| `self.project_id` / `self.algorithm_id` | `ProjectId` / `AlgorithmId` |

C#-specific notes:
- The `safe_log` wrapper is `SafeLog` in C# (PascalCase) and should be gated on `LiveMode`, not `live_mode`.
- Prefer interpolated strings (`$"..."`) over concatenation for log messages — cheaper and easier to read.
- Format numerics explicitly (`:F2`, `:P2`) to keep log lines compact and stable across locales.
