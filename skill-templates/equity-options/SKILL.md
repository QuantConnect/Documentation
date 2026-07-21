---
name: equity-options
description: Use when an algorithm trades options on a SINGLE named underlying Equity — subscribing (py`add_option`cs`AddOption` vs py`add_option_contract`cs`AddOptionContract`), reading the chain (the slice's py`option_chains`cs`OptionChains` vs the py`option_chain()`cs`OptionChain()` method), greeks/IV data freshness, strike/expiry filters, price seeding, and warm-up. Triggers — code calling any of those four APIs; questions like "why are my deltas/IV stale", "why can't I trade the contract I just added", "why is the chain empty", "which API gives decision-time greeks". Skip when — options on a DYNAMIC equity universe (see chained-universes-options), historical/backfilled option data for past dates (see historical-data-equity-options), or multi-leg order placement and assignment/exercise handling (see option-strategies).
---

# Equity Options: subscriptions and chain data — QuantConnect / LEAN

Two decisions shape every equity-options algorithm, and each has two answers that look interchangeable but carry different data: **how you subscribe** (py`add_option`cs`AddOption` vs py`add_option_contract`cs`AddOptionContract`) and **how you read the chain** (the slice's py`option_chains`cs`OptionChains` vs the py`option_chain()`cs`OptionChain()` method). Picking the wrong member of either pair produces algorithms that trade on day-old numbers without any error.

## Greeks/IV freshness — the core rule

There are two different option datasets behind the APIs (doc: "There is a daily, pre-calculated dataset based on the end of the previous trading day. There is also a stream of values that are calculated on-the-fly as your algorithm runs."):

| Access path | Data you get |
|---|---|
| py`option.set_filter(...)`cs`option.SetFilter(...)` filter function (incl. py`.delta()/.iv()`cs`.Delta()/.IV()` filters) | "daily, pre-calculated values based on the end of the **previous trading day**" — not customizable |
| py`self.option_chain(symbol)`cs`OptionChain(symbol)` method | same prior-day EOD pre-calculated values; returns **all tradable contracts**, not just your filter's picks |
| py`self.history[OptionUniverse](...)`cs`History<OptionUniverse>(...)` | same prior-day EOD values, one full chain per trading day |
| the slice: py`data.option_chains`cs`data.OptionChains` (or py`self.current_slice.option_chains`cs`CurrentSlice.OptionChains`) | **current values from the Option price model**, computed on request against live data — customizable via py`option.price_model`cs`option.PriceModel` |

The operating rule that follows: **any intraday decision — entry sizing, delta hedging, IV-threshold checks, strike selection at a decision time — should read the slice's chain.** The py`option_chain()`cs`OptionChain()` method is for **daily contract discovery** (find which contracts exist, filter by prior-day delta/OI, then subscribe); using it for intraday sizing or greeks means every number is one day stale, and nothing errors to tell you. Greeks on slice contracts are computed lazily — accessing py`greeks`cs`Greeks` costs nothing; accessing py`greeks.delta`cs`Greeks.Delta` triggers the calculation — so only read the greeks you need.

## Pick ONE route — and subscribe only what the strategy can trade

Decide the route once, in py`initialize`cs`Initialize`. Mixing them is usually a smell:

- **Universe route** (py`add_option`cs`AddOption` + filter): a universe's purpose is its slice chain. If all selection, pricing, and sizing happen through py`option_chain()`cs`OptionChain()` anyway, the minute-resolution subscriptions are pure cost — either read the slice for the decisions that need live data, or drop the universe and subscribe the picks directly.
- **Discovery route** (py`option_chain()`cs`OptionChain()` + py`add_option_contract`cs`AddOptionContract`): the daily chain picks the contracts; subscribe each pick. Match the data to the decision: the py`option_chain()`cs`OptionChain()` rows are previous-close values — fine when daily-granularity data suits the strategy (screening, ranking, a daily-cadence rule that tolerates day-old marks), but when the strategy calls for decision-time values (intraday sizing, hedging, entry marks), read the subscribed picks' live quotes from the slice. Whichever you use, know which one you are using.
- A subscription earns its cost by being **read** or being **held**: a contract you own needs its subscription (position pricing, fills, expiry processing) even if you never read a chain. What doesn't earn its cost is breadth — a wide py`add_option`cs`AddOption` universe where the algorithm neither reads the slice chain nor holds more than its few picks.

Size the subscription to the trade, not to the chain:

- Prefer a **strategy-shaped filter** when one matches the structure — py`u.iron_condor(30, 5, 10)`cs`u.IronCondor(30, 5, 10)`, py`u.straddle(30)`cs`u.Straddle(30)`, py`u.call_spread(30, 5)`cs`u.CallSpread(30, 5)`, and friends subscribe only the legs the strategy needs.
- Otherwise derive the strike span and DTE window from the structure's own numbers (target deltas, wing widths, expiry rules), adding a delta-band filter (py`u.delta(0.05, 0.30)`cs`u.Delta(0.05m, 0.30m)`) where the structure is delta-targeted. The span should be the tightest one that always contains the tradable candidates — not a safety blanket.
- py`strikes(-100, 100)`cs`Strikes(-100, 100)`, or an unfiltered py`expiration(0, 500)`cs`Expiration(0, 500)` window, subscribes thousands of minute-resolution contracts — a cost bug even when the algorithm is otherwise correct.

## Subscription route 1: py`add_option`cs`AddOption` — a filtered basket (universe)

For strategies that pick contracts from a chain at decision time (spreads, condors, straddles, rolling structures), subscribe once in py`initialize`cs`Initialize`:

```python
self.universe_settings.asynchronous = True
option = self.add_option("SPY")                 # underlying auto-subscribed RAW
option.set_filter(lambda u: u.include_weeklys().strikes(-3, 3).expiration(15, 60))
self._symbol = option.symbol                    # canonical Symbol — save it
```
```csharp
UniverseSettings.Asynchronous = true;
var option = AddOption("SPY");                  // underlying auto-subscribed RAW
option.SetFilter(u => u.IncludeWeeklys().Strikes(-3, 3).Expiration(15, 60));
_symbol = option.Symbol;                        // canonical Symbol — save it
```

- **The canonical py`option.symbol`cs`option.Symbol` is not tradable** — it is the key into py`data.option_chains`cs`data.OptionChains` and the first argument of every `OptionStrategies` factory.
- **Filter timing**: selection "usually runs at the first bar of every day"; a newly selected contract's data arrives "in the next `Slice`". So the chain can be absent on any given event — always guard: py`chain = data.option_chains.get(self._symbol); if not chain: return`cs`if (!data.OptionChains.TryGetValue(_symbol, out var chain)) return;`.
- **Default filter** if you never call py`set_filter`cs`SetFilter`: standards + weeklys, within 1 strike of the underlying price, expiring within 35 days — almost never what a spec wants; set the filter explicitly.
- Filter building blocks: py`strikes(min, max)`cs`Strikes(min, max)` (relative strike counts around the money, not dollars), py`expiration(min_days, max_days)`cs`Expiration(minDays, maxDays)`, py`calls_only()/puts_only()`cs`CallsOnly()/PutsOnly()`, py`weeklys_only()/standards_only()/include_weeklys()`cs`WeeklysOnly()/StandardsOnly()/IncludeWeeklys()`, py`front_month()/back_month()`cs`FrontMonth()/BackMonth()`, greek/OI filters py`delta(min, max)`cs`Delta(min, max)`, py`iv(min, max)`cs`IV(min, max)`, py`open_interest(min, max)`cs`OpenInterest(min, max)` (these compare **prior-day EOD** values — use them for coarse pre-selection, then pick precisely from live slice greeks), and strategy-shaped helpers (py`u.straddle(30)`cs`u.Straddle(30)`, py`u.iron_condor(30, 5, 10)`cs`u.IronCondor(30, 5, 10)`) that narrow the universe to exactly the legs a named strategy needs.
- **Resolution rule**: the option resolution must be ≥ the underlying Equity's resolution (minute equity → minute/hour/daily options). Contract subscriptions support minute, hour, and daily; minute is the default.

Reading the chain at a decision time:

```python
def on_data(self, data: Slice) -> None:
    chain = data.option_chains.get(self._symbol)
    if not chain:
        return
    spot = chain.underlying.price
    atm_call = min((c for c in chain if c.right == OptionRight.CALL),
                   key=lambda c: abs(c.strike - spot))
    delta = atm_call.greeks.delta        # live price-model value, computed on request
    mid = (atm_call.bid_price + atm_call.ask_price) / 2
```
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

In a Scheduled Event there is no slice parameter — read the same object through py`self.current_slice.option_chains`cs`CurrentSlice.OptionChains`.

## Subscription route 2: py`add_option_contract`cs`AddOptionContract` — individual known contracts

For strategies that hold a few specific contracts found by daily screening, discover with the py`option_chain()`cs`OptionChain()` method, then subscribe each pick:

```python
# In a scheduled event — option_chain() carries PRIOR-DAY EOD greeks: fine for
# discovering contracts, wrong for intraday sizing or hedging.
chain = self.option_chain(self._underlying, flatten=True).data_frame
expiry = chain.expiry.min()
symbol = chain[(chain.expiry == expiry) & (chain.right == OptionRight.CALL) &
               (chain.delta > 0.3)].sort_values("openinterest").index[-1]
self.add_option_contract(symbol)
```
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

1. **Seed prices** — a contract subscribed this time step has no data until the next slice ("you'll need to wait until the next `Slice` to receive data and trade the contract"). Set py`self.settings.seed_initial_prices = True`cs`Settings.SeedInitialPrices = true;` in py`initialize`cs`Initialize` to trade it immediately.
2. **RAW underlying** — subscribe the underlying with py`self.add_equity("SPY", data_normalization_mode=DataNormalizationMode.RAW)`cs`AddEquity("SPY", dataNormalizationMode: DataNormalizationMode.Raw)` so strike-vs-price comparisons share a scale. If you skip this, LEAN auto-subscribes (or force-switches an existing subscription) to RAW anyway — see the history caveat below.
3. **Volatility warm-up** — py`set_warm_up`cs`SetWarmUp` in py`initialize`cs`Initialize` cannot anticipate contracts added later; warm the underlying's volatility model in a security initializer (feed ~30 daily bars through py`security.volatility_model.update(...)`cs`security.VolatilityModel.Update(...)`) so slice-chain IV/greeks are sane from the first read. On the py`add_option`cs`AddOption` route the simple py`self.set_warm_up(31, Resolution.DAILY)`cs`SetWarmUp(31, Resolution.Daily)` suffices (default volatility model needs 30+1 daily bars).

To read a subscribed contract from the slice, index the chain with the **canonical**: py`data.option_chains.get(self._contract_symbol.canonical)`cs`data.OptionChains.TryGetValue(_contractSymbol.Canonical, out var chain)`. py`remove_option_contract(symbol)`cs`RemoveOptionContract(symbol)` cancels its open orders and liquidates the position.

## The RAW-underlying history caveat

Because any option subscription forces the underlying Equity to RAW normalization, a py`history()`cs`History()` call on that underlying now returns **raw closes** — dividend gaps included. Any realized-volatility, daily-return, or overnight-gap calculation on an option underlying must request adjusted data explicitly: py`self.history(self._underlying, 22, Resolution.DAILY, data_normalization_mode=DataNormalizationMode.ADJUSTED)`cs`History(_underlying, 22, Resolution.Daily, dataNormalizationMode: DataNormalizationMode.Adjusted)`. Do not "fix" this by subscribing the underlying ADJUSTED — LEAN silently switches it back to RAW.

## Common mistakes

- **Sizing or hedging from py`option_chain()`cs`OptionChain()` intraday.** Its marks and greeks are the previous close; position sizes computed from them are provably off versus decision-time quotes. Decision-time numbers come from the slice's chain.
- **Trading the canonical symbol**, or passing a contract symbol where the canonical is required (chain lookup, `OptionStrategies` factories).
- **No py`if not chain:`cs`TryGetValue` guard** — the chain is legitimately absent before the first daily selection and whenever the filter turns over.
- **Forgetting py`include_weeklys()`cs`IncludeWeeklys()`** when a spec's DTE window (e.g. 21–45 days, closest to 30) needs weekly expiries to hit its target.
- **Carrying a wide universe you never read** — subscribing a broad chain while doing all selection, pricing, and sizing through py`option_chain()`cs`OptionChain()`: every subscription beyond the contracts actually held is pure cost. Read the universe's slice chain for the decisions that need live data, or drop the universe and use py`add_option_contract`cs`AddOptionContract` on the picks.
- **Subscribing a huge chain as a tradability crutch** (±100 strikes, unbounded expiries) so any discovered contract can be ordered directly — subscribe the picks with py`add_option_contract`cs`AddOptionContract` instead, and let a strategy-shaped or structure-derived filter keep the universe minimal.
- **Computing RV/returns on the force-RAW underlying** without an explicit adjusted-normalization history request.
- Only American-style US equity options are supported; strikes in the filter API are **relative counts**, not dollar offsets — py`strikes(-1, 1)`cs`Strikes(-1, 1)` means one strike either side of the money.
