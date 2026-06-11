---
name: fundamental-data-point-attributes-security-reference
description: Use when you need the exact attribute name or meaning of a security-reference field on a QuantConnect/LEAN `Fundamental` object — exchange id, currency, IPO date, security type, share-class description and status, primary-share and dividend-reinvestment flags, delisting info, and the rest of `f.security_reference.*`. Triggers — "what security-reference fields exist", "path to IPO date / exchange id / security type / is primary share", a missing-attribute error on a security-reference path. Skip when — you need company-reference identifiers, company-profile, or statement fields (see the sibling fundamental-data-point-attributes-* skills).
---

# Security Reference attributes — QuantConnect / LEAN `Fundamental`

Reach these off a `Fundamental` snapshot `f` as `f.security_reference.<attribute>` — for example `f.security_reference.ipo_date`. Get `f` from an `add_universe(...)` selection callback, from `self.securities["SPY"].fundamentals`, or from a history request.

These attributes are read **directly** — no period accessor. The `Type` column below tells you what each returns (text, date, boolean, number). For how to navigate the rest of the tree, see the **fundamental-universes** skill.

| Attribute | Type | Description |
|---|---|---|
| `security_symbol` | string | An arrangement of characters (often letters) representing a particular security listed on an exchange or otherwise traded publicly. Note: Morningstar's multi-share class symbols will often contain a "period" within the symbol; e.g. BRK.B for Berkshire Hathaway Class B. |
| `exchange_id` | string | The Id representing the stock exchange that the particular share class is trading. See separate reference document for Exchange Mappings. |
| `currency_id` | string | 3 Character ISO code of the currency that the exchange price is denominated in; i.e. the trading currency of the security. See separate reference document for Currency Mappings. |
| `ipo_date` | DateTime | The initial day that the share begins trading on a public exchange. |
| `is_depositary_receipt` | bool | Indicator to denote if the share class is a depository receipt. 1 denotes it is an ADR or GDR; otherwise 0. |
| `depositary_receipt_ratio` | Double | The number of underlying common shares backing each American Depository Receipt traded. |
| `security_type` | string | Each security will be assigned to one of the below security type classifications; - Common Stock (ST00000001) - Preferred Stock (ST00000002) - Units (ST000000A1) |
| `share_class_description` | string | Provides information when applicable such as whether the share class is Class A or Class B, an ADR, GDR, or a business development company (BDC). For preferred stocks, this field provides more detail about the preferred share class. |
| `share_class_status` | string | At the ShareClass level; each share is assigned to 1 of 4 possible status classifications; (A) Active, (D) Deactive, (I) Inactive, or (O) Obsolete: - Active-Share class is currently trading in a public market, and we have fundamental data available. - Deactive-Share class was once Active, but is no longer trading due to share being delisted from the exchange. - Inactive-Share class is currently trading in a public market, but no fundamental data is available. - Obsolete-Share class was once Inactive, but is no longer trading due to share being delisted from the exchange. |
| `is_primary_share` | bool | This indicator will denote if the indicated share is the primary share for the company. A "1" denotes the primary share, a "0" denotes a share that is not the primary share. The primary share is defined as the first share that a company IPO'd with and is still actively trading. If this share is no longer trading, we will denote the primary share as the share with the highest volume. |
| `is_dividend_reinvest` | bool | Shareholder election plan to re-invest cash dividend into additional shares. |
| `is_direct_invest` | bool | A plan to make it possible for individual investors to invest in public companies without going through a stock broker. |
| `investment_id` | string | Identifier assigned to each security Morningstar covers. |
| `ipo_offer_price` | Double | IPO offer price indicates the price at which an issuer sells its shares under an initial public offering (IPO). The offer price is set by issuer and its underwriters. |
| `delisting_date` | DateTime | The date on which an inactive security was delisted from an exchange. |
| `delisting_reason` | string | The reason for an inactive security's delisting from an exchange. The full list of Delisting Reason codes can be found within the Data Definitions- Appendix A DelistingReason Codes tab. |
| `mic` | string | The MIC (market identifier code) of the related shareclass of the company. See Data Appendix A for the relevant MIC to exchange name mapping. |
| `common_share_sub_type` | string | Refers to the type of securities that can be found within the equity database. For the vast majority, this value will populate as null for regular common shares. For a minority of shareclasses, this will populate as either "Participating Preferred", "Closed-End Fund", "Foreign Share", or "Foreign Participated Preferred" which reflects our limited coverage of these types of securities within our equity database. |
| `ipo_offer_price_range` | string | The estimated offer price range (low-high) for a new IPO. The field should be used until the final IPO price becomes available, as populated in the data field "IPOPrice". |
| `exchange_sub_market_global_id` | string | Classification to denote different Marketplace or Market tiers within a stock exchange. |
| `conversion_ratio` | Double | The relationship between the chosen share class and the primary share class. |
| `par_value` | Double | Nominal value of a security determined by the issuing company. |
| `trading_status` | bool | <remarks> Morningstar DataId: 1028 </remarks> |
| `market_data_id` | string | <remarks> Morningstar DataId: 1029 </remarks> |
