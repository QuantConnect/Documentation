---
name: fundamental-universes
description: Use when selecting or screening a QuantConnect/LEAN Equity universe on Morningstar fundamentals — the py`add_universe(...)`cs`AddUniverse(...)` pattern, the `Fundamental` object and how its data is organized, period accessors for `MultiPeriodField` values, and year-over-year deltas. Covers the Piotroski F-Score, Altman Z-score, Magic Formula, Graham filters, and custom screens. For the exact attribute path and meaning of any field, it points to the fundamental-data-point-attributes-* skills (income statement, balance sheet, cash flow, valuation / operation / earning ratios, asset classification, company / security reference, company profile). Skip when — the universe is index/ETF-constituent only (py`self.universe.etf(...)`cs`Universe.ETF(...)`).
---

# Fundamental universes in QuantConnect / LEAN

Select or screen an Equity universe on Morningstar fundamentals by passing a `Fundamental` callback to py`add_universe(...)`cs`AddUniverse(...)`. Each `Fundamental` object `f` is one company's snapshot; the Morningstar data hangs off it in a large, deeply nested tree. py`f.financial_statements.net_income`cs`f.FinancialStatements.NetIncome` does not exist — net income lives on `IncomeStatement`, one level deeper. Use the map below to find the right sub-object, then open that sub-object's skill for the exact attribute name and meaning; guessing a path wastes a backtest run.

<!-- python-only -->
## Static type checking

Type-hint your `Fundamental` parameters so the IDE autocompletes paths and flags typos like `netincom` before the backtest runs:

- Callback parameter: `def select(self, fundamentals: list[Fundamental]) -> list[Symbol]:`.
- Helpers that take one snapshot: `def get_metrics(self, f: Fundamental):`.
<!-- /python-only -->

## The `Fundamental` object

`f` is passed into your py`add_universe(...)`cs`AddUniverse(...)` selection callback; you can also pull a snapshot per-security with py`f = self.securities["SPY"].fundamentals`cs`var f = Securities["SPY"].Fundamentals`, or request it from history. Its top-level price/volume attributes:

<!-- fundamental-attributes: Fundamental -->

The Morningstar fields are grouped into the sub-objects below. Each has its own skill with a full attribute table and descriptions — open the one you need:

<!-- fundamental-subgroups: Fundamental -->

## Period accessors for `MultiPeriodField` properties

Statement and ratio-growth attributes are `MultiPeriodField` wrappers — append a period accessor to read the number:

- py`.three_months`cs`.ThreeMonths`, py`.six_months`cs`.SixMonths`, py`.nine_months`cs`.NineMonths`, py`.twelve_months`cs`.TwelveMonths` — period-aggregated value (TTM at py`.twelve_months`cs`.TwelveMonths`).
- py`.value`cs`.Value` — most recent reported period (quarterly or annual, whichever filed last).
- py`.one_month`cs`.OneMonth`, py`.two_months`cs`.TwoMonths` — short-window aggregates (rare).

How to choose:

- **Income statement / cash flow**: prefer py`.twelve_months`cs`.TwelveMonths` for cross-company comparability. py`.value`cs`.Value` mixes quarterlies and annuals.
- **Balance sheet**: py`.value`cs`.Value` — point-in-time snapshots; period aggregation is meaningless.
- **Operation / earning ratios**: also `MultiPeriodField` — py`.value`cs`.Value` unless you specifically want a longer window.

Forgetting an accessor is silent: the leaf compares as truthy and inequalities against numbers produce nonsense without raising. Valuation ratios and the company/security-reference, company-profile, and asset-classification fields are **not** wrapped — read those directly, with no accessor.

## Year-over-year deltas

`Fundamental` exposes only the latest reported period. Piotroski / Altman / changes-in-X screens need an earlier snapshot — store one per symbol keyed on py`f.financial_statements.period_ending_date.value`cs`f.FinancialStatements.PeriodEndingDate.Value` and compare against it on later bars.
