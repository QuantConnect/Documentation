---
name: equity-fundamental-data
description: Use to look up the exact path or spelling of any Morningstar fundamental data point on a QuantConnect/LEAN `Fundamental` object `f` — every field under py`f.financial_statements.*`cs`f.FinancialStatements.*` (income statement, balance sheet, cash flow statement), the operation / valuation / earning ratios, earning reports, company profile, company & security reference, and asset classification — plus the Morningstar sector and industry classification code constants. Triggers — a missing-attribute / compile error on a Fundamental property path; questions like "what's the path to net income / operating cash flow / shares outstanding / PE ratio / sector code". Skip when — you need how to build or screen a universe (see the fundamental-universes skill).
---

# Fundamental data-point attributes — QuantConnect / LEAN

Every readable Morningstar data point on a `Fundamental` object, written as a full path from the snapshot `f` with its description. Copy the path you need rather than guessing from English names — a wrong path wastes a backtest run. Get `f` from an py`add_universe(...)`cs`AddUniverse(...)` selection callback (each element is a `Fundamental`), from py`self.securities["SPY"].fundamentals`cs`Securities["SPY"].Fundamentals`, or from a history request.

## Reading the paths

- A path ending in `.[value 1M 2M 3M 6M 9M 12M]` is a `MultiPeriodField` — append **one** period accessor to read the number. py`.value`cs`.Value` is the most recent reported period; the `1M`–`12M` tokens are py`.one_month .two_months .three_months .six_months .nine_months .twelve_months`cs`.OneMonth .TwoMonths .ThreeMonths .SixMonths .NineMonths .TwelveMonths` respectively (trailing-twelve-month at `12M`). e.g. py`f.financial_statements.income_statement.net_income.twelve_months`cs`f.FinancialStatements.IncomeStatement.NetIncome.TwelveMonths`. Forgetting the accessor is silent — the wrapper compares as truthy and numeric inequalities give nonsense.
- A path with **no** bracket is read directly. e.g. py`f.valuation_ratios.pe_ratio`cs`f.ValuationRatios.PERatio`.
- The integer `*_code` fields under `asset_classification` compare against the named constants in the **Classification code constants** section at the end, e.g. py`f.asset_classification.morningstar_sector_code == MorningstarSectorCode.TECHNOLOGY`cs`f.AssetClassification.MorningstarSectorCode == MorningstarSectorCode.Technology`.

## Data points

<!-- fundamental-lookup -->
