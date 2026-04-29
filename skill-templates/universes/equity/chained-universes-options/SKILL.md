---
name: chained-universes-options
description: Use when chaining a dynamic Equity universe (Fundamental or ETF constituents) with an Equity Option universe in a QuantConnect/LEAN algorithm. Triggers — code that wants Option contracts on top of a moving equity universe via py`self.add_universe_options(universe, filter)`cs`AddUniverseOptions(universe, filter)`; questions like "how do I add options on top of my fundamental universe", "options on the top-N PE-ratio stocks", "QQQ constituents with their front-month calls", "why are my option strikes wrong after a stock split", "where do I react to option contracts joining/leaving the chain". Skip when — single static Option universe (call py`add_option`cs`AddOption` once in py`initialize`cs`Initialize`) or a non-Options chain (use the alternative-data chain pattern).
---

# Chained Equity → Equity Options Universes in QuantConnect / LEAN

Chain a dynamic Equity universe (Fundamental, ETF constituents) with Option contracts on each selected underlying by calling py`self.add_universe_options(universe, option_filter)`cs`AddUniverseOptions(universe, optionFilter)` **once in py`initialize`cs`Initialize`**, after creating the parent universe and saving its reference. LEAN handles the rest: the parent's selector picks the equities; LEAN automatically subscribes the matching Option contracts and routes additions/removals through py`on_securities_changed`cs`OnSecuritiesChanged` as the parent universe rotates.

## Required setup

1. **Save the parent universe's reference** — py`add_universe(...)`cs`AddUniverse(...)` returns the `Universe` object you pass to py`add_universe_options(...)`cs`AddUniverseOptions(...)`.
2. **py`self.universe_settings.data_normalization_mode = DataNormalizationMode.RAW`cs`UniverseSettings.DataNormalizationMode = DataNormalizationMode.Raw`** in py`initialize`cs`Initialize`. Option strikes are quoted in raw dollars; the underlying must be in the same scale or strike-relative filters like py`u.strikes(0, 2)`cs`u.Strikes(0, 2)` (ATM ± 2 strikes) compare across scales and pick the wrong contracts — most visible right after a split, when the adjusted price diverges sharply from the raw strike grid.
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

```csharp
public override void Initialize()
{
    // Strikes are raw dollars — keep the underlying in the same scale.
    UniverseSettings.DataNormalizationMode = DataNormalizationMode.Raw;

    var universe = AddUniverse(SelectAssets);
    AddUniverseOptions(universe, OptionFilter);
}

private IEnumerable<Symbol> SelectAssets(IEnumerable<Fundamental> fundamentals)
{
    return fundamentals
        .Where(f => !double.IsNaN(f.ValuationRatios.PERatio))
        .OrderBy(f => f.ValuationRatios.PERatio)
        .Take(10)
        .Select(f => f.Symbol);
}

private OptionFilterUniverse OptionFilter(OptionFilterUniverse u) =>
    u.Strikes(0, 2).FrontMonth().CallsOnly();
```

## Pattern B: ETF constituents → Options

Only the parent universe constructor changes; the py`add_universe_options(universe, filter)`cs`AddUniverseOptions(universe, filter)` call and the filter are identical.

```python
def initialize(self):
    self.universe_settings.data_normalization_mode = DataNormalizationMode.RAW

    etf_universe = self.universe.etf("QQQ", Market.USA, self.universe_settings, self._etf_filter)
    self.add_universe(etf_universe)
    self.add_universe_options(etf_universe, self._option_filter)

def _etf_filter(self, constituents):
    return [c.symbol for c in sorted(constituents, key=lambda c: c.weight, reverse=True)[:10]]
```

```csharp
public override void Initialize()
{
    UniverseSettings.DataNormalizationMode = DataNormalizationMode.Raw;

    var etfUniverse = Universe.ETF("QQQ", Market.USA, UniverseSettings, EtfFilter);
    AddUniverse(etfUniverse);
    AddUniverseOptions(etfUniverse, OptionFilter);
}

private IEnumerable<Symbol> EtfFilter(IEnumerable<ETFConstituentUniverse> constituents) =>
    constituents.OrderByDescending(c => c.Weight).Take(10).Select(c => c.Symbol);
```

## Reacting per-contract

LEAN drives the chain automatically. If you need to react to specific contracts as they join/leave (attach an indicator, set a custom property, liquidate on removal), hook py`on_securities_changed`cs`OnSecuritiesChanged` and branch on py`security.symbol.security_type`cs`security.Symbol.SecurityType` — the same handler sees the equity, its Option canonical, and each subscribed contract.

## Common mistakes

- **Calling py`add_option(symbol)`cs`AddOption(symbol)` from the selector or py`on_securities_changed`cs`OnSecuritiesChanged` to attach options to a chained universe.** That is the static-universe API. Use py`add_universe_options`cs`AddUniverseOptions` once in py`initialize`cs`Initialize` instead.
- **Returning a list of `Symbol` from the Option filter.** The filter must return the `OptionFilterUniverse` (the input, post-fluent-chain). A list silently produces an empty universe.
