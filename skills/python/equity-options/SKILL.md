---
name: equity-options
description: Use when an algorithm trades options on a SINGLE named underlying Equity — subscribing (`add_option` vs `add_option_contract`), reading the chain (the slice's `option_chains` vs the `option_chain()` method), greeks/IV data freshness, strike/expiry filters, price seeding, and warm-up. Triggers — code calling any of those four APIs; questions like "why are my deltas/IV stale", "why can't I trade the contract I just added", "why is the chain empty", "which API gives decision-time greeks". Skip when — options on a DYNAMIC equity universe (see chained-universes-options), historical/backfilled option data for past dates (see historical-data-equity-options), or multi-leg order placement and assignment/exercise handling (see option-strategies).
---

# Equity Options: subscriptions and chain data — QuantConnect / LEAN

Two decisions shape every equity-options algorithm, and each has two answers that look interchangeable but carry different data: **how you subscribe** (`add_option` vs `add_option_contract`) and **how you read the chain** (the slice's `option_chains` vs the `option_chain()` method). Picking the wrong member of either pair produces algorithms that trade on day-old numbers without any error.

## Greeks/IV freshness — the core rule

There are two different option datasets behind the APIs (doc: "There is a daily, pre-calculated dataset based on the end of the previous trading day. There is also a stream of values that are calculated on-the-fly as your algorithm runs."):

| Access path | Data you get |
|---|---|
| `option.set_filter(...)` filter function (incl. `.delta()/.iv()` filters) | "daily, pre-calculated values based on the end of the **previous trading day**" — not customizable |
| `self.option_chain(symbol)` method | same prior-day EOD pre-calculated values; returns **all tradable contracts**, not just your filter's picks |
| `self.history[OptionUniverse](...)` | same prior-day EOD values, one full chain per trading day |
| the slice: `data.option_chains` (or `self.current_slice.option_chains`) | **current values from the Option price model**, computed on request against live data — customizable via `option.price_model` |

The operating rule that follows: **any intraday decision — entry sizing, delta hedging, IV-threshold checks, strike selection at a decision time — should read the slice's chain.** The `option_chain()` method is for **daily contract discovery** (find which contracts exist, filter by prior-day delta/OI, then subscribe); using it for intraday sizing or greeks means every number is one day stale, and nothing errors to tell you. Greeks on slice contracts are computed lazily — accessing `greeks` costs nothing; accessing `greeks.delta` triggers the calculation — so only read the greeks you need.

## Subscription route 1: `add_option` — a filtered basket (universe)

For strategies that pick contracts from a chain at decision time (spreads, condors, straddles, rolling structures), subscribe once in `initialize`:

```python
self.universe_settings.asynchronous = True
option = self.add_option("SPY")                 # underlying auto-subscribed RAW
option.set_filter(lambda u: u.include_weeklys().strikes(-3, 3).expiration(15, 60))
self._symbol = option.symbol                    # canonical Symbol — save it
```
- **The canonical `option.symbol` is not tradable** — it is the key into `data.option_chains` and the first argument of every `OptionStrategies` factory.
- **Filter timing**: selection "usually runs at the first bar of every day"; a newly selected contract's data arrives "in the next `Slice`". So the chain can be absent on any given event — always guard: `chain = data.option_chains.get(self._symbol); if not chain: return`.
- **Default filter** if you never call `set_filter`: standards + weeklys, within 1 strike of the underlying price, expiring within 35 days — almost never what a spec wants; set the filter explicitly.
- Filter building blocks: `strikes(min, max)` (relative strike counts around the money, not dollars), `expiration(min_days, max_days)`, `calls_only()/puts_only()`, `weeklys_only()/standards_only()/include_weeklys()`, `front_month()/back_month()`, greek/OI filters `delta(min, max)`, `iv(min, max)`, `open_interest(min, max)` (these compare **prior-day EOD** values — use them for coarse pre-selection, then pick precisely from live slice greeks), and strategy-shaped helpers (`u.straddle(30)`, `u.iron_condor(30, 5, 10)`) that narrow the universe to exactly the legs a named strategy needs.
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
In a Scheduled Event there is no slice parameter — read the same object through `self.current_slice.option_chains`.

## Subscription route 2: `add_option_contract` — individual known contracts

For strategies that hold a few specific contracts found by daily screening, discover with the `option_chain()` method, then subscribe each pick:

```python
# In a scheduled event — option_chain() carries PRIOR-DAY EOD greeks: fine for
# discovering contracts, wrong for intraday sizing or hedging.
chain = self.option_chain(self._underlying, flatten=True).data_frame
expiry = chain.expiry.min()
symbol = chain[(chain.expiry == expiry) & (chain.right == OptionRight.CALL) &
               (chain.delta > 0.3)].sort_values("openinterest").index[-1]
self.add_option_contract(symbol)
```
The three idioms that must accompany this route:

1. **Seed prices** — a contract subscribed this time step has no data until the next slice ("you'll need to wait until the next `Slice` to receive data and trade the contract"). Set `self.settings.seed_initial_prices = True` in `initialize` to trade it immediately.
2. **RAW underlying** — subscribe the underlying with `self.add_equity("SPY", data_normalization_mode=DataNormalizationMode.RAW)` so strike-vs-price comparisons share a scale. If you skip this, LEAN auto-subscribes (or force-switches an existing subscription) to RAW anyway — see the history caveat below.
3. **Volatility warm-up** — `set_warm_up` in `initialize` cannot anticipate contracts added later; warm the underlying's volatility model in a security initializer (feed ~30 daily bars through `security.volatility_model.update(...)`) so slice-chain IV/greeks are sane from the first read. On the `add_option` route the simple `self.set_warm_up(31, Resolution.DAILY)` suffices (default volatility model needs 30+1 daily bars).

To read a subscribed contract from the slice, index the chain with the **canonical**: `data.option_chains.get(self._contract_symbol.canonical)`. `remove_option_contract(symbol)` cancels its open orders and liquidates the position.

## The RAW-underlying history caveat

Because any option subscription forces the underlying Equity to RAW normalization, a `history()` call on that underlying now returns **raw closes** — dividend gaps included. Any realized-volatility, daily-return, or overnight-gap calculation on an option underlying must request adjusted data explicitly: `self.history(self._underlying, 22, Resolution.DAILY, data_normalization_mode=DataNormalizationMode.ADJUSTED)`. Do not "fix" this by subscribing the underlying ADJUSTED — LEAN silently switches it back to RAW.

## Common mistakes

- **Sizing or hedging from `option_chain()` intraday.** Its marks and greeks are the previous close; position sizes computed from them are provably off versus decision-time quotes. Decision-time numbers come from the slice's chain.
- **Trading the canonical symbol**, or passing a contract symbol where the canonical is required (chain lookup, `OptionStrategies` factories).
- **No `if not chain:` guard** — the chain is legitimately absent before the first daily selection and whenever the filter turns over.
- **Forgetting `include_weeklys()`** when a spec's DTE window (e.g. 21–45 days, closest to 30) needs weekly expiries to hit its target.
- **Computing RV/returns on the force-RAW underlying** without an explicit adjusted-normalization history request.
- Only American-style US equity options are supported; strikes in the filter API are **relative counts**, not dollar offsets — `strikes(-1, 1)` means one strike either side of the money.
