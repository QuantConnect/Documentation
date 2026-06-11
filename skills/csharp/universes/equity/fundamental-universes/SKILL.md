---
name: fundamental-universes
description: Use when selecting or screening a QuantConnect/LEAN Equity universe on Morningstar fundamentals — the `AddUniverse(...)` pattern, the `Fundamental` object and how its data is organized, period accessors for `MultiPeriodField` values, and year-over-year deltas. Covers the Piotroski F-Score, Altman Z-score, Magic Formula, Graham filters, and custom screens. For the exact path of any field, it points to the equity-fundamental-data skill. Skip when — the universe is index/ETF-constituent only (`Universe.ETF(...)`).
---

# Fundamental universes in QuantConnect / LEAN

Select or screen an Equity universe on Morningstar fundamentals by passing a `Fundamental` callback to `AddUniverse(...)`. Each `Fundamental` object `f` is one company's snapshot; the Morningstar data hangs off it in a large, deeply nested tree. `f.FinancialStatements.NetIncome` does not exist — net income lives on `IncomeStatement`, one level deeper. For the exact path of any field — net income, operating cash flow, PE ratio, sector code, and so on — see the **equity-fundamental-data** skill; guessing a path wastes a backtest run.


## The `Fundamental` object

`f` is passed into your `AddUniverse(...)` selection callback (one per company); you can also pull a snapshot per-security with `var f = Securities["SPY"].Fundamentals`, or request it from history. Beyond price/volume basics (`f.MarketCap`, `f.DollarVolume`, `f.Price`), the Morningstar tree hangs off it: financial statements (income statement, balance sheet, cash flow statement), operation / valuation / earning ratios, earning reports, company profile, company & security reference, and asset classification. The **equity-fundamental-data** skill lists every one of those fields as a full path from `f` — look the path up there.

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
