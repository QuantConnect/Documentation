---
name: fundamental-data-point-attributes-company-profile
description: Use when you need the exact attribute name or meaning of a company-profile field on a QuantConnect/LEAN `Fundamental` object — headquarters address, employee counts, shares outstanding, market cap, enterprise value, homepage, and the rest of py`f.company_profile.*`cs`f.CompanyProfile.*`. Triggers — "what company-profile fields exist", "path to shares outstanding / enterprise value / employee count", a missing-attribute error on a company-profile path. Skip when — you need financial-statement, ratio, or company-reference identifier fields (see the sibling fundamental-data-point-attributes-* skills).
---

# Company Profile attributes — QuantConnect / LEAN `Fundamental`

Reach these off a `Fundamental` snapshot `f` as py`f.company_profile.<attribute>`cs`f.CompanyProfile.<Attribute>` — for example py`f.company_profile.shares_outstanding`cs`f.CompanyProfile.SharesOutstanding`. Get `f` from an py`add_universe(...)`cs`AddUniverse(...)` selection callback, from py`self.securities["SPY"].fundamentals`cs`Securities["SPY"].Fundamentals`, or from a history request.

These attributes are read **directly** — no period accessor. The `Type` column below tells you what each returns (text, integer, decimal, etc.). For how to navigate the rest of the tree, see the **fundamental-universes** skill.

<!-- fundamental-attributes: CompanyProfile -->
