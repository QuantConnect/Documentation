---
name: fundamental-data-point-attributes-earning-ratios
description: Use when you need the exact attribute name or meaning of an earning-ratio (growth) field on a QuantConnect/LEAN `Fundamental` object — diluted and basic EPS growth, dividend-per-share growth, book-value-per-share growth, equity-per-share growth, FCF-per-share growth, and the rest of `f.earning_ratios.*`. Triggers — "what earnings-growth fields exist", "path to diluted EPS growth / dividend growth", a missing-attribute error on an earning-ratio path. Skip when — you need valuation ratios, operating ratios, or the per-period EPS values from earning_reports (see the sibling fundamental-data-point-attributes-* skills).
---

# Earning Ratios attributes — QuantConnect / LEAN `Fundamental`

Reach these off a `Fundamental` snapshot `f` as `f.earning_ratios.<attribute>` — for example `f.earning_ratios.diluted_eps_growth.value`. Get `f` from an `add_universe(...)` selection callback, from `self.securities["SPY"].fundamentals`, or from a history request.

Every attribute below is a `MultiPeriodField` — append a period accessor to read the number: `.value` for the most recent reported period or `.twelve_months` for the trailing-twelve-month window. Forgetting the accessor is silent — the wrapper compares as truthy and numeric inequalities give nonsense. For how to choose accessors and navigate the rest of the tree, see the **fundamental-universes** skill.

| Attribute | Description |
|---|---|
| `diluted_eps_growth` | The growth in the company's diluted earnings per share (EPS) on a percentage basis. Morningstar calculates the annualized growth percentage based on the underlying diluted EPS reported in the Income Statement within the company filings or reports. |
| `diluted_cont_eps_growth` | The growth in the company's diluted EPS from continuing operations on a percentage basis. Morningstar calculates the annualized growth percentage based on the underlying diluted EPS from continuing operations reported in the Income Statement within the company filings or reports. |
| `dps_growth` | The growth in the company's dividends per share (DPS) on a percentage basis. Morningstar calculates the annualized growth percentage based on the underlying DPS from its dividend database. Morningstar collects its DPS from company filings and reports, as well as from third party sources. |
| `equity_per_share_growth` | The growth in the company's book value per share on a percentage basis. Morningstar calculates the annualized growth percentage based on the underlying equity and end of period shares outstanding reported in the company filings or reports. |
| `regression_growthof_dividends_5_years` | The five-year growth rate of dividends per share, calculated using regression analysis. |
| `fcf_per_share_growth` | The growth in the company's free cash flow per share on a percentage basis. Morningstar calculates the growth percentage based on the free cash flow divided by average diluted shares outstanding reported in the Financial Statements within the company filings or reports. |
| `book_value_per_share_growth` | The growth in the company's book value per share on a percentage basis. Morningstar calculates the growth percentage based on the common shareholder's equity reported in the Balance Sheet divided by the diluted shares outstanding within the company filings or reports. |
| `normalized_diluted_eps_growth` | The growth in the company's Normalized Diluted EPS on a percentage basis. |
| `normalized_basic_eps_growth` | The growth in the company's Normalized Basic EPS on a percentage basis. |
