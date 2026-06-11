---
name: fundamental-data-point-attributes-financial-statements
description: Use when you need the structure of py`f.financial_statements`cs`f.FinancialStatements` on a QuantConnect/LEAN `Fundamental` object — the three nested statements (income statement, balance sheet, cash flow statement) and the filing metadata (period ending date, file date, form type, period type, accession number, auditor). Triggers — "where is the income statement / balance sheet / cash flow statement", "path to period ending date / file date / form type", reading the fiscal period or filing date. Skip when — you already know which statement you need (go straight to its fundamental-data-point-attributes-* skill).
---

# Financial Statements — QuantConnect / LEAN `Fundamental`

py`f.financial_statements`cs`f.FinancialStatements` is mostly a container for the three financial statements. Each has its own (large) set of fields, documented in a dedicated skill:

<!-- fundamental-subgroups: FinancialStatements -->

It also carries filing metadata for the most recently reported statement. Reach these as py`f.financial_statements.<attribute>`cs`f.FinancialStatements.<Attribute>` — for example py`f.financial_statements.period_ending_date.value`cs`f.FinancialStatements.PeriodEndingDate.Value`. Each metadata attribute is a `MultiPeriodField`, so append py`.value`cs`.Value` to read it. Get `f` from an py`add_universe(...)`cs`AddUniverse(...)` selection callback, from py`self.securities["SPY"].fundamentals`cs`Securities["SPY"].Fundamentals`, or from a history request.

<!-- fundamental-attributes: FinancialStatements -->
