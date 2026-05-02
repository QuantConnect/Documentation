---
name: alternative-data
description: Use when subscribing to a QuantConnect/LEAN alternative-data class via py`add_data(<AltClass>, symbol)`cs`AddData<AltClass>(symbol)` and reading the result from py`slice`cs`slice` in py`on_data`cs`OnData`. Triggers — "is this dataset a list or single point per bar", "why does iterating slice[dataset_symbol] fail", "why does .property error on a Quiver/RegAlytics/EODHDEconomicEvents value", missing-attribute errors after py`slice[dataset_symbol]`cs`slice[_datasetSymbol]`. Skip when — the dataset is a universe (use alternative-data-universes), Morningstar fundamentals, ETF constituents, or the price feed comes through py`add_equity`cs`AddEquity` / py`add_option`cs`AddOption` instead of py`add_data`cs`AddData`.
---

# Alternative Data Classes

Subscribe with py`add_data(<AltClass>, symbol)`cs`AddData<AltClass>(symbol)` and read from py`slice`cs`slice` in py`on_data`cs`OnData`. Indexing py`slice[dataset_symbol]`cs`slice[_datasetSymbol]` returns either a single data point or a list of data points per bar — see the tables below.

## Single Data Point

Indexing py`slice[dataset_symbol]`cs`slice[_datasetSymbol]` returns a single object; read properties directly.

```python
def on_data(self, slice: Slice) -> None:
    if slice.contains_key(self.dataset_symbol):
        data_point = slice[self.dataset_symbol]
        self.log(f"{data_point.<property>}")
```

```csharp
public override void OnData(Slice slice)
{
    if (slice.ContainsKey(_datasetSymbol))
    {
        var dataPoint = slice[_datasetSymbol];
        Log($"{dataPoint.<Property>}");
    }
}
```

| Class | Dataset | Properties |
| --- | --- | --- |
| `BenzingaNews` | Benzinga News Feed | py`id`cs`Id`, py`author`cs`Author`, py`created_at`cs`CreatedAt`, py`updated_at`cs`UpdatedAt`, py`title`cs`Title`, py`teaser`cs`Teaser`, py`contents`cs`Contents`, py`categories`cs`Categories`, py`symbols`cs`Symbols`, py`tags`cs`Tags` |
| `BitcoinMetadata` | Bitcoin Metadata | py`difficulty`cs`Difficulty`, py`my_wallet_numberof_users`cs`MyWalletNumberofUsers`, py`average_block_size`cs`AverageBlockSize`, py`blockchain_size`cs`BlockchainSize`, py`median_transaction_confirmation_time`cs`MedianTransactionConfirmationTime`, py`miners_revenue`cs`MinersRevenue`, py`hash_rate`cs`HashRate`, py`cost_per_transaction`cs`CostPerTransaction`, py`cost_percentof_transaction_volume`cs`CostPercentofTransactionVolume`, py`estimated_transaction_volume_usd`cs`EstimatedTransactionVolumeUSD`, py`estimated_transaction_volume`cs`EstimatedTransactionVolume`, py`total_output_volume`cs`TotalOutputVolume`, py`numberof_transactionper_block`cs`NumberofTransactionperBlock`, py`numberof_unique_bitcoin_addresses_used`cs`NumberofUniqueBitcoinAddressesUsed`, py`numberof_transactions_excluding_popular_addresses`cs`NumberofTransactionsExcludingPopularAddresses`, py`total_numberof_transactions`cs`TotalNumberofTransactions`, py`numberof_transactions`cs`NumberofTransactions`, py`total_transaction_fees_usd`cs`TotalTransactionFeesUSD`, py`total_transaction_fees`cs`TotalTransactionFees`, py`market_capitalization`cs`MarketCapitalization`, py`total_bitcoins`cs`TotalBitcoins`, py`my_wallet_numberof_transaction_per_day`cs`MyWalletNumberofTransactionPerDay`, py`my_wallet_transaction_volume`cs`MyWalletTransactionVolume` |
| `BrainCompanyFilingLanguageMetrics10K` | Brain Language Metrics on Company Filings | py`report_date`cs`ReportDate`, py`report_category`cs`ReportCategory`, py`report_period`cs`ReportPeriod`, py`previous_report_date`cs`PreviousReportDate`, py`previous_report_category`cs`PreviousReportCategory`, py`previous_report_period`cs`PreviousReportPeriod`, py`report_sentiment`cs`ReportSentiment`, py`risk_factors_statement_sentiment`cs`RiskFactorsStatementSentiment`, py`management_discussion_analyasis_of_financial_condition_and_results_of_operations`cs`ManagementDiscussionAnalyasisOfFinancialConditionAndResultsOfOperations` |
| `BrainCompanyFilingLanguageMetricsAll` | Brain Language Metrics on Company Filings | py`report_date`cs`ReportDate`, py`report_category`cs`ReportCategory`, py`report_period`cs`ReportPeriod`, py`previous_report_date`cs`PreviousReportDate`, py`previous_report_category`cs`PreviousReportCategory`, py`previous_report_period`cs`PreviousReportPeriod`, py`report_sentiment`cs`ReportSentiment`, py`risk_factors_statement_sentiment`cs`RiskFactorsStatementSentiment`, py`management_discussion_analyasis_of_financial_condition_and_results_of_operations`cs`ManagementDiscussionAnalyasisOfFinancialConditionAndResultsOfOperations` |
| `BrainStockRanking10Day` | Brain ML Stock Ranking | py`rank`cs`Rank` |
| `BrainStockRanking21Day` | Brain ML Stock Ranking | py`rank`cs`Rank` |
| `BrainStockRanking2Day` | Brain ML Stock Ranking | py`rank`cs`Rank` |
| `BrainStockRanking3Day` | Brain ML Stock Ranking | py`rank`cs`Rank` |
| `BrainStockRanking5Day` | Brain ML Stock Ranking | py`rank`cs`Rank` |
| `BrainSentimentIndicator30Day` | Brain Sentiment Indicator | py`total_article_mentions`cs`TotalArticleMentions`, py`sentimental_article_mentions`cs`SentimentalArticleMentions`, py`sentiment`cs`Sentiment`, py`total_buzz_volume`cs`TotalBuzzVolume`, py`sentimental_buzz_volume`cs`SentimentalBuzzVolume` |
| `BrainSentimentIndicator7Day` | Brain Sentiment Indicator | py`total_article_mentions`cs`TotalArticleMentions`, py`sentimental_article_mentions`cs`SentimentalArticleMentions`, py`sentiment`cs`Sentiment`, py`total_buzz_volume`cs`TotalBuzzVolume`, py`sentimental_buzz_volume`cs`SentimentalBuzzVolume` |
| `BrainWikipediaPageViews` | Brain Wikipedia Page Views | py`number_views_1`cs`NumberViews1`, py`buzz_1`cs`Buzz1`, py`number_views_7`cs`NumberViews7`, py`buzz_7`cs`Buzz7`, py`number_views_30`cs`NumberViews30`, py`buzz_30`cs`Buzz30` |
| `KavoutCompositeFactorBundle` | Composite Factor Bundle | py`growth`cs`Growth`, py`value_factor`cs`ValueFactor`, py`quality`cs`Quality`, py`momentum`cs`Momentum`, py`low_volatility`cs`LowVolatility` |
| `SmartInsiderIntention` | Corporate Buybacks | py`execution`cs`Execution`, py`execution_entity`cs`ExecutionEntity`, py`execution_holding`cs`ExecutionHolding`, py`amount`cs`Amount`, py`value_currency`cs`ValueCurrency`, py`amount_value`cs`AmountValue`, py`percentage`cs`Percentage`, py`authorization_start_date`cs`AuthorizationStartDate`, py`authorization_end_date`cs`AuthorizationEndDate`, py`price_currency`cs`PriceCurrency`, py`minimum_price`cs`MinimumPrice`, py`maximum_price`cs`MaximumPrice`, py`note_text`cs`NoteText`, py`transaction_id`cs`TransactionID`, py`event_type`cs`EventType`, py`last_update`cs`LastUpdate`, py`last_i_ds_update`cs`LastIDsUpdate`, py`isin`cs`ISIN`, py`usd_market_cap`cs`USDMarketCap`, py`company_id`cs`CompanyID`, py`icb_industry`cs`ICBIndustry`, py`icb_super_sector`cs`ICBSuperSector`, py`icb_sector`cs`ICBSector`, py`icb_sub_sector`cs`ICBSubSector`, py`icb_code`cs`ICBCode`, py`company_name`cs`CompanyName`, py`previous_results_announcement_date`cs`PreviousResultsAnnouncementDate`, py`next_results_announcements_date`cs`NextResultsAnnouncementsDate`, py`next_close_begin`cs`NextCloseBegin`, py`last_close_ended`cs`LastCloseEnded`, py`security_description`cs`SecurityDescription`, py`ticker_country`cs`TickerCountry`, py`ticker_symbol`cs`TickerSymbol`, py`announcement_date`cs`AnnouncementDate`, py`time_released`cs`TimeReleased`, py`time_processed`cs`TimeProcessed`, py`time_released_utc`cs`TimeReleasedUtc`, py`time_processed_utc`cs`TimeProcessedUtc`, py`announced_in`cs`AnnouncedIn` |
| `SmartInsiderTransaction` | Corporate Buybacks | py`buyback_date`cs`BuybackDate`, py`execution`cs`Execution`, py`execution_entity`cs`ExecutionEntity`, py`execution_holding`cs`ExecutionHolding`, py`currency`cs`Currency`, py`execution_price`cs`ExecutionPrice`, py`amount`cs`Amount`, py`gbp_value`cs`GBPValue`, py`eur_value`cs`EURValue`, py`usd_value`cs`USDValue`, py`note_text`cs`NoteText`, py`buyback_percentage`cs`BuybackPercentage`, py`volume_percentage`cs`VolumePercentage`, py`conversion_rate`cs`ConversionRate`, py`amount_adjusted_factor`cs`AmountAdjustedFactor`, py`price_adjusted_factor`cs`PriceAdjustedFactor`, py`treasury_holding`cs`TreasuryHolding`, py`transaction_id`cs`TransactionID`, py`event_type`cs`EventType`, py`last_update`cs`LastUpdate`, py`last_i_ds_update`cs`LastIDsUpdate`, py`isin`cs`ISIN`, py`usd_market_cap`cs`USDMarketCap`, py`company_id`cs`CompanyID`, py`icb_industry`cs`ICBIndustry`, py`icb_super_sector`cs`ICBSuperSector`, py`icb_sector`cs`ICBSector`, py`icb_sub_sector`cs`ICBSubSector`, py`icb_code`cs`ICBCode`, py`company_name`cs`CompanyName`, py`previous_results_announcement_date`cs`PreviousResultsAnnouncementDate`, py`next_results_announcements_date`cs`NextResultsAnnouncementsDate`, py`next_close_begin`cs`NextCloseBegin`, py`last_close_ended`cs`LastCloseEnded`, py`security_description`cs`SecurityDescription`, py`ticker_country`cs`TickerCountry`, py`ticker_symbol`cs`TickerSymbol`, py`announcement_date`cs`AnnouncementDate`, py`time_released`cs`TimeReleased`, py`time_processed`cs`TimeProcessed`, py`time_released_utc`cs`TimeReleasedUtc`, py`time_processed_utc`cs`TimeProcessedUtc`, py`announced_in`cs`AnnouncedIn` |
| `ExtractAlphaCrossAssetModel` | Cross Asset Model | py`spread`cs`Spread`, py`skew`cs`Skew`, py`volume_component`cs`VolumeComponent`, py`score`cs`Score`, py`score_slow`cs`ScoreSlow` |
| `CoinGecko` | Crypto Market Cap | py`coin`cs`Coin`, py`volume`cs`Volume`, py`market_cap`cs`MarketCap` |
| `NasdaqCustomColumns` | Data Link | py`value`cs`Value` |
| `NasdaqDataLink` | Data Link | py`period`cs`Period` |
| `EstimizeConsensus` | Estimize | py`id`cs`Id`, py`source`cs`Source`, py`type`cs`Type`, py`mean`cs`Mean`, py`high`cs`High`, py`low`cs`Low`, py`standard_deviation`cs`StandardDeviation`, py`count`cs`Count`, py`updated_at`cs`UpdatedAt`, py`fiscal_year`cs`FiscalYear`, py`fiscal_quarter`cs`FiscalQuarter` |
| `EstimizeEstimate` | Estimize | py`id`cs`Id`, py`ticker`cs`Ticker`, py`fiscal_year`cs`FiscalYear`, py`fiscal_quarter`cs`FiscalQuarter`, py`created_at`cs`CreatedAt`, py`eps`cs`Eps`, py`revenue`cs`Revenue`, py`user_name`cs`UserName`, py`analyst_id`cs`AnalystId`, py`flagged`cs`Flagged` |
| `EstimizeRelease` | Estimize | py`id`cs`Id`, py`fiscal_year`cs`FiscalYear`, py`fiscal_quarter`cs`FiscalQuarter`, py`release_date`cs`ReleaseDate`, py`eps`cs`Eps`, py`revenue`cs`Revenue`, py`wall_street_eps_estimate`cs`WallStreetEpsEstimate`, py`wall_street_revenue_estimate`cs`WallStreetRevenueEstimate`, py`consensus_eps_estimate`cs`ConsensusEpsEstimate`, py`consensus_revenue_estimate`cs`ConsensusRevenueEstimate`, py`consensus_weighted_eps_estimate`cs`ConsensusWeightedEpsEstimate`, py`consensus_weighted_revenue_estimate`cs`ConsensusWeightedRevenueEstimate` |
| `FearGreedIndex` | Fear and Greed | py`spx`cs`Spx`, py`spx_sma`cs`SpxSma`, py`market_momentum`cs`MarketMomentum`, py`stocks_at_yearly_highs`cs`StocksAtYearlyHighs`, py`stocks_at_yearly_lows`cs`StocksAtYearlyLows`, py`total_for_strength`cs`TotalForStrength`, py`net_yearly_highs_and_lows`cs`NetYearlyHighsAndLows`, py`stock_price_strength`cs`StockPriceStrength`, py`stocks_up_daily`cs`StocksUpDaily`, py`stocks_down_daily`cs`StocksDownDaily`, py`total_for_breadth`cs`TotalForBreadth`, py`mc_clellan_summation_index`cs`McClellanSummationIndex`, py`stock_price_breadth`cs`StockPriceBreadth`, py`put_call_ratio_daily`cs`PutCallRatioDaily`, py`put_call_ratio_sma`cs`PutCallRatioSma`, py`put_call_ratio_normalized`cs`PutCallRatioNormalized`, py`vix`cs`Vix`, py`vix_sma`cs`VixSma`, py`market_volatility`cs`MarketVolatility`, py`stock_returns`cs`StockReturns`, py`bond_returns`cs`BondReturns`, py`stock_bond_return_difference`cs`StockBondReturnDifference`, py`safe_haven_demand`cs`SafeHavenDemand`, py`junk_bond_yield`cs`JunkBondYield`, py`investment_grade_bond_yield`cs`InvestmentGradeBondYield`, py`bond_yield_spread`cs`BondYieldSpread`, py`junk_bond_demand`cs`JunkBondDemand`, py`qc_index`cs`QCIndex`, py`cnn_index`cs`CNNIndex` |
| `QuiverInsiderTrading` | Insider Trading | py`name`cs`Name`, py`shares`cs`Shares`, py`price_per_share`cs`PricePerShare`, py`shares_owned_following`cs`SharesOwnedFollowing` |
| `ExtractAlphaTacticalModel` | Tactical | py`reversal`cs`Reversal`, py`factor_momentum`cs`FactorMomentum`, py`liquidity_shock`cs`LiquidityShock`, py`seasonality`cs`Seasonality`, py`score`cs`Score` |
| `TiingoNews` | Tiingo News Feed | py`source`cs`Source`, py`crawl_date`cs`CrawlDate`, py`url`cs`Url`, py`published_date`cs`PublishedDate`, py`tags`cs`Tags`, py`description`cs`Description`, py`title`cs`Title`, py`article_id`cs`ArticleID`, py`symbols`cs`Symbols` |
| `EODHDUpcomingDividends` | Upcoming Dividends | py`dividend_date`cs`DividendDate`, py`declaration_date`cs`DeclarationDate`, py`report_date`cs`ReportDate`, py`payment_date`cs`PaymentDate`, py`dividend`cs`Dividend` |
| `EODHDUpcomingEarnings` | Upcoming Earnings | py`report_date`cs`ReportDate`, py`report_time`cs`ReportTime`, py`estimate`cs`Estimate` |
| `EODHDUpcomingIPOs` | Upcoming IPOs | py`name`cs`Name`, py`exchange`cs`Exchange`, py`ipo_date`cs`IpoDate`, py`filing_date`cs`FilingDate`, py`amended_date`cs`AmendedDate`, py`lowest_price`cs`LowestPrice`, py`highest_price`cs`HighestPrice`, py`offer_price`cs`OfferPrice`, py`shares`cs`Shares`, py`deal_type`cs`DealType` |
| `EODHDUpcomingSplits` | Upcoming Splits | py`split_date`cs`SplitDate`, py`optionable`cs`Optionable`, py`split_factor`cs`SplitFactor` |
| `USEnergy` | US Energy Information Administration (EIA) | py`value`cs`Value` |
| `Fred` | US Federal Reserve (FRED) | py`value`cs`Value` |
| `QuiverGovernmentContract` | US Government Contracts | py`description`cs`Description`, py`agency`cs`Agency`, py`amount`cs`Amount` |
| `SECReport10K` | US SEC Filings | py`report`cs`Report` |
| `SECReport10Q` | US SEC Filings | py`report`cs`Report` |
| `SECReport8K` | US SEC Filings | py`report`cs`Report` |
| `USTreasuryYieldCurveRate` | US Treasury Yield Curve | py`one_month`cs`OneMonth`, py`two_month`cs`TwoMonth`, py`three_month`cs`ThreeMonth`, py`six_month`cs`SixMonth`, py`one_year`cs`OneYear`, py`two_year`cs`TwoYear`, py`three_year`cs`ThreeYear`, py`five_year`cs`FiveYear`, py`seven_year`cs`SevenYear`, py`ten_year`cs`TenYear`, py`twenty_year`cs`TwentyYear`, py`thirty_year`cs`ThirtyYear` |
| `VIXCentralContango` | VIX Central Contango | py`front_month`cs`FrontMonth`, py`f_1`cs`F1`, py`f_2`cs`F2`, py`f_3`cs`F3`, py`f_4`cs`F4`, py`f_5`cs`F5`, py`f_6`cs`F6`, py`f_7`cs`F7`, py`f_8`cs`F8`, py`f_9`cs`F9`, py`f_10`cs`F10`, py`f_11`cs`F11`, py`f_12`cs`F12`, py`contango_f_2_minus_f_1`cs`Contango_F2_Minus_F1`, py`contango_f_7_minus_f_4`cs`Contango_F7_Minus_F4`, py`contango_f_7_minus_f_4_div_3`cs`Contango_F7_Minus_F4_Div_3`, py`period`cs`Period` |
| `QuiverWallStreetBets` | WallStreetBets | py`date`cs`Date`, py`mentions`cs`Mentions`, py`rank`cs`Rank`, py`sentiment`cs`Sentiment` |

## List Type

Indexing py`slice[dataset_symbol]`cs`slice[_datasetSymbol]` returns the listed outer class; iterate it and read properties off each element.

```python
def on_data(self, slice: Slice) -> None:
    if slice.contains_key(self.dataset_symbol):
        for data_point in slice[self.dataset_symbol]:
            self.log(f"{data_point.<property>}")
```

```csharp
public override void OnData(Slice slice)
{
    if (slice.ContainsKey(_datasetSymbol))
    {
        foreach (var dataPoint in slice[_datasetSymbol])
        {
            Log($"{dataPoint.<Property>}");
        }
    }
}
```

| Class | Dataset | Properties |
| --- | --- | --- |
| `QuiverCNBCs`->`QuiverCNBC` | CNBC Trading | py`notes`cs`Notes`, py`direction`cs`Direction`, py`traders`cs`Traders` |
| `QuiverLobbyings`->`QuiverLobbying` | Corporate Lobbying | py`client`cs`Client`, py`issue`cs`Issue`, py`specific_issue`cs`SpecificIssue`, py`amount`cs`Amount` |
| `EODHDEconomicEvents`->`EODHDEconomicEvent` | Economic Events | py`event_type`cs`EventType`, py`event_period`cs`EventPeriod`, py`country`cs`Country`, py`event_time`cs`EventTime`, py`previous`cs`Previous`, py`estimate`cs`Estimate` |
| `EODHDMacroIndicators`->`EODHDMacroIndicator` | Macroeconomics Indicators | py`indicator`cs`Indicator`, py`country`cs`Country`, py`frequency`cs`Frequency` |
| `ExtractAlphaTrueBeats`->`ExtractAlphaTrueBeat` | True Beats | py`fiscal_period`cs`FiscalPeriod`, py`earnings_metric`cs`EarningsMetric`, py`analyst_estimates_count`cs`AnalystEstimatesCount`, py`true_beat`cs`TrueBeat`, py`expert_beat`cs`ExpertBeat`, py`trend_beat`cs`TrendBeat`, py`management_beat`cs`ManagementBeat` |
| `QuiverCongress`->`QuiverCongressDataPoint` | US Congress Trading | py`record_date`cs`RecordDate`, py`updated_at`cs`UpdatedAt`, py`report_date`cs`ReportDate`, py`transaction_date`cs`TransactionDate`, py`representative`cs`Representative`, py`transaction`cs`Transaction`, py`amount`cs`Amount`, py`maximum_amount`cs`MaximumAmount`, py`house`cs`House`, py`party`cs`Party`, py`district`cs`District`, py`state`cs`State` |
| `RegalyticsRegulatoryArticles`->`RegalyticsRegulatoryArticle` | US Regulatory Alerts - Financial Sector | py`id`cs`Id`, py`title`cs`Title`, py`summary`cs`Summary`, py`status`cs`Status`, py`classification`cs`Classification`, py`filing_type`cs`FilingType`, py`in_federal_register`cs`InFederalRegister`, py`federal_register_number`cs`FederalRegisterNumber`, py`docket_file_number`cs`DocketFileNumber`, py`sec_release_number`cs`SecReleaseNumber`, py`proposed_comments_due_date`cs`ProposedCommentsDueDate`, py`original_publication_date`cs`OriginalPublicationDate`, py`federal_register_publication_date`cs`FederalRegisterPublicationDate`, py`rule_effective_date`cs`RuleEffectiveDate`, py`sourced_at`cs`SourcedAt`, py`latest_update`cs`LatestUpdate`, py`alert_type`cs`AlertType`, py`states`cs`States`, py`agencies`cs`Agencies`, py`sector`cs`Sector`, py`announcement_url`cs`AnnouncementUrl`, py`created_at`cs`CreatedAt` |
| `USDAFruitAndVegetables`->`USDAFruitAndVegetable` | USDA Fruit And Vegetables | py`form`cs`Form`, py`average_retail_price`cs`AverageRetailPrice`, py`unit`cs`Unit`, py`preparation_yield_factor`cs`PreparationYieldFactor`, py`cup_equivalent_size`cs`CupEquivalentSize`, py`cup_equivalent_unit`cs`CupEquivalentUnit`, py`price_per_cup_equivalent`cs`PricePerCupEquivalent` |

## Common mistakes

- Iterating a single-point class — that index returns one object; read py`.property`cs`.Property` directly.
- Reading py`.property`cs`.Property` on a list-class index — iterate first, then read each element.
- Skipping the py`slice.contains_key`cs`slice.ContainsKey` guard — alt-data only lands when there's an event, so the slice may not carry the symbol every bar.
- Two list-class wrappers iterate through a sub-attribute, not the value itself: `USDAFruitAndVegetables` (py`collection.data`cs`collection.Data`) and `EODHDEconomicEvents` (py`slice.get(EODHDEconomicEvents).get(symbol)`cs`slice.Get<EODHDEconomicEvents>().TryGetValue(symbol, out var events)`).
