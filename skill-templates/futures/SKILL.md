---
name: futures
description: Use whenever a strategy trades futures — position sizing (notional/contracts), continuous-contract history and its py`MultiIndex DataFrame`cs`IEnumerable<TradeBar>`, and warm-up. Load it for any futures sizing, history, or warm-up step.
---

# Futures: sizing, history, warm-up

## Position sizing (notional, not py`set_holdings`cs`SetHoldings`)
To take a target notional (portfolio-fraction) exposure in a future, do NOT use py`set_holdings`cs`SetHoldings`: for a future it sizes off (already-leveraged) buying power, so a target near 1.0 takes on many times 1x notional — margin calls and blow-ups — and |target| > 1 is rejected outright. Also, the continuous/canonical future symbol (e.g. /ES) is a DATA symbol and is NOT tradable — py`market_order`cs`MarketOrder` or portfolio reads on it silently do nothing, so `symbol` below must be a real contract (the front month via py`future.mapped`cs`future.Mapped`, or the specific contract the strategy selects). Size by notional with explicit contract math:

```python
price = self.securities[symbol].price
multiplier = self.securities[symbol].symbol_properties.contract_multiplier   # read it; never hardcode (ES = 50)
target_contracts = round(self.portfolio.total_portfolio_value * target_weight / (price * multiplier))   # may be 0 on a small account — correct, not a bug
delta = target_contracts - self.portfolio[symbol].quantity; self.market_order(symbol, delta)   # trade the delta
```

```csharp
var price = Securities[symbol].Price;
var multiplier = Securities[symbol].SymbolProperties.ContractMultiplier;   // read it; never hardcode (ES = 50)
var targetContracts = Math.Round(Portfolio.TotalPortfolioValue * targetWeight / (price * multiplier));   // may be 0 on a small account — correct, not a bug
var delta = targetContracts - Portfolio[symbol].Quantity; MarketOrder(symbol, delta);   // trade the delta
```

<!-- python-only -->
## History — use self.history(); do NOT rebuild a DataFrame from TradeBars
Seeding a raw look-back buffer (e.g. an expanding regression window) is a HISTORY REQUEST, not a warm-up (warm-up pumps data through on_data and updates indicators; a history request hands you a DataFrame to process yourself).
<!-- /python-only -->
<!-- csharp-only -->
## History — use `History<TradeBar>()`; do NOT hand-build any other structure from the bars
Seeding a raw look-back buffer (e.g. an expanding regression window) is a HISTORY REQUEST, not a warm-up (warm-up pumps data through OnData and updates indicators; a history request hands you the bars to process yourself).
<!-- /csharp-only -->
- Request continuous-contract history on the canonical py`future.symbol`cs`future.Symbol`. Roll behavior is set by the py`data_mapping_mode`cs`dataMappingMode` / py`data_normalization_mode`cs`dataNormalizationMode` / py`contract_depth_offset`cs`contractDepthOffset` passed to py`add_future`cs`AddFuture` (e.g. py`DataNormalizationMode.BACKWARDS_RATIO`cs`DataNormalizationMode.BackwardsRatio` for a roll-adjusted price/return series).
<!-- python-only -->
- `self.history(symbol, n, Resolution.DAILY)` ALREADY returns a DataFrame — do NOT iterate `self.history[TradeBar](...)` and build one by hand. For a future it comes back MultiIndexed by (expiry, symbol, time); get a clean time-indexed series by dropping the leading levels:
      closes = self.history(symbol, n, Resolution.DAILY)["close"].droplevel(["expiry", "symbol"])
  then group/resample by month on that Series. (Do NOT use `history.loc[symbol]` — that indexes the expiry level and fails.)
<!-- /python-only -->
<!-- csharp-only -->
- `History<TradeBar>(symbol, n, Resolution.Daily)` ALREADY yields the bars directly — iterate them and read the fields you need (e.g. `bar.Close`); do NOT hand-build any other structure from them.
<!-- /csharp-only -->
<!-- python-only -->
- A request can return fewer bars than asked when available history is limited; check the row count and widen the lookback if short.
<!-- /python-only -->
<!-- csharp-only -->
- A request can return fewer bars than asked when available history is limited; check the bar count and widen the lookback if short.
<!-- /csharp-only -->

## Warm-up (for indicators, not raw buffers)
To ready indicators before the start date: py`set_warm_up(n[, resolution])`cs`SetWarmUp(n[, resolution])` or py`set_warm_up(timedelta(...))`cs`SetWarmUp(TimeSpan.FromDays(...))`, gated with py`if self.is_warming_up: return`cs`if (IsWarmingUp) return;`; or py`warm_up_indicator(symbol, indicator[, resolution])`cs`WarmUpIndicator(symbol, indicator[, resolution])` / py`settings.automatic_indicator_warm_up`cs`Settings.AutomaticIndicatorWarmUp` / py`indicator_history(indicator, symbol, n, resolution)`cs`IndicatorHistory(indicator, symbol, n, resolution)`. After warming, check readiness and widen the lookback if short.
