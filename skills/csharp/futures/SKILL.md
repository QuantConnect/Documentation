---
name: futures
description: Use whenever a strategy trades futures — position sizing (notional/contracts), continuous-contract history and its `IEnumerable<TradeBar>`, and warm-up. Load it for any futures sizing, history, or warm-up step.
---

# Futures: sizing, history, warm-up

## Position sizing (notional, not `SetHoldings`)
To take a target notional (portfolio-fraction) exposure in a future, do NOT use `SetHoldings`: for a future it sizes off (already-leveraged) buying power, so a target near 1.0 takes on many times 1x notional — margin calls and blow-ups — and |target| > 1 is rejected outright. Also, the continuous/canonical future symbol (e.g. /ES) is a DATA symbol and is NOT tradable — `MarketOrder` or portfolio reads on it silently do nothing, so `symbol` below must be a real contract (the front month via `future.Mapped`, or the specific contract the strategy selects). Size by notional with explicit contract math:

```csharp
var price = Securities[symbol].Price;
var multiplier = Securities[symbol].SymbolProperties.ContractMultiplier;   // read it; never hardcode (ES = 50)
var targetContracts = Math.Round(Portfolio.TotalPortfolioValue * targetWeight / (price * multiplier));   // may be 0 on a small account — correct, not a bug
var delta = targetContracts - Portfolio[symbol].Quantity; MarketOrder(symbol, delta);   // trade the delta
```

## Continuous symbol vs mapped contract
- `future.Symbol` is the continuous symbol. Use it for history, consolidators, and indicators, and to read bars in `OnData`: `data.Bars.TryGetValue(future.Symbol, out var bar)`. These keep receiving data across each roll.
- `future.Mapped` is the contract to trade. Use it for orders and portfolio reads.
- `future.Mapped` is `null` in `Initialize` and changes at each roll, so read it when you place the order.
- A roll leaves your position in the old contract, and a portfolio read on the new `future.Mapped` returns 0. Move the position to the new contract in `OnSymbolChangedEvents`:

```csharp
public override void OnSymbolChangedEvents(SymbolChangedEvents symbolChangedEvents)
{
    foreach (var changed in symbolChangedEvents.Values)
    {
        var quantity = Portfolio[changed.OldSymbol].Quantity;
        Liquidate(changed.OldSymbol);
        if (quantity != 0) MarketOrder(changed.NewSymbol, quantity);
    }
}
```

## History — use `History<TradeBar>()`; do NOT hand-build any other structure from the bars
Seeding a raw look-back buffer (e.g. an expanding regression window) is a HISTORY REQUEST, not a warm-up (warm-up pumps data through OnData and updates indicators; a history request hands you the bars to process yourself).
- Request continuous-contract history on the canonical `future.Symbol`. Roll behavior is set by the `dataMappingMode` / `dataNormalizationMode` / `contractDepthOffset` passed to `AddFuture` (e.g. `DataNormalizationMode.BackwardsRatio` for a roll-adjusted price/return series).
- `History<TradeBar>(symbol, n, Resolution.Daily)` ALREADY yields the bars directly — iterate them and read the fields you need (e.g. `bar.Close`); do NOT hand-build any other structure from them.
- A request can return fewer bars than asked when available history is limited; check the bar count and widen the lookback if short.

## Warm-up (for indicators, not raw buffers)
To ready indicators before the start date: `SetWarmUp(n[, resolution])` or `SetWarmUp(TimeSpan.FromDays(...))`, gated with `if (IsWarmingUp) return;`; or `WarmUpIndicator(symbol, indicator[, resolution])` / `Settings.AutomaticIndicatorWarmUp` / `IndicatorHistory(indicator, symbol, n, resolution)`. After warming, check readiness and widen the lookback if short.
