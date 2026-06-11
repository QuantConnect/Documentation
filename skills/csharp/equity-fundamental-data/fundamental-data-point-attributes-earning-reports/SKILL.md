---
name: fundamental-data-point-attributes-earning-reports
description: Use when you need the exact attribute name or meaning of an earnings-report field on a QuantConnect/LEAN `Fundamental` object — basic and diluted EPS, normalized EPS, basic and diluted average shares, dividend per share, plus period metadata, and the rest of `f.EarningReports.*`. Triggers — "what EPS / share-count fields exist", "path to diluted EPS / basic average shares / dividend per share", a missing-attribute error on an earning-reports path. Skip when — you need earnings-growth ratios (earning_ratios) or statement-level fields (see the sibling fundamental-data-point-attributes-* skills).
---

# Earning Reports attributes — QuantConnect / LEAN `Fundamental`

Reach these off a `Fundamental` snapshot `f` as `f.EarningReports.<Attribute>` — for example `f.EarningReports.DilutedEPS.Value`. Get `f` from an `AddUniverse(...)` selection callback, from `Securities["SPY"].Fundamentals`, or from a history request.

Every attribute below is a `MultiPeriodField` — append a period accessor to read the value: `.Value` for the most recent reported period or `.TwelveMonths` for the trailing-twelve-month figure. Forgetting the accessor is silent — the wrapper compares as truthy and numeric inequalities give nonsense. For how to choose accessors and navigate the rest of the tree, see the **fundamental-universes** skill.

| Attribute | Description |
|---|---|
| `PeriodEndingDate` | The exact date that is given in the financial statements for each quarter's end. |
| `FileDate` | Specific date on which a company released its filing to the public. |
| `AccessionNumber` | The accession number is a unique number that EDGAR assigns to each submission as the submission is received. |
| `FormType` | The type of filing of the report: for instance, 10-K (annual report) or 10-Q (quarterly report). |
| `PeriodType` | The nature of the period covered by an individual set of financial results. The output can be: Quarter, Semi-annual or Annual. Assuming a 12-month fiscal year, quarter typically covers a three-month period, semi-annual a six-month period, and annual a twelve-month period. Annual could cover results collected either from preliminary results or an annual report |
| `BasicContinuousOperations` | Basic EPS from Continuing Operations is the earnings from continuing operations reported by the company divided by the weighted average number of common shares outstanding. |
| `BasicDiscontinuousOperations` | Basic EPS from Discontinued Operations is the earnings from discontinued operations reported by the company divided by the weighted average number of common shares outstanding. This only includes gain or loss from discontinued operations. |
| `BasicExtraordinary` | Basic EPS from the Extraordinary Gains/Losses is the earnings attributable to the gains or losses (during the reporting period) from extraordinary items divided by the weighted average number of common shares outstanding. |
| `BasicAccountingChange` | Basic EPS from the Cumulative Effect of Accounting Change is the earnings attributable to the accounting change (during the reporting period) divided by the weighted average number of common shares outstanding. |
| `BasicEPS` | Basic EPS is the bottom line net income divided by the weighted average number of common shares outstanding. |
| `DilutedContinuousOperations` | Diluted EPS from Continuing Operations is the earnings from continuing operations divided by the common shares outstanding adjusted for the assumed conversion of all potentially dilutive securities. Securities having a dilutive effect may include convertible debentures, warrants, options, and convertible preferred stock. |
| `DilutedDiscontinuousOperations` | Diluted EPS from Discontinued Operations is the earnings from discontinued operations divided by the common shares outstanding adjusted for the assumed conversion of all potentially dilutive securities. Securities having a dilutive effect may include convertible debentures, warrants, options, and convertible preferred stock. This only includes gain or loss from discontinued operations. |
| `DilutedExtraordinary` | Diluted EPS from Extraordinary Gain/Losses is the gain or loss from extraordinary items divided by the common shares outstanding adjusted for the assumed conversion of all potentially dilutive securities. Securities having a dilutive effect may include convertible debentures, warrants, options, and convertible preferred stock. |
| `DilutedAccountingChange` | Diluted EPS from Cumulative Effect Accounting Changes is the earnings from accounting changes (in the reporting period) divided by the common shares outstanding adjusted for the assumed conversion of all potentially dilutive securities. Securities having a dilutive effect may include convertible debentures, warrants, options, and convertible preferred stock. |
| `DilutedEPS` | Diluted EPS is the bottom line net income divided by the common shares outstanding adjusted for the assumed conversion of all potentially dilutive securities. Securities having a dilutive effect may include convertible debentures, warrants, options, and convertible preferred stock. This value will be derived when not reported for the fourth quarter and will be less than or equal to Basic EPS. |
| `BasicAverageShares` | The shares outstanding used to calculate Basic EPS, which is the weighted average common share outstanding through the whole accounting period. Note: If Basic Average Shares are not presented by the firm in the Income Statement, this data point will be null. |
| `DilutedAverageShares` | The shares outstanding used to calculate the diluted EPS, assuming the conversion of all convertible securities and the exercise of warrants or stock options. It is the weighted average diluted share outstanding through the whole accounting period. Note: If Diluted Average Shares are not presented by the firm in the Income Statement and Basic Average Shares are presented, Diluted Average Shares will equal Basic Average Shares. However, if neither value is presented by the firm, Diluted Average Shares will be null. |
| `DividendPerShare` | The amount of dividend that a stockholder will receive for each share of stock held. It can be calculated by taking the total amount of dividends paid and dividing it by the total shares outstanding. Dividend per share = total dividend payment/total number of outstanding shares |
| `BasicEPSOtherGainsLosses` | Basic EPS from the Other Gains/Losses is the earnings attributable to the other gains/losses (during the reporting period) divided by the weighted average number of common shares outstanding. |
| `ContinuingAndDiscontinuedBasicEPS` | Basic EPS from Continuing Operations plus Basic EPS from Discontinued Operations. |
| `TaxLossCarryforwardBasicEPS` | The earnings attributable to the tax loss carry forward (during the reporting period). |
| `DilutedEPSOtherGainsLosses` | The earnings from gains and losses (in the reporting period) divided by the common shares outstanding adjusted for the assumed conversion of all potentially dilutive securities. Securities having a dilutive effect may include convertible debentures, warrants, options, convertible preferred stock, etc. |
| `ContinuingAndDiscontinuedDilutedEPS` | Diluted EPS from Continuing Operations plus Diluted EPS from Discontinued Operations. |
| `TaxLossCarryforwardDilutedEPS` | The earnings from any tax loss carry forward (in the reporting period). |
| `NormalizedBasicEPS` | The basic normalized earnings per share. Normalized EPS removes onetime and unusual items from EPS, to provide investors with a more accurate measure of the company's true earnings. Normalized Earnings / Basic Weighted Average Shares Outstanding. |
| `NormalizedDilutedEPS` | The diluted normalized earnings per share. Normalized EPS removes onetime and unusual items from EPS, to provide investors with a more accurate measure of the company's true earnings. Normalized Earnings / Diluted Weighted Average Shares Outstanding. |
| `TotalDividendPerShare` | Total Dividend Per Share is cash dividends and special cash dividends paid per share over a certain period of time. |
| `ReportedNormalizedBasicEPS` | Normalized Basic EPS as reported by the company in the financial statements. |
| `ReportedNormalizedDilutedEPS` | Normalized Diluted EPS as reported by the company in the financial statements. |
| `DividendCoverageRatio` | Reflects a firm's capacity to pay a dividend, and is defined as Earnings Per Share / Dividend Per Share |
