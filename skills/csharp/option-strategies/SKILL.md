---
name: option-strategies
description: Use when placing or managing MULTI-LEG option positions and the option lifecycle in QuantConnect/LEAN — the `OptionStrategies` factory + `Buy/Sell` route, combo orders (`ComboMarketOrder`, `ComboLimitOrder`, `ComboLegLimitOrder`), position-group margin, early assignment, exercise, and expiry cleanup. Triggers — iron condor / straddle / strangle / spread / butterfly / calendar / collar / covered-call code; questions like "how do I place all four legs at once", "why was my short option assigned", "what happens to my position at expiry", "why did my algorithm stop trading after expiry". Skip when — chain subscriptions and greeks freshness (see equity-options), single-leg orders (see orders), historical option data (see historical-data-equity-options).
---

# Option strategies and the option lifecycle — QuantConnect / LEAN

## Placing multi-leg positions: two documented routes, no third

**Route A — `OptionStrategies` factory + `Buy`.** One call builds the whole structure from the **canonical** option symbol (saved from `var option = AddOption(...); _symbol = option.Symbol;`) plus strikes/expiries — no contract symbols needed, all legs placed atomically:

```csharp
var strategy = OptionStrategies.IronCondor(_symbol, farPut, nearPut, nearCall, farCall, expiry);
Buy(strategy, 2);         // 2 condors; Sell(strategy, q) inverts every leg
```

Both directions of every structure have their own factory (`Straddle` / `ShortStraddle`, `IronCondor` / `ShortIronCondor`) and a "short_" factory is still placed with `Buy`. The catalogue (`PascalCase` members of `OptionStrategies`): bear/bull call/put spreads; long/short call/put butterflies (strike order **higher, ATM, lower**); long/short call/put calendar spreads (`(strike, nearExpiry, farExpiry)`); covered/naked/protective calls and puts; protective collar; conversion/reverse conversion; long/short straddles and strangles; long/short iron butterflies and iron condors (strikes ascending); long/short box spreads; long/short jelly rolls; bear/bull call/put ladders; long/short call/put backspreads. Covered/protective/conversion structures embed the underlying — buying one unit of `CoveredCall` is long 100 shares + short 1 call.

**Route B — combo orders.** Build `Leg.Create(symbol, ratio)` lists from actual contract symbols when you need custom ratios, a leg set no factory matches, or limit pricing:

```csharp
var legs = new List<Leg> { Leg.Create(shortCall.Symbol, -1), Leg.Create(longCall.Symbol, 1) };
var tickets = ComboMarketOrder(legs, quantity);        // one ticket per leg
```

- Leg quantities set the **ratio**; the order's `quantity` argument is a global multiplier and sets the combo's direction. At least one positive and one negative leg; each leg a unique contract.
- `ComboLimitOrder(legs, quantity, limitPrice)` — one **net** price for the package, legs fill together when the combined price crosses it; the only combo type documented to also accept an underlying-equity leg. `ComboLegLimitOrder` — per-leg limits via the third argument of `Leg.Create`; each leg fills on its own limit. Both are updatable through the leg tickets until filled.
- Combo orders are synchronous by default with a 5-second wait (extend via `Transactions.MarketOrderFillTimeout`); pass `asynchronous: true` to fire-and-continue. Marketable combos on illiquid OTM contracts "may take a few minutes to fill".

**There is no route C.** Legging into a multi-leg structure with sequential single market orders is not a documented pattern: it carries leg risk (partial structures during fills) and the interim one-sided position can fail margin that the complete structure would pass. Single-leg strategies (naked call/put) are the one case where a plain `MarketOrder` is the documented approach.

## Margin: recognized structures are cheaper

LEAN groups positions that compose a recognized strategy and margins the **group**, not the legs — the docs' example: an ITM long call ($29,150) plus an OTM short call ($101,499) margin at $130,649 separately but **$0 as a bull call spread**. Defined-risk structures (spreads, condors, butterflies) are therefore dramatically cheaper than their leg-sums; don't pre-reject a trade by summing per-leg requirements. For a pre-trade check, wrap the strategy in `new OptionStrategyPositionGroupBuyingPowerModel(strategy)` and query `GetInitialMarginRequirement(...)` against `Portfolio.MarginRemaining` (the result is per one unit — multiply by your quantity).

## Early assignment: an hourly simulation you must expect

Short American options in a backtest can be assigned **before** expiry. The default `DefaultOptionAssignmentModel` scans hourly and assigns a short option that is within 4 days of expiry, at least 5% in the money, and profitable to exercise after fees (European style: expiry day only). Any strategy holding short legs into that window (covered calls, short condors near expiry, diagonals) must handle assignment — or, when the strategy's rules exit before the window (e.g. manage-at-21-DTE), assignment simply never triggers. To disable the simulation deliberately: `(security as Option).SetOptionAssignmentModel(new NullOptionAssignmentModel());` in a security initializer.

**Handle assignment in `OnAssignmentOrderEvent` — not by sniffing `IsAssignment` in the generic order-event handler.** The docs' working examples read the just-delivered share position inside the dedicated handler (holdings are current there) and clean up immediately:

```csharp
public override void OnAssignmentOrderEvent(OrderEvent assignmentEvent)
{
    var shares = assignmentEvent.Quantity * _option.SymbolProperties.ContractMultiplier;
    MarketOrder(assignmentEvent.Symbol.Underlying, -shares, tag: "Assignment cleanup");
}
```

Reacting from the generic `OnOrderEvent` instead has been observed to fire before the delivered shares are booked, so a holdings-based cleanup silently does nothing until a later event. Note the delivered shares appear at the **strike** price via a fill event, simultaneous with a zero-price fill that removes the option contract; assignment after the close cannot be flattened until the next session's open regardless of handler.

## Expiry: LEAN acts on the contracts, not on your state

At expiration LEAN auto-exercises long ITM positions ("Automatic Exercise": zero-price fill removing the contract + a strike-price fill delivering the underlying for physical settlement) and removes OTM contracts worthless. **None of your exit logic runs.** Any strategy that tracks its own open position (a `self._position` object, leg symbol fields) must clear that state when contracts expire — otherwise the algorithm believes a position is still open, its management checks keep early-returning on missing data, and it silently never trades again. If the strategy's rules are supposed to exit before expiry, treat reaching expiry as a bug signal, but still write the state-cleanup path. Manual exercise: `ExerciseOption(contractSymbol, quantity)` — long positions only, American any time, European only at expiry, rejected on insufficient capital.

## Common mistakes

- Selling a `short_*` factory (double inversion) — short factories are **bought**; `Sell` on the long factory is the other spelling of the same trade.
- Butterfly strike order: all four butterfly factories take **(higher, ATM, lower)** — the doc examples' variable names disagree with their own math; follow the order, not the names.
- Passing the underlying Equity symbol or a contract symbol as a factory's first argument — it must be the **canonical** option symbol.
- Summing per-leg margin to pre-check a defined-risk structure (position grouping makes the real requirement far lower).
- Assignment cleanup in the generic order-event handler reading holdings that aren't updated yet — use `OnAssignmentOrderEvent`.
- No expiry-slip state cleanup: position-tracking fields never cleared when contracts expire, permanently halting re-entry.
- Pairing route A with per-leg limit prices — factories place market executions; limit pricing needs route B.
