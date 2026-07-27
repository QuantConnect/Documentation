---
name: equity-fundamental-data
description: START HERE to look up the exact path or spelling of any Morningstar fundamental data point on a QuantConnect/LEAN `Fundamental` object `f`. This skill holds the path-reading rules, the top-level and filing-metadata fields (market cap, `period_ending_date`, `file_date`, ...), and the index of the six field-family skills that hold the full tables — fundamental-income-statement, fundamental-balance-sheet, fundamental-cash-flow-statement, fundamental-ratios, fundamental-company-data, fundamental-classification. Triggers — a missing-attribute / compile error on a Fundamental property path; questions like "what's the path to net income / operating cash flow / shares outstanding / PE ratio / sector code". Skip when — you need how to build or screen a universe (see the fundamental-universes skill).
---

# Fundamental data-point attributes — QuantConnect / LEAN

Morningstar data points are read as a full path from the snapshot `f` — copy the path you need rather than guessing from English names; a wrong path wastes a backtest run. Get `f` from an `add_universe(...)` selection callback (each element is a `Fundamental`), from `self.securities["SPY"].fundamentals`, or from a history request. The field tables are split across skills by family: THIS skill carries the top-level and filing-metadata fields plus the index below — load the family skill that holds your field's table.

## Where every field lives — load the matching skill

| Field family | Load this skill | Contents |
|---|---|---|
| `f.financial_statements.income_statement.*` | `fundamental-income-statement` | revenue, cost/expense lines, operating & net income, EBIT/EBITDA, interest, tax, dividends paid |
| `f.financial_statements.balance_sheet.*` | `fundamental-balance-sheet` | assets, liabilities, equity, debt, working-capital components, share counts |
| `f.financial_statements.cash_flow_statement.*` | `fundamental-cash-flow-statement` | operating / investing / financing cash flows, capex, issuance & repurchase, dividends |
| `f.operation_ratios.*`, `f.valuation_ratios.*`, `f.earning_ratios.*` | `fundamental-ratios` | ROA/ROE/margins/turnover, PE/PB/PS/EV multiples & yields, EPS/DPS growth rates |
| `f.earning_reports.*`, `f.company_reference.*`, `f.security_reference.*`, `f.company_profile.*` | `fundamental-company-data` | EPS & report dates, listing/exchange/share-class reference, company profile basics |
| `f.asset_classification.*` + code constants | `fundamental-classification` | sector / industry-group / industry codes and the `MorningstarSectorCode`-style constants they compare against |

## Reading the paths

- A path ending in `.[value 1M 2M 3M 6M 9M 12M]` is a `MultiPeriodField` — append **one** period accessor to read the number. `.value` is the most recent reported period; the `1M`–`12M` tokens are `.one_month .two_months .three_months .six_months .nine_months .twelve_months` respectively (trailing-twelve-month at `12M`). e.g. `f.financial_statements.income_statement.net_income.twelve_months`. Forgetting the accessor is silent — the wrapper compares as truthy and numeric inequalities give nonsense.
- A path with **no** bracket is read directly. e.g. `f.valuation_ratios.pe_ratio`.
- The integer `*_code` fields under `asset_classification` compare against the named constants in the **fundamental-classification** skill, e.g. `f.asset_classification.morningstar_sector_code == MorningstarSectorCode.TECHNOLOGY`.

## Top-level and filing-metadata data points

The snapshot's own attributes and the filing/timing fields under `f.financial_statements` (period end, file date, period type, ...) — the fields every point-in-time strategy needs:

| Data point | Description |
|---|---|
| `f.dollar_volume` | Gets the day's dollar volume for this symbol |
| `f.volume` | Gets the day's total volume |
| `f.has_fundamental_data` | Returns whether the symbol has fundamental data for the given date |
| `f.price_factor` | Gets the price factor for the given date |
| `f.split_factor` | Gets the split factor for the given date |
| `f.value` | Gets the raw price |
| `f.end_time` | The end time of this data. |
| `f.market_cap` | Price * Total SharesOutstanding. The most current market cap for example, would be the most recent closing price x the most recent reported shares outstanding. For ADR share classes, market cap is price * (ordinary shares outstanding / adr ratio). |
| `f.financial_statements.period_ending_date.[value 1M 2M 3M 6M 9M 12M]` | The exact date that is given in the financial statements for each quarter's end. |
| `f.financial_statements.file_date.[value 1M 2M 3M 6M 9M 12M]` | Specific date on which a company released its filing to the public. |
| `f.financial_statements.accession_number.[value 1M 2M 3M 6M 9M 12M]` | The accession number is a unique number that EDGAR assigns to each submission as the submission is received. |
| `f.financial_statements.form_type.[value 1M 2M 3M 6M 9M 12M]` | The type of filing of the report: for instance, 10-K (annual report) or 10-Q (quarterly report). |
| `f.financial_statements.period_auditor.[value 1M 2M 3M 6M 9M 12M]` | The name of the auditor that performed the financial statement audit for the given period. |
| `f.financial_statements.auditor_report_status.[value 1M 2M 3M 6M 9M 12M]` | Auditor opinion code will be one of the following for each annual period: Code Meaning UQ Unqualified Opinion UE Unqualified Opinion with Explanation QM Qualified - Due to change in accounting method QL Qualified - Due to litigation OT Qualified Opinion - Other AO Adverse Opinion DS Disclaim an opinion UA Unaudited |
| `f.financial_statements.inventory_valuation_method.[value 1M 2M 3M 6M 9M 12M]` | Which method of inventory valuation was used - LIFO, FIFO, Average, Standard costs, Net realizable value, Others, LIFO and FIFO, FIFO and Average, FIFO and other, LIFO and Average, LIFO and other, Average and other, 3 or more methods, None |
| `f.financial_statements.number_of_share_holders.[value 1M 2M 3M 6M 9M 12M]` | The number of shareholders on record |
| `f.financial_statements.period_type.[value 1M 2M 3M 6M 9M 12M]` | The nature of the period covered by an individual set of financial results. The output can be: Quarter, Semi-annual or Annual. Assuming a 12-month fiscal year, quarter typically covers a three-month period, semi-annual a six-month period, and annual a twelve-month period. Annual could cover results collected either from preliminary results or an annual report |
| `f.financial_statements.total_risk_based_capital.[value 1M 2M 3M 6M 9M 12M]` | The sum of Tier 1 and Tier 2 Capital. Tier 1 capital consists of common shareholders equity, perpetual preferred shareholders equity with non-cumulative dividends, retained earnings, and minority interests in the equity accounts of consolidated subsidiaries. Tier 2 capital consists of subordinated debt, intermediate-term preferred stock, cumulative and long-term preferred stock, and a portion of a bank's allowance for loan and lease losses. |
| `f.market` | Gets the market for this symbol |
| `f.price_scale_factor` | Gets the combined factor used to create adjusted prices from raw prices |
| `f.adjusted_price` | Gets the split and dividend adjusted price |
| `f.price` | Gets the raw price |
| `f.data_type` | Market Data Type of this data - does it come in individual price packets or is it grouped into OHLC. |
| `f.is_fill_forward` | True if this is a fill forward piece of data |
| `f.time` | Current time marker of this data packet. |
| `f.symbol` | Symbol representation for underlying Security |
