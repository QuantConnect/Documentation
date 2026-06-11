---
name: fundamental-data-point-attributes-income-statement
description: Use when you need the exact attribute name or meaning of an income-statement field on a QuantConnect/LEAN `Fundamental` object — total revenue, net income, gross profit, operating income, EBIT, EBITDA, research and development, interest expense, tax provision, and the rest of py`f.financial_statements.income_statement.*`cs`f.FinancialStatements.IncomeStatement.*`. Triggers — "what income-statement fields are available", "path to net income / total revenue / EBITDA", a missing-attribute error on an income-statement path. Skip when — you need balance-sheet, cash-flow, ratio, or company fields (see the sibling fundamental-data-point-attributes-* skills).
---

# Income Statement attributes — QuantConnect / LEAN `Fundamental`

Reach these off a `Fundamental` snapshot `f` as py`f.financial_statements.income_statement.<attribute>`cs`f.FinancialStatements.IncomeStatement.<Attribute>` — for example py`f.financial_statements.income_statement.net_income.twelve_months`cs`f.FinancialStatements.IncomeStatement.NetIncome.TwelveMonths`. Get `f` from an py`add_universe(...)`cs`AddUniverse(...)` selection callback, from py`self.securities["SPY"].fundamentals`cs`Securities["SPY"].Fundamentals`, or from a history request.

Every attribute below is a `MultiPeriodField` — append a period accessor to read the number: py`.twelve_months`cs`.TwelveMonths` for the trailing-twelve-month figure (best for comparing across companies) or py`.value`cs`.Value` for the most recent reported period (which mixes quarterly and annual filings). Forgetting the accessor is silent — the wrapper compares as truthy and numeric inequalities give nonsense. For how to choose accessors, type-check `Fundamental` parameters, and navigate the rest of the tree, see the **fundamental-universes** skill.

<!-- fundamental-attributes: IncomeStatement -->
