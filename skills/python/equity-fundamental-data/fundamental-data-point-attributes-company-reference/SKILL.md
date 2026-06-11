---
name: fundamental-data-point-attributes-company-reference
description: Use when you need the exact attribute name or meaning of a company-reference field on a QuantConnect/LEAN `Fundamental` object — company id, short/legal/standard names, CIK, country, fiscal year end, industry template, REIT and limited-partnership flags, auditor, and the rest of `f.company_reference.*`. Triggers — "what company-identifier fields exist", "path to company id / CIK / is REIT / fiscal year end", a missing-attribute error on a company-reference path. Skip when — you need company-profile, security-reference, or statement fields (see the sibling fundamental-data-point-attributes-* skills).
---

# Company Reference attributes — QuantConnect / LEAN `Fundamental`

Reach these off a `Fundamental` snapshot `f` as `f.company_reference.<attribute>` — for example `f.company_reference.company_id` or `f.company_reference.is_reit`. Get `f` from an `add_universe(...)` selection callback, from `self.securities["SPY"].fundamentals`, or from a history request.

These attributes are read **directly** — no period accessor. The `Type` column below tells you what each returns (text, integer, boolean, date). For how to navigate the rest of the tree, see the **fundamental-universes** skill.

| Attribute | Type | Description |
|---|---|---|
| `company_id` | string | 10-digit unique and unchanging Morningstar identifier assigned to every company. |
| `short_name` | string | 25-character max abbreviated name of the firm. In most cases, the short name will simply be the Legal Name less the "Corporation", "Corp.", "Inc.", "Incorporated", etc... |
| `standard_name` | string | The English translation of the foreign legal name if/when applicable. |
| `legal_name` | string | The full name of the registrant as specified in its charter, and most often found on the front cover of the 10K/10Q/20F filing. |
| `country_id` | string | 3 Character ISO code of the country where the firm is domiciled. See separate reference document for Country Mappings. |
| `cik` | string | The Central Index Key; a corporate identifier assigned by the Securities and Exchange Commission (SEC). |
| `company_status` | string | At the Company level; each company is assigned to 1 of 3 possible status classifications; (U) Public, (V) Private, or (O) Obsolete: - Public-Firm is operating and currently has at least one common share class that is currently trading on a public exchange. - Private-Firm is operating but does not have any common share classes currently trading on a public exchange. - Obsolete-Firm is no longer operating because it closed its business, or was acquired. |
| `fiscal_year_end` | Int32 | The Month of the company's latest fiscal year. |
| `industry_template_code` | string | This indicator will denote which one of the six industry data collection templates applies to the company. Each industry data collection template includes data elements that are commonly reported by companies in that industry. N=Normal (Manufacturing), M=Mining, U=Utility, T=Transportation, B=Bank, I=Insurance |
| `primary_share_class_id` | string | The 10-digit unique and unchanging Morningstar identifier assigned to the Primary Share class of a company. The primary share of a company is defined as the first share that was traded publicly and is still actively trading. If this share is no longer trading, the primary share will be the share with the highest volume. |
| `primary_symbol` | string | The symbol of the Primary Share of the company, composed of an arrangement of characters (often letters) representing a particular security listed on an exchange or otherwise traded publicly. The primary share of a company is defined as the first share that was traded publicly and is still actively trading. If this share is no longer trading, the primary share will be the share with the highest volume. Note: Morningstar's multi-share class symbols will often contain a "period" within the symbol; e.g. BRK.B for Berkshire Hathaway Class B. |
| `primary_exchange_id` | string | The Id representing the stock exchange of the Primary Share of the company. See separate reference document for Exchange Mappings. The primary share of a company is defined as the first share that was traded publicly with and is still actively trading. If this share is no longer trading, the primary share will be the share with the highest volume. |
| `business_country_id` | string | In some cases, different from the country of domicile (CountryId; DataID 5). This element is a three (3) Character ISO code of the business country of the security. It is determined by a few factors, including: |
| `legal_name_language_code` | string | The language code for the foreign legal name if/when applicable. Related to DataID 4 (LegalName). |
| `auditor` | string | The legal (registered) name of the company's current auditor. Distinct from DataID 28000 Period Auditor that identifies the Auditor related to that period's financial statements. |
| `auditor_language_code` | string | The ISO code denoting the language text for Auditor's name and contact information. |
| `advisor` | string | The legal (registered) name of the current legal Advisor of the company. |
| `advisor_language_code` | string | The ISO code denoting the language text for Advisor's name and contact information. |
| `is_limited_partnership` | bool | Indicator to denote if the company is a limited partnership, which is a form of business structure comprised of a general partner and limited partners. 1 denotes it is a LP; otherwise 0. |
| `is_reit` | bool | Indicator to denote if the company is a real estate investment trust (REIT). 1 denotes it is a REIT; otherwise 0. |
| `primary_mic` | string | The MIC (market identifier code) of the PrimarySymbol of the company. See Data Appendix A for the relevant MIC to exchange name mapping. |
| `report_style` | Int32 | This refers to the financial template used to collect the company's financial statements. There are two report styles representing two different financial template structures. Report style "1" is most commonly used by US and Canadian companies, and Report style "3" is most commonly used by the rest of the universe. Contact your client manager for access to the respective templates. |
| `yearof_establishment` | string | The year a company was founded. |
| `is_limited_liability_company` | bool | Indicator to denote if the company is a limited liability company. 1 denotes it is a LLC; otherwise 0. |
| `expected_fiscal_year_end` | DateTime | The upcoming expected year end for the company. It is calculated based on current year end (from latest available annual report) + 1 year. |
