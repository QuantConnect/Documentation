---
name: futures
description: Use whenever a strategy trades futures — position sizing (notional/contracts), continuous-contract history and its `MultiIndex DataFrame`, and warm-up. Load it for any futures sizing, history, or warm-up step.
---

# Futures: sizing, history, warm-up

## Position sizing (notional, not `set_holdings`)
To take a target notional (portfolio-fraction) exposure in a future, do NOT use `set_holdings`: for a future it sizes off (already-leveraged) buying power, so a target near 1.0 takes on many times 1x notional — margin calls and blow-ups — and |target| > 1 is rejected outright. Also, the continuous/canonical future symbol (e.g. /ES) is a DATA symbol and is NOT tradable — `market_order` or portfolio reads on it silently do nothing, so `symbol` below must be a real contract (the front month via `future.mapped`, or the specific contract the strategy selects). Size by notional with explicit contract math:

```python
price = self.securities[symbol].price
multiplier = self.securities[symbol].symbol_properties.contract_multiplier   # read it; never hardcode (ES = 50)
target_contracts = round(self.portfolio.total_portfolio_value * target_weight / (price * multiplier))   # may be 0 on a small account — correct, not a bug
delta = target_contracts - self.portfolio[symbol].quantity; self.market_order(symbol, delta)   # trade the delta
```

## History — use self.history(); do NOT rebuild a DataFrame from TradeBars
Seeding a raw look-back buffer (e.g. an expanding regression window) is a HISTORY REQUEST, not a warm-up (warm-up pumps data through on_data and updates indicators; a history request hands you a DataFrame to process yourself).
- Request continuous-contract history on the canonical `future.symbol`. Roll behavior is set by the `data_mapping_mode` / `data_normalization_mode` / `contract_depth_offset` passed to `add_future` (e.g. `DataNormalizationMode.BACKWARDS_RATIO` for a roll-adjusted price/return series).
- `self.history(symbol, n, Resolution.DAILY)` ALREADY returns a DataFrame — do NOT iterate `self.history[TradeBar](...)` and build one by hand. For a future it comes back MultiIndexed by (expiry, symbol, time); get a clean time-indexed series by dropping the leading levels:
      closes = self.history(symbol, n, Resolution.DAILY)["close"].droplevel(["expiry", "symbol"])
  then group/resample by month on that Series. (Do NOT use `history.loc[symbol]` — that indexes the expiry level and fails.)
- A request can return fewer bars than asked when available history is limited; check the row count and widen the lookback if short.

## Warm-up (for indicators, not raw buffers)
To ready indicators before the start date: `set_warm_up(n[, resolution])` or `set_warm_up(timedelta(...))`, gated with `if self.is_warming_up: return`; or `warm_up_indicator(symbol, indicator[, resolution])` / `settings.automatic_indicator_warm_up` / `indicator_history(indicator, symbol, n, resolution)`. After warming, check readiness and widen the lookback if short.
