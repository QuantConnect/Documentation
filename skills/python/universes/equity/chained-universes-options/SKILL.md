---
name: chained-universes-options
description: Use when chaining a dynamic Equity universe (Fundamental or ETF constituents) with an Equity Option universe in a QuantConnect/LEAN algorithm. Triggers — code that wants Option contracts on top of a moving equity universe via `self.add_universe_options(universe, filter)`; questions like "how do I add options on top of my fundamental universe", "options on the top-N PE-ratio stocks", "QQQ constituents with their front-month calls", "why are my option strikes wrong after a stock split", "where do I react to option contracts joining/leaving the chain". Skip when — single static Option universe (call `add_option` once in `initialize`) or a non-Options chain (use the alternative-data chain pattern).
---

# Chained Equity → Equity Options Universes in QuantConnect / LEAN

Chain a dynamic Equity universe (Fundamental, ETF constituents) with Option contracts on each selected underlying by calling `self.add_universe_options(universe, option_filter)` **once in `initialize`**, after creating the parent universe and saving its reference. LEAN handles the rest: the parent's selector picks the equities; LEAN automatically subscribes the matching Option contracts and routes additions/removals through `on_securities_changed` as the parent universe rotates.

## Required setup

1. **Save the parent universe's reference** — `add_universe(...)` returns the `Universe` object you pass to `add_universe_options(...)`.
2. **`self.universe_settings.data_normalization_mode = DataNormalizationMode.RAW`** in `initialize`. Option strikes are quoted in raw dollars; the underlying must be in the same scale or strike-relative filters like `u.strikes(0, 2)` (ATM ± 2 strikes) compare across scales and pick the wrong contracts — most visible right after a split, when the adjusted price diverges sharply from the raw strike grid.
3. **Provide an Option filter** — a function that takes an `OptionFilterUniverse` and returns one. Without it, LEAN subscribes to the entire chain per underlying, which is enormous.

## Pattern A: Fundamental → Options

```python
def initialize(self):
    # Strikes are raw dollars — keep the underlying in the same scale.
    self.universe_settings.data_normalization_mode = DataNormalizationMode.RAW

    universe = self.add_universe(self._select_assets)
    self.add_universe_options(universe, self._option_filter)

def _select_assets(self, fundamentals):
    ranked = sorted(
        (f for f in fundamentals if not np.isnan(f.valuation_ratios.pe_ratio)),
        key=lambda f: f.valuation_ratios.pe_ratio,
    )
    return [f.symbol for f in ranked[:10]]

def _option_filter(self, u):
    return u.strikes(0, 2).front_month().calls_only()
```

## Pattern B: ETF constituents → Options

Only the parent universe constructor changes; the `add_universe_options(universe, filter)` call and the filter are identical.

```python
def initialize(self):
    self.universe_settings.data_normalization_mode = DataNormalizationMode.RAW

    etf_universe = self.universe.etf("QQQ", Market.USA, self.universe_settings, self._etf_filter)
    self.add_universe(etf_universe)
    self.add_universe_options(etf_universe, self._option_filter)

def _etf_filter(self, constituents):
    return [c.symbol for c in sorted(constituents, key=lambda c: c.weight, reverse=True)[:10]]
```

## Reacting per-contract

LEAN drives the chain automatically. If you need to react to specific contracts as they join/leave (attach an indicator, set a custom property, liquidate on removal), hook `on_securities_changed` and branch on `security.symbol.security_type` — the same handler sees the equity, its Option canonical, and each subscribed contract.

## Common mistakes

- **Calling `add_option(symbol)` from the selector or `on_securities_changed` to attach options to a chained universe.** That is the static-universe API. Use `add_universe_options` once in `initialize` instead.
- **Returning a list of `Symbol` from the Option filter.** The filter must return the `OptionFilterUniverse` (the input, post-fluent-chain). A list silently produces an empty universe.
