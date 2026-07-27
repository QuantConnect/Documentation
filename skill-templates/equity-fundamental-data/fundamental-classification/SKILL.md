---
name: fundamental-classification
description: Use to look up the exact path or spelling of any Morningstar ASSET CLASSIFICATION field on a QuantConnect/LEAN `Fundamental` object — everything under py`f.asset_classification.*`cs`f.AssetClassification.*` (Morningstar sector / industry-group / industry codes, style box, financial health grade) — plus the named code constants (`MorningstarSectorCode`, `MorningstarIndustryGroupCode`, `MorningstarIndustryCode`, ...) the integer `*_code` fields compare against. Triggers — "path to sector code / industry group code / how do I filter to Technology / SIC or NAICS code". For other field families start at the equity-fundamental-data skill.
---

# Asset-classification fields and code constants — `Fundamental` data points

Full path from the snapshot `f` with the field's description — copy the path rather than guessing from English names; a wrong path wastes a backtest run. The path-reading rules and the index of all field-family skills are in the **equity-fundamental-data** skill.

## Reading the paths

- A path ending in `.[value 1M 2M 3M 6M 9M 12M]` is a `MultiPeriodField` — append **one** period accessor to read the number. py`.value`cs`.Value` is the most recent reported period; the `1M`–`12M` tokens are py`.one_month .two_months .three_months .six_months .nine_months .twelve_months`cs`.OneMonth .TwoMonths .ThreeMonths .SixMonths .NineMonths .TwelveMonths` respectively (trailing-twelve-month at `12M`). e.g. py`f.financial_statements.income_statement.net_income.twelve_months`cs`f.FinancialStatements.IncomeStatement.NetIncome.TwelveMonths`. Forgetting the accessor is silent — the wrapper compares as truthy and numeric inequalities give nonsense.
- A path with **no** bracket is read directly. e.g. py`f.valuation_ratios.pe_ratio`cs`f.ValuationRatios.PERatio`.
- The integer `*_code` fields compare against the named constants in the **Classification code constants** section at the end, e.g. py`f.asset_classification.morningstar_sector_code == MorningstarSectorCode.TECHNOLOGY`cs`f.AssetClassification.MorningstarSectorCode == MorningstarSectorCode.Technology`.

## Data points

<!-- fundamental-lookup: asset_classification -->
