---
name: historical-data-equity-options
description: >-
  Use when an equity-options strategy needs HISTORICAL option data — implied volatility, greeks, prices, open interest — for PAST dates.
  E.g. a historical implied-volatility series, or backfilling option IV/greeks over a period for a signal or model.
  Triggers — needing an option's IV/greeks at a target expiry/moneyness on a historical date; a lookback of past daily option chains; an INTRADAY historical IV/greek series; "get the past implied vol of these options".
  Two routes — (A) py`self.history[OptionUniverse](option.symbol, ...)`cs`History<OptionUniverse>(option.Symbol, ...)` for the PRE-COMPUTED daily (end-of-day) IV/greeks;
  (B) the IV/greek INDICATORS (py`self.iv`cs`IV`/py`self.d`cs`D`/...) with py`indicator_history`cs`IndicatorHistory` when you need CUSTOM IV (e.g. the average of an ATM call-put pair) OR INTRADAY (minute/second) values — Route A is daily-only.
  Skip for subscribing a LIVE chain to trade (that is chained-universes-options).
---

# Historical equity-option data — IV, greeks, prices for PAST dates

Use this to read an option's **implied volatility or greeks on historical dates** (a historical IV series, backfilling option greeks over a period, etc.). This is PAST option data — different from subscribing a live chain to trade (see `chained-universes-options`).

There are two routes. Prefer **(A)** for a straight historical IV/greek read; use **(B)** when you need a custom IV (e.g. the *average of the ATM call and put IV*, or a specific pricing model) **or intraday (minute/second) values** — Route A is daily / end-of-day only.

## Route A — OptionUniverse history (pre-computed daily IV/greeks)
The history request takes the **canonical option `Symbol`** for the underlying — it returns ALL tradable contracts each day (no filter needed; any py`set_filter`cs`SetFilter` only affects the live py`on_data`cs`OnData` chain). Obtain the canonical symbol whichever way the algorithm is built:
- static single underlying: py`canonical = self.add_option("AAPL").symbol`cs`var canonical = AddOption("AAPL").Symbol` (py`add_index_option`cs`AddIndexOption` for SPX);
- dynamic equity universe via py`add_universe_options`cs`AddUniverseOptions`: the canonical symbols are the KEYS of the option chains in py`on_data`cs`OnData` — py`for canonical, chain in slice.option_chains.items(): ...`cs`foreach (var (canonical, chain) in slice.OptionChains) { ... }`;
- from any contract `Symbol` you already hold: py`canonical = contract_symbol.canonical`cs`var canonical = contractSymbol.Canonical`.
```python
history = self.history[OptionUniverse](canonical, start, end)   # or (canonical, timedelta(30))
for option_universe in history:                        # one daily chain per element
    day = option_universe.end_time
    for c in option_universe:                          # every tradable contract that day
        iv, delta = c.implied_volatility, c.greeks.delta          # c.greeks.gamma/.vega/.theta/.rho ; c.price ; c.volume ; c.open_interest
        # strike/expiry/right are NOT properties of OptionUniverse - read them off the symbol:
        strike, expiry, right = c.symbol.id.strike_price, c.symbol.id.date, c.symbol.id.option_right
```

```csharp
var history = History<OptionUniverse>(canonical, start, end);   // or (canonical, TimeSpan.FromDays(30))
foreach (var optionUniverse in history)                // one daily chain per element
{
    var day = optionUniverse.EndTime;
    // every tradable contract that day:
    foreach (var c in optionUniverse.Data.Select(contract => contract as OptionUniverse))
    {
        var (iv, delta) = (c.ImpliedVolatility, c.Greeks.Delta);  // c.Greeks.Gamma/.Vega/.Theta/.Rho ; c.Price ; c.Volume ; c.OpenInterest
        // strike/expiry/right are NOT properties of OptionUniverse - read them off the symbol:
        var (strike, expiry, right) = (c.Symbol.ID.StrikePrice, c.Symbol.ID.Date, c.Symbol.ID.OptionRight);
    }
}
```

<!-- python-only -->
For a DataFrame instead, call plain `self.history(canonical, ..., flatten=True)` — **WITHOUT** the `[OptionUniverse]` type (the `[OptionUniverse]` form returns objects; `flatten=True` returns the DataFrame). Columns: `impliedvolatility, delta, gamma, vega, theta, rho, open/high/low/close, volume, openinterest, underlying, value`, indexed by `(time, symbol)`. Strike/expiry/right are NOT columns — they are in the symbol — so use the object form above to filter by expiry/moneyness.

<!-- /python-only -->
These IV/greek values are **daily, pre-computed** (end of the prior trading day), one value per contract per trading day, and **cannot be customized**. If that is fine (e.g. a single ATM contract's IV on a given date), Route A is simplest. **For an intraday series, or a custom IV, use Route B.** Pick ATM by delta ≈ 0.5:
```python
calls = [c for c in option_universe if c.symbol.id.option_right == OptionRight.CALL
         and c.symbol.id.date.date() == target_expiry and c.implied_volatility]   # drop 0-IV (no-quote) contracts
atm_call = min(calls, key=lambda c: abs(abs(c.greeks.delta) - 0.5)) if calls else None
atm_call_iv = atm_call.implied_volatility        # average with the ATM put for a call-put ATM IV
```

```csharp
var calls = optionUniverse.Data
    .Select(contract => contract as OptionUniverse)
    .Where(c => c.Symbol.ID.OptionRight == OptionRight.Call
        && c.Symbol.ID.Date.Date == targetExpiry && c.ImpliedVolatility != 0m)   // drop 0-IV (no-quote) contracts
    .ToList();
var atmCall = calls.OrderBy(c => Math.Abs(Math.Abs(c.Greeks.Delta) - 0.5m)).FirstOrDefault();
var atmCallIv = atmCall?.ImpliedVolatility;      // average with the ATM put for a call-put ATM IV
```

## Route B — IV / greek indicators (custom computation and intraday; historical via py`indicator_history`cs`IndicatorHistory`)
When you need to **control how IV is computed** — e.g. averaging an ATM call-put pair, or using a specific pricing model — build an indicator on the specific contract, using the **mirror** (the paired call/put). This is also the **only** way to get **intraday** (minute/second) historical IV/greeks — Route A returns one value per trading day.
<!-- python-only -->
First identify the contracts (current chain as a DataFrame):

```python
chain = self.option_chain(underlying, flatten=True).data_frame   # cols: expiry, strike, right, underlyinglastprice
# filter: chain.expiry >= target, keep the min expiry, keep min abs(strike - underlyinglastprice) -> the ATM call & put symbols
self.add_option_contract(call); self.add_option_contract(put)    # subscribe both contracts (and the underlying)
```
<!-- /python-only -->
<!-- csharp-only -->
First identify the contracts (current chain):

```csharp
var chain = OptionChain(underlying);   // contracts with Expiry, Strike, Right, UnderlyingLastPrice
// Filter: chain.Where(c => c.Expiry >= target), keep the min expiry, keep min Math.Abs(c.Strike - c.UnderlyingLastPrice) -> the ATM call & put Symbols.
AddOptionContract(call); AddOptionContract(put);    // subscribe both contracts (and the underlying)
```
<!-- /csharp-only -->

**Automatic** — helper methods py`self.iv`cs`IV` (IV), py`self.d`cs`D` (delta), py`self.g`cs`G` (gamma), py`self.v`cs`V` (vega), py`self.t`cs`T` (theta), py`self.r`cs`R` (rho). Signature py`self.iv(symbol, mirror_option=None, risk_free_rate=None, dividend_yield=None, option_model=None, resolution=None)`cs`IV(symbol, mirrorOption = null, riskFreeRate = null, dividendYield = null, optionModel = null, resolution = null)`; defaults: risk-free from the Interest Rate Provider, dividend from the dividend model, py`option_model=OptionPricingModelType.BINOMIAL_COX_ROSS_RUBINSTEIN`cs`optionModel = OptionPricingModelType.BinomialCoxRossRubinstein`, `resolution` = the contract subscription's resolution.
```python
iv = self.iv(call, put)                                          # mirror = the put
iv.set_smoothing_function(lambda iv, mirror_iv: (iv + mirror_iv) * 0.5)   # = the average of the ATM call+put IV
```

```csharp
_iv = IV(call, put);                                             // mirror = the put
_iv.SetSmoothingFunction((iv, mirrorIv) => (iv + mirrorIv) * 0.5m);   // = the average of the ATM call+put IV
```

**Manual** — construct the indicator class directly for full control (own models / feed your own data). Classes `ImpliedVolatility`, `Delta`, `Gamma`, `Vega`, `Theta`, `Rho`; constructor py`(option, risk_free_rate_model, dividend_yield_provider, mirror_option, option_pricing_model)`cs`(option, riskFreeRateModel, dividendYieldProvider, mirrorOption, optionPricingModel)`:
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

```csharp
var div = new DividendYieldProvider(underlying);
_iv = new ImpliedVolatility(call, RiskFreeInterestRateModel, div, put, OptionPricingModelType.ForwardTree);
// then in OnData(slice): option+mirror IV come from QUOTE bars, the underlying from TRADE bars (or use IndicatorHistory)
var q = slice.QuoteBars;
var b = slice.Bars;
if (q.ContainsKey(call) && q.ContainsKey(put) && b.ContainsKey(underlying))   // guard: all three present this bar
{
    foreach (var dp in new[] {
        new IndicatorDataPoint(call, q[call].EndTime, q[call].Close),
        new IndicatorDataPoint(put,  q[put].EndTime,  q[put].Close),
        new IndicatorDataPoint(underlying, b[underlying].EndTime, b[underlying].Close) })
    {
        _iv.Update(dp);
    }
    var value = _iv.Current.Value;
}
```

**Historical values** (either construction) without hand-feeding: py`self.indicator_history(iv, [call, put, underlying], period_or_dates, resolution)`cs`IndicatorHistory(_iv, new[] { call, put, underlying }, periodOrDates, resolution)` — it resets the indicator, requests history for those symbols, and replays it.
<!-- python-only -->
Iterate the `IndicatorDataPoint`s (`.end_time`, `.value`), or use `.data_frame` (the IV value is the `current` column).
<!-- /python-only -->
<!-- csharp-only -->
Iterate the resulting points (`.Current.EndTime`, `.Current.Value`).
<!-- /csharp-only -->
**Resolution sets granularity: py`Resolution.MINUTE`cs`Resolution.Minute`/py`Resolution.SECOND`cs`Resolution.Second` give an intraday series, py`Resolution.DAILY`cs`Resolution.Daily` one value per trading day.** (For streaming py`self.iv`cs`IV` updates instead of py`indicator_history`cs`IndicatorHistory`, granularity follows the contract subscription resolution — py`self.add_option_contract(call, Resolution.MINUTE)`cs`AddOptionContract(call, Resolution.Minute)`.)

## Rules
- **History uses the CANONICAL option symbol** (get it any of the three ways in Route A), not individual contract symbols; it returns ALL contracts each day and is **empty** if there is no data in the window — always check.
<!-- python-only -->
- IV = `c.implied_volatility` (object) / `impliedvolatility` (DataFrame). Greeks under `c.greeks.*` (`.delta/.gamma/.vega/.theta/.rho`). **Strike/expiry/right are NOT properties of `OptionUniverse`** — read them off the symbol: `c.symbol.id.strike_price` / `c.symbol.id.date` / `c.symbol.id.option_right`.
- **Guard empties and zeros.** History is empty when the window has no data — `if not history: return` (objects) / `if df.empty: return` (DataFrame). And a contract's `implied_volatility`/greeks are **0** on days it had no quote (illiquid strikes/far expiries) — drop them before averaging, ranking, or dividing by IV: `[c for c in option_universe if c.implied_volatility]` (objects) / `df[df.impliedvolatility != 0]` (DataFrame). `min(calls, ...)` raises on an empty list, so check first.
<!-- /python-only -->
<!-- csharp-only -->
- IV = `c.ImpliedVolatility`. Greeks under `c.Greeks.*` (`.Delta/.Gamma/.Vega/.Theta/.Rho`). **Strike/expiry/right are NOT properties of `OptionUniverse`** — read them off the symbol: `c.Symbol.ID.StrikePrice` / `c.Symbol.ID.Date` / `c.Symbol.ID.OptionRight`.
- **Guard empties and zeros.** History is empty when the window has no data — `if (!history.Any()) return;`. And a contract's `ImpliedVolatility`/greeks are **0** on days it had no quote (illiquid strikes/far expiries) — drop them before averaging, ranking, or dividing by IV: `.Where(c => c.ImpliedVolatility != 0)`. `FirstOrDefault()` returns `null` on an empty sequence, so check it before dereferencing.
<!-- /csharp-only -->
- **Cost:** option data is heavy — request **per underlying and only over the window you need** (e.g. a single month rather than the whole history), never the whole universe over the whole backtest at once.
- **Research Environment** is identical (py`self`cs`this`→`qb`). Route A: py`qb = QuantBook(); option = qb.add_option(ticker); qb.history[OptionUniverse](option.symbol, start, end)`cs`var qb = new QuantBook(); var option = qb.AddOption(ticker); qb.History<OptionUniverse>(option.Symbol, start, end)`. Route B: py`qb.add_option_contract(call); qb.add_option_contract(put); iv = qb.iv(call, put); qb.indicator_history(iv, [call, put, underlying], timedelta(30), Resolution.MINUTE)`cs`qb.AddOptionContract(call); qb.AddOptionContract(put); var iv = qb.IV(call, put); qb.IndicatorHistory(iv, new[] { call, put, underlying }, TimeSpan.FromDays(30), Resolution.Minute)`.
