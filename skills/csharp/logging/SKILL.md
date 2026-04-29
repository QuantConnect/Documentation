---
name: logging
description: Use when adding or reviewing logging in a QuantConnect/LEAN algorithm. Triggers — code uses `Log`, `Debug`, `Error`, `Quit`; questions like "why are my logs missing", "log quota exceeded", "too many log lines per bar", "how do I log every fill", "where do my prints go", "save backtest data for Research analysis", "log spam in `OnData` / inside loops". Skip when — goal is sending alerts out of the algo (email/SMS/webhook → `notifications` skill).
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

```csharp
Log("My log message");
Debug("My debug message");
Error("My error message");
Quit("My quit message");   // follow with `return;` — Quit schedules a stop but the method keeps running
```

Default to `Log`. Reach for the others only when their specific behavior is needed: `Debug` / `Error` for a *handful* of events per day you want to see live in the Cloud Terminal, `Quit` only on unrecoverable state.

## Patterns

### Log on events, not on bars

A logger in `OnData` writes ~390 lines per simulated trading day at minute resolution. Move it to `OnOrderEvent` (or another event handler) so it fires only when something happened:

```csharp
public override void OnOrderEvent(OrderEvent orderEvent)
{
    if (orderEvent.Status == OrderStatus.Filled)
    {
        Log($"{Time}: filled {orderEvent.Symbol} qty={orderEvent.FillQuantity} @ {orderEvent.FillPrice:F2}");
    }
}
```

### Aggregate inside loops; log once after

A per-symbol log inside the rebalance loop is N lines per rebalance — for a 5000-symbol universe, that's 5000 lines. Compute first, log a summary outside the loop:

```csharp
var targets = selected.Select(s => (Symbol: s, Weight: ComputeWeight(s))).ToList();
var top = targets.OrderByDescending(t => Math.Abs(t.Weight)).Take(5).ToList();
Log($"{Time}: rebalanced {targets.Count}, top: [{string.Join(\", \", top)}]");
foreach (var (s, w) in targets) SetHoldings(s, w);
```

### Daily summary — only the active positions

`OnEndOfDay` fires once per traded symbol per day, so a universe of 5000 symbols would call it 5000 times. Drive the summary from a Scheduled Event instead — once per trading day, at a time outside any data bar (5 minutes after the US Equity close works on every resolution; see the `scheduled-events` skill for the rules).

```csharp
public override void Initialize()
{
    Schedule.On(DateRules.EveryDay("SPY"), TimeRules.At(16, 5), DailySummary);
}

private void DailySummary()
{
    var active = Portfolio.Where(kvp => kvp.Value.Invested).ToList();
    Log($"{Time.Date:yyyy-MM-dd}: {active.Count} positions, total ${Portfolio.TotalPortfolioValue:F2}");
    foreach (var kvp in active)
    {
        Log($"  {kvp.Key}: qty={kvp.Value.Quantity} unrealized={kvp.Value.UnrealizedProfit:F2}");
    }
}
```

The summary records the *useful* state — only positions actually held, not zeroes for every symbol in the universe.

### End-of-algorithm summary

`OnEndOfAlgorithm` fires exactly once. Put final stats here.

```csharp
public override void OnEndOfAlgorithm()
{
    Log($"Final portfolio value: {Portfolio.TotalPortfolioValue:F2}");
    Log($"Wins: {_wins}, losses: {_losses}");
}
```

### Log values *before* the branch, not after

The point of a diagnostic log is to answer "why did (or didn't) this branch fire?" — which requires logging the inputs **before** the `if`, regardless of outcome. Logging only inside the taken branch tells you nothing about the cases where it wasn't.

```csharp
// Good — log every term in the condition before the if.
SafeLog($"{Time}: close={bar.Close:F2} ema={_ema.Current.Value:F2} qty={Portfolio[_spy].Quantity}");
if (bar.Close > _ema.Current.Value && Portfolio[_spy].Quantity == 0)
{
    SetHoldings(_spy, 1);
}
```

If the `if` checks three things, log all three. When an order doesn't happen, the line shows which term blocked it.

### `safe_log` wrapper for live-only diagnostics

In live trading the volume is naturally capped by wall-clock time, so chatty diagnostics that would blow the backtest quota are fine. Route them through a wrapper that's a no-op in backtests:

```csharp
private void SafeLog(string message)
{
    if (LiveMode) Log(message);
}
```

Use it for verbose diagnostics you'd only need *if* something went wrong in live — backtests stay clean, and when an incident happens the log already contains the trail.

### Prefer order tags over a `log` call after the order

Put the *why* on the order's `tag=` rather than a separate `log` line — it travels with the order, shows on the ticket and trade list, and costs nothing against the log quota. Include the values that drove the decision, not just a label.

```csharp
MarketOrder("SPY", 1, tag: $"{Time}: close={bar.Close:F2} ema={_ema.Current.Value:F2}");
```

### Object Store for structured Research data

For structured rows you'll analyze in a Research notebook (e.g. a CSV of every signal evaluation), the log file is the wrong tool — size-capped and awkward to parse. Accumulate rows in memory and save once in `OnEndOfAlgorithm`:

```csharp
public override void Initialize()
{
    _content = "time,symbol,price,tag\n";
}

public override void OnOrderEvent(OrderEvent orderEvent)
{
    if (orderEvent.Status == OrderStatus.Filled)
        _content += $"{orderEvent.UtcTime},{orderEvent.Symbol},{orderEvent.FillPrice},{orderEvent.Ticket.Tag}\n";
}

public override void OnEndOfAlgorithm()
{
    ObjectStore.Save($"{ProjectId}-{AlgorithmId}.txt", _content);
}
```

Read back in Research with `qb.ObjectStore.Read(key)`. Use a stable, unique key like `{project_id}-{algorithm_id}.txt` so runs don't clobber each other. Backtests only — in live, the 100K-line log file is the right place for diagnostics.

## Silent footgun: duplicate-message suppression

If you log the **exact same string** more than once, only the first call is written — the rest are dropped silently. A per-bar log like `Log("Portfolio value below threshold")` looks fine on bar one and then disappears. Always include a changing value (timestamp, price, symbol) so each line is distinct:

```csharp
Log($"{Time}: portfolio below threshold ({Portfolio.TotalPortfolioValue:F2})");
```

Embedding the actual value being logged usually makes the message unique on its own, removing the need for an explicit timestamp.

## Common mistakes

- **Logging raw market data** (bars, trades, quotes from subscribed datasets). Not permitted *and* the highest-volume content possible. Log the derived quantity (signal value, portfolio value, decision).
- **End-of-run state logged from `OnData`** — belongs in `OnEndOfAlgorithm` so it fires exactly once.
- **No logging at all in live.** When something goes wrong you'll have nothing to diagnose against. Always log fills and key state transitions; route verbose diagnostics through `safe_log`.
- **`Debug` / `Error` from `OnData` on minute/tick data.** Rate-limited to ~1/second, so most calls vanish. For high-frequency diagnostics use `log`; reserve `debug`/`error` for a handful of notable events per day.
- **`Quit` without a following `return`.** The algorithm *will* stop, but the current method keeps executing until it returns naturally; orders placed after the quit call still go through.
