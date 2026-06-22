---
name: alternative-data
description: Use when subscribing to a QuantConnect/LEAN alternative-data class via `AddData<AltClass>(symbol)` and reading the result from `slice` in `OnData`. Triggers — "is this dataset a list or single point per bar", "why does iterating slice[dataset_symbol] fail", "why does .property error on a Quiver/RegAlytics/EODHDEconomicEvents value", missing-attribute errors after `slice[_datasetSymbol]`. Skip when — the dataset is a universe (use alternative-data-universes), Morningstar fundamentals, ETF constituents, or the price feed comes through `AddEquity` / `AddOption` instead of `AddData`.
---

# Alternative Data Classes

Subscribe with `AddData<AltClass>(symbol)` and read from `slice` in `OnData`. Indexing `slice[_datasetSymbol]` returns either a single data point or a list of data points per bar — see the tables below.

## Single Data Point

Indexing `slice[_datasetSymbol]` returns a single object; read properties directly.

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
| `BenzingaNews` | Benzinga News Feed | `Id`, `Author`, `CreatedAt`, `UpdatedAt`, `Title`, `Teaser`, `Contents`, `Categories`, `Symbols`, `Tags` |
| `BitcoinMetadata` | Bitcoin Metadata | `Difficulty`, `MyWalletNumberofUsers`, `AverageBlockSize`, `BlockchainSize`, `MedianTransactionConfirmationTime`, `MinersRevenue`, `HashRate`, `CostPerTransaction`, `CostPercentofTransactionVolume`, `EstimatedTransactionVolumeUSD`, `EstimatedTransactionVolume`, `TotalOutputVolume`, `NumberofTransactionperBlock`, `NumberofUniqueBitcoinAddressesUsed`, `NumberofTransactionsExcludingPopularAddresses`, `TotalNumberofTransactions`, `NumberofTransactions`, `TotalTransactionFeesUSD`, `TotalTransactionFees`, `MarketCapitalization`, `TotalBitcoins`, `MyWalletNumberofTransactionPerDay`, `MyWalletTransactionVolume` |
| `BrainCompanyFilingLanguageMetrics10K` | Brain Language Metrics on Company Filings | `ReportDate`, `ReportCategory`, `ReportPeriod`, `PreviousReportDate`, `PreviousReportCategory`, `PreviousReportPeriod`, `ReportSentiment`, `RiskFactorsStatementSentiment`, `ManagementDiscussionAnalyasisOfFinancialConditionAndResultsOfOperations` |
| `BrainCompanyFilingLanguageMetricsAll` | Brain Language Metrics on Company Filings | `ReportDate`, `ReportCategory`, `ReportPeriod`, `PreviousReportDate`, `PreviousReportCategory`, `PreviousReportPeriod`, `ReportSentiment`, `RiskFactorsStatementSentiment`, `ManagementDiscussionAnalyasisOfFinancialConditionAndResultsOfOperations` |
| `BrainStockRanking10Day` | Brain ML Stock Ranking | `Rank` |
| `BrainStockRanking21Day` | Brain ML Stock Ranking | `Rank` |
| `BrainStockRanking2Day` | Brain ML Stock Ranking | `Rank` |
| `BrainStockRanking3Day` | Brain ML Stock Ranking | `Rank` |
| `BrainStockRanking5Day` | Brain ML Stock Ranking | `Rank` |
| `BrainSentimentIndicator30Day` | Brain Sentiment Indicator | `TotalArticleMentions`, `SentimentalArticleMentions`, `Sentiment`, `TotalBuzzVolume`, `SentimentalBuzzVolume` |
| `BrainSentimentIndicator7Day` | Brain Sentiment Indicator | `TotalArticleMentions`, `SentimentalArticleMentions`, `Sentiment`, `TotalBuzzVolume`, `SentimentalBuzzVolume` |
| `BrainWikipediaPageViews` | Brain Wikipedia Page Views | `NumberViews1`, `Buzz1`, `NumberViews7`, `Buzz7`, `NumberViews30`, `Buzz30` |
| `KavoutCompositeFactorBundle` | Composite Factor Bundle | `Growth`, `ValueFactor`, `Quality`, `Momentum`, `LowVolatility` |
| `SmartInsiderIntention` | Corporate Buybacks | `Execution`, `ExecutionEntity`, `ExecutionHolding`, `Amount`, `ValueCurrency`, `AmountValue`, `Percentage`, `AuthorizationStartDate`, `AuthorizationEndDate`, `PriceCurrency`, `MinimumPrice`, `MaximumPrice`, `NoteText`, `TransactionID`, `EventType`, `LastUpdate`, `LastIDsUpdate`, `ISIN`, `USDMarketCap`, `CompanyID`, `ICBIndustry`, `ICBSuperSector`, `ICBSector`, `ICBSubSector`, `ICBCode`, `CompanyName`, `PreviousResultsAnnouncementDate`, `NextResultsAnnouncementsDate`, `NextCloseBegin`, `LastCloseEnded`, `SecurityDescription`, `TickerCountry`, `TickerSymbol`, `AnnouncementDate`, `TimeReleased`, `TimeProcessed`, `TimeReleasedUtc`, `TimeProcessedUtc`, `AnnouncedIn` |
| `SmartInsiderTransaction` | Corporate Buybacks | `BuybackDate`, `Execution`, `ExecutionEntity`, `ExecutionHolding`, `Currency`, `ExecutionPrice`, `Amount`, `GBPValue`, `EURValue`, `USDValue`, `NoteText`, `BuybackPercentage`, `VolumePercentage`, `ConversionRate`, `AmountAdjustedFactor`, `PriceAdjustedFactor`, `TreasuryHolding`, `TransactionID`, `EventType`, `LastUpdate`, `LastIDsUpdate`, `ISIN`, `USDMarketCap`, `CompanyID`, `ICBIndustry`, `ICBSuperSector`, `ICBSector`, `ICBSubSector`, `ICBCode`, `CompanyName`, `PreviousResultsAnnouncementDate`, `NextResultsAnnouncementsDate`, `NextCloseBegin`, `LastCloseEnded`, `SecurityDescription`, `TickerCountry`, `TickerSymbol`, `AnnouncementDate`, `TimeReleased`, `TimeProcessed`, `TimeReleasedUtc`, `TimeProcessedUtc`, `AnnouncedIn` |
| `ExtractAlphaCrossAssetModel` | Cross Asset Model | `Spread`, `Skew`, `VolumeComponent`, `Score`, `ScoreSlow` |
| `CoinGecko` | Crypto Market Cap | `Coin`, `Volume`, `MarketCap` |
| `NasdaqCustomColumns` | Data Link | `Value` |
| `NasdaqDataLink` | Data Link | `Period` |
| `EstimizeConsensus` | Estimize | `Id`, `Source`, `Type`, `Mean`, `High`, `Low`, `StandardDeviation`, `Count`, `UpdatedAt`, `FiscalYear`, `FiscalQuarter` |
| `EstimizeEstimate` | Estimize | `Id`, `Ticker`, `FiscalYear`, `FiscalQuarter`, `CreatedAt`, `Eps`, `Revenue`, `UserName`, `AnalystId`, `Flagged` |
| `EstimizeRelease` | Estimize | `Id`, `FiscalYear`, `FiscalQuarter`, `ReleaseDate`, `Eps`, `Revenue`, `WallStreetEpsEstimate`, `WallStreetRevenueEstimate`, `ConsensusEpsEstimate`, `ConsensusRevenueEstimate`, `ConsensusWeightedEpsEstimate`, `ConsensusWeightedRevenueEstimate` |
| `FearGreedIndex` | Fear and Greed | `Spx`, `SpxSma`, `MarketMomentum`, `StocksAtYearlyHighs`, `StocksAtYearlyLows`, `TotalForStrength`, `NetYearlyHighsAndLows`, `StockPriceStrength`, `StocksUpDaily`, `StocksDownDaily`, `TotalForBreadth`, `McClellanSummationIndex`, `StockPriceBreadth`, `PutCallRatioDaily`, `PutCallRatioSma`, `PutCallRatioNormalized`, `Vix`, `VixSma`, `MarketVolatility`, `StockReturns`, `BondReturns`, `StockBondReturnDifference`, `SafeHavenDemand`, `JunkBondYield`, `InvestmentGradeBondYield`, `BondYieldSpread`, `JunkBondDemand`, `QCIndex`, `CNNIndex` |
| `QuiverInsiderTrading` | Insider Trading | `Date`, `FileDate`, `TransactionCode`, `PricePerShare`, `Shares`, `SharesOwnedFollowing`, `AcquiredDisposedCode`, `DirectOrIndirectOwnership`, `Name`, `OfficerTitle`, `IsDirector`, `IsOfficer`, `IsTenPercentOwner`, `IsOther` |
| `ExtractAlphaTacticalModel` | Tactical | `Reversal`, `FactorMomentum`, `LiquidityShock`, `Seasonality`, `Score` |
| `TiingoNews` | Tiingo News Feed | `Source`, `CrawlDate`, `Url`, `PublishedDate`, `Tags`, `Description`, `Title`, `ArticleID`, `Symbols` |
| `EODHDUpcomingDividends` | Upcoming Dividends | `DividendDate`, `DeclarationDate`, `ReportDate`, `PaymentDate`, `Dividend` |
| `EODHDUpcomingEarnings` | Upcoming Earnings | `ReportDate`, `ReportTime`, `Estimate` |
| `EODHDUpcomingIPOs` | Upcoming IPOs | `Name`, `Exchange`, `IpoDate`, `FilingDate`, `AmendedDate`, `LowestPrice`, `HighestPrice`, `OfferPrice`, `Shares`, `DealType` |
| `EODHDUpcomingSplits` | Upcoming Splits | `SplitDate`, `Optionable`, `SplitFactor` |
| `BLSEconomicSurveysCes` | US Bureau of Labor Statistics (BLS) | `TotalNonfarm`, `TotalPrivate`, `AverageHourlyEarnings`, `AverageWeeklyHours`, `AverageWeeklyEarnings`, `ProductionHourlyEarnings`, `ProductionEmployees`, `Manufacturing`, `GoodsProducing`, `PrivateServiceProviding`, `Construction`, `RetailTrade`, `FinancialActivities`, `EducationAndHealthServices`, `LeisureAndHospitality`, `MiningAndLogging` |
| `BLSEconomicSurveysCpi` | US Bureau of Labor Statistics (BLS) | `AllItems`, `CoreCpi`, `Food`, `FoodAtHome`, `FoodAwayFromHome`, `Energy`, `Shelter`, `RentOfPrimaryResidence`, `Gasoline`, `MedicalCare`, `Apparel`, `EducationAndCommunication`, `NewVehicles`, `UsedCarsAndTrucks`, `CollegeTuitionAndFees` |
| `BLSEconomicSurveysJolts` | US Bureau of Labor Statistics (BLS) | `JobOpenings`, `JobOpeningsRate`, `Hires`, `HiresRate`, `Quits`, `QuitsRate`, `TotalSeparations`, `LayoffsAndDischarges` |
| `BLSEconomicSurveysPpi` | US Bureau of Labor Statistics (BLS) | `FinalDemand`, `CorePpi`, `FinalDemandLessFoodEnergyTrade`, `FinalDemandGoods`, `FinalDemandServices`, `FinalDemandConstruction`, `AllCommodities`, `FarmProducts`, `ProcessedFoodsAndFeeds`, `CrudePetroleum`, `FinalDemandGoodsLessFoods` |
| `USEnergy` | US Energy Information Administration (EIA) | `Value` |
| `Fred` | US Federal Reserve (FRED) | `Value` |
| `QuiverGovernmentContract` | US Government Contracts | `Description`, `Agency`, `Amount` |
| `SECReport10K` | US SEC Filings | `Report` |
| `SECReport10Q` | US SEC Filings | `Report` |
| `SECReport8K` | US SEC Filings | `Report` |
| `USTreasuryYieldCurveRate` | US Treasury Yield Curve | `OneMonth`, `TwoMonth`, `ThreeMonth`, `SixMonth`, `OneYear`, `TwoYear`, `ThreeYear`, `FiveYear`, `SevenYear`, `TenYear`, `TwentyYear`, `ThirtyYear` |
| `VIXCentralContango` | VIX Central Contango | `FrontMonth`, `F1`, `F2`, `F3`, `F4`, `F5`, `F6`, `F7`, `F8`, `F9`, `F10`, `F11`, `F12`, `Contango_F2_Minus_F1`, `Contango_F7_Minus_F4`, `Contango_F7_Minus_F4_Div_3`, `Period` |

## List Type

Indexing `slice[_datasetSymbol]` returns the listed outer class; iterate it and read properties off each element.

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
| `QuiverCNBCs`->`QuiverCNBC` | CNBC Trading | `Notes`, `Direction`, `Traders`, `AdviceDate` |
| `QuiverLobbyings`->`QuiverLobbying` | Corporate Lobbying | `Client`, `Issue`, `SpecificIssue`, `Amount` |
| `EODHDEconomicEvents`->`EODHDEconomicEvent` | Economic Events | `EventType`, `EventPeriod`, `Country`, `EventTime`, `Previous`, `Estimate` |
| `EODHDMacroIndicators`->`EODHDMacroIndicator` | Macroeconomics Indicators | `Indicator`, `Country`, `Frequency` |
| `ExtractAlphaTrueBeats`->`ExtractAlphaTrueBeat` | True Beats | `FiscalPeriod`, `EarningsMetric`, `AnalystEstimatesCount`, `TrueBeat`, `ExpertBeat`, `TrendBeat`, `ManagementBeat` |
| `QuiverCongress`->`QuiverCongressDataPoint` | US Congress Trading | `RecordDate`, `UpdatedAt`, `ReportDate`, `TransactionDate`, `Representative`, `Transaction`, `Amount`, `MaximumAmount`, `House`, `Party`, `District`, `State` |
| `RegalyticsRegulatoryArticles`->`RegalyticsRegulatoryArticle` | US Regulatory Alerts - Financial Sector | `Id`, `Title`, `Summary`, `Status`, `Classification`, `FilingType`, `InFederalRegister`, `FederalRegisterNumber`, `DocketFileNumber`, `SecReleaseNumber`, `ProposedCommentsDueDate`, `OriginalPublicationDate`, `FederalRegisterPublicationDate`, `RuleEffectiveDate`, `SourcedAt`, `LatestUpdate`, `AlertType`, `States`, `Agencies`, `Sector`, `AnnouncementUrl`, `CreatedAt` |
| `USDAFruitAndVegetables`->`USDAFruitAndVegetable` | USDA Fruit And Vegetables | `Form`, `AverageRetailPrice`, `Unit`, `PreparationYieldFactor`, `CupEquivalentSize`, `CupEquivalentUnit`, `PricePerCupEquivalent` |

## Common mistakes

- Iterating a single-point class — that index returns one object; read `.Property` directly.
- Reading `.Property` on a list-class index — iterate first, then read each element.
- Skipping the `slice.ContainsKey` guard — alt-data only lands when there's an event, so the slice may not carry the symbol every bar.
- Two list-class wrappers iterate through a sub-attribute, not the value itself: `USDAFruitAndVegetables` (`collection.Data`) and `EODHDEconomicEvents` (`slice.Get<EODHDEconomicEvents>().TryGetValue(symbol, out var events)`).
