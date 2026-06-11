---
name: fundamental-data-point-attributes-earning-ratios
description: Use when you need the exact attribute name or meaning of an earning-ratio (growth) field on a QuantConnect/LEAN `Fundamental` object — diluted and basic EPS growth, dividend-per-share growth, book-value-per-share growth, equity-per-share growth, FCF-per-share growth, and the rest of py`f.earning_ratios.*`cs`f.EarningRatios.*`. Triggers — "what earnings-growth fields exist", "path to diluted EPS growth / dividend growth", a missing-attribute error on an earning-ratio path. Skip when — you need valuation ratios, operating ratios, or the per-period EPS values from earning_reports (see the sibling fundamental-data-point-attributes-* skills).
---

# Earning Ratios attributes — QuantConnect / LEAN `Fundamental`

Reach these off a `Fundamental` snapshot `f` as py`f.earning_ratios.<attribute>`cs`f.EarningRatios.<Attribute>` — for example py`f.earning_ratios.diluted_eps_growth.value`cs`f.EarningRatios.DilutedEPSGrowth.Value`. Get `f` from an py`add_universe(...)`cs`AddUniverse(...)` selection callback, from py`self.securities["SPY"].fundamentals`cs`Securities["SPY"].Fundamentals`, or from a history request.

Every attribute below is a `MultiPeriodField` — append a period accessor to read the number: py`.value`cs`.Value` for the most recent reported period or py`.twelve_months`cs`.TwelveMonths` for the trailing-twelve-month window. Forgetting the accessor is silent — the wrapper compares as truthy and numeric inequalities give nonsense. For how to choose accessors and navigate the rest of the tree, see the **fundamental-universes** skill.

<!-- fundamental-attributes: EarningRatios -->
