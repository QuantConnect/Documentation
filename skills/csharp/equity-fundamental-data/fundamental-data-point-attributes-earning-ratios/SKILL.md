---
name: fundamental-data-point-attributes-earning-ratios
description: Use when you need the exact attribute name or meaning of an earning-ratio (growth) field on a QuantConnect/LEAN `Fundamental` object — diluted and basic EPS growth, dividend-per-share growth, book-value-per-share growth, equity-per-share growth, FCF-per-share growth, and the rest of `f.EarningRatios.*`. Triggers — "what earnings-growth fields exist", "path to diluted EPS growth / dividend growth", a missing-attribute error on an earning-ratio path. Skip when — you need valuation ratios, operating ratios, or the per-period EPS values from earning_reports (see the sibling fundamental-data-point-attributes-* skills).
---

# Earning Ratios attributes — QuantConnect / LEAN `Fundamental`

Reach these off a `Fundamental` snapshot `f` as `f.EarningRatios.<Attribute>` — for example `f.EarningRatios.DilutedEPSGrowth.Value`. Get `f` from an `AddUniverse(...)` selection callback, from `Securities["SPY"].Fundamentals`, or from a history request.

Every attribute below is a `MultiPeriodField` — append a period accessor to read the number: `.Value` for the most recent reported period or `.TwelveMonths` for the trailing-twelve-month window. Forgetting the accessor is silent — the wrapper compares as truthy and numeric inequalities give nonsense. For how to choose accessors and navigate the rest of the tree, see the **fundamental-universes** skill.

| Attribute | Description |
|---|---|
| `DilutedEPSGrowth` | The growth in the company's diluted earnings per share (EPS) on a percentage basis. Morningstar calculates the annualized growth percentage based on the underlying diluted EPS reported in the Income Statement within the company filings or reports. |
| `DilutedContEPSGrowth` | The growth in the company's diluted EPS from continuing operations on a percentage basis. Morningstar calculates the annualized growth percentage based on the underlying diluted EPS from continuing operations reported in the Income Statement within the company filings or reports. |
| `DPSGrowth` | The growth in the company's dividends per share (DPS) on a percentage basis. Morningstar calculates the annualized growth percentage based on the underlying DPS from its dividend database. Morningstar collects its DPS from company filings and reports, as well as from third party sources. |
| `EquityPerShareGrowth` | The growth in the company's book value per share on a percentage basis. Morningstar calculates the annualized growth percentage based on the underlying equity and end of period shares outstanding reported in the company filings or reports. |
| `RegressionGrowthofDividends5Years` | The five-year growth rate of dividends per share, calculated using regression analysis. |
| `FCFPerShareGrowth` | The growth in the company's free cash flow per share on a percentage basis. Morningstar calculates the growth percentage based on the free cash flow divided by average diluted shares outstanding reported in the Financial Statements within the company filings or reports. |
| `BookValuePerShareGrowth` | The growth in the company's book value per share on a percentage basis. Morningstar calculates the growth percentage based on the common shareholder's equity reported in the Balance Sheet divided by the diluted shares outstanding within the company filings or reports. |
| `NormalizedDilutedEPSGrowth` | The growth in the company's Normalized Diluted EPS on a percentage basis. |
| `NormalizedBasicEPSGrowth` | The growth in the company's Normalized Basic EPS on a percentage basis. |
