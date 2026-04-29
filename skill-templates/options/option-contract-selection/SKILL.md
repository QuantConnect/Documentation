---
name: option-contract-selection
description: Use when selecting and subscribing to individual option contracts in QuantConnect/LEAN. Triggers -- py`self.option_chain`cs`OptionChain` filtering, py`add_option_contract`cs`AddOptionContract`, py`add_index_option_contract`cs`AddIndexOptionContract`, "select ATM option", "nearest expiry call/put", "subscribe to individual contracts", "option assignment", "index options SPX SPXW". Skip when adding options on a dynamic equity universe -- use the chained-universes-options skill instead.
---

# Option Contract Selection in QuantConnect / LEAN

Use py`self.option_chain(symbol)`cs`OptionChain(symbol)` to read the daily chain without subscribing to the full option universe. Filter for right, expiry, and strike, then subscribe with py`self.add_option_contract(contract)`cs`AddOptionContract(contract)`. Unsubscribe with py`self.remove_option_contract(symbol)`cs`RemoveOptionContract(symbol)`.

## Filter the daily chain

```python
chain = self.option_chain(self._spy)
if not chain:
    return
calls = [
    c for c in chain
    if c.right == OptionRight.CALL and
    c.strike > self._spy.price and
    (c.expiry - self.time).days > 5
]
puts = [
    c for c in chain
    if c.right == OptionRight.PUT and
    c.strike < self._spy.price and
    (c.expiry - self.time).days > 5
]
```

```csharp
var chain = OptionChain(_spy).ToList();
if (!chain.Any()) return;
var calls = chain.Where(c => c.Right == OptionRight.Call &&
    c.Strike > _spy.Price && (c.Expiry - Time).TotalDays > 5).ToList();
var puts = chain.Where(c => c.Right == OptionRight.Put &&
    c.Strike < _spy.Price && (c.Expiry - Time).TotalDays > 5).ToList();
```

## `_select_contract` helper

Pass `min` to pick the lowest OTM call strike; pass `max` for the highest OTM put strike.

```python
def _select_contract(self, chain, min_max):
    if not chain:
        return
    expiry = min(chain, key=lambda c: c.expiry).expiry
    chain = [c for c in chain if c.expiry == expiry]
    strike = min_max(chain, key=lambda c: c.strike).strike
    contract = next(c for c in chain if c.strike == strike)
    contract = self.add_option_contract(contract)
    self.buy(contract, 1)
```

```csharp
private void SelectContract(IEnumerable<OptionUniverse> chain, bool selectMin)
{
    if (!chain.Any()) return;
    var expiry = chain.Min(c => c.Expiry);
    var atExpiry = chain.Where(c => c.Expiry == expiry);
    var contract = selectMin
        ? atExpiry.OrderBy(c => c.Strike).First()
        : atExpiry.OrderByDescending(c => c.Strike).First();
    var security = AddOptionContract(contract);
    Buy(security, 1);
}
```

## ATM pair selection (straddles)

Use py`.id.date`cs`.ID.Date`, py`.id.strike_price`cs`.ID.StrikePrice`, and py`.id.option_right`cs`.ID.OptionRight` to access raw expiry and strike fields from the daily chain.

```python
expiries = {c.id.date for c in chain}
target_expiry = min(expiries, key=lambda e: abs((e - self.time.date()).days - 7))
contracts = [c for c in chain if c.id.date == target_expiry]
atm_strike = min(contracts, key=lambda c: abs(c.id.strike_price - self._spy.price)).id.strike_price
calls = [c for c in contracts if c.id.option_right == OptionRight.CALL and c.id.strike_price == atm_strike]
puts  = [c for c in contracts if c.id.option_right == OptionRight.PUT  and c.id.strike_price == atm_strike]
call_security = self.add_option_contract(calls[0])
put_security  = self.add_option_contract(puts[0])
```

```csharp
var targetExpiry = chain.Select(c => c.ID.Date).Distinct()
    .OrderBy(e => Math.Abs((e - Time.Date).TotalDays - 7)).First();
var contracts = chain.Where(c => c.ID.Date == targetExpiry).ToList();
var atmStrike = contracts.OrderBy(c => Math.Abs(c.ID.StrikePrice - _spy.Price)).First().ID.StrikePrice;
var callSecurity = AddOptionContract(contracts.First(c =>
    c.ID.OptionRight == OptionRight.Call && c.ID.StrikePrice == atmStrike));
var putSecurity = AddOptionContract(contracts.First(c =>
    c.ID.OptionRight == OptionRight.Put && c.ID.StrikePrice == atmStrike));
```

## Index option contracts

Use py`add_index_option_contract`cs`AddIndexOptionContract` for SPX/SPXW. Index options are European-style (exercise at expiration only).

```python
index = self.add_index("SPX")
chain = self.option_chain(index)
calls = [c for c in chain if c.right == OptionRight.CALL and ...]
contract = self.add_index_option_contract(calls[0])
```

```csharp
var index = AddIndex("SPX");
var chain = OptionChain(index).Where(c => c.Right == OptionRight.Call && ...);
var contract = AddIndexOptionContract(chain.First());
```

## Assignment handling

```python
def on_order_event(self, order_event: OrderEvent) -> None:
    if (order_event.symbol.security_type == SecurityType.EQUITY and
            order_event.status == OrderStatus.FILLED):
        self.liquidate(self._spy, tag="Assignment liquidation")
```

```csharp
public override void OnOrderEvent(OrderEvent orderEvent)
{
    if (orderEvent.Symbol.SecurityType == SecurityType.Equity &&
        orderEvent.Status == OrderStatus.Filled)
        Liquidate(_spy, "Assignment liquidation");
}
```

## Common mistakes

- **Calling py`add_option(symbol)`cs`AddOption(symbol)` per equity in a universe.** Use py`add_universe_options`cs`AddUniverseOptions` once in py`initialize`cs`Initialize` -- see the chained-universes-options skill.
- **Accessing `.price` or Greeks before py`add_option_contract`cs`AddOptionContract`.** The daily chain is read-only. Call py`add_option_contract`cs`AddOptionContract` first and use the returned `Security` for live price and Greeks.
- **Placing stop-loss or limit orders at daily or hourly resolution.** Use minute resolution.
- **Not handling assignment.** Implement py`on_order_event`cs`OnOrderEvent` to liquidate delivered shares.
