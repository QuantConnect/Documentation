---
name: fundamental-universes
description: Use when selecting or screening a QuantConnect/LEAN Equity universe on Morningstar fundamentals — the `add_universe(...)` pattern, the `Fundamental` object and how its data is organized, period accessors for `MultiPeriodField` values, and year-over-year deltas. Covers the Piotroski F-Score, Altman Z-score, Magic Formula, Graham filters, and custom screens. For the exact attribute path and meaning of any field, it points to the fundamental-data-point-attributes-* skills (income statement, balance sheet, cash flow, valuation / operation / earning ratios, asset classification, company / security reference, company profile). Skip when — the universe is index/ETF-constituent only (`self.universe.etf(...)`).
---

# Fundamental universes in QuantConnect / LEAN

Select or screen an Equity universe on Morningstar fundamentals by passing a `Fundamental` callback to `add_universe(...)`. Each `Fundamental` object `f` is one company's snapshot; the Morningstar data hangs off it in a large, deeply nested tree. `f.financial_statements.net_income` does not exist — net income lives on `IncomeStatement`, one level deeper. Use the map below to find the right sub-object, then open that sub-object's skill for the exact attribute name and meaning; guessing a path wastes a backtest run.

## Static type checking

Type-hint your `Fundamental` parameters so the IDE autocompletes paths and flags typos like `netincom` before the backtest runs:

- Callback parameter: `def select(self, fundamentals: list[Fundamental]) -> list[Symbol]:`.
- Helpers that take one snapshot: `def get_metrics(self, f: Fundamental):`.

## The `Fundamental` object

`f` is passed into your `add_universe(...)` selection callback; you can also pull a snapshot per-security with `f = self.securities["SPY"].fundamentals`, or request it from history. Its top-level price/volume attributes:

| Attribute | Type | Description |
|---|---|---|
| `dollar_volume` | Double | Gets the day's dollar volume for this symbol |
| `volume` | int | Gets the day's total volume |
| `has_fundamental_data` | bool | Returns whether the symbol has fundamental data for the given date |
| `price_factor` | decimal | Gets the price factor for the given date |
| `split_factor` | decimal | Gets the split factor for the given date |
| `value` | decimal | Gets the raw price |
| `end_time` | DateTime | The end time of this data. |
| `market_cap` | int | Price * Total SharesOutstanding. The most current market cap for example, would be the most recent closing price x the most recent reported shares outstanding. For ADR share classes, market cap is price * (ordinary shares outstanding / adr ratio). |
| `market` | string | Gets the market for this symbol |
| `price_scale_factor` | decimal | Gets the combined factor used to create adjusted prices from raw prices |
| `adjusted_price` | decimal | Gets the split and dividend adjusted price |
| `price` | decimal | Gets the raw price |
| `data_type` | MarketDataType | Market Data Type of this data - does it come in individual price packets or is it grouped into OHLC. |
| `is_fill_forward` | bool | True if this is a fill forward piece of data |
| `time` | DateTime | Current time marker of this data packet. |
| `symbol` | Symbol | Symbol representation for underlying Security |

The Morningstar fields are grouped into the sub-objects below. Each has its own skill with a full attribute table and descriptions — open the one you need:

- `company_reference` — see the **fundamental-data-point-attributes-company-reference** skill
- `security_reference` — see the **fundamental-data-point-attributes-security-reference** skill
- `financial_statements` — see the **fundamental-data-point-attributes-financial-statements** skill
- `earning_reports` — see the **fundamental-data-point-attributes-earning-reports** skill
- `operation_ratios` — see the **fundamental-data-point-attributes-operation-ratios** skill
- `earning_ratios` — see the **fundamental-data-point-attributes-earning-ratios** skill
- `valuation_ratios` — see the **fundamental-data-point-attributes-valuation-ratios** skill
- `company_profile` — see the **fundamental-data-point-attributes-company-profile** skill
- `asset_classification` — see the **fundamental-data-point-attributes-asset-classification** skill

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
