---
name: fundamental-data-point-attributes-cash-flow-statement
description: Use when you need the exact attribute name or meaning of a cash-flow-statement field on a QuantConnect/LEAN `Fundamental` object — operating, investing and financing cash flow, free cash flow, capital expenditure, depreciation and amortization, stock-based compensation, dividends paid, and the rest of py`f.financial_statements.cash_flow_statement.*`cs`f.FinancialStatements.CashFlowStatement.*`. Triggers — "what cash-flow fields are available", "path to operating cash flow / free cash flow / capex", a missing-attribute error on a cash-flow path. Skip when — you need income-statement, balance-sheet, ratio, or company fields (see the sibling fundamental-data-point-attributes-* skills).
---

# Cash Flow Statement attributes — QuantConnect / LEAN `Fundamental`

Reach these off a `Fundamental` snapshot `f` as py`f.financial_statements.cash_flow_statement.<attribute>`cs`f.FinancialStatements.CashFlowStatement.<Attribute>` — for example py`f.financial_statements.cash_flow_statement.operating_cash_flow.twelve_months`cs`f.FinancialStatements.CashFlowStatement.OperatingCashFlow.TwelveMonths`. Get `f` from an py`add_universe(...)`cs`AddUniverse(...)` selection callback, from py`self.securities["SPY"].fundamentals`cs`Securities["SPY"].Fundamentals`, or from a history request.

Every attribute below is a `MultiPeriodField` — append a period accessor to read the number: py`.twelve_months`cs`.TwelveMonths` for the trailing-twelve-month figure (best for comparing across companies) or py`.value`cs`.Value` for the most recent reported period (which mixes quarterly and annual filings). Forgetting the accessor is silent — the wrapper compares as truthy and numeric inequalities give nonsense. For how to choose accessors and navigate the rest of the tree, see the **fundamental-universes** skill.

<!-- fundamental-attributes: CashFlowStatement -->
