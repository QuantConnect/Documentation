---
name: fundamental-universes
description: Use when selecting or screening a QuantConnect/LEAN Equity universe on Morningstar fundamentals — the `AddUniverse(...)` pattern, the `Fundamental` object and how its data is organized, period accessors for `MultiPeriodField` values, and year-over-year deltas. Covers the Piotroski F-Score, Altman Z-score, Magic Formula, Graham filters, and custom screens. For the exact attribute path and meaning of any field, it points to the fundamental-data-point-attributes-* skills (income statement, balance sheet, cash flow, valuation / operation / earning ratios, asset classification, company / security reference, company profile). Skip when — the universe is index/ETF-constituent only (`Universe.ETF(...)`).
---

# Fundamental universes in QuantConnect / LEAN

Select or screen an Equity universe on Morningstar fundamentals by passing a `Fundamental` callback to `AddUniverse(...)`. Each `Fundamental` object `f` is one company's snapshot; the Morningstar data hangs off it in a large, deeply nested tree. `f.FinancialStatements.NetIncome` does not exist — net income lives on `IncomeStatement`, one level deeper. Use the map below to find the right sub-object, then open that sub-object's skill for the exact attribute name and meaning; guessing a path wastes a backtest run.


## The `Fundamental` object

`f` is passed into your `AddUniverse(...)` selection callback; you can also pull a snapshot per-security with `var f = Securities["SPY"].Fundamentals`, or request it from history. Its top-level price/volume attributes:

| Attribute | Type | Description |
|---|---|---|
| `DollarVolume` | Double | Gets the day's dollar volume for this symbol |
| `Volume` | int | Gets the day's total volume |
| `HasFundamentalData` | bool | Returns whether the symbol has fundamental data for the given date |
| `PriceFactor` | decimal | Gets the price factor for the given date |
| `SplitFactor` | decimal | Gets the split factor for the given date |
| `Value` | decimal | Gets the raw price |
| `EndTime` | DateTime | The end time of this data. |
| `MarketCap` | int | Price * Total SharesOutstanding. The most current market cap for example, would be the most recent closing price x the most recent reported shares outstanding. For ADR share classes, market cap is price * (ordinary shares outstanding / adr ratio). |
| `Market` | string | Gets the market for this symbol |
| `PriceScaleFactor` | decimal | Gets the combined factor used to create adjusted prices from raw prices |
| `AdjustedPrice` | decimal | Gets the split and dividend adjusted price |
| `Price` | decimal | Gets the raw price |
| `DataType` | MarketDataType | Market Data Type of this data - does it come in individual price packets or is it grouped into OHLC. |
| `IsFillForward` | bool | True if this is a fill forward piece of data |
| `Time` | DateTime | Current time marker of this data packet. |
| `Symbol` | Symbol | Symbol representation for underlying Security |

The Morningstar fields are grouped into the sub-objects below. Each has its own skill with a full attribute table and descriptions — open the one you need:

- `CompanyReference` — see the **fundamental-data-point-attributes-company-reference** skill
- `SecurityReference` — see the **fundamental-data-point-attributes-security-reference** skill
- `FinancialStatements` — see the **fundamental-data-point-attributes-financial-statements** skill
- `EarningReports` — see the **fundamental-data-point-attributes-earning-reports** skill
- `OperationRatios` — see the **fundamental-data-point-attributes-operation-ratios** skill
- `EarningRatios` — see the **fundamental-data-point-attributes-earning-ratios** skill
- `ValuationRatios` — see the **fundamental-data-point-attributes-valuation-ratios** skill
- `CompanyProfile` — see the **fundamental-data-point-attributes-company-profile** skill
- `AssetClassification` — see the **fundamental-data-point-attributes-asset-classification** skill

## Period accessors for `MultiPeriodField` properties

Statement and ratio-growth attributes are `MultiPeriodField` wrappers — append a period accessor to read the number:

- `.ThreeMonths`, `.SixMonths`, `.NineMonths`, `.TwelveMonths` — period-aggregated value (TTM at `.TwelveMonths`).
- `.Value` — most recent reported period (quarterly or annual, whichever filed last).
- `.OneMonth`, `.TwoMonths` — short-window aggregates (rare).

How to choose:

- **Income statement / cash flow**: prefer `.TwelveMonths` for cross-company comparability. `.Value` mixes quarterlies and annuals.
- **Balance sheet**: `.Value` — point-in-time snapshots; period aggregation is meaningless.
- **Operation / earning ratios**: also `MultiPeriodField` — `.Value` unless you specifically want a longer window.

Forgetting an accessor is silent: the leaf compares as truthy and inequalities against numbers produce nonsense without raising. Valuation ratios and the company/security-reference, company-profile, and asset-classification fields are **not** wrapped — read those directly, with no accessor.

## Year-over-year deltas

`Fundamental` exposes only the latest reported period. Piotroski / Altman / changes-in-X screens need an earlier snapshot — store one per symbol keyed on `f.FinancialStatements.PeriodEndingDate.Value` and compare against it on later bars.
