---
name: equity-fundamental-data
description: START HERE to look up the exact path or spelling of any Morningstar fundamental data point on a QuantConnect/LEAN `Fundamental` object `f`. This skill holds the path-reading rules, the top-level and filing-metadata fields (market cap, `period_ending_date`, `file_date`, ...), and the index of the six field-family skills that hold the full tables — fundamental-income-statement, fundamental-balance-sheet, fundamental-cash-flow-statement, fundamental-ratios, fundamental-company-data, fundamental-classification. Triggers — a missing-attribute / compile error on a Fundamental property path; questions like "what's the path to net income / operating cash flow / shares outstanding / PE ratio / sector code". Skip when — you need how to build or screen a universe (see the fundamental-universes skill).
---

# Fundamental data-point attributes — QuantConnect / LEAN

Morningstar data points are read as a full path from the snapshot `f` — copy the path you need rather than guessing from English names; a wrong path wastes a backtest run. Get `f` from an `AddUniverse(...)` selection callback (each element is a `Fundamental`), from `Securities["SPY"].Fundamentals`, or from a history request. The field tables are split across skills by family: THIS skill carries the top-level and filing-metadata fields plus the index below — load the family skill that holds your field's table.

## Where every field lives — load the matching skill

| Field family | Load this skill | Contents |
|---|---|---|
| `f.FinancialStatements.IncomeStatement.*` | `fundamental-income-statement` | revenue, cost/expense lines, operating & net income, EBIT/EBITDA, interest, tax, dividends paid |
| `f.FinancialStatements.BalanceSheet.*` | `fundamental-balance-sheet` | assets, liabilities, equity, debt, working-capital components, share counts |
| `f.FinancialStatements.CashFlowStatement.*` | `fundamental-cash-flow-statement` | operating / investing / financing cash flows, capex, issuance & repurchase, dividends |
| `f.OperationRatios.*`, `f.ValuationRatios.*`, `f.EarningRatios.*` | `fundamental-ratios` | ROA/ROE/margins/turnover, PE/PB/PS/EV multiples & yields, EPS/DPS growth rates |
| `f.EarningReports.*`, `f.CompanyReference.*`, `f.SecurityReference.*`, `f.CompanyProfile.*` | `fundamental-company-data` | EPS & report dates, listing/exchange/share-class reference, company profile basics |
| `f.AssetClassification.*` + code constants | `fundamental-classification` | sector / industry-group / industry codes and the `MorningstarSectorCode`-style constants they compare against |

## Reading the paths

- A path ending in `.[value 1M 2M 3M 6M 9M 12M]` is a `MultiPeriodField` — append **one** period accessor to read the number. `.Value` is the most recent reported period; the `1M`–`12M` tokens are `.OneMonth .TwoMonths .ThreeMonths .SixMonths .NineMonths .TwelveMonths` respectively (trailing-twelve-month at `12M`). e.g. `f.FinancialStatements.IncomeStatement.NetIncome.TwelveMonths`. Forgetting the accessor is silent — the wrapper compares as truthy and numeric inequalities give nonsense.
- A path with **no** bracket is read directly. e.g. `f.ValuationRatios.PERatio`.
- The integer `*_code` fields under `asset_classification` compare against the named constants in the **fundamental-classification** skill, e.g. `f.AssetClassification.MorningstarSectorCode == MorningstarSectorCode.Technology`.

## Top-level and filing-metadata data points

The snapshot's own attributes and the filing/timing fields under `f.FinancialStatements` (period end, file date, period type, ...) — the fields every point-in-time strategy needs:

| Data point | Description |
|---|---|
| `f.DollarVolume` | Gets the day's dollar volume for this symbol |
| `f.Volume` | Gets the day's total volume |
| `f.HasFundamentalData` | Returns whether the symbol has fundamental data for the given date |
| `f.PriceFactor` | Gets the price factor for the given date |
| `f.SplitFactor` | Gets the split factor for the given date |
| `f.Value` | Gets the raw price |
| `f.EndTime` | The end time of this data. |
| `f.MarketCap` | Price * Total SharesOutstanding. The most current market cap for example, would be the most recent closing price x the most recent reported shares outstanding. For ADR share classes, market cap is price * (ordinary shares outstanding / adr ratio). |
| `f.FinancialStatements.PeriodEndingDate.[Value 1M 2M 3M 6M 9M 12M]` | The period ending date of the financial statements, dated by the filing the balance sheet, income statement and cash flow statement were reported in. Each statement also carries its own date: BalanceSheet.PeriodEndingDate, IncomeStatement.PeriodEndingDate and CashFlowStatement.PeriodEndingDate. |
| `f.FinancialStatements.FileDate.[Value 1M 2M 3M 6M 9M 12M]` | Specific date on which a company released its filing to the public. |
| `f.FinancialStatements.AccessionNumber.[Value 1M 2M 3M 6M 9M 12M]` | The accession number is a unique number that EDGAR assigns to each submission as the submission is received. |
| `f.FinancialStatements.FormType.[Value 1M 2M 3M 6M 9M 12M]` | The type of filing of the report: for instance, 10-K (annual report) or 10-Q (quarterly report). |
| `f.FinancialStatements.AuditorReportStatus.[Value 1M 2M 3M 6M 9M 12M]` | Auditor opinion code will be one of the following for each annual period: Code Meaning UQ Unqualified Opinion UE Unqualified Opinion with Explanation QM Qualified - Due to change in accounting method QL Qualified - Due to litigation OT Qualified Opinion - Other AO Adverse Opinion DS Disclaim an opinion UA Unaudited |
| `f.FinancialStatements.PeriodType.[Value 1M 2M 3M 6M 9M 12M]` | The nature of the period covered by an individual set of financial results. The output can be: Quarter, Semi-annual or Annual. Assuming a 12-month fiscal year, quarter typically covers a three-month period, semi-annual a six-month period, and annual a twelve-month period. Annual could cover results collected either from preliminary results or an annual report |
| `f.FinancialStatements.TotalRiskBasedCapital.[Value 1M 2M 3M 6M 9M 12M]` | The total capital ratio: total regulatory capital, Tier 1 plus Tier 2, divided by risk weighted assets. |
| `f.FinancialStatements.CommonEquityTier1CapitalRatio.[Value 1M 2M 3M 6M 9M 12M]` | Common equity tier 1 capital divided by risk weighted assets |
| `f.FinancialStatements.CommonEquityTier1Capital.[Value 1M 2M 3M 6M 9M 12M]` | Common equity tier 1 capital, the highest quality regulatory capital |
| `f.FinancialStatements.LiquidityCoverageRatio.[Value 1M 2M 3M 6M 9M 12M]` | High quality liquid assets divided by projected net cash outflows over thirty days |
| `f.FinancialStatements.NetInterestMargin.[Value 1M 2M 3M 6M 9M 12M]` | Net interest income divided by average earning assets |
| `f.FinancialStatements.NetInterestSpread.[Value 1M 2M 3M 6M 9M 12M]` | The difference between the yield on earning assets and the rate paid on funding |
| `f.FinancialStatements.NonPerformingAssetsLoans.[Value 1M 2M 3M 6M 9M 12M]` | Loans on which the borrower is not making interest or principal payments as scheduled |
| `f.FinancialStatements.RiskWeightedAsset.[Value 1M 2M 3M 6M 9M 12M]` | Assets weighted by credit risk, the denominator of the regulatory capital ratios |
| `f.FinancialStatements.Tier1CapitalRatio.[Value 1M 2M 3M 6M 9M 12M]` | Tier 1 capital divided by risk weighted assets |
| `f.FinancialStatements.Tier1Capital.[Value 1M 2M 3M 6M 9M 12M]` | Tier 1 capital: common equity, qualifying preferred equity and retained earnings |
| `f.FinancialStatements.Tier1LeverageRatio.[Value 1M 2M 3M 6M 9M 12M]` | Tier 1 capital divided by average total consolidated assets |
| `f.FinancialStatements.Tier2CapitalRatio.[Value 1M 2M 3M 6M 9M 12M]` | Tier 2 capital divided by risk weighted assets |
| `f.FinancialStatements.Tier2Capital.[Value 1M 2M 3M 6M 9M 12M]` | Tier 2 capital: subordinated debt, cumulative preferred stock and loan loss allowances |
| `f.FinancialStatements.TotalCapital.[Value 1M 2M 3M 6M 9M 12M]` | The sum of tier 1 and tier 2 capital, in currency rather than as a ratio |
| `f.FinancialStatements.AdjustedBasicNetAssetValue.[Value 1M 2M 3M 6M 9M 12M]` | Net asset value adjusted per the reporting standard, on a basic share basis |
| `f.FinancialStatements.AdjustedDilutedNetAssetValue.[Value 1M 2M 3M 6M 9M 12M]` | Net asset value adjusted per the reporting standard, on a diluted share basis |
| `f.FinancialStatements.ReportedBasicAdjustedFundFromOperations.[Value 1M 2M 3M 6M 9M 12M]` | Adjusted funds from operations as reported, on a basic share basis |
| `f.FinancialStatements.ReportedDilutedAdjustedFundFromOperations.[Value 1M 2M 3M 6M 9M 12M]` | Adjusted funds from operations as reported, on a diluted share basis |
| `f.FinancialStatements.ReportedDilutedFundFromOperations.[Value 1M 2M 3M 6M 9M 12M]` | Funds from operations as reported, on a diluted share basis |
| `f.FinancialStatements.AdjustedBasicNetAssetValuePerShare.[Value 1M 2M 3M 6M 9M 12M]` | Adjusted net asset value per basic share |
| `f.FinancialStatements.AdjustedDilutedNetAssetValuePerShare.[Value 1M 2M 3M 6M 9M 12M]` | Adjusted net asset value per diluted share |
| `f.FinancialStatements.ReportedBasicAdjustedFundFromOperationsPerShare.[Value 1M 2M 3M 6M 9M 12M]` | Adjusted funds from operations per basic share, as reported |
| `f.FinancialStatements.ReportedBasicFundFromOperationsPerShare.[Value 1M 2M 3M 6M 9M 12M]` | Funds from operations per basic share, as reported |
| `f.FinancialStatements.ReportedDilutedAdjustedFundFromOperationsPerShare.[Value 1M 2M 3M 6M 9M 12M]` | Adjusted funds from operations per diluted share, as reported |
| `f.FinancialStatements.ReportedDilutedFundFromOperationsPerShare.[Value 1M 2M 3M 6M 9M 12M]` | Funds from operations per diluted share, as reported |
| `f.Market` | Gets the market for this symbol |
| `f.PriceScaleFactor` | Gets the combined factor used to create adjusted prices from raw prices |
| `f.AdjustedPrice` | Gets the split and dividend adjusted price |
| `f.Price` | Gets the raw price |
| `f.DataType` | Market Data Type of this data - does it come in individual price packets or is it grouped into OHLC. |
| `f.IsFillForward` | True if this is a fill forward piece of data |
| `f.Time` | Current time marker of this data packet. |
| `f.Symbol` | Symbol representation for underlying Security |
