---
name: fundamental-data-point-attributes-financial-statements
description: Use when you need the structure of `f.financial_statements` on a QuantConnect/LEAN `Fundamental` object — the three nested statements (income statement, balance sheet, cash flow statement) and the filing metadata (period ending date, file date, form type, period type, accession number, auditor). Triggers — "where is the income statement / balance sheet / cash flow statement", "path to period ending date / file date / form type", reading the fiscal period or filing date. Skip when — you already know which statement you need (go straight to its fundamental-data-point-attributes-* skill).
---

# Financial Statements — QuantConnect / LEAN `Fundamental`

`f.financial_statements` is mostly a container for the three financial statements. Each has its own (large) set of fields, documented in a dedicated skill:

- `income_statement` — see the **fundamental-data-point-attributes-income-statement** skill
- `balance_sheet` — see the **fundamental-data-point-attributes-balance-sheet** skill
- `cash_flow_statement` — see the **fundamental-data-point-attributes-cash-flow-statement** skill

It also carries filing metadata for the most recently reported statement. Reach these as `f.financial_statements.<attribute>` — for example `f.financial_statements.period_ending_date.value`. Each metadata attribute is a `MultiPeriodField`, so append `.value` to read it. Get `f` from an `add_universe(...)` selection callback, from `self.securities["SPY"].fundamentals`, or from a history request.

| Attribute | Description |
|---|---|
| `period_ending_date` | The exact date that is given in the financial statements for each quarter's end. |
| `file_date` | Specific date on which a company released its filing to the public. |
| `accession_number` | The accession number is a unique number that EDGAR assigns to each submission as the submission is received. |
| `form_type` | The type of filing of the report: for instance, 10-K (annual report) or 10-Q (quarterly report). |
| `period_auditor` | The name of the auditor that performed the financial statement audit for the given period. |
| `auditor_report_status` | Auditor opinion code will be one of the following for each annual period: Code Meaning UQ Unqualified Opinion UE Unqualified Opinion with Explanation QM Qualified - Due to change in accounting method QL Qualified - Due to litigation OT Qualified Opinion - Other AO Adverse Opinion DS Disclaim an opinion UA Unaudited |
| `inventory_valuation_method` | Which method of inventory valuation was used - LIFO, FIFO, Average, Standard costs, Net realizable value, Others, LIFO and FIFO, FIFO and Average, FIFO and other, LIFO and Average, LIFO and other, Average and other, 3 or more methods, None |
| `number_of_share_holders` | The number of shareholders on record |
| `period_type` | The nature of the period covered by an individual set of financial results. The output can be: Quarter, Semi-annual or Annual. Assuming a 12-month fiscal year, quarter typically covers a three-month period, semi-annual a six-month period, and annual a twelve-month period. Annual could cover results collected either from preliminary results or an annual report |
| `total_risk_based_capital` | The sum of Tier 1 and Tier 2 Capital. Tier 1 capital consists of common shareholders equity, perpetual preferred shareholders equity with non-cumulative dividends, retained earnings, and minority interests in the equity accounts of consolidated subsidiaries. Tier 2 capital consists of subordinated debt, intermediate-term preferred stock, cumulative and long-term preferred stock, and a portion of a bank's allowance for loan and lease losses. |
