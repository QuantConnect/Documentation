---
name: alternative-data
description: Use when subscribing to a QuantConnect/LEAN alternative-data class via `add_data(<AltClass>, symbol)` and reading the result from `slice` in `on_data`. Triggers — "is this dataset a list or single point per bar", "why does iterating slice[dataset_symbol] fail", "why does .property error on a Quiver/RegAlytics/EODHDEconomicEvents value", missing-attribute errors after `slice[dataset_symbol]`. Skip when — the dataset is a universe (use alternative-data-universes), Morningstar fundamentals, ETF constituents, or the price feed comes through `add_equity` / `add_option` instead of `add_data`.
---

# Alternative Data Classes

Subscribe with `add_data(<AltClass>, symbol)` and read from `slice` in `on_data`. Indexing `slice[dataset_symbol]` returns either a single data point or a list of data points per bar — see the tables below.

## Single Data Point

Indexing `slice[dataset_symbol]` returns a single object; read properties directly.

```python
def on_data(self, slice: Slice) -> None:
    if slice.contains_key(self.dataset_symbol):
        data_point = slice[self.dataset_symbol]
        self.log(f"{data_point.<property>}")
```

| Class | Dataset | Properties |
| --- | --- | --- |
| `BenzingaNews` | Benzinga News Feed | `id`, `author`, `created_at`, `updated_at`, `title`, `teaser`, `contents`, `categories`, `symbols`, `tags` |
| `BitcoinMetadata` | Bitcoin Metadata | `difficulty`, `my_wallet_numberof_users`, `average_block_size`, `blockchain_size`, `median_transaction_confirmation_time`, `miners_revenue`, `hash_rate`, `cost_per_transaction`, `cost_percentof_transaction_volume`, `estimated_transaction_volume_usd`, `estimated_transaction_volume`, `total_output_volume`, `numberof_transactionper_block`, `numberof_unique_bitcoin_addresses_used`, `numberof_transactions_excluding_popular_addresses`, `total_numberof_transactions`, `numberof_transactions`, `total_transaction_fees_usd`, `total_transaction_fees`, `market_capitalization`, `total_bitcoins`, `my_wallet_numberof_transaction_per_day`, `my_wallet_transaction_volume` |
| `BrainCompanyFilingLanguageMetrics10K` | Brain Language Metrics on Company Filings | `report_date`, `report_category`, `report_period`, `previous_report_date`, `previous_report_category`, `previous_report_period`, `report_sentiment`, `risk_factors_statement_sentiment`, `management_discussion_analyasis_of_financial_condition_and_results_of_operations` |
| `BrainCompanyFilingLanguageMetricsAll` | Brain Language Metrics on Company Filings | `report_date`, `report_category`, `report_period`, `previous_report_date`, `previous_report_category`, `previous_report_period`, `report_sentiment`, `risk_factors_statement_sentiment`, `management_discussion_analyasis_of_financial_condition_and_results_of_operations` |
| `BrainStockRanking10Day` | Brain ML Stock Ranking | `rank` |
| `BrainStockRanking21Day` | Brain ML Stock Ranking | `rank` |
| `BrainStockRanking2Day` | Brain ML Stock Ranking | `rank` |
| `BrainStockRanking3Day` | Brain ML Stock Ranking | `rank` |
| `BrainStockRanking5Day` | Brain ML Stock Ranking | `rank` |
| `BrainSentimentIndicator30Day` | Brain Sentiment Indicator | `total_article_mentions`, `sentimental_article_mentions`, `sentiment`, `total_buzz_volume`, `sentimental_buzz_volume` |
| `BrainSentimentIndicator7Day` | Brain Sentiment Indicator | `total_article_mentions`, `sentimental_article_mentions`, `sentiment`, `total_buzz_volume`, `sentimental_buzz_volume` |
| `BrainWikipediaPageViews` | Brain Wikipedia Page Views | `number_views_1`, `buzz_1`, `number_views_7`, `buzz_7`, `number_views_30`, `buzz_30` |
| `KavoutCompositeFactorBundle` | Composite Factor Bundle | `growth`, `value_factor`, `quality`, `momentum`, `low_volatility` |
| `SmartInsiderIntention` | Corporate Buybacks | `execution`, `execution_entity`, `execution_holding`, `amount`, `value_currency`, `amount_value`, `percentage`, `authorization_start_date`, `authorization_end_date`, `price_currency`, `minimum_price`, `maximum_price`, `note_text`, `transaction_id`, `event_type`, `last_update`, `last_i_ds_update`, `isin`, `usd_market_cap`, `company_id`, `icb_industry`, `icb_super_sector`, `icb_sector`, `icb_sub_sector`, `icb_code`, `company_name`, `previous_results_announcement_date`, `next_results_announcements_date`, `next_close_begin`, `last_close_ended`, `security_description`, `ticker_country`, `ticker_symbol`, `announcement_date`, `time_released`, `time_processed`, `time_released_utc`, `time_processed_utc`, `announced_in` |
| `SmartInsiderTransaction` | Corporate Buybacks | `buyback_date`, `execution`, `execution_entity`, `execution_holding`, `currency`, `execution_price`, `amount`, `gbp_value`, `eur_value`, `usd_value`, `note_text`, `buyback_percentage`, `volume_percentage`, `conversion_rate`, `amount_adjusted_factor`, `price_adjusted_factor`, `treasury_holding`, `transaction_id`, `event_type`, `last_update`, `last_i_ds_update`, `isin`, `usd_market_cap`, `company_id`, `icb_industry`, `icb_super_sector`, `icb_sector`, `icb_sub_sector`, `icb_code`, `company_name`, `previous_results_announcement_date`, `next_results_announcements_date`, `next_close_begin`, `last_close_ended`, `security_description`, `ticker_country`, `ticker_symbol`, `announcement_date`, `time_released`, `time_processed`, `time_released_utc`, `time_processed_utc`, `announced_in` |
| `ExtractAlphaCrossAssetModel` | Cross Asset Model | `spread`, `skew`, `volume_component`, `score`, `score_slow` |
| `CoinGecko` | Crypto Market Cap | `coin`, `volume`, `market_cap` |
| `NasdaqCustomColumns` | Data Link | `value` |
| `NasdaqDataLink` | Data Link | `period` |
| `EstimizeConsensus` | Estimize | `id`, `source`, `type`, `mean`, `high`, `low`, `standard_deviation`, `count`, `updated_at`, `fiscal_year`, `fiscal_quarter` |
| `EstimizeEstimate` | Estimize | `id`, `ticker`, `fiscal_year`, `fiscal_quarter`, `created_at`, `eps`, `revenue`, `user_name`, `analyst_id`, `flagged` |
| `EstimizeRelease` | Estimize | `id`, `fiscal_year`, `fiscal_quarter`, `release_date`, `eps`, `revenue`, `wall_street_eps_estimate`, `wall_street_revenue_estimate`, `consensus_eps_estimate`, `consensus_revenue_estimate`, `consensus_weighted_eps_estimate`, `consensus_weighted_revenue_estimate` |
| `FearGreedIndex` | Fear and Greed | `spx`, `spx_sma`, `market_momentum`, `stocks_at_yearly_highs`, `stocks_at_yearly_lows`, `total_for_strength`, `net_yearly_highs_and_lows`, `stock_price_strength`, `stocks_up_daily`, `stocks_down_daily`, `total_for_breadth`, `mc_clellan_summation_index`, `stock_price_breadth`, `put_call_ratio_daily`, `put_call_ratio_sma`, `put_call_ratio_normalized`, `vix`, `vix_sma`, `market_volatility`, `stock_returns`, `bond_returns`, `stock_bond_return_difference`, `safe_haven_demand`, `junk_bond_yield`, `investment_grade_bond_yield`, `bond_yield_spread`, `junk_bond_demand`, `qc_index`, `cnn_index` |
| `QuiverInsiderTrading` | Insider Trading | `name`, `shares`, `price_per_share`, `shares_owned_following` |
| `ExtractAlphaTacticalModel` | Tactical | `reversal`, `factor_momentum`, `liquidity_shock`, `seasonality`, `score` |
| `TiingoNews` | Tiingo News Feed | `source`, `crawl_date`, `url`, `published_date`, `tags`, `description`, `title`, `article_id`, `symbols` |
| `EODHDUpcomingDividends` | Upcoming Dividends | `dividend_date`, `declaration_date`, `report_date`, `payment_date`, `dividend` |
| `EODHDUpcomingEarnings` | Upcoming Earnings | `report_date`, `report_time`, `estimate` |
| `EODHDUpcomingIPOs` | Upcoming IPOs | `name`, `exchange`, `ipo_date`, `filing_date`, `amended_date`, `lowest_price`, `highest_price`, `offer_price`, `shares`, `deal_type` |
| `EODHDUpcomingSplits` | Upcoming Splits | `split_date`, `optionable`, `split_factor` |
| `USEnergy` | US Energy Information Administration (EIA) | `value` |
| `Fred` | US Federal Reserve (FRED) | `value` |
| `QuiverGovernmentContract` | US Government Contracts | `description`, `agency`, `amount` |
| `SECReport10K` | US SEC Filings | `report` |
| `SECReport10Q` | US SEC Filings | `report` |
| `SECReport8K` | US SEC Filings | `report` |
| `USTreasuryYieldCurveRate` | US Treasury Yield Curve | `one_month`, `two_month`, `three_month`, `six_month`, `one_year`, `two_year`, `three_year`, `five_year`, `seven_year`, `ten_year`, `twenty_year`, `thirty_year` |
| `VIXCentralContango` | VIX Central Contango | `front_month`, `f_1`, `f_2`, `f_3`, `f_4`, `f_5`, `f_6`, `f_7`, `f_8`, `f_9`, `f_10`, `f_11`, `f_12`, `contango_f_2_minus_f_1`, `contango_f_7_minus_f_4`, `contango_f_7_minus_f_4_div_3`, `period` |
| `QuiverWallStreetBets` | WallStreetBets | `date`, `mentions`, `rank`, `sentiment` |

## List Type

Indexing `slice[dataset_symbol]` returns the listed outer class; iterate it and read properties off each element.

```python
def on_data(self, slice: Slice) -> None:
    if slice.contains_key(self.dataset_symbol):
        for data_point in slice[self.dataset_symbol]:
            self.log(f"{data_point.<property>}")
```

| Class | Dataset | Properties |
| --- | --- | --- |
| `QuiverCNBCs`->`QuiverCNBC` | CNBC Trading | `notes`, `direction`, `traders` |
| `QuiverLobbyings`->`QuiverLobbying` | Corporate Lobbying | `client`, `issue`, `specific_issue`, `amount` |
| `EODHDEconomicEvents`->`EODHDEconomicEvent` | Economic Events | `event_type`, `event_period`, `country`, `event_time`, `previous`, `estimate` |
| `EODHDMacroIndicators`->`EODHDMacroIndicator` | Macroeconomics Indicators | `indicator`, `country`, `frequency` |
| `ExtractAlphaTrueBeats`->`ExtractAlphaTrueBeat` | True Beats | `fiscal_period`, `earnings_metric`, `analyst_estimates_count`, `true_beat`, `expert_beat`, `trend_beat`, `management_beat` |
| `QuiverCongress`->`QuiverCongressDataPoint` | US Congress Trading | `record_date`, `updated_at`, `report_date`, `transaction_date`, `representative`, `transaction`, `amount`, `maximum_amount`, `house`, `party`, `district`, `state` |
| `RegalyticsRegulatoryArticles`->`RegalyticsRegulatoryArticle` | US Regulatory Alerts - Financial Sector | `id`, `title`, `summary`, `status`, `classification`, `filing_type`, `in_federal_register`, `federal_register_number`, `docket_file_number`, `sec_release_number`, `proposed_comments_due_date`, `original_publication_date`, `federal_register_publication_date`, `rule_effective_date`, `sourced_at`, `latest_update`, `alert_type`, `states`, `agencies`, `sector`, `announcement_url`, `created_at` |
| `USDAFruitAndVegetables`->`USDAFruitAndVegetable` | USDA Fruit And Vegetables | `form`, `average_retail_price`, `unit`, `preparation_yield_factor`, `cup_equivalent_size`, `cup_equivalent_unit`, `price_per_cup_equivalent` |

## Common mistakes

- Iterating a single-point class — that index returns one object; read `.property` directly.
- Reading `.property` on a list-class index — iterate first, then read each element.
- Skipping the `slice.contains_key` guard — alt-data only lands when there's an event, so the slice may not carry the symbol every bar.
- Two list-class wrappers iterate through a sub-attribute, not the value itself: `USDAFruitAndVegetables` (`collection.data`) and `EODHDEconomicEvents` (`slice.get(EODHDEconomicEvents).get(symbol)`).
