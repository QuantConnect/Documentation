---
name: fundamental-data-point-attributes-operation-ratios
description: Use when you need the exact attribute name or meaning of an operation-ratio field on a QuantConnect/LEAN `Fundamental` object — gross/operating/net/EBITDA margins, ROE, ROA, ROIC, asset/inventory/receivable turnover, current/quick/cash ratios, leverage, and growth rates, plus the rest of `f.operation_ratios.*`. Triggers — "what margin/turnover/liquidity fields exist", "path to ROE / net margin / current ratio", a missing-attribute error on an operation-ratio path. Skip when — you need valuation ratios, earnings-growth ratios, or statement-level fields (see the sibling fundamental-data-point-attributes-* skills).
---

# Operation Ratios attributes — QuantConnect / LEAN `Fundamental`

Reach these off a `Fundamental` snapshot `f` as `f.operation_ratios.<attribute>` — for example `f.operation_ratios.roe.value`. Get `f` from an `add_universe(...)` selection callback, from `self.securities["SPY"].fundamentals`, or from a history request.

Every attribute below is a `MultiPeriodField` — append a period accessor to read the number: `.value` for the most recent reported period (the usual choice for a ratio), or a longer window such as `.twelve_months` where you want a smoother figure. Forgetting the accessor is silent — the wrapper compares as truthy and numeric inequalities give nonsense. For how to choose accessors and navigate the rest of the tree, see the **fundamental-universes** skill.

| Attribute | Description |
|---|---|
| `revenue_growth` | The growth in the company's revenue on a percentage basis. Morningstar calculates the growth percentage based on the underlying revenue data reported in the Income Statement within the company filings or reports. |
| `operation_income_growth` | The growth in the company's operating income on a percentage basis. Morningstar calculates the growth percentage based on the underlying operating income data reported in the Income Statement within the company filings or reports. |
| `net_income_growth` | The growth in the company's net income on a percentage basis. Morningstar calculates the growth percentage based on the underlying net income data reported in the Income Statement within the company filings or reports. |
| `net_income_cont_ops_growth` | The growth in the company's net income from continuing operations on a percentage basis. Morningstar calculates the growth percentage based on the underlying net income from continuing operations data reported in the Income Statement within the company filings or reports. This figure represents the rate of net income growth for parts of the business that will continue to generate revenue in the future. |
| `cfo_growth` | The growth in the company's cash flow from operations on a percentage basis. Morningstar calculates the growth percentage based on the underlying cash flow from operations data reported in the Cash Flow Statement within the company filings or reports. |
| `fcf_growth` | The growth in the company's free cash flow on a percentage basis. Morningstar calculates the growth percentage based on the underlying cash flow from operations and capital expenditures data reported in the Cash Flow Statement within the company filings or reports: Free Cash Flow = Cash flow from operations - Capital Expenditures. |
| `operation_revenue_growth_3_month_avg` | The growth in the company's operating revenue on a percentage basis. Morningstar calculates the growth percentage based on the underlying operating revenue data reported in the Income Statement within the company filings or reports. |
| `gross_margin` | Refers to the ratio of gross profit to revenue. Morningstar calculates the ratio by using the underlying data reported in the company filings or reports: (Revenue - Cost of Goods Sold) / Revenue. |
| `operation_margin` | Refers to the ratio of operating income to revenue. Morningstar calculates the ratio by using the underlying data reported in the company filings or reports: Operating Income / Revenue. |
| `pretax_margin` | Refers to the ratio of pretax income to revenue. Morningstar calculates the ratio by using the underlying data reported in the company filings or reports: Pretax Income / Revenue. |
| `net_margin` | Refers to the ratio of net income to revenue. Morningstar calculates the ratio by using the underlying data reported in the company filings or reports: Net Income / Revenue. |
| `tax_rate` | Refers to the ratio of tax provision to pretax income. Morningstar calculates the ratio by using the underlying data reported in the company filings or reports: Tax Provision / Pretax Income. [Note: Valid only when positive pretax income, and positive tax expense (not tax benefit)] |
| `ebit_margin` | Refers to the ratio of earnings before interest and taxes to revenue. Morningstar calculates the ratio by using the underlying data reported in the company filings or reports: EBIT / Revenue. |
| `ebitda_margin` | Refers to the ratio of earnings before interest, taxes and depreciation and amortization to revenue. Morningstar calculates the ratio by using the underlying data reported in the company filings or reports: EBITDA / Revenue. |
| `sales_per_employee` | Refers to the ratio of Revenue to Employees. Morningstar calculates the ratio by using the underlying data reported in the company filings or reports: Revenue / Employee Number. |
| `current_ratio` | Refers to the ratio of Current Assets to Current Liabilities. Morningstar calculates the ratio by using the underlying data reported in the Balance Sheet within the company filings or reports: Current Assets / Current Liabilities. |
| `quick_ratio` | Refers to the ratio of liquid assets to Current Liabilities. Morningstar calculates the ratio by using the underlying data reported in the Balance Sheet within the company filings or reports:(Cash, Cash Equivalents, and Short Term Investments + Receivables ) / Current Liabilities. |
| `long_term_debt_total_capital_ratio` | Refers to the ratio of Long Term Debt to Total Capital. Morningstar calculates the ratio by using the underlying data reported in the Balance Sheet within the company filings or reports: Long-Term Debt And Capital Lease Obligation / (Long-Term Debt And Capital Lease Obligation + Total Shareholder's Equity) |
| `interest_coverage` | Refers to the ratio of EBIT to Interest Expense. Morningstar calculates the ratio by using the underlying data reported in the Income Statement within the company filings or reports: EBIT / Interest Expense. |
| `long_term_debt_equity_ratio` | Refers to the ratio of Long Term Debt to Common Equity. Morningstar calculates the ratio by using the underlying data reported in the Balance Sheet within the company filings or reports: Long-Term Debt And Capital Lease Obligation / Common Equity. [Note: Common Equity = Total Shareholder's Equity - Preferred Stock] |
| `financial_leverage` | Refers to the ratio of Total Assets to Common Equity. Morningstar calculates the ratio by using the underlying data reported in the Balance Sheet within the company filings or reports: Total Assets / Common Equity. [Note: Common Equity = Total Shareholder's Equity - Preferred Stock] |
| `total_debt_equity_ratio` | Refers to the ratio of Total Debt to Common Equity. Morningstar calculates the ratio by using the underlying data reported in the Balance Sheet within the company filings or reports: (Current Debt And Current Capital Lease Obligation + Long-Term Debt And Long-Term Capital Lease Obligation / Common Equity. [Note: Common Equity = Total Shareholder's Equity - Preferred Stock] |
| `normalized_net_profit_margin` | Normalized Income / Total Revenue. A measure of profitability of the company calculated by finding Normalized Net Profit as a percentage of Total Revenues. |
| `days_in_sales` | 365 / Receivable Turnover |
| `days_in_inventory` | 365 / Inventory turnover |
| `days_in_payment` | 365 / Payable turnover |
| `cash_conversion_cycle` | Days In Inventory + Days In Sales - Days In Payment |
| `receivable_turnover` | Revenue / Average Accounts Receivables |
| `inventory_turnover` | Cost Of Goods Sold / Average Inventory |
| `payment_turnover` | Cost of Goods Sold / Average Accounts Payables |
| `fix_assets_turonver` | Revenue / Average PP&amp;E |
| `assets_turnover` | Revenue / Average Total Assets |
| `roe` | Net Income / Average Total Common Equity |
| `roa` | Net Income / Average Total Assets |
| `roic` | Net Income / (Total Equity + Long-term Debt and Capital Lease Obligation + Short-term Debt and Capital Lease Obligation) |
| `fcf_sales_ratio` | Free Cash flow / Revenue |
| `fcf_net_income_ratio` | Free Cash Flow / Net Income |
| `cap_ex_sales_ratio` | Capital Expenditure / Revenue |
| `debt_to_assets` | This is a leverage ratio used to determine how much debt (a sum of long term and current portion of debt) a company has on its balance sheet relative to total assets. This ratio examines the percent of the company that is financed by debt. |
| `common_equity_to_assets` | This is a financial ratio of common stock equity to total assets that indicates the relative proportion of equity used to finance a company's assets. |
| `capital_expenditure_annual_5_yr_growth` | This is the compound annual growth rate of the company's capital spending over the last 5 years. Capital Spending is the sum of the Capital Expenditure items found in the Statement of Cash Flows. |
| `gross_profit_annual_5_yr_growth` | This is the compound annual growth rate of the company's Gross Profit over the last 5 years. |
| `gross_margin_5_yr_avg` | This is the simple average of the company's Annual Gross Margin over the last 5 years. Gross Margin is Total Revenue minus Cost of Goods Sold divided by Total Revenue and is expressed as a percentage. |
| `post_tax_margin_5_yr_avg` | This is the simple average of the company's Annual Post Tax Margin over the last 5 years. Post tax margin is Post tax divided by total revenue for the same period. |
| `pre_tax_margin_5_yr_avg` | This is the simple average of the company's Annual Pre Tax Margin over the last 5 years. Pre tax margin is Pre tax divided by total revenue for the same period. |
| `profit_margin_5_yr_avg` | This is the simple average of the company's Annual Net Profit Margin over the last 5 years. Net profit margin is post tax income divided by total revenue for the same period. |
| `roe_5_yr_avg` | This is the simple average of the company's ROE over the last 5 years. Return on equity reveals how much profit a company has earned in comparison to the total amount of shareholder equity found on the balance sheet. |
| `roa_5_yr_avg` | This is the simple average of the company's ROA over the last 5 years. Return on asset is calculated by dividing a company's annual earnings by its average total assets. |
| `avg_5_yrs_roic` | This is the simple average of the company's ROIC over the last 5 years. Return on invested capital is calculated by taking net operating profit after taxes and dividends and dividing by the total amount of capital invested and expressing the result as a percentage. |
| `normalized_roic` | [Normalized Income + (Interest Expense * (1-Tax Rate))] / Invested Capital |
| `regression_growth_operating_revenue_5_years` | The five-year growth rate of operating revenue, calculated using regression analysis. |
| `cash_ratio` | Indicates a company's short-term liquidity, defined as short term liquid investments (cash, cash equivalents, short term investments) divided by current liabilities. |
| `cashto_total_assets` | Represents the percentage of a company's total assets is in cash. |
| `capital_expenditureto_ebitda` | Measures the amount a company is investing in its business relative to EBITDA generated in a given period. |
| `fc_fto_cfo` | Indicates the percentage of a company's operating cash flow is free to be invested in its business after capital expenditures. |
| `stockholders_equity_growth` | The growth in the stockholder's equity on a percentage basis. Morningstar calculates the growth percentage based on the residual interest in the assets of the enterprise that remains after deducting its liabilities reported in the Balance Sheet within the company filings or reports. |
| `total_assets_growth` | The growth in the total assets on a percentage basis. Morningstar calculates the growth percentage based on the total assets reported in the Balance Sheet within the company filings or reports. |
| `total_liabilities_growth` | The growth in the total liabilities on a percentage basis. Morningstar calculates the growth percentage based on the total liabilities reported in the Balance Sheet within the company filings or reports. |
| `total_debt_equity_ratio_growth` | The growth in the company's total debt to equity ratio on a percentage basis. Morningstar calculates the growth percentage based on the total debt divided by the shareholder's equity reported in the Balance Sheet within the company filings or reports. |
| `cash_ratio_growth` | The growth in the company's cash ratio on a percentage basis. Morningstar calculates the growth percentage based on the short term liquid investments (cash, cash equivalents, short term investments) divided by current liabilities reported in the Balance Sheet within the company filings or reports. |
| `ebitda_growth` | The growth in the company's EBITDA on a percentage basis. Morningstar calculates the growth percentage based on the earnings minus expenses (excluding interest, tax, depreciation, and amortization expenses) reported in the Financial Statements within the company filings or reports. |
| `cash_flow_from_financing_growth` | The growth in the company's cash flows from financing on a percentage basis. Morningstar calculates the growth percentage based on the financing cash flows reported in the Cash Flow Statement within the company filings or reports. |
| `cash_flow_from_investing_growth` | The growth in the company's cash flows from investing on a percentage basis. Morningstar calculates the growth percentage based on the cash flows from investing reported in the Cash Flow Statement within the company filings or reports. |
| `cap_ex_growth` | The growth in the company's capital expenditures on a percentage basis. Morningstar calculates the growth percentage based on the capital expenditures reported in the Cash Flow Statement within the company filings or reports. |
| `current_ratio_growth` | The growth in the company's current ratio on a percentage basis. Morningstar calculates the growth percentage based on the current assets divided by current liabilities reported in the Balance Sheet within the company filings or reports. |
| `working_capital_turnover_ratio` | Total revenue / working capital (current assets minus current liabilities) |
| `net_income_per_employee` | Refers to the ratio of Net Income to Employees. Morningstar calculates the ratio by using the underlying data reported in the company filings or reports: Net Income / Employee Number. |
| `solvency_ratio` | Measure of whether a company's cash flow is sufficient to meet its short-term and long-term debt requirements. The lower this ratio is, the greater the probability that the company will be in financial distress. Net Income + Depreciation, Depletion and Amortization/ average of annual Total Liabilities over the most recent two periods. |
| `expense_ratio` | A measure of operating performance for Insurance companies, as it shows the relationship between the premiums earned and administrative expenses related to claims such as fees and commissions. A number of 1 or lower is preferred, as this means the premiums exceed the expenses. Calculated as: (Deferred Policy Acquisition Amortization Expense+Fees and Commission Expense+Other Underwriting Expenses+Selling, General and Administrative) / Net Premiums Earned |
| `loss_ratio` | A measure of operating performance for Insurance companies, as it shows the relationship between the premiums earned and the expenses related to claims. A number of 1 or lower is preferred, as this means the premiums exceed the expenses. Calculated as: Benefits, Claims and Loss Adjustment Expense, Net / Net Premiums Earned |
