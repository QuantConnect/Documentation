---
name: fundamental-data-point-attributes-balance-sheet
description: Use when you need the exact attribute name or meaning of a balance-sheet field on a QuantConnect/LEAN `Fundamental` object — total assets, cash, total debt, inventory, shareholders' equity, retained earnings, working capital, and the rest of py`f.financial_statements.balance_sheet.*`cs`f.FinancialStatements.BalanceSheet.*`. Triggers — "what balance-sheet fields are available", "path to total equity / long-term debt / working capital", a missing-attribute error on a balance-sheet path. Skip when — you need income-statement, cash-flow, ratio, or company-profile fields (see the sibling fundamental-data-point-attributes-* skills).
---

# Balance Sheet attributes — QuantConnect / LEAN `Fundamental`

Reach these off a `Fundamental` snapshot `f` as py`f.financial_statements.balance_sheet.<attribute>`cs`f.FinancialStatements.BalanceSheet.<Attribute>` — for example py`f.financial_statements.balance_sheet.total_assets.value`cs`f.FinancialStatements.BalanceSheet.TotalAssets.Value`. Get `f` from an py`add_universe(...)`cs`AddUniverse(...)` selection callback, from py`self.securities["SPY"].fundamentals`cs`Securities["SPY"].Fundamentals`, or from a history request.

Every balance-sheet attribute is a `MultiPeriodField` — append a period accessor to read the value: py`.value`cs`.Value` for the most recent reported period, or py`.twelve_months`cs`.TwelveMonths` for the trailing-twelve-month figure. Balance-sheet items are point-in-time snapshots, so py`.value`cs`.Value` is almost always what you want; forgetting the accessor is silent (the wrapper compares as truthy and numeric inequalities give nonsense). For how to choose accessors, type-check `Fundamental` parameters, and navigate the rest of the tree, see the **fundamental-universes** skill.

<!-- fundamental-attributes: BalanceSheet -->
