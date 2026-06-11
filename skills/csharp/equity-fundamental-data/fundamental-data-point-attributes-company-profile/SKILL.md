---
name: fundamental-data-point-attributes-company-profile
description: Use when you need the exact attribute name or meaning of a company-profile field on a QuantConnect/LEAN `Fundamental` object — headquarters address, employee counts, shares outstanding, market cap, enterprise value, homepage, and the rest of `f.CompanyProfile.*`. Triggers — "what company-profile fields exist", "path to shares outstanding / enterprise value / employee count", a missing-attribute error on a company-profile path. Skip when — you need financial-statement, ratio, or company-reference identifier fields (see the sibling fundamental-data-point-attributes-* skills).
---

# Company Profile attributes — QuantConnect / LEAN `Fundamental`

Reach these off a `Fundamental` snapshot `f` as `f.CompanyProfile.<Attribute>` — for example `f.CompanyProfile.SharesOutstanding`. Get `f` from an `AddUniverse(...)` selection callback, from `Securities["SPY"].Fundamentals`, or from a history request.

These attributes are read **directly** — no period accessor. The `Type` column below tells you what each returns (text, integer, decimal, etc.). For how to navigate the rest of the tree, see the **fundamental-universes** skill.

| Attribute | Type | Description |
|---|---|---|
| `HeadquarterAddressLine1` | string | The headquarter address as given in the latest report |
| `HeadquarterAddressLine2` | string | The headquarter address as given in the latest report |
| `HeadquarterAddressLine3` | string | The headquarter address as given in the latest report |
| `HeadquarterAddressLine4` | string | The headquarter address as given in the latest report |
| `HeadquarterAddressLine5` | string | The headquarter address as given in the latest report |
| `HeadquarterCity` | string | The headquarter city as given in the latest report |
| `HeadquarterProvince` | string | The headquarter state or province as given in the latest report |
| `HeadquarterCountry` | string | The headquarter country as given in the latest report |
| `HeadquarterPostalCode` | string | The headquarter postal code as given in the latest report |
| `HeadquarterPhone` | string | The headquarter phone number as given in the latest report |
| `HeadquarterFax` | string | The headquarter fax number as given in the latest report |
| `HeadquarterHomepage` | string | The headquarters' website address as given in the latest report |
| `TotalEmployeeNumber` | Int32 | The number of employees as indicated on the latest Annual Report, 10-K filing, Form 20-F or equivalent report indicating the employee count at the end of latest fiscal year. |
| `ContactEmail` | string | Company's contact email address |
| `AverageEmployeeNumber` | Int32 | Average number of employees from Annual Report |
| `RegisteredAddressLine1` | string | Details for registered office contact information including address full details, phone and |
| `RegisteredAddressLine2` | string | Address for registered office |
| `RegisteredAddressLine3` | string | Address for registered office |
| `RegisteredAddressLine4` | string | Address for registered office |
| `RegisteredCity` | string | City for registered office |
| `RegisteredProvince` | string | Province for registered office |
| `RegisteredCountry` | string | Country for registered office |
| `RegisteredPostalCode` | string | Postal Code for registered office |
| `RegisteredPhone` | string | Phone number for registered office |
| `RegisteredFax` | string | Fax number for registered office |
| `IsHeadOfficeSameWithRegisteredOfficeFlag` | bool | Flag to denote whether head and registered offices are the same |
| `SharesOutstanding` | int | The latest total shares outstanding reported by the company; most common source of this information is from the cover of the 10K, 10Q, or 20F filing. This figure is an aggregated shares outstanding number for a company. It can be used to calculate the most accurate market cap, based on each individual share's trading price and the total aggregated shares outstanding figure. |
| `MarketCap` | int | Price * Total SharesOutstanding. The most current market cap for example, would be the most recent closing price x the most recent reported shares outstanding. For ADR share classes, market cap is price * (ordinary shares outstanding / adr ratio). |
| `EnterpriseValue` | int | This number tells you what cash return you would get if you bought the entire company, including its debt. Enterprise Value = Market Cap + Preferred stock + Long-Term Debt And Capital Lease + Short Term Debt And Capital Lease + Securities Sold But Not Yet Repurchased - Cash, Cash Equivalent And Market Securities - Securities Purchased with Agreement to Resell - Securities Borrowed. |
| `ShareClassLevelSharesOutstanding` | int | The latest shares outstanding reported by the company of a particular share class; most common source of this information is from the cover of the 10K, 10Q, or 20F filing. This figure is an aggregated shares outstanding number for a particular share class of the company. |
| `SharesOutstandingWithBalanceSheetEndingDate` | int | Total shares outstanding reported by the company as of the balance sheet period ended date. The most common source of this information is from the 10K, 10Q, or 20F filing. This figure is an aggregated shares outstanding number for a company. |
| `ReasonofSharesChange` | string | The reason for the change in a company's total shares outstanding from the previous record. Examples could be share issuances or share buy-back. This field will only be populated when total shares outstanding is collected from a press release. |
