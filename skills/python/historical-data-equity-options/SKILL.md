---
name: historical-data-equity-options
description: >-
  Use when an equity-options strategy needs HISTORICAL option data — implied volatility, greeks, prices, open interest — for PAST dates.
  E.g. a historical implied-volatility series, or backfilling option IV/greeks over a period for a signal or model.
  Triggers — needing an option's IV/greeks at a target expiry/moneyness on a historical date; a lookback of past daily option chains; an INTRADAY historical IV/greek series; "get the past implied vol of these options".
  Two routes — (A) `self.history[OptionUniverse](option.symbol, ...)` for the PRE-COMPUTED daily (end-of-day) IV/greeks;
  (B) the IV/greek INDICATORS (`self.iv`/`self.d`/...) with `indicator_history` when you need CUSTOM IV (e.g. the average of an ATM call-put pair) OR INTRADAY (minute/second) values — Route A is daily-only.
  Skip for subscribing a LIVE chain to trade (that is chained-universes-options).
---

# Historical equity-option data — IV, greeks, prices for PAST dates

Use this to read an option's **implied volatility or greeks on historical dates** (a historical IV series, backfilling option greeks over a period, etc.). This is PAST option data — different from subscribing a live chain to trade (see `chained-universes-options`).

There are two routes. Prefer **(A)** for a straight historical IV/greek read; use **(B)** when you need a custom IV (e.g. the *average of the ATM call and put IV*, or a specific pricing model) **or intraday (minute/second) values** — Route A is daily / end-of-day only.

## Route A — OptionUniverse history (pre-computed daily IV/greeks)
The history request takes the **canonical option `Symbol`** for the underlying — it returns ALL tradable contracts each day (no filter needed; any `set_filter` only affects the live `on_data` chain). Obtain the canonical symbol whichever way the algorithm is built:
- static single underlying: `canonical = self.add_option("AAPL").symbol` (`add_index_option` for SPX);
- dynamic equity universe via `add_universe_options`: the canonical symbols are the KEYS of the option chains in `on_data` — `for canonical, chain in slice.option_chains.items(): ...`;
- from any contract `Symbol` you already hold: `canonical = contract_symbol.canonical`.
```python
history = self.history[OptionUniverse](canonical, start, end)   # or (canonical, timedelta(30))
for option_universe in history:                        # one daily chain per element
    day = option_universe.end_time
    for c in option_universe:                          # every tradable contract that day
        iv, delta = c.implied_volatility, c.greeks.delta          # c.greeks.gamma/.vega/.theta/.rho ; c.price ; c.volume ; c.open_interest
        # strike/expiry/right are NOT properties of OptionUniverse - read them off the symbol:
        strike, expiry, right = c.symbol.id.strike_price, c.symbol.id.date, c.symbol.id.option_right
```

For a DataFrame instead, call plain `self.history(canonical, ..., flatten=True)` — **WITHOUT** the `[OptionUniverse]` type (the `[OptionUniverse]` form returns objects; `flatten=True` returns the DataFrame). Columns: `impliedvolatility, delta, gamma, vega, theta, rho, open/high/low/close, volume, openinterest, underlying, value`, indexed by `(time, symbol)`. Strike/expiry/right are NOT columns — they are in the symbol — so use the object form above to filter by expiry/moneyness.

These IV/greek values are **daily, pre-computed** (end of the prior trading day), one value per contract per trading day, and **cannot be customized**. If that is fine (e.g. a single ATM contract's IV on a given date), Route A is simplest. **For an intraday series, or a custom IV, use Route B.** Pick ATM by delta ≈ 0.5:
```python
calls = [c for c in option_universe if c.symbol.id.option_right == OptionRight.CALL
         and c.symbol.id.date.date() == target_expiry and c.implied_volatility]   # drop 0-IV (no-quote) contracts
atm_call = min(calls, key=lambda c: abs(abs(c.greeks.delta) - 0.5)) if calls else None
atm_call_iv = atm_call.implied_volatility        # average with the ATM put for a call-put ATM IV
```

## Route B — IV / greek indicators (custom computation and intraday; historical via `indicator_history`)
When you need to **control how IV is computed** — e.g. averaging an ATM call-put pair, or using a specific pricing model — build an indicator on the specific contract, using the **mirror** (the paired call/put). This is also the **only** way to get **intraday** (minute/second) historical IV/greeks — Route A returns one value per trading day.
First identify the contracts (current chain as a DataFrame):

```python
chain = self.option_chain(underlying, flatten=True).data_frame   # cols: expiry, strike, right, underlyinglastprice
# filter: chain.expiry >= target, keep the min expiry, keep min abs(strike - underlyinglastprice) -> the ATM call & put symbols
self.add_option_contract(call); self.add_option_contract(put)    # subscribe both contracts (and the underlying)
```

**Automatic** — helper methods `self.iv` (IV), `self.d` (delta), `self.g` (gamma), `self.v` (vega), `self.t` (theta), `self.r` (rho). Signature `self.iv(symbol, mirror_option=None, risk_free_rate=None, dividend_yield=None, option_model=None, resolution=None)`; defaults: risk-free from the Interest Rate Provider, dividend from the dividend model, `option_model=OptionPricingModelType.BINOMIAL_COX_ROSS_RUBINSTEIN`, `resolution` = the contract subscription's resolution.
```python
iv = self.iv(call, put)                                          # mirror = the put
iv.set_smoothing_function(lambda iv, mirror_iv: (iv + mirror_iv) * 0.5)   # = the average of the ATM call+put IV
```

**Manual** — construct the indicator class directly for full control (own models / feed your own data). Classes `ImpliedVolatility`, `Delta`, `Gamma`, `Vega`, `Theta`, `Rho`; constructor `(option, risk_free_rate_model, dividend_yield_provider, mirror_option, option_pricing_model)`:
```python
div = DividendYieldProvider(underlying)
iv = ImpliedVolatility(call, self.risk_free_interest_rate_model, div, put, OptionPricingModelType.FORWARD_TREE)
# then in on_data(slice): option+mirror IV come from QUOTE bars, the underlying from TRADE bars (or use indicator_history)
q, b = slice.quote_bars, slice.bars
if call in q and put in q and underlying in b:                   # guard: all three present this bar
    for dp in [IndicatorDataPoint(call, q[call].end_time, q[call].close),
               IndicatorDataPoint(put,  q[put].end_time,  q[put].close),
               IndicatorDataPoint(underlying, b[underlying].end_time, b[underlying].close)]:
        iv.update(dp)
    value = iv.current.value
```

**Historical values** (either construction) without hand-feeding: `self.indicator_history(iv, [call, put, underlying], period_or_dates, resolution)` — it resets the indicator, requests history for those symbols, and replays it.
Iterate the `IndicatorDataPoint`s (`.end_time`, `.value`), or use `.data_frame` (the IV value is the `current` column).
**Resolution sets granularity: `Resolution.MINUTE`/`Resolution.SECOND` give an intraday series, `Resolution.DAILY` one value per trading day.** (For streaming `self.iv` updates instead of `indicator_history`, granularity follows the contract subscription resolution — `self.add_option_contract(call, Resolution.MINUTE)`.)

## Rules
- **History uses the CANONICAL option symbol** (get it any of the three ways in Route A), not individual contract symbols; it returns ALL contracts each day and is **empty** if there is no data in the window — always check.
- IV = `c.implied_volatility` (object) / `impliedvolatility` (DataFrame). Greeks under `c.greeks.*` (`.delta/.gamma/.vega/.theta/.rho`). **Strike/expiry/right are NOT properties of `OptionUniverse`** — read them off the symbol: `c.symbol.id.strike_price` / `c.symbol.id.date` / `c.symbol.id.option_right`.
- **Guard empties and zeros.** History is empty when the window has no data — `if not history: return` (objects) / `if df.empty: return` (DataFrame). And a contract's `implied_volatility`/greeks are **0** on days it had no quote (illiquid strikes/far expiries) — drop them before averaging, ranking, or dividing by IV: `[c for c in option_universe if c.implied_volatility]` (objects) / `df[df.impliedvolatility != 0]` (DataFrame). `min(calls, ...)` raises on an empty list, so check first.
- **Cost:** option data is heavy — request **per underlying and only over the window you need** (e.g. a single month rather than the whole history), never the whole universe over the whole backtest at once.
- **Research Environment** is identical (`self`→`qb`). Route A: `qb = QuantBook(); option = qb.add_option(ticker); qb.history[OptionUniverse](option.symbol, start, end)`. Route B: `qb.add_option_contract(call); qb.add_option_contract(put); iv = qb.iv(call, put); qb.indicator_history(iv, [call, put, underlying], timedelta(30), Resolution.MINUTE)`.
