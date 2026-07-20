---
name: crypto
description: Use whenever building a CRYPTO universe (a dynamic set of coins on an exchange), ranking coins by dollar volume, or trading crypto long/short — including short-selling crypto and which venues support margin.
---

# Crypto universes and crypto long/short — use the exchange's own tradable set

## Build the universe from the venue's NATIVE crypto universe
To select a dynamic set of cryptocurrencies, use the exchange-native `CryptoUniverse` for the venue you trade on. Do NOT pull a coin list from an external dataset (e.g. CoinGecko market cap) and then build tradable symbols with `Symbol.Create` — that mapping fails for any coin the venue does not list (`symbol could not be found ... for <market> market` runtime error). Every symbol `CryptoUniverse` returns is tradable on that venue.

```csharp
UniverseSettings.Resolution = Resolution.Daily;
SetBrokerageModel(BrokerageName.Bitfinex, AccountType.Margin);
_universe = AddUniverse(CryptoUniverse.Bitfinex(SelectAssets));

private IEnumerable<Symbol> SelectAssets(IEnumerable<CryptoUniverse> data)
{
    return data
        .Where(x => x.VolumeInUsd != null)          // each x: .Symbol .Close .Volume .VolumeInUsd
        .OrderByDescending(x => x.VolumeInUsd)
        .Select(x => x.Symbol)
        .ToList();                                  // all qualifying coins (see breadth note below)
}
```
- Per-venue helpers: `CryptoUniverse.Binance` / `.BinanceUS` / `.Bitfinex` / `.Kraken` / `.Bybit` / `.Coinbase`. Use the one that matches your brokerage model so the universe and the orders are on the same exchange.
- **Breadth is the STRATEGY's call, not a default.** How many coins to keep depends on the spec: a *broad cross-section* strategy returns them all (as above); a *"top-N most liquid"* strategy slices `.Take(N)`. Do NOT impose a top-N cap (e.g. `.Take(50)`) unless the spec explicitly asks for one — silently capping a broad-universe strategy to the most-liquid handful changes the method.

## Dollar volume — use `VolumeInUsd`
A crypto bar's `Volume` is in base-asset units and is not comparable across coins. The `CryptoUniverse` element exposes USD volume directly as **`x.VolumeInUsd`** — rank and weight by that, not raw unit volume. (Outside the universe callback, dollar volume = bar `Volume` × `Price`.)

## Short-selling crypto needs a MARGIN venue
A spot/cash crypto account cannot short. To hold short crypto positions, set a Margin account on a venue whose QC brokerage model supports it (verified against the Lean brokerage models):
- Margin / can short: **Binance, BinanceUS, Bitfinex, Kraken, Bybit** — `SetBrokerageModel(BrokerageName.<venue>, AccountType.Margin)`. Bitfinex defaults to margin.
- Cash-only, CANNOT short: **Coinbase, GDAX** (the model rejects a margin account).

Match the venue you short on to the `CryptoUniverse` helper you select with (e.g. `BrokerageName.Bitfinex` + `CryptoUniverse.Bitfinex`).
