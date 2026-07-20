---
name: cross-sectional
description: Use for any cross-sectional strategy that ranks a large universe and trades only a subset (e.g. long-short deciles/quintiles). The principle — narrow to a bounded set as early as you can, store the target weights, and subscribe to only that bounded set (never the whole universe); the rebalance orders the py`dict`cs`Dictionary` (finalizing weights there only when they need a subscription-only dataset). How you obtain the ranking inputs depends on the universe dataset (see below) — there is no single recipe.
---

# Cross-sectional long-short — trade a subset without subscribing to the whole universe

A cross-sectional strategy screens a large universe but trades only a small subset (e.g. a long-short of two quintiles). The cost trap: add the whole universe and compute the signal in py`on_data`cs`OnData`/the rebalance — QC then subscribes to every screened name and streams its data every bar, and the algorithm crawls. The principle is to narrow to a bounded set as early as possible, store the target weights, and return only that bounded set so QC subscribes to just those; the rebalance then places the orders.

## Where the ranking can happen depends on the universe dataset
The universe selection function hands you that dataset's universe data points, and **each dataset exposes different attributes — and some datasets provide no universe selection at all.** So there is no one recipe; pick by what the data point gives you:
- **Ranking inputs ARE in the data point** (e.g. a Fundamental universe exposes py`f.price`cs`f.Price`, py`f.market_cap`cs`f.MarketCap`, valuation/financial ratios) → rank directly from those attributes in the universe selection function.
- **The signal needs price history the data point lacks** (a trailing return, momentum, max-daily-return, vol) → either request history INSIDE the universe selection function — py`self.history(symbols, n, Resolution.DAILY)`cs`History(symbols, n, Resolution.Daily)` works there (verified) and returns py`DataFrame`cs`Slice` objects for the candidates even though they aren't subscribed yet — OR maintain per-symbol indicators across daily selections (the `indicator-universes` skill / SelectionData pattern). Shrink to a cheap candidate set with the data point's own attributes FIRST, then pull history only for those. (A pure history-*length* check is different — you don't need py`history()`cs`History()` for it at all; use py`ipo_date`cs`IPODate`, see Notes.)
- **The dataset doesn't support selection, or doesn't expose what you need** → you cannot rank in the universe selection function; subscribe to a bounded candidate set and rank in the algorithm instead.

Check the specific dataset's docs (or the `fundamental-universes` / `indicator-universes` / `alternative-data-universes` skills) for which attributes its universe data point carries before assuming you can compute the signal in the universe selection function.

## Narrow to a bounded set in the universe selection function; never return the whole universe
The one hard rule: the universe selection function must return only a BOUNDED set — the names you'll trade, or a bounded candidate set — never the full screened universe. Whatever it returns is what QC subscribes to and streams every bar, so returning thousands of names you won't trade is the slow trap.

Do as much as the available data allows in the universe selection function:
- If the ranking AND weighting inputs are obtainable there (the data point's attributes, or py`self.history()`cs`History()` in the universe selection function), finish the job: slice to the traded long/short names, weight them, store py`self._weight_by_symbol`cs`_weightBySymbol`, and return only those names. The rebalance function then just orders the py`dict`cs`Dictionary`.
- If finalizing the weights or signal needs data with NO universe selection (e.g. the weighting depends on the SEC reports dataset, readable only after subscribing), you can't compute it in the universe selection function. Select a BOUNDED candidate set there from what IS available, return it (so QC subscribes only to those), then read the extra dataset and compute the final weights in the rebalance function / py`on_data`cs`OnData`.

**Anti-pattern:** returning the whole screened universe from the universe selection function and ranking off py`self._universe.selected`cs`_universe.Selected` in the rebalance — that subscribes QC to every screened name. The fix is NOT "never compute in the rebalance" (sometimes you must) — it's "never subscribe to more than a bounded set."
```python
def _select_assets(self, data_points):
    # narrow to a bounded set; compute weights here IF the data allows
    # filters on the data point's attributes; ranking inputs from attributes, history() here, or indicators
    # ... rank, then slice to the long & short names (`longs`, `shorts`) ...
    # weight each leg; DEFAULT +0.5 long / -0.5 short -> dollar-neutral, ~100% gross. mcap = your weight metric.
    lt = sum(mcap[s] for s in longs); st = sum(mcap[s] for s in shorts)
    self._weight_by_symbol = {**{s: +0.5*mcap[s]/lt for s in longs}, **{s: -0.5*mcap[s]/st for s in shorts}}
    return list(self._weight_by_symbol.keys())    # bounded set -> QC subscribes to only these

def _rebalance(self):
    # order the stored weights (finalize them here only if they need a subscription-only dataset)
    if not self._weight_by_symbol:
        return
    targets = [PortfolioTarget(s, w) for s, w in self._weight_by_symbol.items()]
    self.set_holdings(targets, liquidate_existing_holdings=True)   # also closes names that left the set
```

```csharp
private Dictionary<Symbol, decimal> _weightBySymbol = new();

private IEnumerable<Symbol> SelectAssets(IEnumerable<BaseData> dataPoints)
{
    // narrow to a bounded set; compute weights here IF the data allows
    // filters on the data point's attributes; ranking inputs from attributes, History() here, or indicators
    // ... rank, then slice to the long & short names (`longs`, `shorts`) ...
    // weight each leg; DEFAULT +0.5 long / -0.5 short -> dollar-neutral, ~100% gross. mcap = your weight metric.
    var lt = longs.Sum(s => mcap[s]);
    var st = shorts.Sum(s => mcap[s]);
    _weightBySymbol = longs.ToDictionary(s => s, s => +0.5m * mcap[s] / lt);
    foreach (var s in shorts)
    {
        _weightBySymbol[s] = -0.5m * mcap[s] / st;
    }
    return _weightBySymbol.Keys;    // bounded set -> QC subscribes to only these
}

private void Rebalance()
{
    // order the stored weights (finalize them here only if they need a subscription-only dataset)
    if (_weightBySymbol.Count == 0)
    {
        return;
    }
    var targets = _weightBySymbol.Select(kv => new PortfolioTarget(kv.Key, kv.Value)).ToList();
    SetHoldings(targets, liquidateExistingHoldings: true);   // also closes names that left the set
}
```

## Notes
- Schedule the rebalance to fire AFTER the universe selection function runs that day; see the `scheduled-events` skill for daily-data timing.
- py`history()`cs`History()` in the universe selection function is a HISTORY REQUEST, not a warm-up: it returns pre-start data, so the first rebalance trades immediately. Do NOT py`set_warm_up`cs`SetWarmUp` for it — warm-up is only for streaming indicators that accumulate state bar-by-bar.
- **Checking whether a name has *enough* history (a "≥ N days of history" / listing-age / seasoning screen) does NOT need a py`history()`cs`History()` request.** Pulling N daily bars for every one of thousands of screened candidates just to count them stalls the algorithm. Instead read py`f.security_reference.ipo_date`cs`f.SecurityReference.IPODate` (a py`datetime`cs`DateTime` already on the Fundamental data point) and keep names listed long enough: py`(self.time.date() - f.security_reference.ipo_date.date()).days >= N*7//5`cs`(Time.Date - f.SecurityReference.IPODate.Date).Days >= N*7/5` (N trading days ≈ N×7/5 calendar days; 126 ≈ ~6 months). (Other Fundamental attributes: the `fundamental-universes` skill.)
- **Default leg sizing: +0.5 long / −0.5 short** (dollar-neutral, ~100% gross). Do NOT default to ±1.0 (200% gross) — it routinely exceeds a margin account's buying power and throws insufficient-buying-power / margin errors. Only exceed ~100% gross if the spec explicitly requires it.
