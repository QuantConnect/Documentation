---
name: fundamental-data-point-attributes-security-reference
description: Use when you need the exact attribute name or meaning of a security-reference field on a QuantConnect/LEAN `Fundamental` object — exchange id, currency, IPO date, security type, share-class description and status, primary-share and dividend-reinvestment flags, delisting info, and the rest of py`f.security_reference.*`cs`f.SecurityReference.*`. Triggers — "what security-reference fields exist", "path to IPO date / exchange id / security type / is primary share", a missing-attribute error on a security-reference path. Skip when — you need company-reference identifiers, company-profile, or statement fields (see the sibling fundamental-data-point-attributes-* skills).
---

# Security Reference attributes — QuantConnect / LEAN `Fundamental`

Reach these off a `Fundamental` snapshot `f` as py`f.security_reference.<attribute>`cs`f.SecurityReference.<Attribute>` — for example py`f.security_reference.ipo_date`cs`f.SecurityReference.IPODate`. Get `f` from an py`add_universe(...)`cs`AddUniverse(...)` selection callback, from py`self.securities["SPY"].fundamentals`cs`Securities["SPY"].Fundamentals`, or from a history request.

These attributes are read **directly** — no period accessor. The `Type` column below tells you what each returns (text, date, boolean, number). For how to navigate the rest of the tree, see the **fundamental-universes** skill.

<!-- fundamental-attributes: SecurityReference -->
