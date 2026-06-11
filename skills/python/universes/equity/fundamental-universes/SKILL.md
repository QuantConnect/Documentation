---
name: fundamental-universes
description: Use when selecting or screening a QuantConnect/LEAN Equity universe on Morningstar fundamentals — the `add_universe(...)` pattern, the `Fundamental` object and how its data is organized, period accessors for `MultiPeriodField` values, and year-over-year deltas. Covers the Piotroski F-Score, Altman Z-score, Magic Formula, Graham filters, and custom screens. For the exact path of any field, it points to the equity-fundamental-data skill. Skip when — the universe is index/ETF-constituent only (`self.universe.etf(...)`).
---

# Fundamental universes in QuantConnect / LEAN

Select or screen an Equity universe on Morningstar fundamentals by passing a `Fundamental` callback to `add_universe(...)`. Each `Fundamental` object `f` is one company's snapshot; the Morningstar data hangs off it in a large, deeply nested tree. `f.financial_statements.net_income` does not exist — net income lives on `IncomeStatement`, one level deeper. For the exact path of any field — net income, operating cash flow, PE ratio, sector code, and so on — see the **equity-fundamental-data** skill; guessing a path wastes a backtest run.

## Static type checking

Type-hint your `Fundamental` parameters so the IDE autocompletes paths and flags typos like `netincom` before the backtest runs:

- Callback parameter: `def select(self, fundamentals: list[Fundamental]) -> list[Symbol]:`.
- Helpers that take one snapshot: `def get_metrics(self, f: Fundamental):`.

## The `Fundamental` object

`f` is passed into your `add_universe(...)` selection callback (one per company); you can also pull a snapshot per-security with `f = self.securities["SPY"].fundamentals`, or request it from history. Beyond price/volume basics (`f.market_cap`, `f.dollar_volume`, `f.price`), the Morningstar tree hangs off it: financial statements (income statement, balance sheet, cash flow statement), operation / valuation / earning ratios, earning reports, company profile, company & security reference, and asset classification. The **equity-fundamental-data** skill lists every one of those fields as a full path from `f` — look the path up there.

## Period accessors for `MultiPeriodField` properties

Statement and ratio-growth attributes are `MultiPeriodField` wrappers — append a period accessor to read the number:

- `.three_months`, `.six_months`, `.nine_months`, `.twelve_months` — period-aggregated value (TTM at `.twelve_months`).
- `.value` — most recent reported period (quarterly or annual, whichever filed last).
- `.one_month`, `.two_months` — short-window aggregates (rare).

How to choose:

- **Income statement / cash flow**: prefer `.twelve_months` for cross-company comparability. `.value` mixes quarterlies and annuals.
- **Balance sheet**: `.value` — point-in-time snapshots; period aggregation is meaningless.
- **Operation / earning ratios**: also `MultiPeriodField` — `.value` unless you specifically want a longer window.

Forgetting an accessor is silent: the leaf compares as truthy and inequalities against numbers produce nonsense without raising. Valuation ratios and the company/security-reference, company-profile, and asset-classification fields are **not** wrapped — read those directly, with no accessor.

## Year-over-year deltas

`Fundamental` exposes only the latest reported period. Piotroski / Altman / changes-in-X screens need an earlier snapshot — store one per symbol keyed on `f.financial_statements.period_ending_date.value` and compare against it on later bars.
