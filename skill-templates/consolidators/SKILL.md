---
name: consolidators
description: Use when aggregating bars in QuantConnect/LEAN. Triggers -- py`consolidate`cs`Consolidate`, Calendar.MONTHLY, Calendar.WEEKLY, Resolution.DAILY, timedelta, TradeBar handler, RollingWindow, manual warm-up history loop, py`subscription_manager.remove_consolidator`cs`SubscriptionManager.RemoveConsolidator`, on_securities_changed.
---

# Consolidators in QuantConnect / LEAN

Use py`algorithm.consolidate(security, period, handler)`cs`algorithm.Consolidate(symbol, period, handler)` inside py`on_securities_changed`cs`OnSecuritiesChanged` to aggregate bars. Store the returned consolidator references for cleanup on removal.

## Core pattern

```python
def on_securities_changed(self, algorithm, changes):
    for security in changes.added_securities:
        security.consolidators = [
            algorithm.consolidate(security, Calendar.MONTHLY, self._on_monthly),
            algorithm.consolidate(security, Resolution.DAILY, self._on_daily),
        ]
```

```csharp
private Dictionary<Symbol, List<IDataConsolidator>> _consolidators = new();

public override void OnSecuritiesChanged(QCAlgorithm algorithm, SecurityChanges changes)
{
    foreach (var security in changes.AddedSecurities)
        _consolidators[security.Symbol] = new List<IDataConsolidator>
        {
            algorithm.Consolidate(security.Symbol, Calendar.Monthly, OnMonthly),
            algorithm.Consolidate(security.Symbol, Resolution.Daily, OnDaily),
        };
}
```

## Period types

Pass py`Calendar.MONTHLY`cs`Calendar.Monthly`, py`Calendar.WEEKLY`cs`Calendar.Weekly`, py`Resolution.DAILY`cs`Resolution.Daily`, or a time delta for custom bar sizes.

```python
algorithm.consolidate(security, timedelta(minutes=30), handler)
```

```csharp
algorithm.Consolidate(security.Symbol, TimeSpan.FromMinutes(30), handler);
```

## Lambda handler with closure safety

Use py`algorithm.securities[bar.symbol]`cs`securities[bar.Symbol]` in the lambda body instead of the local `security` variable.

```python
security.monthly_window = RollingWindow[TradeBar](13)
security.daily_returns = RollingWindow(280)
security.consolidators = [
    algorithm.consolidate(security, Calendar.MONTHLY,
        lambda bar: algorithm.securities[bar.symbol].monthly_window.add(bar)),
    algorithm.consolidate(security, Resolution.DAILY,
        lambda bar: algorithm.securities[bar.symbol].daily_returns.add(
            (bar.close - bar.open) / bar.open)),
]
```

```csharp
var sym = security.Symbol;
_consolidators[sym] = new List<IDataConsolidator>
{
    algorithm.Consolidate(sym, Calendar.Monthly,
        (TradeBar bar) => _monthlyWindow[bar.Symbol].Add(bar)),
    algorithm.Consolidate(sym, Resolution.Daily,
        (TradeBar bar) => _dailyReturns[bar.Symbol].Add(
            (bar.Close - bar.Open) / bar.Open)),
};
```

## RollingWindow types

py`RollingWindow(N)`cs`RollingWindow<decimal>(N)` stores floats. py`RollingWindow[TradeBar](N)`cs`RollingWindow<TradeBar>(N)` stores typed bars.

In Python, attach both directly to the security object as duck-typed attributes (shown above). In C#, use a `Dictionary<Symbol, RollingWindow<T>>` as a class field.

## Manual warm-up

```python
history = algorithm.history[TradeBar](security, 600)
for bar in history:
    for consolidator in security.consolidators:
        consolidator.update(bar)
```

```csharp
foreach (var bar in algorithm.History<TradeBar>(security.Symbol, 600))
    foreach (var c in _consolidators[security.Symbol])
        c.Update(bar);
```

## Cleanup

```python
for security in changes.removed_securities:
    for consolidator in security.consolidators:
        algorithm.subscription_manager.remove_consolidator(security, consolidator)
```

```csharp
foreach (var security in changes.RemovedSecurities)
    foreach (var c in _consolidators[security.Symbol])
        algorithm.SubscriptionManager.RemoveConsolidator(security.Symbol, c);
```

## Common mistakes

- **Lambda captures `security` directly.** Use `algorithm.securities[bar.symbol]` in the handler body.
- **Missing cleanup.** Call `remove_consolidator` for every consolidator in the removed securities loop.
- **Wrong RollingWindow type for floats.** Use py`RollingWindow(N)`cs`RollingWindow<decimal>(N)`, not py`RollingWindow[TradeBar](N)`cs`RollingWindow<TradeBar>(N)`.
