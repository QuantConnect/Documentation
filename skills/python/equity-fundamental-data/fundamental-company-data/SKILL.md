---
name: fundamental-company-data
description: Use to look up the exact path or spelling of any Morningstar EARNING REPORT, COMPANY/SECURITY REFERENCE, or COMPANY PROFILE field on a QuantConnect/LEAN `Fundamental` object — everything under `f.earning_reports.*` (EPS, DPS, report/file dates, shares), `f.company_reference.*` (country, exchange, industry template), `f.security_reference.*` (security type, primary share, listing status, IPO date), and `f.company_profile.*`. Triggers — "path to file date of the earning report / basic EPS / primary exchange / share class / is primary share / IPO date". For other field families start at the equity-fundamental-data skill.
---

# Company data fields — `Fundamental` data points

Full path from the snapshot `f` with the field's description — copy the path rather than guessing from English names; a wrong path wastes a backtest run. The path-reading rules and the index of all field-family skills are in the **equity-fundamental-data** skill.

## Reading the paths

- A path ending in `.[value 1M 2M 3M 6M 9M 12M]` is a `MultiPeriodField` — append **one** period accessor to read the number. `.value` is the most recent reported period; the `1M`–`12M` tokens are `.one_month .two_months .three_months .six_months .nine_months .twelve_months` respectively (trailing-twelve-month at `12M`). e.g. `f.financial_statements.income_statement.net_income.twelve_months`. Forgetting the accessor is silent — the wrapper compares as truthy and numeric inequalities give nonsense.
- A path with **no** bracket is read directly. e.g. `f.valuation_ratios.pe_ratio`.
- The integer `*_code` fields under `asset_classification` compare against the named constants in the **fundamental-classification** skill.

## Data points

| Data point | Description |
|---|---|
| `f.company_reference.company_id` | 10-digit unique and unchanging Morningstar identifier assigned to every company. |
| `f.company_reference.short_name` | 25-character max abbreviated name of the firm. In most cases, the short name will simply be the Legal Name less the "Corporation", "Corp.", "Inc.", "Incorporated", etc... |
| `f.company_reference.standard_name` | The English translation of the foreign legal name if/when applicable. |
| `f.company_reference.legal_name` | The full name of the registrant as specified in its charter, and most often found on the front cover of the 10K/10Q/20F filing. |
| `f.company_reference.country_id` | 3 Character ISO code of the country where the firm is domiciled. See separate reference document for Country Mappings. |
| `f.company_reference.cik` | The Central Index Key; a corporate identifier assigned by the Securities and Exchange Commission (SEC). |
| `f.company_reference.company_status` | At the Company level; each company is assigned to 1 of 3 possible status classifications; (U) Public, (V) Private, or (O) Obsolete: - Public-Firm is operating and currently has at least one common share class that is currently trading on a public exchange. - Private-Firm is operating but does not have any common share classes currently trading on a public exchange. - Obsolete-Firm is no longer operating because it closed its business, or was acquired. |
| `f.company_reference.fiscal_year_end` | The Month of the company's latest fiscal year. |
| `f.company_reference.industry_template_code` | This indicator will denote which one of the six industry data collection templates applies to the company. Each industry data collection template includes data elements that are commonly reported by companies in that industry. N=Normal (Manufacturing), M=Mining, U=Utility, T=Transportation, B=Bank, I=Insurance |
| `f.company_reference.primary_share_class_id` | The 10-digit unique and unchanging Morningstar identifier assigned to the Primary Share class of a company. The primary share of a company is defined as the first share that was traded publicly and is still actively trading. If this share is no longer trading, the primary share will be the share with the highest volume. |
| `f.company_reference.primary_symbol` | The symbol of the Primary Share of the company, composed of an arrangement of characters (often letters) representing a particular security listed on an exchange or otherwise traded publicly. The primary share of a company is defined as the first share that was traded publicly and is still actively trading. If this share is no longer trading, the primary share will be the share with the highest volume. Note: Morningstar's multi-share class symbols will often contain a "period" within the symbol; e.g. BRK.B for Berkshire Hathaway Class B. |
| `f.company_reference.primary_exchange_id` | The Id representing the stock exchange of the Primary Share of the company. See separate reference document for Exchange Mappings. The primary share of a company is defined as the first share that was traded publicly with and is still actively trading. If this share is no longer trading, the primary share will be the share with the highest volume. |
| `f.company_reference.business_country_id` | In some cases, different from the country of domicile (CountryId; DataID 5). This element is a three (3) Character ISO code of the business country of the security. It is determined by a few factors, including: |
| `f.company_reference.legal_name_language_code` | The language code for the foreign legal name if/when applicable. Related to DataID 4 (LegalName). |
| `f.company_reference.auditor` | The legal (registered) name of the company's current auditor. Distinct from DataID 28000 Period Auditor that identifies the Auditor related to that period's financial statements. |
| `f.company_reference.auditor_language_code` | The ISO code denoting the language text for Auditor's name and contact information. |
| `f.company_reference.advisor` | The legal (registered) name of the current legal Advisor of the company. |
| `f.company_reference.advisor_language_code` | The ISO code denoting the language text for Advisor's name and contact information. |
| `f.company_reference.is_limited_partnership` | Indicator to denote if the company is a limited partnership, which is a form of business structure comprised of a general partner and limited partners. 1 denotes it is a LP; otherwise 0. |
| `f.company_reference.is_reit` | Indicator to denote if the company is a real estate investment trust (REIT). 1 denotes it is a REIT; otherwise 0. |
| `f.company_reference.primary_mic` | The MIC (market identifier code) of the PrimarySymbol of the company. See Data Appendix A for the relevant MIC to exchange name mapping. |
| `f.company_reference.report_style` | This refers to the financial template used to collect the company's financial statements. There are two report styles representing two different financial template structures. Report style "1" is most commonly used by US and Canadian companies, and Report style "3" is most commonly used by the rest of the universe. Contact your client manager for access to the respective templates. |
| `f.company_reference.yearof_establishment` | The year a company was founded. |
| `f.company_reference.is_limited_liability_company` | Indicator to denote if the company is a limited liability company. 1 denotes it is a LLC; otherwise 0. |
| `f.company_reference.expected_fiscal_year_end` | The upcoming expected year end for the company. It is calculated based on current year end (from latest available annual report) + 1 year. |
| `f.security_reference.security_symbol` | An arrangement of characters (often letters) representing a particular security listed on an exchange or otherwise traded publicly. Note: Morningstar's multi-share class symbols will often contain a "period" within the symbol; e.g. BRK.B for Berkshire Hathaway Class B. |
| `f.security_reference.exchange_id` | The Id representing the stock exchange that the particular share class is trading. See separate reference document for Exchange Mappings. |
| `f.security_reference.currency_id` | 3 Character ISO code of the currency that the exchange price is denominated in; i.e. the trading currency of the security. See separate reference document for Currency Mappings. |
| `f.security_reference.ipo_date` | The initial day that the share begins trading on a public exchange. |
| `f.security_reference.is_depositary_receipt` | Indicator to denote if the share class is a depository receipt. 1 denotes it is an ADR or GDR; otherwise 0. |
| `f.security_reference.depositary_receipt_ratio` | The number of underlying common shares backing each American Depository Receipt traded. |
| `f.security_reference.security_type` | Each security will be assigned to one of the below security type classifications; - Common Stock (ST00000001) - Preferred Stock (ST00000002) - Units (ST000000A1) |
| `f.security_reference.share_class_description` | Provides information when applicable such as whether the share class is Class A or Class B, an ADR, GDR, or a business development company (BDC). For preferred stocks, this field provides more detail about the preferred share class. |
| `f.security_reference.share_class_status` | At the ShareClass level; each share is assigned to 1 of 4 possible status classifications; (A) Active, (D) Deactive, (I) Inactive, or (O) Obsolete: - Active-Share class is currently trading in a public market, and we have fundamental data available. - Deactive-Share class was once Active, but is no longer trading due to share being delisted from the exchange. - Inactive-Share class is currently trading in a public market, but no fundamental data is available. - Obsolete-Share class was once Inactive, but is no longer trading due to share being delisted from the exchange. |
| `f.security_reference.is_primary_share` | This indicator will denote if the indicated share is the primary share for the company. A "1" denotes the primary share, a "0" denotes a share that is not the primary share. The primary share is defined as the first share that a company IPO'd with and is still actively trading. If this share is no longer trading, we will denote the primary share as the share with the highest volume. |
| `f.security_reference.is_dividend_reinvest` | Shareholder election plan to re-invest cash dividend into additional shares. |
| `f.security_reference.is_direct_invest` | A plan to make it possible for individual investors to invest in public companies without going through a stock broker. |
| `f.security_reference.investment_id` | Identifier assigned to each security Morningstar covers. |
| `f.security_reference.ipo_offer_price` | IPO offer price indicates the price at which an issuer sells its shares under an initial public offering (IPO). The offer price is set by issuer and its underwriters. |
| `f.security_reference.delisting_date` | The date on which an inactive security was delisted from an exchange. |
| `f.security_reference.delisting_reason` | The reason for an inactive security's delisting from an exchange. The full list of Delisting Reason codes can be found within the Data Definitions- Appendix A DelistingReason Codes tab. |
| `f.security_reference.mic` | The MIC (market identifier code) of the related shareclass of the company. See Data Appendix A for the relevant MIC to exchange name mapping. |
| `f.security_reference.common_share_sub_type` | Refers to the type of securities that can be found within the equity database. For the vast majority, this value will populate as null for regular common shares. For a minority of shareclasses, this will populate as either "Participating Preferred", "Closed-End Fund", "Foreign Share", or "Foreign Participated Preferred" which reflects our limited coverage of these types of securities within our equity database. |
| `f.security_reference.ipo_offer_price_range` | The estimated offer price range (low-high) for a new IPO. The field should be used until the final IPO price becomes available, as populated in the data field "IPOPrice". |
| `f.security_reference.exchange_sub_market_global_id` | Classification to denote different Marketplace or Market tiers within a stock exchange. |
| `f.security_reference.conversion_ratio` | The relationship between the chosen share class and the primary share class. |
| `f.security_reference.par_value` | Nominal value of a security determined by the issuing company. |
| `f.security_reference.trading_status` | <remarks> Morningstar DataId: 1028 </remarks> |
| `f.security_reference.market_data_id` | <remarks> Morningstar DataId: 1029 </remarks> |
| `f.earning_reports.period_ending_date.[value 1M 2M 3M 6M 9M 12M]` | The exact date that is given in the financial statements for each quarter's end. |
| `f.earning_reports.file_date.[value 1M 2M 3M 6M 9M 12M]` | Specific date on which a company released its filing to the public. |
| `f.earning_reports.accession_number.[value 1M 2M 3M 6M 9M 12M]` | The accession number is a unique number that EDGAR assigns to each submission as the submission is received. |
| `f.earning_reports.form_type.[value 1M 2M 3M 6M 9M 12M]` | The type of filing of the report: for instance, 10-K (annual report) or 10-Q (quarterly report). |
| `f.earning_reports.period_type.[value 1M 2M 3M 6M 9M 12M]` | The nature of the period covered by an individual set of financial results. The output can be: Quarter, Semi-annual or Annual. Assuming a 12-month fiscal year, quarter typically covers a three-month period, semi-annual a six-month period, and annual a twelve-month period. Annual could cover results collected either from preliminary results or an annual report |
| `f.earning_reports.basic_continuous_operations.[value 1M 2M 3M 6M 9M 12M]` | Basic EPS from Continuing Operations is the earnings from continuing operations reported by the company divided by the weighted average number of common shares outstanding. |
| `f.earning_reports.basic_discontinuous_operations.[value 1M 2M 3M 6M 9M 12M]` | Basic EPS from Discontinued Operations is the earnings from discontinued operations reported by the company divided by the weighted average number of common shares outstanding. This only includes gain or loss from discontinued operations. |
| `f.earning_reports.basic_extraordinary.[value 1M 2M 3M 6M 9M 12M]` | Basic EPS from the Extraordinary Gains/Losses is the earnings attributable to the gains or losses (during the reporting period) from extraordinary items divided by the weighted average number of common shares outstanding. |
| `f.earning_reports.basic_accounting_change.[value 1M 2M 3M 6M 9M 12M]` | Basic EPS from the Cumulative Effect of Accounting Change is the earnings attributable to the accounting change (during the reporting period) divided by the weighted average number of common shares outstanding. |
| `f.earning_reports.basic_eps.[value 1M 2M 3M 6M 9M 12M]` | Basic EPS is the bottom line net income divided by the weighted average number of common shares outstanding. |
| `f.earning_reports.diluted_continuous_operations.[value 1M 2M 3M 6M 9M 12M]` | Diluted EPS from Continuing Operations is the earnings from continuing operations divided by the common shares outstanding adjusted for the assumed conversion of all potentially dilutive securities. Securities having a dilutive effect may include convertible debentures, warrants, options, and convertible preferred stock. |
| `f.earning_reports.diluted_discontinuous_operations.[value 1M 2M 3M 6M 9M 12M]` | Diluted EPS from Discontinued Operations is the earnings from discontinued operations divided by the common shares outstanding adjusted for the assumed conversion of all potentially dilutive securities. Securities having a dilutive effect may include convertible debentures, warrants, options, and convertible preferred stock. This only includes gain or loss from discontinued operations. |
| `f.earning_reports.diluted_extraordinary.[value 1M 2M 3M 6M 9M 12M]` | Diluted EPS from Extraordinary Gain/Losses is the gain or loss from extraordinary items divided by the common shares outstanding adjusted for the assumed conversion of all potentially dilutive securities. Securities having a dilutive effect may include convertible debentures, warrants, options, and convertible preferred stock. |
| `f.earning_reports.diluted_accounting_change.[value 1M 2M 3M 6M 9M 12M]` | Diluted EPS from Cumulative Effect Accounting Changes is the earnings from accounting changes (in the reporting period) divided by the common shares outstanding adjusted for the assumed conversion of all potentially dilutive securities. Securities having a dilutive effect may include convertible debentures, warrants, options, and convertible preferred stock. |
| `f.earning_reports.diluted_eps.[value 1M 2M 3M 6M 9M 12M]` | Diluted EPS is the bottom line net income divided by the common shares outstanding adjusted for the assumed conversion of all potentially dilutive securities. Securities having a dilutive effect may include convertible debentures, warrants, options, and convertible preferred stock. This value will be derived when not reported for the fourth quarter and will be less than or equal to Basic EPS. |
| `f.earning_reports.basic_average_shares.[value 1M 2M 3M 6M 9M 12M]` | The shares outstanding used to calculate Basic EPS, which is the weighted average common share outstanding through the whole accounting period. Note: If Basic Average Shares are not presented by the firm in the Income Statement, this data point will be null. |
| `f.earning_reports.diluted_average_shares.[value 1M 2M 3M 6M 9M 12M]` | The shares outstanding used to calculate the diluted EPS, assuming the conversion of all convertible securities and the exercise of warrants or stock options. It is the weighted average diluted share outstanding through the whole accounting period. Note: If Diluted Average Shares are not presented by the firm in the Income Statement and Basic Average Shares are presented, Diluted Average Shares will equal Basic Average Shares. However, if neither value is presented by the firm, Diluted Average Shares will be null. |
| `f.earning_reports.dividend_per_share.[value 1M 2M 3M 6M 9M 12M]` | The amount of dividend that a stockholder will receive for each share of stock held. It can be calculated by taking the total amount of dividends paid and dividing it by the total shares outstanding. Dividend per share = total dividend payment/total number of outstanding shares |
| `f.earning_reports.basic_eps_other_gains_losses.[value 1M 2M 3M 6M 9M 12M]` | Basic EPS from the Other Gains/Losses is the earnings attributable to the other gains/losses (during the reporting period) divided by the weighted average number of common shares outstanding. |
| `f.earning_reports.continuing_and_discontinued_basic_eps.[value 1M 2M 3M 6M 9M 12M]` | Basic EPS from Continuing Operations plus Basic EPS from Discontinued Operations. |
| `f.earning_reports.tax_loss_carryforward_basic_eps.[value 1M 2M 3M 6M 9M 12M]` | The earnings attributable to the tax loss carry forward (during the reporting period). |
| `f.earning_reports.diluted_eps_other_gains_losses.[value 1M 2M 3M 6M 9M 12M]` | The earnings from gains and losses (in the reporting period) divided by the common shares outstanding adjusted for the assumed conversion of all potentially dilutive securities. Securities having a dilutive effect may include convertible debentures, warrants, options, convertible preferred stock, etc. |
| `f.earning_reports.continuing_and_discontinued_diluted_eps.[value 1M 2M 3M 6M 9M 12M]` | Diluted EPS from Continuing Operations plus Diluted EPS from Discontinued Operations. |
| `f.earning_reports.tax_loss_carryforward_diluted_eps.[value 1M 2M 3M 6M 9M 12M]` | The earnings from any tax loss carry forward (in the reporting period). |
| `f.earning_reports.normalized_basic_eps.[value 1M 2M 3M 6M 9M 12M]` | The basic normalized earnings per share. Normalized EPS removes onetime and unusual items from EPS, to provide investors with a more accurate measure of the company's true earnings. Normalized Earnings / Basic Weighted Average Shares Outstanding. |
| `f.earning_reports.normalized_diluted_eps.[value 1M 2M 3M 6M 9M 12M]` | The diluted normalized earnings per share. Normalized EPS removes onetime and unusual items from EPS, to provide investors with a more accurate measure of the company's true earnings. Normalized Earnings / Diluted Weighted Average Shares Outstanding. |
| `f.earning_reports.total_dividend_per_share.[value 1M 2M 3M 6M 9M 12M]` | Total Dividend Per Share is cash dividends and special cash dividends paid per share over a certain period of time. |
| `f.earning_reports.reported_normalized_basic_eps.[value 1M 2M 3M 6M 9M 12M]` | Normalized Basic EPS as reported by the company in the financial statements. |
| `f.earning_reports.reported_normalized_diluted_eps.[value 1M 2M 3M 6M 9M 12M]` | Normalized Diluted EPS as reported by the company in the financial statements. |
| `f.earning_reports.dividend_coverage_ratio.[value 1M 2M 3M 6M 9M 12M]` | Reflects a firm's capacity to pay a dividend, and is defined as Earnings Per Share / Dividend Per Share |
| `f.company_profile.headquarter_address_line_1` | The headquarter address as given in the latest report |
| `f.company_profile.headquarter_address_line_2` | The headquarter address as given in the latest report |
| `f.company_profile.headquarter_address_line_3` | The headquarter address as given in the latest report |
| `f.company_profile.headquarter_address_line_4` | The headquarter address as given in the latest report |
| `f.company_profile.headquarter_address_line_5` | The headquarter address as given in the latest report |
| `f.company_profile.headquarter_city` | The headquarter city as given in the latest report |
| `f.company_profile.headquarter_province` | The headquarter state or province as given in the latest report |
| `f.company_profile.headquarter_country` | The headquarter country as given in the latest report |
| `f.company_profile.headquarter_postal_code` | The headquarter postal code as given in the latest report |
| `f.company_profile.headquarter_phone` | The headquarter phone number as given in the latest report |
| `f.company_profile.headquarter_fax` | The headquarter fax number as given in the latest report |
| `f.company_profile.headquarter_homepage` | The headquarters' website address as given in the latest report |
| `f.company_profile.total_employee_number` | The number of employees as indicated on the latest Annual Report, 10-K filing, Form 20-F or equivalent report indicating the employee count at the end of latest fiscal year. |
| `f.company_profile.contact_email` | Company's contact email address |
| `f.company_profile.average_employee_number` | Average number of employees from Annual Report |
| `f.company_profile.registered_address_line_1` | Details for registered office contact information including address full details, phone and |
| `f.company_profile.registered_address_line_2` | Address for registered office |
| `f.company_profile.registered_address_line_3` | Address for registered office |
| `f.company_profile.registered_address_line_4` | Address for registered office |
| `f.company_profile.registered_city` | City for registered office |
| `f.company_profile.registered_province` | Province for registered office |
| `f.company_profile.registered_country` | Country for registered office |
| `f.company_profile.registered_postal_code` | Postal Code for registered office |
| `f.company_profile.registered_phone` | Phone number for registered office |
| `f.company_profile.registered_fax` | Fax number for registered office |
| `f.company_profile.is_head_office_same_with_registered_office_flag` | Flag to denote whether head and registered offices are the same |
| `f.company_profile.shares_outstanding` | The latest total shares outstanding reported by the company; most common source of this information is from the cover of the 10K, 10Q, or 20F filing. This figure is an aggregated shares outstanding number for a company. It can be used to calculate the most accurate market cap, based on each individual share's trading price and the total aggregated shares outstanding figure. |
| `f.company_profile.market_cap` | Price * Total SharesOutstanding. The most current market cap for example, would be the most recent closing price x the most recent reported shares outstanding. For ADR share classes, market cap is price * (ordinary shares outstanding / adr ratio). |
| `f.company_profile.enterprise_value` | This number tells you what cash return you would get if you bought the entire company, including its debt. Enterprise Value = Market Cap + Preferred stock + Long-Term Debt And Capital Lease + Short Term Debt And Capital Lease + Securities Sold But Not Yet Repurchased - Cash, Cash Equivalent And Market Securities - Securities Purchased with Agreement to Resell - Securities Borrowed. |
| `f.company_profile.share_class_level_shares_outstanding` | The latest shares outstanding reported by the company of a particular share class; most common source of this information is from the cover of the 10K, 10Q, or 20F filing. This figure is an aggregated shares outstanding number for a particular share class of the company. |
| `f.company_profile.shares_outstanding_with_balance_sheet_ending_date` | Total shares outstanding reported by the company as of the balance sheet period ended date. The most common source of this information is from the 10K, 10Q, or 20F filing. This figure is an aggregated shares outstanding number for a company. |
| `f.company_profile.reasonof_shares_change` | The reason for the change in a company's total shares outstanding from the previous record. Examples could be share issuances or share buy-back. This field will only be populated when total shares outstanding is collected from a press release. |
