---
name: historical-data-equity-options
description: >-
  Use when an equity-options strategy needs HISTORICAL option data — implied volatility, greeks, prices, open interest — for PAST dates.
  E.g. a historical implied-volatility series, or backfilling option IV/greeks over a period for a signal or model.
  Triggers — needing an option's IV/greeks at a target expiry/moneyness on a historical date; a lookback of past daily option chains; an INTRADAY historical IV/greek series; "get the past implied vol of these options".
  Two routes — (A) `History<OptionUniverse>(option.Symbol, ...)` for the PRE-COMPUTED daily (end-of-day) IV/greeks;
  (B) the IV/greek INDICATORS (`IV`/`D`/...) with `IndicatorHistory` when you need CUSTOM IV (e.g. the average of an ATM call-put pair) OR INTRADAY (minute/second) values — Route A is daily-only.
  Skip for subscribing a LIVE chain to trade (that is chained-universes-options).
---

# Historical equity-option data — IV, greeks, prices for PAST dates

Use this to read an option's **implied volatility or greeks on historical dates** (a historical IV series, backfilling option greeks over a period, etc.). This is PAST option data — different from subscribing a live chain to trade (see `chained-universes-options`).

There are two routes. Prefer **(A)** for a straight historical IV/greek read; use **(B)** when you need a custom IV (e.g. the *average of the ATM call and put IV*, or a specific pricing model) **or intraday (minute/second) values** — Route A is daily / end-of-day only.

## Route A — OptionUniverse history (pre-computed daily IV/greeks)
The history request takes the **canonical option `Symbol`** for the underlying — it returns ALL tradable contracts each day (no filter needed; any `SetFilter` only affects the live `OnData` chain). Obtain the canonical symbol whichever way the algorithm is built:
- static single underlying: `var canonical = AddOption("AAPL").Symbol` (`AddIndexOption` for SPX);
- dynamic equity universe via `AddUniverseOptions`: the canonical symbols are the KEYS of the option chains in `OnData` — `foreach (var (canonical, chain) in slice.OptionChains) { ... }`;
- from any contract `Symbol` you already hold: `var canonical = contractSymbol.Canonical`.
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

These IV/greek values are **daily, pre-computed** (end of the prior trading day), one value per contract per trading day, and **cannot be customized**. If that is fine (e.g. a single ATM contract's IV on a given date), Route A is simplest. **For an intraday series, or a custom IV, use Route B.** Pick ATM by delta ≈ 0.5:
```csharp
var calls = optionUniverse.Data
    .Select(contract => contract as OptionUniverse)
    .Where(c => c.Symbol.ID.OptionRight == OptionRight.Call
        && c.Symbol.ID.Date.Date == targetExpiry && c.ImpliedVolatility != 0m)   // drop 0-IV (no-quote) contracts
    .ToList();
var atmCall = calls.OrderBy(c => Math.Abs(Math.Abs(c.Greeks.Delta) - 0.5m)).FirstOrDefault();
var atmCallIv = atmCall?.ImpliedVolatility;      // average with the ATM put for a call-put ATM IV
```

## Route B — IV / greek indicators (custom computation and intraday; historical via `IndicatorHistory`)
When you need to **control how IV is computed** — e.g. averaging an ATM call-put pair, or using a specific pricing model — build an indicator on the specific contract, using the **mirror** (the paired call/put). This is also the **only** way to get **intraday** (minute/second) historical IV/greeks — Route A returns one value per trading day.
First identify the contracts (current chain):

```csharp
var chain = OptionChain(underlying);   // contracts with Expiry, Strike, Right, UnderlyingLastPrice
// Filter: chain.Where(c => c.Expiry >= target), keep the min expiry, keep min Math.Abs(c.Strike - c.UnderlyingLastPrice) -> the ATM call & put Symbols.
AddOptionContract(call); AddOptionContract(put);    // subscribe both contracts (and the underlying)
```

**Automatic** — helper methods `IV` (IV), `D` (delta), `G` (gamma), `V` (vega), `T` (theta), `R` (rho). Signature `IV(symbol, mirrorOption = null, riskFreeRate = null, dividendYield = null, optionModel = null, resolution = null)`; defaults: risk-free from the Interest Rate Provider, dividend from the dividend model, `optionModel = OptionPricingModelType.BinomialCoxRossRubinstein`, `resolution` = the contract subscription's resolution.
```csharp
_iv = IV(call, put);                                             // mirror = the put
_iv.SetSmoothingFunction((iv, mirrorIv) => (iv + mirrorIv) * 0.5m);   // = the average of the ATM call+put IV
```

**Manual** — construct the indicator class directly for full control (own models / feed your own data). Classes `ImpliedVolatility`, `Delta`, `Gamma`, `Vega`, `Theta`, `Rho`; constructor `(option, riskFreeRateModel, dividendYieldProvider, mirrorOption, optionPricingModel)`:
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

**Historical values** (either construction) without hand-feeding: `IndicatorHistory(_iv, new[] { call, put, underlying }, periodOrDates, resolution)` — it resets the indicator, requests history for those symbols, and replays it.
Iterate the resulting points (`.Current.EndTime`, `.Current.Value`).
**Resolution sets granularity: `Resolution.Minute`/`Resolution.Second` give an intraday series, `Resolution.Daily` one value per trading day.** (For streaming `IV` updates instead of `IndicatorHistory`, granularity follows the contract subscription resolution — `AddOptionContract(call, Resolution.Minute)`.)

## Rules
- **History uses the CANONICAL option symbol** (get it any of the three ways in Route A), not individual contract symbols; it returns ALL contracts each day and is **empty** if there is no data in the window — always check.
- IV = `c.ImpliedVolatility`. Greeks under `c.Greeks.*` (`.Delta/.Gamma/.Vega/.Theta/.Rho`). **Strike/expiry/right are NOT properties of `OptionUniverse`** — read them off the symbol: `c.Symbol.ID.StrikePrice` / `c.Symbol.ID.Date` / `c.Symbol.ID.OptionRight`.
- **Guard empties and zeros.** History is empty when the window has no data — `if (!history.Any()) return;`. And a contract's `ImpliedVolatility`/greeks are **0** on days it had no quote (illiquid strikes/far expiries) — drop them before averaging, ranking, or dividing by IV: `.Where(c => c.ImpliedVolatility != 0)`. `FirstOrDefault()` returns `null` on an empty sequence, so check it before dereferencing.
- **Cost:** option data is heavy — request **per underlying and only over the window you need** (e.g. a single month rather than the whole history), never the whole universe over the whole backtest at once.
- **Research Environment** is identical (`this`→`qb`). Route A: `var qb = new QuantBook(); var option = qb.AddOption(ticker); qb.History<OptionUniverse>(option.Symbol, start, end)`. Route B: `qb.AddOptionContract(call); qb.AddOptionContract(put); var iv = qb.IV(call, put); qb.IndicatorHistory(iv, new[] { call, put, underlying }, TimeSpan.FromDays(30), Resolution.Minute)`.
