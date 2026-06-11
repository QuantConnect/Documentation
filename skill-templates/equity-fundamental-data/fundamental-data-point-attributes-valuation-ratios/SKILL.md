---
name: fundamental-data-point-attributes-valuation-ratios
description: Use when you need the exact attribute name or meaning of a valuation-ratio field on a QuantConnect/LEAN `Fundamental` object — PE, PB, PS, PEG, EV/EBITDA, earnings/FCF/dividend yields, book/sales/cash-flow per share, and the rest of py`f.valuation_ratios.*`cs`f.ValuationRatios.*` (read directly, no period accessor). Triggers — "what valuation fields exist", "path to PE ratio / PB ratio / EV-to-EBITDA / dividend yield", a missing-attribute error on a valuation-ratio path. Skip when — you need operating ratios, earnings-growth ratios, or statement-level fields (see the sibling fundamental-data-point-attributes-* skills).
---

# Valuation Ratios attributes — QuantConnect / LEAN `Fundamental`

Reach these off a `Fundamental` snapshot `f` as py`f.valuation_ratios.<attribute>`cs`f.ValuationRatios.<Attribute>` — for example py`f.valuation_ratios.pe_ratio`cs`f.ValuationRatios.PERatio` or py`f.valuation_ratios.ev_to_ebitda`cs`f.ValuationRatios.EVToEBITDA`. Get `f` from an py`add_universe(...)`cs`AddUniverse(...)` selection callback, from py`self.securities["SPY"].fundamentals`cs`Securities["SPY"].Fundamentals`, or from a history request.

Unlike the financial statements, valuation ratios are read **directly** — there is no period accessor. py`f.valuation_ratios.pe_ratio`cs`f.ValuationRatios.PERatio` returns the number straight away; appending py`.value`cs`.Value` is an error. The `Type` column below shows what each returns (most are numbers). For how to navigate the rest of the tree, see the **fundamental-universes** skill.

<!-- fundamental-attributes: ValuationRatios -->
