# Research Environment FAQ

This file answers common questions about the QuantConnect Research Environment, and each answer links to the full documentation page.

## Key Concepts

### What is the Research Environment?

The Research Environment is a Jupyter notebook environment where you access QuantConnect data through the `QuantBook` class. It supports Python and C# notebooks. You can use it to test hypotheses, plot data, train machine learning models, and analyze backtest results before you write a backtest.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/key-concepts/getting-started>

### How do I start the Research Environment in QuantConnect Cloud?

To start the Research Environment, open a notebook file in one of your cloud projects. Each new project contains a `research.ipynb` notebook by default, which you can find in the Explorer panel. Opening the notebook connects it to a research node.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/research/getting-started>

### Why do I get "NameError: name 'QuantBook' is not defined" when I run the first cell?

This error occurs when you run cells before the notebook connects to the Jupyter server and kernel. The connection can take up to one minute. Wait until the kernel button in the top-right corner changes from `base (Python x.x.x)` to `Foundation-Py-Default` or `Foundation-C#-Default`, and then run the cells.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/research/getting-started>

### Why did my notebook cells stop responding?

In QuantConnect Cloud, notebook cells have a 15-minute timeout period before they become unresponsive. If this happens, restart the notebook to run cells again. To keep long computations safe, save intermediate results to the Object Store so you can reload them after a restart.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/research/getting-started>

### Should I test my idea in the Research Environment or in a backtest?

Test the idea in the Research Environment first. Backtests are event-driven simulations that are slow and inefficient for testing the foundation of a strategy. In research, you analyze data in batches to confirm a hypothesis with statistical analysis, and then you backtest the confirmed hypothesis.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/key-concepts/research-engine>

### What is QuantBook and where is its API reference?

`QuantBook` is a wrapper on `QCAlgorithm`, so it gives you every `QCAlgorithm` method plus a few research helpers. The extra helpers are `universe_history` (`UniverseHistory`), `future_history` (`FutureHistory`), and `option_history` (`OptionHistory`). For the rest of the API, use the `QCAlgorithm` reference.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/key-concepts/research-engine>

### Which backtesting features are not available in the Research Environment?

Event-driven features such as universe selection and `on_data` (`OnData`) events don't run in the Research Environment. Instead, you get data with history requests, and you can still create indicators, consolidate data, and plot charts. To see the assets a universe selects over time, use `universe_history`.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/key-concepts/research-engine>

### How do I import code from my project files into a notebook?

In a Python notebook, use the `import` statement with the name of the project file, such as `from helpers import my_method`. Importing project code is available only for Python. In C# notebooks, define the classes and methods you need directly in the notebook cells.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/key-concepts/research-engine>

### Why doesn't my notebook use the latest version of a project file I changed?

The research session keeps the version of the file it imported when the session started. To load the latest version, restart the Research Environment session. To restart it, stop the research node and then open the notebook again.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/key-concepts/research-engine>

### Why does my C# notebook say "The type or namespace name could not be found"?

This error occurs when you don't load the assembly files in the C# notebook. Load the assembly files and import the QuantConnect, Plotly.NET, Accord, and Deedle packages in their own cell before you run the rest of the notebook.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/key-concepts/research-engine>

### How do I stop a research node?

In QuantConnect Cloud, open the project and click the Resources icon in the right navigation menu. Then click the stop button next to the research node. You need stop node permissions to stop research nodes. For the local Research Environment, manage kernels with the JupyterLab interface.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/key-concepts/research-engine>

### Which research node does my notebook run on?

When you launch the Research Environment, it uses the best-performing research node by default, and you can select a specific node. CPU nodes are available on a fair usage basis. Up to three members can share a GPU node, so its available processing power depends on the server load.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/organizations/resources>

### Can two people edit the same notebook at the same time?

The Research Environment doesn't support simultaneous editing. A team member can view your notebook while you edit it, but only one person can edit it at a time. To let team members see your notebook, add them to the project.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/research/deployment>

## Initialization

### How do I set the date range for history requests in QuantBook?

The start date of your `QuantBook` sets the latest date of data that history requests return. By default, the start date is the current day. To analyze an earlier date, call `qb.set_start_date` (`SetStartDate`). Keep the end date later than the start date.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/initialization>

### Which time zone do notebook timestamps use?

The default notebook time zone is Eastern Time. The notebook time zone applies to the `datetime` arguments of history requests and to the `time` property of `Slice` objects. DataFrame timestamps and the `end_time` of each bar use the exchange time zone. To change the notebook time zone, call `qb.set_time_zone` (`SetTimeZone`).

Docs: <https://www.quantconnect.com/docs/v2/research-environment/initialization>

### How much data can I load into a notebook?

The Research Environment has no official data limit, but each security subscription requires about 5 MB of RAM. Larger research nodes let you request more data.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/initialization>

## Datasets

### What data can I access in the Research Environment?

You can access most of the Dataset Market in the Research Environment, including Equity, Crypto, Forex, and derivative data going back as far as 1998. To get the data, create a security subscription and then make a history request, the same way you do in a backtest.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/datasets/key-concepts>

### Do I need to add a security before I request its history?

Yes. You need a subscription before you can request historical data for a security. Call the subscription method for the asset class, such as `qb.add_equity` (`AddEquity`), and pass the returned `Symbol` to `qb.history` (`History`).

Docs: <https://www.quantconnect.com/docs/v2/research-environment/datasets/us-equity>

### Why does research history differ from the same request in a backtest?

In the Research Environment, all historical data is available at once. A history request for the last 10 days returns the 10 days before today. The same request in a backtest returns the 10 days before the algorithm time. This difference makes look-ahead bias easier to introduce in research.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/datasets/key-concepts>

### What resolution and bar type does a history request use by default?

The `history` method uses the resolution of your security subscription. If you have no matching subscription, it uses minute resolution. `TradeBar` is the default bar type. For assets without trade data, such as Forex and CFDs, request `QuoteBar` data.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/datasets/key-concepts>

### Should I request a DataFrame or data objects?

The DataFrame is the most popular return type, and LEAN unpacks `Slice` objects to build it. If you need `TradeBar` or `QuoteBar` objects, request that type directly, such as `qb.history[TradeBar](symbol, 10)`. Requesting a DataFrame and converting it back wastes computational resources.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/datasets/key-concepts>

### Why do daily US Equity bars appear on Saturday and skip Monday?

DataFrames index each bar by its end time, which is when the bar closes and the data becomes available. A daily bar for Friday ends after Friday's session, so its timestamp falls on Saturday. The bar for Monday's session has a Tuesday timestamp.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/datasets/key-concepts>

### Why does my history request return an empty DataFrame?

History requests return an empty DataFrame when no data exists for the period, and slicing an empty DataFrame throws an exception. Check `df.empty` before you slice the result. If you run the Research Environment locally, check that your data directory contains the data you request.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/datasets/key-concepts>

### How do I get 5-minute or 4-hour bars in a notebook?

Request data at a standard resolution and aggregate it. You can update a consolidator with the history results, or use the pandas `resample` method. Before you resample, call `reset_index` to drop the `Symbol` level of the index, and then apply a downsampling method such as `ohlc`.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/datasets/key-concepts>

### Why don't my history results include pre-market and after-hours data?

History requests use the extended market hours setting of your subscription, which is off by default. Pass `extended_market_hours=True` when you create the subscription or call `history`. Extended hours data is available only at minute, second, and tick resolution. Daily and hourly bars cover only regular trading hours.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/datasets/key-concepts>

### How do I turn off fill-forward in history results?

Fill-forward is the default data setting, so LEAN repeats the previous data point when a sample has no new data. To disable it, set `fill_forward=False` when you create the subscription or when you call `history`.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/datasets/key-concepts>

### Why does a multi-symbol tick request return only one tick per security?

When you request tick data for multiple securities, each `Ticks` object contains only the last tick of each security for that time slice. To get every tick, request tick history for one security at a time.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/datasets/key-concepts>

### Are US Equity prices in research adjusted for splits and dividends?

Yes. By default, LEAN adjusts US Equity data for splits and dividends. The adjusted modes use the full split and dividend history, so prices are the same regardless of the `QuantBook` time. To change the mode, pass a `data_normalization_mode` argument to `add_equity` or `history`.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/datasets/us-equity>

### What does "the start date will be adjusted if there are numerical precision errors in the factor files" mean?

Some securities have so many splits that LEAN can't calculate adjusted prices with correct numerical precision. For these securities, LEAN moves the start date of the history request forward. To get the full history, use raw data normalization.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/datasets/us-equity>

### How do I show tickers instead of security identifiers in the DataFrame index?

The `symbol` level of the DataFrame index shows the string form of each `SecurityIdentifier`. To show tickers, map each identifier string to its ticker in a dictionary. Then set the values of the `symbol` index level to the list of tickers.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/datasets/us-equity>

### How do I get a table of closing prices with one column per symbol?

Select the `close` column of the history DataFrame and call `unstack(level=0)`. The result has one column for each `Symbol` and one row for each timestamp.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/datasets/us-equity>

### How do I get historical fundamental data in research?

Call `history` with the `Fundamental` type to get all fundamental properties for the symbols you pass. Call it with the `Fundamentals` type to get data for every US Equity in the dataset, including companies that no longer trade. The data comes from the US Fundamental Data from Morningstar.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/datasets/equity-fundamental-data>

### How do I get historical prices for an Equity Option universe?

Subscribe to the underlying with raw data normalization, call `qb.add_option` (`AddOption`), and set a contract filter. Then call `qb.option_history` (`OptionHistory`) with a start and end date. The default filter selects contracts within one strike of the underlying price that expire within 35 days.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/datasets/equity-options/universes>

### How do I get daily Greeks, implied volatility, and open interest for a whole option chain?

Call `history` with the `OptionUniverse` type and the canonical option `Symbol`, such as `qb.history[OptionUniverse](option.symbol, start, end)`. The result contains the entire chain for each trading day, including prices, volume, open interest, implied volatility, and Greeks. Pass `flatten=True` to get a flat DataFrame.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/datasets/equity-options/universes>

### Why doesn't history for an individual option contract include Greeks or implied volatility?

History requests for individual contracts return only trade, quote, and open interest data. To get daily Greeks and implied volatility, use the `OptionUniverse` history request. For intraday values, create the option indicators for the contract and its mirror contract, and update them with history data.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/datasets/equity-options/individual-contracts>

### How do I get the option chain for a specific past date?

Set the `QuantBook` start date to the analysis date, and then call `qb.option_chain` (`OptionChain`) with the underlying `Symbol`. The method returns the contracts that were tradable on that date. Select the contracts you want and add them with `qb.add_option_contract` (`AddOptionContract`).

Docs: <https://www.quantconnect.com/docs/v2/research-environment/datasets/equity-options/individual-contracts>

### Why should I disable fill-forward for individual option contracts?

Option contracts have only a few open interest data points per day. To account for this, disable fill-forward when you call `add_option_contract` (`AddOptionContract`).

Docs: <https://www.quantconnect.com/docs/v2/research-environment/datasets/equity-options/individual-contracts>

### Which resolutions are available for Equity Option data?

Equity Option history is available at minute, hour, and daily resolution for trade and quote bars. Second resolution is available only in live trading, and tick data isn't available.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/datasets/equity-options/key-concepts>

### Are Equity Option prices adjusted for splits of the underlying?

No. LEAN doesn't adjust Equity Option data for splits and dividends of the underlying. The data normalization mode doesn't change Option history results.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/datasets/equity-options/key-concepts>

### How do I index an options DataFrame by contract Symbol only?

Option history DataFrames have index levels for expiry, strike, option right, contract `Symbol`, and time. Rows for the underlying leave some of these levels empty. Call `droplevel` to remove the first three levels, and then index the DataFrame with the contract `Symbol`.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/datasets/equity-options/universes>

### How do I get index option data such as SPXW weeklies?

Add the underlying index with `qb.add_index` (`AddIndex`). Then call `qb.add_index_option` (`AddIndexOption`) with the index `Symbol` and, for non-standard index options, the target option ticker such as `"SPXW"`. The only market for index options is `Market.USA`.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/datasets/index-options/universes>

### Why does `future_history` return no contracts?

The default Futures contract filter doesn't select any contracts. Call `set_filter` (`SetFilter`) on the `Future` object before you call `qb.future_history` (`FutureHistory`). Then pass the continuous contract `Symbol` with a start and end date.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/datasets/futures/universes>

### How do I get history for a continuous Futures contract?

Use the `symbol` property of the `Future` object that `qb.add_future` (`AddFuture`) returns. The `data_mapping_mode` argument sets when the contract rolls over. The `data_normalization_mode` argument sets price adjustment, and `contract_depth_offset` selects the front month or a back month.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/datasets/futures/key-concepts>

### Why does my Futures DataFrame show an expiry of December 30, 1899?

The continuous Futures contract doesn't expire, so its rows use the default expiry date of December 30, 1899. This date has no practical meaning.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/datasets/futures/universes>

### How do I see when the continuous Futures contract rolled?

Call `history` with the `SymbolChangedEvent` type and the continuous contract `Symbol`. The result contains the old and new contract `Symbol` for each rollover.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/datasets/futures/universes>

### Why can't I use open interest rollover for VIX Futures?

VIX Futures don't support continuous contract rolling with the `DataMappingMode.OPEN_INTEREST` or `DataMappingMode.OPEN_INTEREST_ANNUAL` modes. Use another data mapping mode for VIX Futures.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/datasets/futures/key-concepts>

### How do I get daily data for every tradable Futures contract?

Call `history` with the `FutureUniverse` type and the continuous contract `Symbol`. The result contains the entire Futures chain for each trading day, with prices, volume, and open interest. The filter you set on the universe doesn't apply to this request.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/datasets/futures/universes>

### How should I request Futures tick data?

Ticks are a sparse dataset, so request them over a trailing period of time or between a start and end time. Tick data is grouped into one-millisecond buckets. To tell trades from quotes, read the `tick_type` property of each tick.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/datasets/futures/individual-contracts>

### How do I get Futures Options history?

Add the underlying Future and select the Futures contract you want. Then call `qb.option_history` with the Futures contract `Symbol`, a start date, and an end date. For individual contracts, call `qb.add_future_option_contract` (`AddFutureOptionContract`) with fill-forward disabled.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/datasets/futures-options/universes>

### Why is there no volume or trade data in my Forex or CFD history?

Forex and CFD data contains quotes only, so history requests return `QuoteBar` data. The only market for Forex and CFD is `Market.OANDA`.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/datasets/forex>

### How do I load an alternative dataset in a notebook?

Call `qb.add_data` (`AddData`) with the dataset class, a ticker, and an optional resolution, and then call `history`. In C# notebooks, load the dataset DLL first. If you don't pass a resolution, the dataset uses its default resolution, which the dataset listing shows.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/datasets/alternative-data>

### How do I use my own custom data in a notebook?

Define a class that extends `PythonData` (`BaseData`) and overrides the `get_source` and `reader` methods, and sort the data file in chronological order. Then call `qb.add_data` and `history`. To read a remote file directly, call `qb.download` and parse the content with `pandas.read_csv`.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/datasets/custom-data>

## Charting

### Which charting libraries can I use in research notebooks?

Python notebooks support Bokeh, Matplotlib, Plotly, and Seaborn. C# notebooks support Plotly.NET. Pandas objects also have a `plot` method that uses Matplotlib.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/charting>

### How do I plot charts in a C# notebook?

Use the Plotly.NET package, which generates plotly.js charts in .NET languages. Load the Plotly.NET assemblies in the notebook, create a chart such as `Chart2D.Chart.Line`, and then display it.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/charting/plotly-net>

## Universes

### How do I see which assets my universe would select over time?

Define the universe with `qb.add_universe` (`AddUniverse`) and a filter function. Then call `qb.universe_history` (`UniverseHistory`) with the universe and a start date. The result contains the selected data objects for each selection time. Pass `flatten=True` to get a flat DataFrame.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/universes>

### Can I get historical ETF constituents in research?

Yes. Create the universe with `qb.universe.etf` (`Universe.ETF`) and a selection function, pass it to `add_universe`, and then call `universe_history`. Each day lists the `ETFConstituentUniverse` objects with the constituent weights.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/universes>

### How do I get universe data for Options and Futures?

Options and Futures universes use their own helper methods. Call `qb.option_history` for Options and `qb.future_history` for Futures. Other datasets that support universe selection work with `universe_history`.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/universes>

## Indicators

### How do I calculate an indicator over historical data in a notebook?

Create the indicator and call `qb.indicator_history` (`IndicatorHistory`) with the indicator, the `Symbol`, the number of bars, and the resolution. In Python, the `data_frame` property of the result holds the indicator values. You can also loop through history results and update the indicator manually.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/indicators/data-point-indicators>

### How do I calculate an indicator on weekly or other custom-period bars?

Create a consolidator, such as a `TradeBarConsolidator` for 7 days, and attach a handler that updates the indicator with each consolidated bar. Then loop through the history results and update the consolidator with each bar.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/indicators/custom-resolutions>

### How do I create a custom indicator in a Python notebook?

Define a class that inherits from `PythonIndicator`. The class must have a `value` attribute and an `update` method. To use a rolling period of data, inherit from `WindowIndicator` instead.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/indicators/custom-indicators>

## Object Store

### Can I read data in research that my backtest saved to the Object Store?

Yes. The Object Store belongs to your organization, so backtests, live algorithms, and notebooks in all of its projects share it. Read the data in the notebook with the same key the algorithm used to save it.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/object-store>

### Why does my notebook read old Object Store data after another project saved new data?

The Research Environment caches Object Store data when it reads or writes a key. If another project saves new data under the same key, your notebook still returns the cached data. To get the new data, call `qb.object_store.clear` (`Clear`) and read the key again.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/object-store>

### Do I need a paid plan to use the Object Store in research?

In QuantConnect Cloud, you need a paid organization to access the Object Store, and you must stay within your storage quota. To check the quota, read `qb.object_store.max_size` and `qb.object_store.max_files`. If you run the Research Environment locally, storage is limited only by your hardware.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/object-store>

### Which data types can I save to the Object Store from a notebook?

Python notebooks can save `bytes` and string objects with `save_bytes` and `save_string`. C# notebooks can also save JSON and XML objects with `SaveJson<T>` and `SaveXml<T>`. In QuantConnect Cloud, you need storage create permissions to save data.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/object-store>

### How do I save a DataFrame to the Object Store?

Call `qb.object_store.get_file_path` (`GetFilePath`) with a key to get a file path, and then call the DataFrame `to_csv` method with that path. To load it, call `pandas.read_csv` with the same path. Pandas also supports Parquet and Pickle formats.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/object-store>

### Why does deleting an Object Store key raise an exception?

The `delete` method raises an exception when the key doesn't exist. Before you delete or read data, check that the key exists with `qb.object_store.contains_key` (`ContainsKey`). You also need storage delete permissions in QuantConnect Cloud.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/object-store>

### What is the .agent folder in my Object Store?

The `.agent` folder is a reserved folder that the QuantConnect AI agents read. Its `skills`, `memories`, and `templates` subfolders hold the custom skills, memories, and project templates for your organization. To add or update these files, upload them with the Algorithm Lab, CLI, or API.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/object-store>

## Machine Learning

### Which machine learning libraries can I use in research?

The Research Environment supports Keras, TensorFlow, Scikit-Learn, hmmlearn, gplearn, PyTorch, Stable Baselines, tslearn, and XGBoost. Each library has a tutorial that shows how to build, train, test, and store a model.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/machine-learning/key-concepts>

### How do I request a library that isn't installed?

Contact QuantConnect to request the library. The team reviews each library for security before deployment, which takes 2–4 weeks. The Lean GitHub repository lists the library requests under review.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/machine-learning/key-concepts>

### How do I use a model I trained in research in a backtest or live algorithm?

Save the trained model to the Object Store from the notebook. Then load it from the Object Store in your backtest or live algorithm. Each library tutorial shows how to store and load its models.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/machine-learning/key-concepts>

### Why can't I import Aesara or gplearn?

Aesara and gplearn are available only in the Legacy package environment. Select the Legacy package environment for your project to use them.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/machine-learning/popular-libraries/gplearn>

## Debugging

### Can I debug code in a research notebook?

Yes, for Python notebooks. Open the notebook in your project and add at least one breakpoint in a cell. Then click the drop-down arrow in the top-left corner of the cell and click Debug Cell. The Research Environment debugger isn't available for C#.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/debugging>

### How do I inspect variables while the notebook debugger is paused?

The Variables section of the Run and Debug panel shows the local variables at the current breakpoint. Click an object to see its members, or right-click a variable and click Set Value to change it. To track an expression, add it to the Watch section.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/debugging>

## Meta Analysis

### How do I load backtest results into a notebook?

Call `api.read_backtest` (`ReadBacktest`) with the project Id and backtest Id. The method returns a snapshot of the backtest at that moment, so a running backtest doesn't include all of its data yet.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/meta-analysis/backtest-analysis>

### Why do I get only 100 orders when I read backtest or live orders?

Each call to `read_backtest_orders` or `read_live_orders` returns at most 100 orders. To get more, pass `start` and `end` arguments and page through the orders in windows of 100 or fewer. The `length` property of the result is the total number of orders.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/meta-analysis/backtest-analysis>

### Why do my order fill plots show only one fill per order?

The order fill plots in the meta analysis tutorials show only the last fill of each trade. If an order has partial fills, the plot displays only the last one.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/meta-analysis/backtest-analysis>

### How do I analyze optimization results in research?

Call `api.read_optimization` (`ReadOptimization`) with the optimization Id. The returned `Optimization` object lets you analyze how different parameter combinations affect performance.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/meta-analysis/optimization-analysis>

### Why do the closed trades of my live algorithm include only the current deployment?

A live algorithm loses its in-memory state when it stops, so the trade builder starts empty after each redeployment. To rebuild the full trade list, read all project orders with `read_live_orders` and replay their fill events through a new trade builder.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/meta-analysis/live-analysis>

### How do I compare live trading performance with a backtest?

Run an out-of-sample backtest that starts at the live launch time with the same starting equity. Then overlay both equity curves and order fills in a notebook. The Live Reconciliation tutorial reads these parameters from the live "Strategy Equity" chart and scores the result.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/meta-analysis/live-reconciliation>

### What are good reconciliation scores?

Reconciliation uses the annualized dynamic time warping (DTW) distance and the correlation of daily returns. A DTW distance below 0.2 is a decent score, and a returns correlation of 0.8 or above is a good score. You need both metrics because each one misses part of the similarity.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/meta-analysis/live-reconciliation>

### Can I deploy or stop live algorithms from a notebook?

Yes. In a notebook, use the QuantConnect API to compile projects, find available live nodes, and deploy the algorithms. To stop live algorithms, call `api.stop_live_algorithm` (`StopLiveAlgorithm`) with each project Id.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/meta-analysis/live-deployment-automation>

## Applying Research

### How do I turn research code into a backtest?

Replace `QuantBook()` with `self` and replace any `QuantBook` helper methods with their `QCAlgorithm` counterparts. A common pattern is to move the model into a Scheduled Event that selects assets and places orders. The Applying Research tutorials show the full research-to-production workflow.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/applying-research/key-concepts>

### Can I contribute a research tutorial?

Yes. Find a topic with the WishList tag in the Research GitHub repository and open a pull request with your tutorial. Accepted contributions earn a QCC reward and are featured in the community forum.

Docs: <https://www.quantconnect.com/docs/v2/research-environment/applying-research/key-concepts>
