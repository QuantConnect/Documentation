---
name: fundamental-data-point-attributes-asset-classification
description: Use when you need the attribute name or meaning of an asset-classification field on a QuantConnect/LEAN `Fundamental` object — Morningstar sector, industry, industry-group and economy-sphere codes, style box, stock type, growth/value/size scores, financial-health and profitability grades, SIC and NAICS — plus the named code constants (MorningstarSectorCode, MorningstarIndustryCode, etc.). Reach them at py`f.asset_classification.*`cs`f.AssetClassification.*`. Triggers — "what sector/industry codes exist", "path to morningstar sector code", filtering a universe by sector or industry. Skip when — you need financial-statement, ratio, or company fields (see the sibling fundamental-data-point-attributes-* skills).
---

# Asset Classification attributes — QuantConnect / LEAN `Fundamental`

Reach these off a `Fundamental` snapshot `f` as py`f.asset_classification.<attribute>`cs`f.AssetClassification.<Attribute>` — for example py`f.asset_classification.morningstar_sector_code`cs`f.AssetClassification.MorningstarSectorCode`. Get `f` from an py`add_universe(...)`cs`AddUniverse(...)` selection callback, from py`self.securities["SPY"].fundamentals`cs`Securities["SPY"].Fundamentals`, or from a history request.

These attributes are read **directly** — no period accessor. The sector, industry, industry-group and economy-sphere codes are integers; compare them to the named constants listed below, e.g. py`f.asset_classification.morningstar_sector_code == MorningstarSectorCode.TECHNOLOGY`cs`f.AssetClassification.MorningstarSectorCode == MorningstarSectorCode.Technology`. For how to navigate the rest of the tree, see the **fundamental-universes** skill.

<!-- fundamental-attributes: AssetClassification -->

## Sector codes — `MorningstarSectorCode`

<!-- fundamental-attributes: MorningstarSectorCode -->

## Economy-sphere codes — `MorningstarEconomySphereCode`

<!-- fundamental-attributes: MorningstarEconomySphereCode -->

## Industry-group codes — `MorningstarIndustryGroupCode`

<!-- fundamental-attributes: MorningstarIndustryGroupCode -->

## Industry codes — `MorningstarIndustryCode`

<!-- fundamental-attributes: MorningstarIndustryCode -->
