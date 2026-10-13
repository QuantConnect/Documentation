# LEAN Engine FAQ

This file answers common questions about the LEAN engine, contributions, the data format, and the capacity statistic. Each answer links to the full docs page.

## Getting Started

### What is LEAN and which programming languages does it support?

LEAN is the open-source algorithmic trading engine for research, backtesting, and live trading that powers QuantConnect. Its core is written in C#, and it runs on Linux, Mac, and Windows. You can write LEAN algorithms in Python 3.11 or C#.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/getting-started>

### What is the easiest way to run LEAN on my own computer?

We recommend the LEAN CLI for local development. The LEAN CLI runs your algorithms on your machine with the official LEAN Docker images and your own data. It also lets you deploy to QuantConnect Cloud and access LEAN data.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/getting-started#03-Developing-with-Lean-CLI>

### How do I install and build LEAN from source?

Download the zip file of the latest `master` branch of the LEAN GitHub repository, or clone the repository with Git. On Windows, open `QuantConnect.Lean.sln` in Visual Studio, build the solution, and press F5 to run it. On Mac and Linux, build and run the solution with the .NET SDK from Visual Studio Code or the command line.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/getting-started#04-Installation-Instructions>

### Can I use Visual Studio for Mac to develop LEAN?

Microsoft discontinued Visual Studio for Mac, so use Visual Studio Code instead. Install Visual Studio Code, the C# Dev Kit extension, and the .NET SDK, and then open the LEAN directory. To run the solution, press F5 or choose **Run and Debug** from the Activity Bar.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/getting-started#04-Installation-Instructions>

### How do I set up Python algorithms in a local LEAN installation?

The `Algorithm.Python` project in the LEAN repository explains the full Python installation process. Follow its readme to configure Python for your local build. To avoid manual setup, use the LEAN CLI, which runs Python algorithms in the official Docker images.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/getting-started#04-Installation-Instructions>

### Why do I get a connection refused error when local LEAN connects to Interactive Brokers?

Set the `ib-tws-dir` and `ib-controller-dir` fields in the `config.json` file to the paths of your TWS and IBController folders. If the error persists, change the `ib-port` field from 4002 to 4001 to match the port in your IB Gateway or TWS settings.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/getting-started#04-Installation-Instructions>

### How is the LEAN engine organized?

LEAN is a set of modular plugins that you can extend without touching other files. The main plugins are the result handler (`IResultHandler`), data feed (`IDataFeed`), transaction handler (`ITransactionHandler`), real-time handler (`IRealtimeHandler`), and setup handler (`ISetupHandler`). You configure them in the `config.json` file of the Launcher project.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/getting-started#02-System-Overview>

### How does LEAN switch between backtesting and live trading?

The `config.json` file groups the plugins into named environments, and the environment you select sets the mode LEAN runs in. In backtests, the data feed reads files from disk and the real-time handler runs on simulated time. In live trading, the data feed connects to a stream and the transaction handler sends orders to a real brokerage.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/getting-started#02-System-Overview>

### How do I request a feature or report a bug in LEAN?

Create a GitHub Issue on the LEAN repository and react to it with a thumbs up emoji to add it to the roadmap. The QuantConnect team gives priority to the feature requests and bugs with the most votes, so vote for existing issues that matter to you.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/getting-started#05-Roadmap>

### How can I sponsor LEAN development?

To become a QuantConnect sponsor, see the QuantConnect Sponsorship page on GitHub. Sponsorship funds support LEAN infrastructure, free educational material, community support, more data in the Dataset Market, and more brokerage connections.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/getting-started#06-Sponsorships>

## Contributing Datasets

### How do I get my dataset listed in the QuantConnect Dataset Market?

Contact the QuantConnect team for a quick review, and then follow the dataset contribution tutorial to build, process, test, and document the dataset. Datasets must be well defined, with realistic point-in-time timestamps. Ideally, a dataset has at least a 2-year track record, a reputable maintainer, full documentation, and code examples.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/contributions/datasets/key-concepts>

### Where do I start building a new dataset integration?

Create a repository from the `Lean.DataSource.SDK` template on GitHub and name it `Lean.DataSource.<vendorNameDatasetName>`. Then run the `renameDataset.sh` script to replace the placeholder names. Start from the SDK template instead of an existing data source because we update the SDK periodically.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/contributions/datasets/defining-data-models>

### What file format must a contributed dataset use?

The input to your data model must be one or more CSV files in chronological order. QuantConnect hosts your data, so the `get_source` (`GetSource`) method must return a `SubscriptionDataSource` with the `SubscriptionTransportMedium.LocalFile` transport medium and the `FileFormat.Csv` format.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/contributions/datasets/key-concepts#04-Data-Sources>

### Which timestamp should each data point in my dataset have?

Set `end_time` (`EndTime`) to the time the data point first became available for consumption. If you don't set `time` (`Time`), it defaults to `end_time`. In universe files, the file date must be the date the data is available, and you set `time` to that date minus the period.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/contributions/datasets/defining-data-models>

### What is a linked dataset and when do I set RequiresMapping?

A dataset is linked when it describes specific securities, such as the closing price of AAPL or the page views of the AAPL Wikipedia page. A linked dataset must map to its underlying assets through time. Set `RequiresMapping` to true so LEAN handles the security and ticker mapping.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/contributions/datasets/key-concepts#08-Linked-Datasets>

### How do I make Equity tickers in my dataset point-in-time?

If your Equity-linked data ignores ticker changes, adjust the historical tickers in the processing script with a map file provider and a security identifier. This step needs the US Equity Security Master dataset. If you don't have it, contact QuantConnect.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/contributions/datasets/rendering-data/rendering-data-with-python>

### Which time zone should my dataset classes declare?

The `DataTimeZone` method of your data source class declares the time zone of your dataset. If your dataset has trading data and universe data, the `DataTimeZone` methods of both classes must return the same time zone.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/contributions/datasets/key-concepts#06-TimeZones>

### Can I write the data processing script in Python, C#, or a notebook?

Yes, the contribution tutorial covers a Python script, a C# program, and a Jupyter notebook. Each option saves the full history to the `output` directory in your machine's root directory and a sample to the repository `output` directory. Your pull request must contain the sample data so we can review it and run the demonstration algorithms.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/contributions/datasets/rendering-data/rendering-data-with-python>

### What resolution should universe selection data have?

If your universe selection data has a higher frequency than hourly, resample it to hourly or daily resolution in your processing script. Process the data period by period, using the date range the script receives.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/contributions/datasets/rendering-data/rendering-data-with-python>

### How do I test a dataset before I submit it?

Build the data source project and copy its `output` data into the `Lean/Data` directory. Then backtest the C# and Python demonstration algorithms in a local copy of LEAN. The backtests must run without errors. Then define the `CreateNewInstance` method in the test file and run the unit tests.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/contributions/datasets/testing-data-models>

### Which dataset properties must I document?

Process the entire dataset to collect its start date, asset coverage, data density, resolution, time zone, processing schedule, full processing duration, and daily update duration. Time the full run and a one-day update in your processing script because we use these times to schedule the processing job. Fill in the `listing-about.md` and `listing-documentation.md` files.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/contributions/datasets/data-documentation>

### What happens after QuantConnect accepts my dataset?

We create a page for the dataset in the Dataset Market. You can then use the dataset in QuantConnect Cloud algorithms and contribute an example algorithm for the listing. After the listing is complete, we add the dataset to the downloading data tutorial.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/contributions/datasets/data-documentation>

## Contributing Brokerages

### How can I connect LEAN to a brokerage it doesn't support yet?

You can build a brokerage plugin and contribute it to LEAN. Fork LEAN, create a repository from the `Lean.Brokerages.Template` template named `Lean.Brokerages.<brokerageName>`, and run the `renameBrokerage.sh` script. You need a working knowledge of C#. Some later chapters of the brokerage development guide are still under construction.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/contributions/brokerages/setting-up-your-environment>

### How does LEAN choose which brokerage implementation to create?

LEAN reads the brokerage name from the `live-mode-brokerage` value in the configuration file and uses it to create the matching `IBrokerageFactory`. The factory builds the brokerage from the job packet. Values in the job packet override the defaults that the factory loads from `config.json`.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/contributions/brokerages/laying-the-foundation>

### Does a LEAN brokerage need a streaming connection?

LEAN works best with streaming or socket-based brokerage connections because they translate broker events into LEAN events easily. Without streaming order events, you must poll for fills, which adds risks and challenges.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/contributions/brokerages/creating-the-brokerage>

### Can I use my broker's SDK library in a LEAN brokerage plugin?

Yes, if the SDK has a permissive license and is compatible with the .NET version LEAN uses. Copy the library into its own subfolder of the brokerage project instead of adding a Git submodule. Keep the original comments and headers, or add a header that references the source.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/contributions/brokerages/creating-the-brokerage>

### How should a brokerage plugin handle disconnections and errors?

For a soft failure, such as a lost internet connection or a 502 server error, create a `BrokerageMessageEvent` so the algorithm can handle the message. For a hard failure, such as an incorrect password or an unsupported API method, throw an exception with the error details.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/contributions/brokerages/creating-the-brokerage>

### What should the PlaceOrder method of a brokerage return?

`PlaceOrder` returns true when the brokerage accepts the order. It returns false if the order is invalid, the brokerage rejects it immediately, or an internet outage occurs. Check `IsConnected` before you send the order, and throw an exception if the brokerage isn't connected.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/contributions/brokerages/creating-the-brokerage>

### Is there a test harness for a new brokerage implementation?

Yes. Create a test class that extends the base class in `Lean/Tests/Brokerages/BrokerageTests.cs`. This test framework tests all the methods of an `IBrokerage` implementation, so you can follow a test-driven workflow.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/contributions/brokerages/creating-the-brokerage>

## Contributing Indicators

### How do I contribute a new indicator to LEAN?

First, create a GitHub Issue on the LEAN repository with a description of the indicator, links to resources, and the source of third-party values. Then add the indicator class to `Lean/Indicators`, a helper method to `QCAlgorithm.Indicators.cs`, unit tests, and a pull request to the Documentation repository. The LEAN GitHub Issues also list indicators that community members have requested.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/contributions/indicators>

### Why does an indicator pull request need third-party values?

Third-party values validate that your indicator implementation is correct. Include values from a renowned open-source project, such as TA-Lib or QuantLib, or from a highly credible website. Save the values as a CSV file in `Lean/Tests/TestData` for the unit tests.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/contributions/indicators#02-Get-Third-Party-Values>

### Which base class should my new indicator inherit?

Data point indicators inherit `IndicatorBase<IndicatorDataPoint>` or `WindowIndicator<IndicatorDataPoint>`. Bar indicators use `QuoteBar` or `TradeBar` objects, so they work for Forex and CFD securities that have no trade data. TradeBar indicators use `TradeBar` objects, often for their volume.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/contributions/indicators>

### How do I support warm-up and automatic updates in a new indicator?

Define the `WarmUpPeriod` and `IsReady` properties, and implement the `IIndicatorWarmUpPeriodProvider` interface so the `WarmUpIndicator` method can warm it up. For automatic updates, add a helper method that calls `InitializeIndicator` to create a consolidator and register the indicator.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/contributions/indicators>

### How does an indicator discard invalid values?

Override the `ValidateAndComputeNextValue` method to return an `IndicatorResult` with an `IndicatorStatus` value. If the status marks the value as invalid, LEAN doesn't pass the value to the algorithm. For example, a moving average can produce unexpected values from extreme quotes.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/contributions/indicators>

### How do I add a new moving average type?

Add a member to the `MovingAverageType` enumeration and a matching case in each `AsIndicator` method of `MovingAverageTypeExtensions.cs`. Then add a test case to `MovingAverageTypeExtensionsTests.cs` that checks the abstraction methods create your indicator.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/contributions/indicators#10-Extra-Steps-for-Moving-Average-Types>

## Data Format

### What file format does LEAN use for market data?

LEAN reads flat files on disk in an open, human-readable format that needs no database. Each data file is a CSV file in a zip archive. Each line is one data point, and the CSV files have no header row.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/data-format/key-concepts>

### How are LEAN data folders organized?

LEAN organizes data by security type, market, and resolution. Tick, second, and minute data use one zip file per ticker and day at `<securityType>/<market>/<resolution>/<ticker>/<date>_<tickType>.zip`. Hour and daily data use one zip file for many days at `<securityType>/<market>/<resolution>/<ticker>.zip`.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/data-format/key-concepts#02-Folder-Structure>

### Why are the prices in US Equity data files so large?

US Equity, Equity Options, and Index Options files store prices multiplied by 10,000, so a price of $644.80 is 6448000 in the file. LEAN divides these prices by 10,000 when it reads the files. Files of the other asset classes, including Index data, store the actual prices.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/data-format/key-concepts#03-Price-Representation>

### What does the first column of a LEAN data file mean?

The first column is the time. In tick, second, and minute files, it's the number of milliseconds since midnight of the file date, so consecutive minute bars differ by 60000. In hour and daily files, the time has the `YYYYMMDD HH:mm` format. The time of a bar is the start of its period.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/data-format/key-concepts#04-Time-Representation>

### Which time zone are the times in LEAN data files?

The times are in the data time zone of the security, which can differ from its exchange time zone. To find it, look up the `dataTimeZone` value in `market-hours/market-hours-database.json`. LEAN converts the times to the exchange time zone when it reads the files.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/data-format/key-concepts#04-Time-Representation>

### Why are some minutes missing from a LEAN data file?

If a security has no activity in a period, the file has no row for that period.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/data-format/key-concepts#03-Price-Representation>

### Why are the bid or ask columns empty in some quote bar rows?

If a period has no bid quotes, the four bid price columns are empty and the bid size is 0. The same rule applies to the ask side. The other side of the row still holds its open, high, low, close, and size.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/data-format/core-data-types#03-Quote-Bar>

### What are the columns of trade bar and quote bar rows?

A trade bar row has time, open, high, low, close, and volume. A quote bar row has time, then bid open, high, low, close, and size, then ask open, high, low, close, and size. These columns are the same for every asset class.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/data-format/core-data-types>

### Which data types does each asset class have?

The following list shows the data types in each asset class:

- US Equity: trade and quote data at tick, second, and minute resolution, and trade data only at hour and daily resolution.
- Equity Options, Index Options, Futures, and Future Options: trade, quote, and open interest data.
- Index: trade data only, with the index value in the price columns.
- Forex and CFD: quote data only.
- Crypto and Crypto Futures: trade and quote data.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/data-format/key-concepts>

### Why does a US Equity quote tick have a price of 0?

Each US Equity quote tick has data for one side of the book, and the other side has a price and size of 0. For example, an ask update has a bid price and bid size of 0.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/data-format/us-equity#04-Tick-Data>

### What do the Suspicious and Sale Condition columns in US Equity tick data mean?

The Suspicious column is 1 if the tick is suspicious and 0 otherwise. The Sale Condition column holds a hexadecimal bit mask of `TradeConditionFlags` for trades or `QuoteConditionFlags` for quotes. When LEAN builds bars, it excludes the prices of suspicious ticks and of ticks with some condition flags.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/data-format/us-equity#04-Tick-Data>

### How are option contract files named?

Each Equity Options or Index Options zip file holds one CSV file per contract. The CSV name includes the style, right, strike multiplied by 10,000, and expiry. For example, `20140606_aapl_minute_quote_american_call_10000000_20150117.csv` holds quotes of the AAPL $1,000 call that expires on January 17, 2015.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/data-format/equity-options#02-Folder-Structure>

### Where are SPX weekly (SPXW) option files?

Index Options folders use the ticker of the option, which can differ from the ticker of the underlying index. SPX weekly options use the `spxw` ticker, so their files are under `indexoption/usa/<resolution>/spxw`.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/data-format/index-options#02-Folder-Structure>

### What time zone does Index and Index Options data use?

The data time zone of Index data depends on the index. For example, SPX data uses America/Chicago, so its 9:30 AM New York open appears as 8:30 AM. Index Options data uses America/New_York, which differs from the America/Chicago exchange time zone.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/data-format/index>

### How are Futures data files organized?

Each Futures zip file holds one CSV file per contract, and the CSV name ends with the contract month in `YYYYMM` format. For example, `future/cme/minute/es/20131006_quote.zip` contains `20131006_es_minute_quote_201312.csv` for the December 2013 ES contract. Futures files store actual prices, and the cme and comex markets use the UTC data time zone.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/data-format/futures>

### How are Future Options data files organized?

Future Options folders include the contract month of the underlying Future at every resolution. The folder ticker is the option ticker, which can differ from the Future ticker, such as `og` for options on GC Futures. The data time zone is the data time zone of the underlying Future, such as UTC for ES Future Options.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/data-format/future-options>

### Why doesn't Forex or CFD data have volume?

Forex and CFD files have quote data only, so they have no trades and no trade volume. Forex and CFD files share one format and differ only in the security type folder. Their tick files hold the bid and ask prices only.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/data-format/forex-and-cfd>

### Why does Crypto data have a separate folder for each exchange?

The market folder separates assets that have the same ticker but trade on different exchanges. For example, BTCUSDT trades on several exchanges at slightly different prices, so each exchange has its own folder under `crypto`. Crypto data uses the UTC data time zone.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/data-format/crypto>

### What does the perp suffix mean in Crypto Futures file names?

The `perp` suffix marks a perpetual contract. For a contract with an expiry date, LEAN uses the contract month as the suffix instead. For example, `cryptofuture/binance/minute/adausdt/20221213_trade.zip` contains `20221213_adausdt_minute_trade_perp.csv`.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/data-format/crypto-futures>

## Statistics

### What does the strategy capacity statistic measure?

Capacity estimates how much capital a strategy can trade before market impact degrades its performance. LEAN takes a capacity snapshot at the end of each week. The reported value smooths the snapshots with an exponentially weighted model.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/statistics/capacity>

### How does LEAN estimate the capacity of each security?

After each order fill, LEAN records the dollar volume of the security for a period that depends on its resolution and liquidity. The strategy can use only part of this volume. The available portion is 2% for daily, 5% for hour, 20% for minute, and 50% for second and tick data.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/statistics/capacity>

### Why does my strategy capacity drop when I trade more often?

LEAN applies a fast trading volume discount factor to securities that the algorithm trades intraday. The more often the strategy trades a security, the lower its capacity, down to 20% of the undiscounted value. Frequent trading makes a large position harder to build without market impact.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/statistics/capacity>

### How does LEAN estimate capacity for Forex, CFD, and Crypto?

Forex and CFD data have no trade volume or quote size. LEAN treats them as deeply liquid assets with about $25,000,000 of depth per minute. For Crypto, LEAN estimates volume from the average size on the bid and ask.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/statistics/capacity>

### Why is my strategy capacity so low?

The weekly snapshot uses the security with the least available dollar volume, so one illiquid holding limits the capacity of the whole strategy. LEAN also scales the value down by the number of fills in that security and by its weight in the portfolio. LEAN records capacity data after order fills, so a week without fills adds no volume.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/statistics/capacity>

### How can I increase the capacity of my strategy?

Trade a large weight of the portfolio in liquid securities with high volume. Keep only a small portion of the portfolio in illiquid, low-volume assets.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/statistics/capacity>

## Class Reference

### Where can I find the LEAN class reference?

The LEAN class reference is on lean.io. You can also download the LEAN engine documentation as a PDF for Python or C# from the LEAN Engine landing page.

Docs: <https://www.lean.io/docs/v2/lean-engine/class-reference/index.html>
