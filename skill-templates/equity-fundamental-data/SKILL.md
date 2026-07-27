---
name: equity-fundamental-data
description: START HERE to look up the exact path or spelling of any Morningstar fundamental data point on a QuantConnect/LEAN `Fundamental` object `f`. This skill holds the path-reading rules, the top-level and filing-metadata fields (market cap, `period_ending_date`, `file_date`, ...), and the index of the six field-family skills that hold the full tables — fundamental-income-statement, fundamental-balance-sheet, fundamental-cash-flow-statement, fundamental-ratios, fundamental-company-data, fundamental-classification. Triggers — a missing-attribute / compile error on a Fundamental property path; questions like "what's the path to net income / operating cash flow / shares outstanding / PE ratio / sector code". Skip when — you need how to build or screen a universe (see the fundamental-universes skill).
---

# Fundamental data-point attributes — QuantConnect / LEAN

Morningstar data points are read as a full path from the snapshot `f` — copy the path you need rather than guessing from English names; a wrong path wastes a backtest run. Get `f` from an py`add_universe(...)`cs`AddUniverse(...)` selection callback (each element is a `Fundamental`), from py`self.securities["SPY"].fundamentals`cs`Securities["SPY"].Fundamentals`, or from a history request. The field tables are split across skills by family: THIS skill carries the top-level and filing-metadata fields plus the index below — load the family skill that holds your field's table.

## Where every field lives — load the matching skill

| Field family | Load this skill | Contents |
|---|---|---|
| py`f.financial_statements.income_statement.*`cs`f.FinancialStatements.IncomeStatement.*` | `fundamental-income-statement` | revenue, cost/expense lines, operating & net income, EBIT/EBITDA, interest, tax, dividends paid |
| py`f.financial_statements.balance_sheet.*`cs`f.FinancialStatements.BalanceSheet.*` | `fundamental-balance-sheet` | assets, liabilities, equity, debt, working-capital components, share counts |
| py`f.financial_statements.cash_flow_statement.*`cs`f.FinancialStatements.CashFlowStatement.*` | `fundamental-cash-flow-statement` | operating / investing / financing cash flows, capex, issuance & repurchase, dividends |
| py`f.operation_ratios.*`cs`f.OperationRatios.*`, py`f.valuation_ratios.*`cs`f.ValuationRatios.*`, py`f.earning_ratios.*`cs`f.EarningRatios.*` | `fundamental-ratios` | ROA/ROE/margins/turnover, PE/PB/PS/EV multiples & yields, EPS/DPS growth rates |
| py`f.earning_reports.*`cs`f.EarningReports.*`, py`f.company_reference.*`cs`f.CompanyReference.*`, py`f.security_reference.*`cs`f.SecurityReference.*`, py`f.company_profile.*`cs`f.CompanyProfile.*` | `fundamental-company-data` | EPS & report dates, listing/exchange/share-class reference, company profile basics |
| py`f.asset_classification.*`cs`f.AssetClassification.*` + code constants | `fundamental-classification` | sector / industry-group / industry codes and the `MorningstarSectorCode`-style constants they compare against |

## Reading the paths

- A path ending in `.[value 1M 2M 3M 6M 9M 12M]` is a `MultiPeriodField` — append **one** period accessor to read the number. py`.value`cs`.Value` is the most recent reported period; the `1M`–`12M` tokens are py`.one_month .two_months .three_months .six_months .nine_months .twelve_months`cs`.OneMonth .TwoMonths .ThreeMonths .SixMonths .NineMonths .TwelveMonths` respectively (trailing-twelve-month at `12M`). e.g. py`f.financial_statements.income_statement.net_income.twelve_months`cs`f.FinancialStatements.IncomeStatement.NetIncome.TwelveMonths`. Forgetting the accessor is silent — the wrapper compares as truthy and numeric inequalities give nonsense.
- A path with **no** bracket is read directly. e.g. py`f.valuation_ratios.pe_ratio`cs`f.ValuationRatios.PERatio`.
- The integer `*_code` fields under `asset_classification` compare against the named constants in the **fundamental-classification** skill, e.g. py`f.asset_classification.morningstar_sector_code == MorningstarSectorCode.TECHNOLOGY`cs`f.AssetClassification.MorningstarSectorCode == MorningstarSectorCode.Technology`.

## Top-level and filing-metadata data points

The snapshot's own attributes and the filing/timing fields under py`f.financial_statements`cs`f.FinancialStatements` (period end, file date, period type, ...) — the fields every point-in-time strategy needs:

<!-- fundamental-lookup: !financial_statements.income_statement, !financial_statements.balance_sheet, !financial_statements.cash_flow_statement, !operation_ratios, !valuation_ratios, !earning_ratios, !earning_reports, !company_reference, !security_reference, !company_profile, !asset_classification -->
