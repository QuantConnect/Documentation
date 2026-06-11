---
name: fundamental-data-point-attributes-company-reference
description: Use when you need the exact attribute name or meaning of a company-reference field on a QuantConnect/LEAN `Fundamental` object — company id, short/legal/standard names, CIK, country, fiscal year end, industry template, REIT and limited-partnership flags, auditor, and the rest of py`f.company_reference.*`cs`f.CompanyReference.*`. Triggers — "what company-identifier fields exist", "path to company id / CIK / is REIT / fiscal year end", a missing-attribute error on a company-reference path. Skip when — you need company-profile, security-reference, or statement fields (see the sibling fundamental-data-point-attributes-* skills).
---

# Company Reference attributes — QuantConnect / LEAN `Fundamental`

Reach these off a `Fundamental` snapshot `f` as py`f.company_reference.<attribute>`cs`f.CompanyReference.<Attribute>` — for example py`f.company_reference.company_id`cs`f.CompanyReference.CompanyId` or py`f.company_reference.is_reit`cs`f.CompanyReference.IsREIT`. Get `f` from an py`add_universe(...)`cs`AddUniverse(...)` selection callback, from py`self.securities["SPY"].fundamentals`cs`Securities["SPY"].Fundamentals`, or from a history request.

These attributes are read **directly** — no period accessor. The `Type` column below tells you what each returns (text, integer, boolean, date). For how to navigate the rest of the tree, see the **fundamental-universes** skill.

<!-- fundamental-attributes: CompanyReference -->
