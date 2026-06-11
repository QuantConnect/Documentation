---
name: fundamental-data-point-attributes-earning-reports
description: Use when you need the exact attribute name or meaning of an earnings-report field on a QuantConnect/LEAN `Fundamental` object — basic and diluted EPS, normalized EPS, basic and diluted average shares, dividend per share, plus period metadata, and the rest of py`f.earning_reports.*`cs`f.EarningReports.*`. Triggers — "what EPS / share-count fields exist", "path to diluted EPS / basic average shares / dividend per share", a missing-attribute error on an earning-reports path. Skip when — you need earnings-growth ratios (earning_ratios) or statement-level fields (see the sibling fundamental-data-point-attributes-* skills).
---

# Earning Reports attributes — QuantConnect / LEAN `Fundamental`

Reach these off a `Fundamental` snapshot `f` as py`f.earning_reports.<attribute>`cs`f.EarningReports.<Attribute>` — for example py`f.earning_reports.diluted_eps.value`cs`f.EarningReports.DilutedEPS.Value`. Get `f` from an py`add_universe(...)`cs`AddUniverse(...)` selection callback, from py`self.securities["SPY"].fundamentals`cs`Securities["SPY"].Fundamentals`, or from a history request.

Every attribute below is a `MultiPeriodField` — append a period accessor to read the value: py`.value`cs`.Value` for the most recent reported period or py`.twelve_months`cs`.TwelveMonths` for the trailing-twelve-month figure. Forgetting the accessor is silent — the wrapper compares as truthy and numeric inequalities give nonsense. For how to choose accessors and navigate the rest of the tree, see the **fundamental-universes** skill.

<!-- fundamental-attributes: EarningReports -->
