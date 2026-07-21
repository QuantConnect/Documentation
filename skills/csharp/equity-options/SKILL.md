---
name: equity-options
description: Use when an algorithm trades options on a SINGLE named underlying Equity — subscribing (`AddOption` vs `AddOptionContract`), reading the chain (the slice's `OptionChains` vs the `OptionChain()` method), greeks/IV data freshness, strike/expiry filters, price seeding, and warm-up. Triggers — code calling any of those four APIs; questions like "why are my deltas/IV stale", "why can't I trade the contract I just added", "why is the chain empty", "which API gives decision-time greeks". Skip when — options on a DYNAMIC equity universe (see chained-universes-options), historical/backfilled option data for past dates (see historical-data-equity-options), or multi-leg order placement and assignment/exercise handling (see option-strategies).
---

# Equity Options: subscriptions and chain data — QuantConnect / LEAN

Two decisions shape every equity-options algorithm, and each has two answers that look interchangeable but carry different data: **how you subscribe** (`AddOption` vs `AddOptionContract`) and **how you read the chain** (the slice's `OptionChains` vs the `OptionChain()` method). Picking the wrong member of either pair produces algorithms that trade on day-old numbers without any error.

## Greeks/IV freshness — the core rule

There are two different option datasets behind the APIs (doc: "There is a daily, pre-calculated dataset based on the end of the previous trading day. There is also a stream of values that are calculated on-the-fly as your algorithm runs."):

| Access path | Data you get |
|---|---|
| `option.SetFilter(...)` filter function (incl. `.Delta()/.IV()` filters) | "daily, pre-calculated values based on the end of the **previous trading day**" — not customizable |
| `OptionChain(symbol)` method | same prior-day EOD pre-calculated values; returns **all tradable contracts**, not just your filter's picks |
| `History<OptionUniverse>(...)` | same prior-day EOD values, one full chain per trading day |
| the slice: `data.OptionChains` (or `CurrentSlice.OptionChains`) | **current values from the Option price model**, computed on request against live data — customizable via `option.PriceModel` |

The operating rule that follows: **any intraday decision — entry sizing, delta hedging, IV-threshold checks, strike selection at a decision time — should read the slice's chain.** The `OptionChain()` method is for **daily contract discovery** (find which contracts exist, filter by prior-day delta/OI, then subscribe); using it for intraday sizing or greeks means every number is one day stale, and nothing errors to tell you. Greeks on slice contracts are computed lazily — accessing `Greeks` costs nothing; accessing `Greeks.Delta` triggers the calculation — so only read the greeks you need.

## Subscription route 1: `AddOption` — a filtered basket (universe)

For strategies that pick contracts from a chain at decision time (spreads, condors, straddles, rolling structures), subscribe once in `Initialize`:

```csharp
UniverseSettings.Asynchronous = true;
var option = AddOption("SPY");                  // underlying auto-subscribed RAW
option.SetFilter(u => u.IncludeWeeklys().Strikes(-3, 3).Expiration(15, 60));
_symbol = option.Symbol;                        // canonical Symbol — save it
```

- **The canonical `option.Symbol` is not tradable** — it is the key into `data.OptionChains` and the first argument of every `OptionStrategies` factory.
- **Filter timing**: selection "usually runs at the first bar of every day"; a newly selected contract's data arrives "in the next `Slice`". So the chain can be absent on any given event — always guard: `if (!data.OptionChains.TryGetValue(_symbol, out var chain)) return;`.
- **Default filter** if you never call `SetFilter`: standards + weeklys, within 1 strike of the underlying price, expiring within 35 days — almost never what a spec wants; set the filter explicitly.
- Filter building blocks: `Strikes(min, max)` (relative strike counts around the money, not dollars), `Expiration(minDays, maxDays)`, `CallsOnly()/PutsOnly()`, `WeeklysOnly()/StandardsOnly()/IncludeWeeklys()`, `FrontMonth()/BackMonth()`, greek/OI filters `Delta(min, max)`, `IV(min, max)`, `OpenInterest(min, max)` (these compare **prior-day EOD** values — use them for coarse pre-selection, then pick precisely from live slice greeks), and strategy-shaped helpers (`u.Straddle(30)`, `u.IronCondor(30, 5, 10)`) that narrow the universe to exactly the legs a named strategy needs.
- **Resolution rule**: the option resolution must be ≥ the underlying Equity's resolution (minute equity → minute/hour/daily options). Contract subscriptions support minute, hour, and daily; minute is the default.

Reading the chain at a decision time:

```csharp
public override void OnData(Slice data)
{
    if (!data.OptionChains.TryGetValue(_symbol, out var chain)) return;
    var spot = chain.Underlying.Price;
    var atmCall = chain.Where(c => c.Right == OptionRight.Call)
        .OrderBy(c => Math.Abs(c.Strike - spot)).First();
    var delta = atmCall.Greeks.Delta;    // live price-model value, computed on request
    var mid = (atmCall.BidPrice + atmCall.AskPrice) / 2;
}
```

In a Scheduled Event there is no slice parameter — read the same object through `CurrentSlice.OptionChains`.

## Subscription route 2: `AddOptionContract` — individual known contracts

For strategies that hold a few specific contracts found by daily screening, discover with the `OptionChain()` method, then subscribe each pick:

```csharp
// In a scheduled event — OptionChain() carries PRIOR-DAY EOD greeks: fine for
// discovering contracts, wrong for intraday sizing or hedging.
var chain = OptionChain(_underlying);
var expiry = chain.Min(c => c.Expiry);
var symbol = chain
    .Where(c => c.Expiry == expiry && c.Right == OptionRight.Call && c.Greeks.Delta > 0.3m)
    .OrderBy(c => c.OpenInterest).Last().Symbol;
AddOptionContract(symbol);
```

The three idioms that must accompany this route:

1. **Seed prices** — a contract subscribed this time step has no data until the next slice ("you'll need to wait until the next `Slice` to receive data and trade the contract"). Set `Settings.SeedInitialPrices = true;` in `Initialize` to trade it immediately.
2. **RAW underlying** — subscribe the underlying with `AddEquity("SPY", dataNormalizationMode: DataNormalizationMode.Raw)` so strike-vs-price comparisons share a scale. If you skip this, LEAN auto-subscribes (or force-switches an existing subscription) to RAW anyway — see the history caveat below.
3. **Volatility warm-up** — `SetWarmUp` in `Initialize` cannot anticipate contracts added later; warm the underlying's volatility model in a security initializer (feed ~30 daily bars through `security.VolatilityModel.Update(...)`) so slice-chain IV/greeks are sane from the first read. On the `AddOption` route the simple `SetWarmUp(31, Resolution.Daily)` suffices (default volatility model needs 30+1 daily bars).

To read a subscribed contract from the slice, index the chain with the **canonical**: `data.OptionChains.TryGetValue(_contractSymbol.Canonical, out var chain)`. `RemoveOptionContract(symbol)` cancels its open orders and liquidates the position.

## The RAW-underlying history caveat

Because any option subscription forces the underlying Equity to RAW normalization, a `History()` call on that underlying now returns **raw closes** — dividend gaps included. Any realized-volatility, daily-return, or overnight-gap calculation on an option underlying must request adjusted data explicitly: `History(_underlying, 22, Resolution.Daily, dataNormalizationMode: DataNormalizationMode.Adjusted)`. Do not "fix" this by subscribing the underlying ADJUSTED — LEAN silently switches it back to RAW.

## Common mistakes

- **Sizing or hedging from `OptionChain()` intraday.** Its marks and greeks are the previous close; position sizes computed from them are provably off versus decision-time quotes. Decision-time numbers come from the slice's chain.
- **Trading the canonical symbol**, or passing a contract symbol where the canonical is required (chain lookup, `OptionStrategies` factories).
- **No `TryGetValue` guard** — the chain is legitimately absent before the first daily selection and whenever the filter turns over.
- **Forgetting `IncludeWeeklys()`** when a spec's DTE window (e.g. 21–45 days, closest to 30) needs weekly expiries to hit its target.
- **Computing RV/returns on the force-RAW underlying** without an explicit adjusted-normalization history request.
- Only American-style US equity options are supported; strikes in the filter API are **relative counts**, not dollar offsets — `Strikes(-1, 1)` means one strike either side of the money.
