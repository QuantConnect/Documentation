---
name: fundamental-universes
description: Use when reading Morningstar fundamental fields off a `Fundamental` object in a QuantConnect/LEAN algorithm — anything under py`f.financial_statements.*`cs`f.FinancialStatements.*`, py`f.operation_ratios.*`cs`f.OperationRatios.*`, py`f.valuation_ratios.*`cs`f.ValuationRatios.*`, py`f.earning_ratios.*`cs`f.EarningRatios.*`, etc. inside an py`add_universe(...)`cs`AddUniverse(...)` callback. Triggers — missing-attribute / compile error on a Fundamental property path; questions like "what's the path to net income / operating cash flow / shares outstanding"; coding the Piotroski F-Score, Altman Z-score, Magic Formula, Graham filters, or any custom screen off Morningstar fields. Skip when — the universe is index/ETF-constituent only (py`self.universe.etf(...)`cs`Universe.ETF(...)`).
---

# Fundamental Property Paths in QuantConnect / LEAN

The Morningstar tree on `Fundamental` is large and deeply nested. py`f.financial_statements.net_income`cs`f.FinancialStatements.NetIncome` does not exist — net income lives on `IncomeStatement`, one level deeper. Use the lookup below instead of guessing from English names; a wrong path wastes a backtest run.

<!-- python-only -->
## Static type checking

Type-hint your `Fundamental` parameters so the IDE autocompletes paths and flags typos like `netincom` before the backtest runs:

- Callback parameter: `def select(self, fundamentals: list[Fundamental]) -> list[Symbol]:`.
- Helpers that take one snapshot: `def get_metrics(self, f: Fundamental):`.
<!-- /python-only -->

## How to read the lookup

- The first row, py`fundamental`cs`Fundamental`, is the root — call this `f` below. It's the object passed into your py`add_universe(...)`cs`AddUniverse(...)` selection callback, and you can also pull a snapshot per-security: py`f = self.securities["SPY"].fundamentals`cs`var f = Securities["SPY"].Fundamentals`. Every other path chains from `f`.
- Each heading after the root is the accessor name from its parent. py`company_reference`cs`CompanyReference` means py`f.company_reference`cs`f.CompanyReference`; py`income_statement`cs`IncomeStatement` is reached as py`f.financial_statements.income_statement`cs`f.FinancialStatements.IncomeStatement`.
- Comma-separated values are properties on that node. Chain them: under py`income_statement`cs`IncomeStatement`, py`net_income`cs`NetIncome` is py`f.financial_statements.income_statement.net_income`cs`f.FinancialStatements.IncomeStatement.NetIncome`.
- A trailing `*` marks a `MultiPeriodField` wrapper — append a period accessor (most often py`.value`cs`.Value`) to read the number. Unmarked properties return their type directly (number / string, or another node listed).
- The helper classes at the bottom (e.g. `MorningstarSectorCode`) hold named integer constants for comparison: py`f.asset_classification.morningstar_sector_code == MorningstarSectorCode.TECHNOLOGY`cs`f.AssetClassification.MorningstarSectorCode == MorningstarSectorCode.Technology`. 

## Period accessors for `*` properties

- py`.three_months`cs`.ThreeMonths`, py`.six_months`cs`.SixMonths`, py`.nine_months`cs`.NineMonths`, py`.twelve_months`cs`.TwelveMonths` — period-aggregated value (TTM at py`.twelve_months`cs`.TwelveMonths`).
- py`.value`cs`.Value` — most recent reported period (quarterly or annual, whichever filed last).
- py`.one_month`cs`.OneMonth`, py`.two_months`cs`.TwoMonths` — short-window aggregates (rare).

How to choose:

- **Income statement / cash flow**: prefer py`.twelve_months`cs`.TwelveMonths` for cross-company comparability. py`.value`cs`.Value` mixes quarterlies and annuals.
- **Balance sheet**: py`.value`cs`.Value` — point-in-time snapshots; period aggregation is meaningless.
- **Ratio groups**: also `MultiPeriodField` — py`.value`cs`.Value` unless you specifically want a longer window.

Forgetting an accessor is silent: the leaf compares as truthy and inequalities against numbers produce nonsense without raising.

<!-- fundamental-lookup -->

## Year-over-year deltas

`Fundamental` exposes only the latest reported period. Piotroski / Altman / changes-in-X screens need an earlier snapshot — store one per symbol keyed on py`f.financial_statements.period_ending_date.value`cs`f.FinancialStatements.PeriodEndingDate.Value` and compare against it on later bars.