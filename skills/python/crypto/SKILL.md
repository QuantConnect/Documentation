---
name: crypto
description: Use whenever building a CRYPTO universe (a dynamic set of coins on an exchange), ranking coins by dollar volume, or trading crypto long/short — including short-selling crypto and which venues support margin.
---

# Crypto universes and crypto long/short — use the exchange's own tradable set

## Build the universe from the venue's NATIVE crypto universe
To select a dynamic set of cryptocurrencies, use the exchange-native `CryptoUniverse` for the venue you trade on. Do NOT pull a coin list from an external dataset (e.g. CoinGecko market cap) and then build tradable symbols with `Symbol.create` — that mapping fails for any coin the venue does not list (`symbol could not be found ... for <market> market` runtime error). Every symbol `CryptoUniverse` returns is tradable on that venue.

```python
self.universe_settings.resolution = Resolution.DAILY
self.set_brokerage_model(BrokerageName.BITFINEX, AccountType.MARGIN)
self._universe = self.add_universe(CryptoUniverse.bitfinex(self._select_assets))

def _select_assets(self, data):         # data: list[CryptoUniverse]
    coins = [x for x in data if x.volume_in_usd]    # each x: .symbol .close .volume .volume_in_usd
    coins.sort(key=lambda x: x.volume_in_usd, reverse=True)
    return [x.symbol for x in coins]                # all qualifying coins (see breadth note below)
```

- Per-venue helpers: `CryptoUniverse.binance` / `.binance_us` / `.bitfinex` / `.kraken` / `.bybit` / `.coinbase`. Use the one that matches your brokerage model so the universe and the orders are on the same exchange.
- **Breadth is the STRATEGY's call, not a default.** How many coins to keep depends on the spec: a *broad cross-section* strategy returns them all (as above); a *"top-N most liquid"* strategy slices `coins[:N]`. Do NOT impose a top-N cap (e.g. `coins[:50]`) unless the spec explicitly asks for one — silently capping a broad-universe strategy to the most-liquid handful changes the method.

## Dollar volume — use `volume_in_usd`
A crypto bar's `volume` is in base-asset units and is not comparable across coins. The `CryptoUniverse` element exposes USD volume directly as **`x.volume_in_usd`** — rank and weight by that, not raw unit volume. (Outside the universe callback, dollar volume = bar `volume` × `price`.)

## Short-selling crypto needs a MARGIN venue
A spot/cash crypto account cannot short. To hold short crypto positions, set a Margin account on a venue whose QC brokerage model supports it (verified against the Lean brokerage models):
- Margin / can short: **Binance, BinanceUS, Bitfinex, Kraken, Bybit** — `set_brokerage_model(BrokerageName.<venue>, AccountType.MARGIN)`. Bitfinex defaults to margin.
- Cash-only, CANNOT short: **Coinbase, GDAX** (the model rejects a margin account).

Match the venue you short on to the `CryptoUniverse` helper you select with (e.g. `BrokerageName.BITFINEX` + `CryptoUniverse.bitfinex`).
