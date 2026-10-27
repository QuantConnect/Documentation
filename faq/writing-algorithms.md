# Writing Algorithms FAQ

This file answers common questions about writing QuantConnect algorithms, from key concepts through historical data. Each answer links to the full documentation page.

## Key Concepts

### Why does my Python algorithm fail with "name 'QCAlgorithm' is not defined"?

Start every Python file in your project with `from AlgorithmImports import *`. This import brings the LEAN classes, such as `QCAlgorithm`, `Resolution`, and `Symbol`, into the file. Your algorithm class must inherit from `QCAlgorithm`. To share logic between files, put it in helper classes and store instances of them as attributes of your algorithm.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/key-concepts/getting-started>

### What does "Algorithm took longer than 10 minutes on a single time loop" mean?

LEAN lets your algorithm spend up to 10 minutes on each time slice, unless you call the `train` (`Train`) method for the work. If a single event handler, universe selection, or `initialize` (`Initialize`) call runs longer, LEAN stops the algorithm. Common causes are bulk history requests in `initialize`, unbounded universes, and model training inside data handlers. Move training into the `train` method and cut the data each step processes.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/key-concepts/algorithm-engine#06-Event-Flow>

### Can I define more than one algorithm class in a project?

No. Each project must define exactly one algorithm class. To trade several strategies in one algorithm, use the Algorithm Framework and add one Alpha model per strategy.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/key-concepts/algorithm-engine#03-Your-Algorithm-and-LEAN>

### Is LEAN multi-threaded, and do I need thread-safe code?

LEAN loads data on several threads, but it triggers your algorithm's events synchronously in backtests. In live trading, order events arrive immediately from the brokerage thread, so a limit order can fill between data bars. Scheduled Events also run on a separate thread in live trading so they fire on time. Use thread-safe collections, or locks around shared collections, in live algorithms.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/key-concepts/algorithm-engine#04-Threads-in-LEAN>

### Can my backtest see future data?

No. LEAN is a streaming engine, so it passes data to your algorithm in time order, the same way live trading does. You can only access data from before the Time Frontier, and the algorithm `time` (`Time`) property always equals that frontier. This design prevents look-ahead bias and lets the same code run in backtests and live trading.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/key-concepts/time-modeling/timeslices#02-Time-Frontier>

### Why are my bar timestamps different from another platform's?

LEAN passes a bar to your algorithm at the bar's end time, which is `bar.end_time = bar.time + bar.period` (`bar.EndTime = bar.Time + bar.Period`). Many free data providers stamp bars with their start time while including the close price. Compare bars by the same timestamp convention to avoid off-by-one-bar errors when you import data or compare indicator values.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/key-concepts/time-modeling/periods#03-Start-and-End-Time>

### When does my algorithm receive daily bars, and when do orders based on them execute?

LEAN emits a daily bar at market close or at midnight, depending on the `daily_precise_end_time` (`DailyPreciseEndTime`) setting, which defaults to market close. The bar includes every tick from the open to the close. If you analyze Friday's bar and place an order, LEAN sends the order to your brokerage on Friday after the market close.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/key-concepts/time-modeling/periods#04-Period-Values>

### Why does indexing the Slice raise a KeyError or return None?

The `Slice` only contains data for a security when a data point arrived at that moment. It can be empty for a thinly traded security, or in the same time step you added the subscription. If the `Slice` holds only auxiliary data for a security, `slice.contains_key(symbol)` (`slice.ContainsKey(symbol)`) can return `True` while `slice[symbol]` returns `None`. Check both before you use the data.

```python
if slice.contains_key(self._symbol) and slice[self._symbol]:
    data = slice[self._symbol]
```

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/key-concepts/time-modeling/timeslices#04-Get-Time-Slices>

### What happens if my on_data method takes longer than the bar period?

In backtests, the following slices queue up, so your algorithm still receives every slice. In live trading, the next event triggers when the current one finishes, and it carries the most recent slice. The slices in between are skipped. To run intensive computation before the market opens, use a Scheduled Event or the `train` (`Train`) method.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/key-concepts/event-handlers#02-Data-Events>

### Why does Python raise a TypeError when I subtract two times?

The algorithm `time` (`Time`) property and the `time` and `end_time` of data objects are naive `datetime` objects. The algorithm `utc_time` (`UtcTime`) property and the `utc_time` of order events are timezone-aware `datetime` objects in UTC. Python raises a `TypeError` when you subtract a naive `datetime` from an aware one. Compare `self.utc_time` with order event times, and compare `self.time` with data times.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/key-concepts/time-modeling/time-zones#04-Algorithm-Time-Zone>

### What is the default time zone of an algorithm?

The default algorithm time zone is Eastern Time, which is UTC-4 in summer and UTC-5 in winter. To change it, call the `set_time_zone` (`SetTimeZone`) method with an IANA time zone string or a `TimeZones` member. Data objects use the time zone of their exchange, and LEAN synchronizes all data in UTC so it arrives in the same `Slice`.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/key-concepts/time-modeling/time-zones#04-Algorithm-Time-Zone>

### What does a string like "AAPL R735QTJ8XC9X" mean?

The string is a serialized `SecurityIdentifier`, which uniquely identifies an asset without a database lookup. The first part is the first ticker the asset listed under, so you can see an old ticker such as `GOOCV` for Google. The rest encodes the market, security type, and contract details. To turn the string back into a `Symbol`, call the `symbol` (`Symbol`) method.

```python
symbol = self.symbol("GOOCV VP83T1ZUHROL")
```

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/key-concepts/security-identifiers#02-Encoding-Symbols>

### How do I handle a stock whose ticker changed, such as FB to META?

`Symbol` objects are permanent and track the underlying company when its ticker changes, so compare `Symbol` objects instead of ticker strings. The `add_equity` (`AddEquity`) method assumes you pass the current ticker. To create a `Symbol` from an old ticker, call `SecurityIdentifier.generate_equity` with a `mapping_resolve_date` when that ticker traded. Then pass the result to the `Symbol` constructor.

```python
security_id = SecurityIdentifier.generate_equity("ATHN", Market.USA, mapping_resolve_date=datetime(2021, 12, 1))
symbol = Symbol(security_id, "ATHN")
```

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/key-concepts/security-identifiers#04-Tickers>

### Should I pass ticker strings or Symbol objects to API methods?

Pass `Symbol` objects. Many methods accept a ticker string such as "IBM" and use the Symbol Cache to guess which `Symbol` you mean. We recommend you save explicit references to your `Symbol` objects instead of relying on that guess.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/key-concepts/security-identifiers#05-Symbol-Cache>

### Can I convert CUSIP, ISIN, FIGI, or SEDOL identifiers to a Symbol?

Yes. The `cusip` (`CUSIP`), `isin` (`ISIN`), `composite_figi` (`CompositeFIGI`), and `sedol` (`SEDOL`) methods convert an industry identifier to a `Symbol`. Pass a `Symbol` to the same methods to get the identifier back.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/key-concepts/security-identifiers#07-Industry-Standard-Identifiers>

### Why do I get "'MethodBinding' object is not iterable"?

You iterated a LEAN dictionary method without calling it. Objects such as `self.portfolio`, `self.securities`, and `slice.bars` work like Python dictionaries, but you must call `keys()`, `values()`, and `items()` with parentheses. To convert a LEAN enumerable, such as a `MemoizingEnumerable`, into a native list, wrap it in `list()`.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/key-concepts/python-and-lean#05-Dictionary-Types>

### Can I attach my own attributes, such as an indicator, to a Security object?

Yes. In Python, assign the attribute directly, such as `security.roc = self.roc(security, 21)`, and read it back through `self.securities`. In C#, cast the `Security` to `dynamic`, or use its `Set` and `Get<T>` methods. You can only add properties this way to the `Security` type.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/key-concepts/duck-typing>

### How do I use the QCAlgorithm API from another class?

Pass the algorithm instance, `self` (`this`), to the constructor of your class and store it. LEAN creates some objects for you, such as custom data objects. In that case, assign the algorithm to a global static variable in `initialize` (`Initialize`) and read it from the class. We recommend the instance reference whenever possible to keep the global namespace clean.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/key-concepts/globals-and-statics>

### Which Python libraries can I use, and can you add a new one?

The Supported Libraries page lists the packages in each environment. Python libraries are bundled into separate environments because not all of them are compatible with each other, so set the environment of your project first. To request a new library, contact us. The review and deployment process takes 2-4 weeks because the libraries run on our servers.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/key-concepts/libraries>

### How do I reuse my own code across several projects?

Create a project library. A project library is a QuantConnect project you merge into other projects, so you don't duplicate code files. The steps to create one depend on whether you use the Cloud Platform, the Local Platform, or the CLI.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/key-concepts/libraries#99-Project-Libraries>

### What is the difference between the debug and log methods?

Both record string messages. The `debug` (`Debug`) method also streams the message to the Cloud Terminal while the algorithm runs and shows it in orange. Place log statements carefully, because a statement inside a busy loop can generate gigabytes of logs over a backtest.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/key-concepts/debugging-tools#03-Logs>

### Will a bigger backtesting node make my backtest faster?

Usually not. A single backtest runs on one node, and your algorithm's events fire synchronously, so extra cores serve LEAN's data loading. The B2-8, B4-12, and B8-16 nodes run at the same clock speed, so your code runs at the same speed on all three. Larger nodes add RAM and cores. To speed up a backtest, reduce the data volume and the work per event first.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/key-concepts/algorithm-performance#07-Node-Sizing>

### My node has 4 cores, so why does my algorithm only use about 170% CPU?

This reading is expected for a Python algorithm. The CPython Global Interpreter Lock lets only one thread run Python code at a time, so your algorithm code uses about one core. The rest comes from LEAN subsystems written in C#, such as the data feed. A larger node does not raise the number.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/key-concepts/algorithm-performance#98-Common-Questions>

### Would rewriting my algorithm in C# make it much faster?

Only marginally for most strategies. The engine does the heavy work in C# whichever language you use, so the language only affects your own code. Your events still fire synchronously in backtests. A rewrite pays off when the strategy processes a high rate of events, such as tick resolution or a large universe.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/key-concepts/algorithm-performance#06-Language-Choice>

### How do I find out what makes my backtest slow?

Enable the Performance chart, which reports CPU, RAM, and the time LEAN spends in each subsystem. The chart is off by default, so set it before you run the backtest. Fix the code path behind the tallest series, such as `Subscriptions`, `HistoryDataPoints`, or `OnData`. When no series isolates the cost, add a Python profiler.

```python
self.settings.performance_sample_period = timedelta(7)
```

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/key-concepts/algorithm-performance#02-Measure-First>

### How do I make my backtest run faster?

Reduce the data the algorithm consumes, because runtime is roughly proportional to the number of data points. Use the coarsest resolution that answers the question, cap the universe size, and filter Option chains tightly. Leave extended market hours off unless you trade them. Remove history requests from `on_data`, loops, and selection functions, and move periodic logic into Scheduled Events.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/key-concepts/algorithm-performance#03-Reduce-Data-Volume>

### How much memory does my algorithm need?

As a rule of thumb, each security subscription costs about 5MB of RAM, so a 512MB node holds about 100 subscriptions. Indicators, history results, rolling windows, and models come out of the same budget. Size the node so the algorithm averages below 80% of its RAM limit. Bulk history calls, Option chains, and universe selection are the usual sources of memory spikes.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/key-concepts/algorithm-performance#07-Node-Sizing>

### What do the "Memory usage over 80% capacity" and "Memory Usage Maxed Out" messages mean?

LEAN samples the process RAM and smooths it with an exponential moving average. It logs the 80% warning once a minute while the smoothed reading is above 80% of the node limit. It stops the algorithm with "Memory Usage Maxed Out" when the smoothed reading exceeds the limit, or when one sample exceeds twice the limit. Short spikes survive, but a sustained level above the limit does not.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/key-concepts/debugging-tools#07-Memory-Metrics>

### How can I avoid look-ahead bias in my research?

Trade a dynamic universe of assets and use point-in-time data. If a dataset has no point-in-time data, apply a reporting lag yourself. Avoid choosing the universe or indicator parameters from assets that performed well over the backtest period. Adjusted prices can also distort factors that depend on the price level, such as a low-price factor.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/key-concepts/research-guide#07-Look-ahead-Bias>

## Initialization

### Why does my backtest end before today's date?

By default, the backtest end date is yesterday, one millisecond before midnight. Organization managers can also set an out-of-sample period, which forces all backtests to end a number of months before the current date. To use a different range, call the `set_start_date` (`SetStartDate`) and `set_end_date` (`SetEndDate`) methods within the range your organization allows.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/initialization#02-Set-Dates>

### Do the start date, end date, and starting cash apply in live trading?

No. In live trading, LEAN ignores the `set_start_date` (`SetStartDate`), `set_end_date` (`SetEndDate`), and `set_cash` (`SetCash`) methods. It loads your cash balances, holdings, and open orders from your brokerage account instead.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/initialization#04-Set-Cash>

### How do I start a backtest with a non-USD balance, such as BTC?

Call the `set_cash` (`SetCash`) method with the currency ticker and the quantity. Call it once for each currency you want to hold. The default starting cash is $100,000 USD.

```python
self.set_cash("BTC", 10)
self.set_cash("EUR", 10000)
```

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/initialization#04-Set-Cash>

### How do I change the account currency?

Call the `set_account_currency` (`SetAccountCurrency`) method in `initialize` (`Initialize`) before you add any data, and preferably before the `set_cash` (`SetCash`) method. The equity curve, benchmark, and statistics use the account currency. If you call the method more than once with different currencies, only the first call takes effect.

```python
self.set_account_currency("EUR")
```

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/initialization#03-Set-Account-Currency>

### How do I model a cash account instead of a margin account?

Call the `set_brokerage_model` (`SetBrokerageModel`) method with a brokerage and `AccountType.CASH` (`AccountType.Cash`). LEAN models a margin account by default. Cash accounts don't allow leverage.

```python
self.set_brokerage_model(BrokerageName.INTERACTIVE_BROKERS_BROKERAGE, AccountType.CASH)
```

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/initialization#05-Set-Brokerage-and-Cash-Model>

### Why did my security initializer remove the default fee and fill models?

The `set_security_initializer` (`SetSecurityInitializer`) method replaces the default initializer. The default initializer sets the brokerage model's fill, slippage, fee, buying power, settlement, short availability, and margin interest models, the leverage, and a price seeder. To keep those defaults and change only some models, call the `add_security_initializer` (`AddSecurityInitializer`) method before you create subscriptions.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/initialization#07-Set-Security-Initializer>

### Why doesn't my warm-up period run?

The warm-up uses the subscriptions you add in `initialize` (`Initialize`) to gather historical data. If you don't create subscriptions in `initialize`, the warm-up doesn't occur. If you call `set_warm_up` (`SetWarmUp`) without a resolution, it uses the lowest resolution of your subscriptions.

```python
self.set_warm_up(100, Resolution.DAILY)
```

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/initialization#14-Set-Warm-Up-Period>

### How do I know when my algorithm is ready to trade?

Define an `on_warmup_finished` (`OnWarmupFinished`) method. LEAN calls it once initialization and warm-up are complete, even if you don't set a warm-up period. Don't override the `post_initialize` (`PostInitialize`) method, because LEAN uses it for its own routines.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/initialization#98-Post-Initialization>

### Which benchmark does my algorithm use?

If you don't set a brokerage model, the default benchmark is SPY. If you set a brokerage model, the model defines the default. To change it, call the `set_benchmark` (`SetBenchmark`) method with a security, a constant function, or a custom data `Symbol`. If you pass a ticker you haven't subscribed to, LEAN creates a US Equity subscription, so subscribe to the benchmark security first.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/initialization#11-Set-Benchmark>

### Why does my live algorithm subscribe to securities I didn't add?

In live trading, LEAN also subscribes to the securities already in your brokerage portfolio. It gives them the lowest resolution of the subscriptions you created. For example, if you add second, minute, and hour subscriptions, your existing holdings get second resolution.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/initialization#08-Add-Data>

### Is there a limit to how much data I can add?

There is no official limit, but each security subscription needs about 5MB of RAM. Larger nodes let you run algorithms with bigger universes.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/initialization#08-Add-Data>

### Why doesn't my change to free_portfolio_value take effect?

The `free_portfolio_value` (`FreePortfolioValue`) setting is a buying power buffer. You must update it after the `initialize` (`Initialize`) method. To size the buffer as a share of the portfolio, set `free_portfolio_value_percentage` (`FreePortfolioValuePercentage`) instead, which defaults to 0.0025.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/initialization#10-Set-Algorithm-Settings>

### Why are the backtest names in my optimization missing their parameter values?

The `set_name` (`SetName`) method overwrites the default optimization backtest names, which contain the parameter values. To keep them, build a name that includes the parameters, such as `f"Backtest ({fast_period},{slow_period})"`. Your algorithm can add 20 tags with `add_tag` (`AddTag`), and LEAN truncates the name and tags after 200 characters.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/initialization#97-Set-Name-and-Tags>

## Securities

### Why can't I place orders for an index, a continuous Future, or a canonical Option?

These securities aren't tradable. The `is_tradable` (`IsTradable`) property is `False` for indices, canonical Option securities, and continuous Futures contracts. Trade a specific Futures or Option contract instead. In live trading, custom data securities are also not tradable, even when the data represents a tradable asset.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/securities/key-concepts#05-Tradable-Status>

### Why does on_data receive the same price repeatedly when no trades happen?

Fill forward is the default data setting. When a period has no new data, LEAN repeats the previous data point, so `on_data` (`OnData`) runs even without new trades. To disable it, pass `fill_forward=False` when you create the subscription. Without fill forward, you can get stale fills and zero trade volume.

```python
self.add_equity("SPY", fill_forward=False)
```

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/securities/requesting-data#05-Fill-Forward>

### Why does remove_security sell my position?

The `remove_security` (`RemoveSecurity`) method cancels your open orders for the security and liquidates your holdings. Only call it when you intend to exit the position.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/securities/requesting-data#07-Remove-Subscriptions>

### How do I get pre-market and after-hours data?

Pass `extended_market_hours=True` when you create the subscription. By default, subscriptions only cover regular trading hours. You only receive extended market hours data with minute, second, or tick resolution. Daily and hourly bars only reflect regular trading hours.

```python
self.add_equity("SPY", extended_market_hours=True)
```

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/securities/market-hours#03-Extended-Market-Hours>

### How do I check whether the market is open?

Use the `exchange.hours` (`Exchange.Hours`) property of the `Security`. Its `is_open` (`IsOpen`), `is_date_open` (`IsDateOpen`), `get_next_market_open` (`GetNextMarketOpen`), and `get_next_market_close` (`GetNextMarketClose`) methods use the exchange calendar, including holidays and early closes.

```python
is_open_now = self.securities[self._symbol].exchange.hours.is_open(self.time, extended_market_hours=False)
```

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/securities/market-hours#02-Market-Status>

### Can I filter out bad ticks or suspicious data?

Yes. Call the `set_data_filter` (`SetDataFilter`) method on the `Security` with a class that implements a `filter` (`Filter`) method. The method returns `True` for data points LEAN should filter out. The default filter of every security type doesn't filter out any data. For a universe, set the filter in a security initializer.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/securities/filtering-data>

### Is QuantConnect tick data cleaned before it reaches my algorithm?

No. Tick data is raw and unfiltered, so it can contain bad ticks, such as dark pool prints you can't trade. For US Equities, we set the `suspicious` (`Suspicious`) flag on ticks that look untradable, and we exclude them from the bars we build. Only use tick data if you understand the risks and can filter ticks yourself.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/securities/asset-classes/us-equity/data-preparation#04-Suspicious-Ticks>

### How does LEAN build US Equity bars from ticks?

LEAN aggregates ticks into bars but excludes some ticks from the price. Excluded ticks include suspicious ticks, trades with excluded condition flags such as odd lots, and FINRA trades once FINRA filtering activates for the day. The volume of an excluded trade still counts in the bar. If no tick in a period sets the price, the period has no bar.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/securities/asset-classes/us-equity/data-preparation#03-Bar-Building>

### Why don't QuantConnect's daily open and close match another platform?

We set the opening and closing prices of the first and last bars of the day to the official auction prices of the listing exchange. Other platforms might not use these official prices. Our US Equity data comes from the Securities Information Processor, which covers 100% of the market, while feeds such as BATS cover about 6-7%. Prices are also split and dividend adjusted by default.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/securities/asset-classes/us-equity/data-preparation#05-Market-Auction-Prices>

### Why don't my second bars contain the official opening price?

Exchanges usually publish the official auction prices 2-30 seconds after the open and close. LEAN waits until the end of the bar for the auction price, then emits the bar on time. Most second bars miss the official opening price. Minute bars usually include it, but exchange publishing delays can cause exceptions.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/securities/asset-classes/us-equity/data-preparation#05-Market-Auction-Prices>

### Why do my live bars differ slightly from the backtest bars for the same minute?

In live trading, LEAN builds bars from exchange timestamps with microsecond accuracy. A tick can land in a different bar in live trading than in the historical data. As a result, the open, high, low, close, and volume of individual bars can differ slightly.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/securities/asset-classes/us-equity/data-preparation#06-Live-and-Backtesting-Differences>

### Why are QuoteBar open, high, low, and close equal to the bid or the ask?

The `open`, `high`, `low`, and `close` of a `QuoteBar` are the mean of the bid and ask values. If the bid or the ask has no data for the period, they copy the side that has data. Read `quote_bar.bid` and `quote_bar.ask` to get each side.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/securities/asset-classes/us-equity/handling-data#04-Quotes>

### How does LEAN group ticks in a Slice?

In backtests, LEAN groups ticks into 1-millisecond buckets. In live trading, it groups ticks into buckets of about 70 milliseconds. Each `Slice` holds a list of ticks for each `Symbol`.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/securities/asset-classes/us-equity/handling-data#05-Ticks>

### How does LEAN adjust US Equity prices for splits and dividends?

By default, LEAN uses the `ADJUSTED` (`Adjusted`) data normalization mode, which adjusts prices for splits and dividends into a smooth curve. Other modes include `RAW`, `SPLIT_ADJUSTED`, and `TOTAL_RETURN`. Adjusted modes use the entire split and dividend history, so you get the same prices regardless of the backtest end date. To trade on actual prices, pass `data_normalization_mode=DataNormalizationMode.RAW`.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/securities/asset-classes/us-equity/requesting-data#11-Data-Normalization>

### Why don't my backtest prices match the historical prices on my broker's chart?

LEAN adjusts US Equity prices for splits and dividends by default. Past prices therefore differ from the raw prices that traded on those days. To see the traded prices, subscribe with the `RAW` (`Raw`) data normalization mode. To warm up indicators with adjusted data while trading raw prices, request history with the `SCALED_RAW` (`ScaledRaw`) mode.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/securities/asset-classes/us-equity/requesting-data#11-Data-Normalization>

### What happens to my position when a stock splits?

With an adjusted normalization mode, LEAN factors the split into the price and volume. With the `RAW` (`Raw`) mode or in live trading, LEAN adjusts your position by the split factor and credits any fractional remainder to your cash. It also scales your open orders and closes any Option positions on the stock. Reset and warm up your indicators and consolidators with `SCALED_RAW` (`ScaledRaw`) data after the split.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/securities/asset-classes/us-equity/corporate-actions#02-Splits>

### Do I receive dividend payments in my cash balance?

It depends on the data normalization mode. With the `ADJUSTED` (`Adjusted`) or `TOTAL_RETURN` (`TotalReturn`) mode, LEAN factors dividends into the price. With the other modes, and in live trading, LEAN adds the dividend payment to your cashbook.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/securities/asset-classes/us-equity/corporate-actions#03-Dividends>

### When do corporate actions occur in backtests and live trading?

In backtests, splits, dividends, and other corporate actions occur at midnight Eastern Time. In live trading, the corporate action data arrives at about 6-7 AM Eastern Time, so that's when they occur.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/securities/asset-classes/us-equity/corporate-actions#06-Live-Trading-Considerations>

### What happens to my open orders when a company changes its ticker?

LEAN cancels open orders for a security when its ticker changes, and it sends a `SymbolChangedEvent` to your algorithm. Your `Symbol` stays the same. To keep the order, resubmit it from the `on_order_event` (`OnOrderEvent`) method when the cancel tag contains "symbol changed event".

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/securities/asset-classes/us-equity/corporate-actions#04-Symbol-Changes>

### What happens when a stock I hold is delisted?

LEAN sends a delisting warning on the stock's final trading day, so you can exit the position gracefully. If you still hold the stock when the delisting occurs, LEAN liquidates it automatically. The `on_securities_changed` (`OnSecuritiesChanged`) event then reports the security as removed.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/securities/asset-classes/us-equity/corporate-actions#05-Delistings>

### Why do my short trades work in backtests but fail in live trading?

Backtests assume short orders succeed unless you model short availability. In live trading, your brokerage must have shares to lend. Add a shortable provider so LEAN only opens shorts when shares are available to borrow. LEAN doesn't charge borrowing costs by default, so set the `ShortMarginInterestRateModel` to simulate them.

```python
security.set_shortable_provider(InteractiveBrokersShortableProvider())
security.margin_interest_rate_model = ShortMarginInterestRateModel()
```

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/securities/asset-classes/us-equity/shorting>

### How much leverage does LEAN give me by default?

In backtests, US Equities get 2x leverage on margin accounts and no leverage on cash accounts. To change it, pass a `leverage` argument when you add the security. Options, Futures, and Future Options are already leveraged, so you can't change their leverage with the default margin model. In live trading, your brokerage determines the leverage.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/securities/asset-classes/us-equity/requesting-data#09-Margin-and-Leverage>

### Why can't I trade an Option contract right after I add it?

A contract you add with `add_option_contract` (`AddOptionContract`) has no price until the next `Slice` arrives. To trade it in the same time step, seed its price. Enable the `seed_initial_prices` (`SeedInitialPrices`) setting, or set the price in a security initializer. The same applies to Futures, Future Option, and Index Option contracts you add individually.

```python
self.settings.seed_initial_prices = True
```

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/securities/asset-classes/equity-options/requesting-data/individual-contracts#02-Create-Subscriptions>

### Which data normalization mode should the underlying of my Equity Options use?

Subscribe to the underlying Equity with the `RAW` (`Raw`) data normalization mode. If your algorithm has an Equity universe, set the universe data normalization mode to `RAW` before you add the universe. If you add an Option contract without an underlying subscription, LEAN subscribes to the underlying with the `RAW` mode for you.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/securities/asset-classes/equity-options/requesting-data/individual-contracts#02-Create-Subscriptions>

### What resolutions are available for Option data?

Minute is the densest resolution of historical Equity Option data, and the live stream also provides second resolution. To use second data live and still backtest the same code, choose the resolution from `live_mode` (`LiveMode`). Second resolution isn't available in history requests or warm-up periods.

```python
resolution = Resolution.SECOND if self.live_mode else Resolution.MINUTE
```

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/securities/asset-classes/equity-options/requesting-data/individual-contracts#03-Resolutions>

### When does LEAN calculate the Greeks of an Option contract?

LEAN calculates Greeks and implied volatility only when you request them, because they are expensive to compute. Accessing the `greeks` (`Greeks`) property doesn't trigger the calculation, but accessing `greeks.delta` (`Greeks.Delta`) does. To keep your algorithm fast, only read the values you need.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/securities/asset-classes/equity-options/handling-data#05-Option-Contracts>

### Is Equity Option data adjusted for splits and dividends?

No. LEAN doesn't adjust Equity Option data for the splits and dividends of the underlying, and the data normalization mode doesn't change that. If you hold an Option contract when a corporate action occurs for the underlying Equity, LEAN closes your position.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/securities/asset-classes/equity-options/requesting-data/individual-contracts#09-Data-Normalization>

### Which Option exercise styles are supported?

US Equity Options are American-style only. US Index Options are European-style only. US Future Options are European-style only.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/securities/asset-classes/equity-options>

### How often does open interest update?

Open interest is calculated once per day. To get the latest value, read the `open_interest` (`OpenInterest`) property of the Option or Futures contract.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/securities/asset-classes/futures/handling-data#06-Futures-Contracts>

### Should I add Futures contracts individually or with a universe?

In most Futures algorithms, a universe is the easier choice. When you call `set_filter` (`SetFilter`), LEAN subscribes to every contract that passes the filter, and you pick contracts from the `FuturesChain` in the `Slice`. Use `add_future_contract` (`AddFutureContract`) when you want to manage each contract subscription yourself.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/securities/asset-classes/futures/requesting-data/individual-contracts>

### Which Futures markets have backtesting data?

Historical data for backtesting is unavailable for the `ICE`, `INDIA`, `SGX`, and `NYSELIFFE` markets. In live trading, LEAN sources data for these markets from your brokerage or a third-party data provider.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/securities/asset-classes/futures/requesting-data/individual-contracts#04-Supported-Markets>

### Why does the Futures market open at 9:30 AM in my algorithm?

LEAN models most Futures market hours with three segments. The pre-market runs from 00:00 to 09:30, the market from 09:30 to 17:00, and the post-market from 18:00 to midnight. This standard segmentation exists because some Futures, like VIX, have pre- and post-market hours. As a result, a Scheduled Event for the market open fires at 9:30 AM. To receive data outside the regular segment, enable extended market hours.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/securities/asset-classes/futures/requesting-data/individual-contracts#07-Extended-Market-Hours>

### Why doesn't my Crypto position survive a redeploy?

When you buy a pair like BTCUSD, LEAN trades USD for BTC in your cashbook. The BTCUSD holding is a virtual pair that represents the open trade. LEAN tracks virtual pairs while the algorithm runs, but they don't survive between deployments. Some brokerages save them, and some deployment flows let you add virtual pairs manually.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/securities/asset-classes/crypto/holdings#06-Stateful-Redeployments>

### How do I short Crypto or use leverage?

You need a margin account. Some Crypto brokerages support both cash and margin accounts, while others only support cash accounts. Check the Account Types section of your brokerage guide.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/securities/asset-classes/crypto/holdings#02-Cash-vs-Margin-Brokerage-Accounts>

### How do I trade gold (XAUUSD)?

Add it as a CFD with the `add_cfd` (`AddCfd`) method. The OANDA CFD dataset provides the backtest data, and the QuantConnect data provider supports OANDA CFDs in live trading. In backtests, set the CFD market to OANDA.

```python
self._symbol = self.add_cfd("XAUUSD").symbol
```

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/securities/asset-classes/cfd/requesting-data#02-OANDA-Subscriptions>

### Can I backtest Interactive Brokers CFDs?

No. Historical data for Interactive Brokers CFDs is unavailable, so backtest CFDs on the OANDA market. In live trading, include the Interactive Brokers data provider to receive IB CFD data. LEAN doesn't model IB CFD market hours, so anchor Scheduled Events to a US Equity `Symbol`. LEAN also doesn't model the corporate actions of IB stock CFDs.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/securities/asset-classes/cfd/requesting-data#03-Interactive-Brokers-Subscriptions>

### I live in the EU and can't buy US ETFs. What can I trade instead?

If regulation prevents you from trading US ETFs, you can trade their CFD equivalents in live mode through Interactive Brokers. Add them with `add_cfd` (`AddCfd`) and `Market.INTERACTIVE_BROKERS` (`Market.InteractiveBrokers`). Use the underlying Equity for indicators and corporate action events.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/securities/asset-classes/cfd/requesting-data#03-Interactive-Brokers-Subscriptions>

### Which indices have second-resolution data?

Only SPX, NDX, and VIX support second resolution among Index subscriptions. The default resolution is minute. You can't trade indices, but you can use their bars for signals and trade related Futures or Index Options.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/securities/asset-classes/index/requesting-data#03-Resolutions>

## Portfolio

### How does LEAN calculate the average price of my position?

LEAN uses cost averaging. The average price is the weighted average of all your purchase prices, so it doesn't use first-in, first-out (FIFO) accounting. Reducing a position without closing it doesn't change the average price. The `holdings_cost` (`HoldingsCost`) property equals the holding quantity times the average price.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/portfolio/key-concepts#05-Cost-Averaging-Accounting>

### Why does invested return False when I hold a small quantity?

If your quantity is less than the lot size of the asset, the `invested` (`Invested`) property is `False`. The quantity can fall below the minimum order size, for example after a split or a partial trade.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/portfolio/key-concepts#03-Holdings-Status>

### How do I start a backtest with existing holdings?

Subscribe to the security, then call the `set_holdings` (`SetHoldings`) method of its `SecurityHolding` object with the average price and quantity. Guard the call so it only runs in backtests, because live algorithms load holdings from the brokerage.

```python
if not self.live_mode:
    self.portfolio["SPY"].set_holdings(average_price=500, quantity=100)
```

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/portfolio/holdings#05-Set-Initial-Holdings>

### How do I add or withdraw cash during a backtest?

Call the `add_amount` (`AddAmount`) method of the currency in the cashbook. Pass a negative amount to withdraw. In live trading, deposit and withdraw through your brokerage account, because `add_amount` would put your algorithm out of sync with the brokerage balance.

```python
self.portfolio.cash_book["USD"].add_amount(100)
```

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/portfolio/cashbook#05-Deposits-and-Withdrawals>

### Why can't I spend the cash from a sale right away?

In a cash account, Equity and Option trades settle in T+1, so LEAN holds the proceeds in the `unsettled_cash_book` (`UnsettledCashBook`) until settlement. You can't spend unsettled cash. Margin accounts settle trades immediately.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/portfolio/cashbook#03-Settled-vs-Unsettled-Cash>

### Why did LEAN add securities such as BTCUSDT that I didn't request?

LEAN adds internal subscriptions to convert your cash balances into the account currency. For example, if you only add BTCETH and set the account currency to USDT, LEAN adds BTCUSDT and ETHUSDT as internal feeds.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/portfolio/cashbook#02-Account-Currency>

### Does the unrealized profit include fees and the spread?

Yes. LEAN defines `unrealized_profit` (`UnrealizedProfit`) with the `total_close_profit` (`TotalCloseProfit`) method. That method subtracts the estimated fee from the fee model by default. It uses the bid price to value a sell and the ask price to value a buy.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/portfolio/holdings#03-Get-Total-Close-Profit>

## Universes

### How do I create a universe of S&P 500 or Nasdaq-100 stocks?

Create an ETF constituents universe on an ETF that tracks the index, such as SPY or QQQ. Pass `self.universe.etf("SPY")` (`Universe.ETF`) to `self.add_universe` (`AddUniverse`). To select a subset of the constituents, pass a filter function that receives `ETFConstituentUniverse` objects and returns a list of `Symbol` objects. The US ETF Constituents dataset includes 2,650 US ETFs.

```python
self.add_universe(self.universe.etf("SPY"))
```

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/universes/equity/etf-constituents-universes#02-Create-Universes>

### Why are weekly or 0DTE Option contracts missing from my Option chain?

The default Option universe filter in LEAN includes standard and weekly contracts. It keeps only contracts within 1 strike of the underlying price that expire within 35 days. Set a filter that covers the contracts you need, such as `expiration(0, 0)` for 0DTE contracts. For Index Options that trade under a separate ticker, pass the target ticker to `self.add_index_option` (`AddIndexOption`), for example `self.add_index_option(index.symbol, "SPXW")`.

```python
index = self.add_index("SPX")
option = self.add_index_option(index.symbol, "SPXW")
option.set_filter(lambda u: u.expiration(0, 0).strikes(-3, 3))
```

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/universes/index-options#99-Examples>

### How do I add Option contracts for the stocks that my fundamental or ETF universe selects?

Chain the universes with `self.add_universe_options` (`AddUniverseOptions`). Pass it the `Universe` object that `self.add_universe` returns and an Option filter function. LEAN then creates an Equity Options universe for each stock the first universe selects. The documented examples also set `self.universe_settings.data_normalization_mode` to `DataNormalizationMode.RAW` so strike prices compare fairly with the underlying price.

```python
universe = self.add_universe(self._fundamental_function)
self.add_universe_options(universe, lambda u: u.strikes(0, 2).front_month().calls_only())
```

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/universes/equity/chained-universes#06-Chain-Fundamental-and-US-Equity-Options>

### Why do some Fundamental properties return NaN?

Not every company reports every Morningstar field, so unreported values are NaN. Before you sort or rank `Fundamental` objects by a property, filter out the objects that have a NaN value for that property. Some assets, such as ETFs, don't have fundamental data at all, so check the `has_fundamental_data` (`HasFundamentalData`) property too.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/universes/equity/fundamental-universes#02-Create-Universes>

### Is the fundamental universe free of survivorship bias?

Yes. The Morningstar US Fundamentals dataset that powers fundamental universes includes delisted companies, so your backtests select from the stocks that existed at each date. The universe excludes ETFs, ADRs, and OTC securities.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/universes/equity/fundamental-universes#98-Common-Questions>

### Why does my algorithm run out of memory or slow down with a large universe?

Each asset in a universe consumes about 5 MB of RAM. A filter that selects thousands of assets can exhaust the memory of your node. Return only the assets you trade from your filter function. Check the RAM capacity of your backtesting and live trading nodes before you size the universe.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/universes/equity/fundamental-universes#02-Create-Universes>

### Can I call add_equity inside my universe filter function?

No. Return a list of `Symbol` objects from the filter function, and LEAN subscribes to them automatically. Calling `self.add_equity` (`AddEquity`) inside the filter creates duplicate subscriptions and unexpected behavior.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/universes/equity/fundamental-universes#98-Common-Questions>

### When does fundamental universe selection run in live trading?

The live data for fundamental universe selection arrives at 6 or 7 AM Eastern Time (ET). Fundamental universe selection runs for live algorithms between 7 and 8 AM ET, so you can place trades before the market opens. Schedule your logic after this window because the selection data isn't ready at midnight.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/universes/equity/fundamental-universes#11-Live-Trading-Considerations>

### How do I make universe selection run weekly or monthly instead of daily?

Call `self.universe_settings.schedule.on` (`UniverseSettings.Schedule.On`) with a date rule before you add the universe. Most universes run daily by default. In live trading, scheduled universes run at roughly 8 AM Eastern Time so the data has time to process.

```python
self.universe_settings.schedule.on(self.date_rules.month_start())
self.add_universe(self._fundamental_function)
```

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/universes/settings#10-Schedule>

### How do I run universe selection at a specific time of day?

Add a dataless scheduled universe with a `ScheduledUniverse` object, which accepts a date rule and a time rule. The `schedule` universe setting only accepts a date rule, so it can't control the time of day. The selection function of a dataless scheduled universe receives only the algorithm time and returns a list of `Symbol` objects.

```python
self.add_universe(ScheduledUniverse(self.date_rules.month_start(), self.time_rules.at(8, 0), self._select_symbols))
```

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/universes/dataless-scheduled-universes>

### Why does a security stay in my algorithm after my universe filter drops it?

LEAN keeps a removed security in the universe `members` (`Members`) collection while you own it or have an open order for it. LEAN also keeps it until it meets the `minimum_time_in_universe` (`MinimumTimeInUniverse`) setting, which defaults to one day. Liquidate the position and cancel open orders to let LEAN remove the subscription.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/universes/key-concepts#09-Universe-Manager>

### How do I get only the assets that my universe currently selects?

Save the `Universe` object that `self.add_universe` returns and read its `selected` (`Selected`) property. The `members` (`Members`) property and `self.active_securities` (`ActiveSecurities`) can contain more assets, such as assets with open positions or open orders. In a multi-universe algorithm, keep a reference to each universe to read its constituents separately.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/universes/key-concepts#11-Selected-Securities>

### How do I know when assets enter or leave my universe?

LEAN calls the `on_securities_changed` (`OnSecuritiesChanged`) event handler with a `SecurityChanges` object. Read `changes.added_securities` (`AddedSecurities`) and `changes.removed_securities` (`RemovedSecurities`) to get the assets. If you liquidate removed assets in this handler, LEAN creates market on open orders and frees buying power when the market opens.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/universes/key-concepts#06-Security-Changed-Events>

### How do I keep my universe the same on some selection days?

Return `Universe.UNCHANGED` (`Universe.Unchanged`) from your filter function. LEAN then keeps the current constituents without any changes.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/universes/key-concepts#08-Select-Current-Constituents>

### How do I speed up universe selection?

Enable `self.universe_settings.asynchronous` (`UniverseSettings.Asynchronous`) to run selection concurrently with the rest of the algorithm. In chained universes, apply the lightest filters first, such as dollar volume, before heavier fundamental or alternative data filters. To compute statistics across all assets, maintain your own matrix of daily prices instead of large history requests for each new asset.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/universes/equity/chained-universes#04-Universe-Data-Weights>

### What can't I do in my filter function when asynchronous selection is enabled?

Don't make history requests, and don't read the portfolio, securities, or transactions state. These objects depend on the algorithm time, which might not be what you expect while the filter runs asynchronously. If the filter function updates a class variable, don't update that variable anywhere else in the algorithm.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/universes/settings#09-Asynchronous-Selection>

### Why do the Greeks in my Option filter differ from the Greeks I see in on_data?

The Greeks and implied volatility in the Option universe filter are daily, pre-calculated values based on the end of the previous trading day. You can't customize these filter values. To get intraday values or a different calculation, create Option indicators for the contracts in your universe or set a different pricing model.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/universes/equity-options#09-Greeks-and-Implied-Volatility>

### How do I select Option contracts from several different delta or strike ranges at once?

Each filter method you chain on an `OptionFilterUniverse` narrows the previous result. To combine several ranges, define an isolated filter method that builds your own list of contract `Symbol` objects. Return that list with `option_filter_universe.contracts(symbols)` (`Contracts`).

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/universes/equity-options#05-Filter-by-Other-Contract-Properties>

### How do I get all tradable Option contracts with their Greeks, including contracts outside my filter?

Call `self.option_chain` (`OptionChain`) with the `Symbol` of your Option or its underlying asset. It returns an `OptionUniverse` object for each currently tradable contract, with daily pre-calculated Greeks and implied volatility. This method was previously named `option_chain_provider.get_option_contract_list` (`OptionChainProvider.GetOptionContractList`).

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/universes/equity-options#08-Navigate-Daily-Option-Chains>

### Why does the history of my Option universe include contracts that my filter excludes?

A history request for `OptionUniverse` data on the canonical Option `Symbol` returns the entire Option chain for each trading day. The request doesn't apply your universe filter. Filter the returned contracts yourself, for example by their Greeks or expiry.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/universes/equity-options#10-Historical-Data>

### Why did adding an Option universe change my underlying Equity data to raw prices?

Equity Option universes require raw prices for the underlying Equity. If you don't already subscribe to the underlying, LEAN subscribes with `DataNormalizationMode.RAW` (`DataNormalizationMode.Raw`). If your existing subscription uses another normalization mode, LEAN changes it to raw.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/universes/equity-options#02-Create-Universes>

### Why is my FuturesChain empty?

By default, a Futures universe in LEAN only subscribes to the continuous contract and doesn't add any contracts to the `FuturesChain`. Call `set_filter` (`SetFilter`) on the `Future` object that `self.add_future` (`AddFuture`) returns, for example `future.set_filter(0, 182)`. LEAN subscribes to every contract that passes the filter.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/universes/futures#13-Filter-Contracts>

### Can I place orders on the continuous Futures contract?

No, the continuous contract isn't tradable. Place orders on the currently selected contract with the `mapped` (`Mapped`) property of the `Future` object. The continuous `symbol` has the adjusted price, and the `mapped` contract has the raw price.

```python
self.market_order(self._future.mapped, 1)
```

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/universes/futures#12-Continous-Contracts>

### When do continuous Futures contracts roll over?

When the contract rolls, the `mapped` property points to the next contract and `on_data` (`OnData`) receives a `SymbolChangedEvent`. In backtests, the event occurs at midnight Eastern Time (ET). In live trading, it occurs when the mapping data arrives at 6 or 7 AM ET. The `data_mapping_mode` (`dataMappingMode`) argument of `self.add_future` defines when rollovers occur.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/universes/futures#12-Continous-Contracts>

### Why doesn't my Futures filter include the next contract when I roll?

Your filter excludes contracts that expire after its maximum expiry. Set the filter wide enough to include the next contract on the date you plan to roll. For example, a week before the front month expires, the next quarterly contract is about 98 days away. A filter of 0 to 92 days excludes it.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/universes/futures>

### How do I get the Option chain for a Future Option universe?

Create the canonical Future Option `Symbol` from a Futures contract with `Symbol.create_canonical_option` (`Symbol.CreateCanonicalOption`). Use that `Symbol` to index `slice.option_chains` (`OptionChains`). You can also loop through `slice.option_chains` to read the chain of each Futures contract.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/universes/future-options#04-Navigate-Option-Chains>

### Can I trade weekly Future Options?

No. Future Option universes only contain standard contracts, and weekly and non-standard contracts aren't available. The `weeklys_only` (`WeeklysOnly`) filter doesn't currently work for Future Options. By default, the universe includes contracts that expire within 35 days.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/universes/future-options#03-Filter-Contracts>

### Why did my backtest results change after the Morningstar fundamental data migration?

Morningstar retired the feeds behind the US Fundamental Data dataset, and QuantConnect rebuilt its entire history from the new feeds. Ratio, growth, per-share, and average properties now take the filing date of their statement, so most values arrive later. The new dataset also covers more US Equities, which changes which companies pass your universe filter.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/universes/equity/morningstar-migration>

### Why does reading a Fundamental property throw a NotSupportedException?

Morningstar no longer provides some data points or some of their period windows. LEAN keeps these members so your algorithm compiles, but reading one throws a `NotSupportedException`. The exception message names the replacement member, if one exists. To find the retired members your algorithm uses, run a short backtest.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/universes/equity/morningstar-migration#05-Retired-Data-Points>

### How do I compare my strategy on the old and new Morningstar datasets?

Run the backtest on both datasets and compare the equity curves. To use the old dataset, change the LEAN engine branch of your project while the old dataset is still available. The old dataset is fully deprecated for backtesting and live trading on October 31, 2026.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/universes/equity/morningstar-migration#06-Backtest-Impact>

### How do I get fundamental data for a stock that isn't in my universe?

Call `self.fundamentals` (`Fundamentals`) with a `Symbol` or a list of `Symbol` objects. The method returns the fundamentals for the current algorithm time, whether or not you subscribe to the asset. For a subscribed Equity, read the `fundamentals` property of its `Security` object.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/universes/equity/fundamental-universes#03-Direct-Access>

### Should I still use CoarseFundamental and FineFundamental universe selection?

No. The coarse and fine universe selection API was deprecated in November 2023. Use the `Fundamental` universe API, where one filter function receives `Fundamental` objects and returns a list of `Symbol` objects.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/universes/equity/legacy-fundamental-universes>

### How do I build a universe from my own list of tickers or my own dataset?

Create a custom universe. Define a class that extends `PythonData` (`BaseData`) and implements the `get_source` (`GetSource`) and `reader` (`Reader`) methods. Then pass the class and a selector function to `self.add_universe`. The data source must serve data in chronological order with a unique timestamp for each data point.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/universes/custom-universes#03-Define-Custom-Universe-Types>

### Which alternative datasets can I use for universe selection?

Datasets that support Equity universe selection include Brain, Upcoming Earnings, Dividends, IPOs, and Splits, Insider Trading, US Congress Trading, Corporate Buybacks, and several others. For Crypto, only the Crypto Market Cap dataset supports universe selection. An alternative data universe limits your universe to the securities in the dataset.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/universes/equity/alternative-data-universes#02-Supported-Datasets>

### When does Crypto universe selection run in live trading?

In live mode, the Crypto universe data pipeline has a 16-hour delay. Your algorithm receives the `CryptoUniverse` objects at around 16:00 to 16:30 Coordinated Universal Time (UTC) each day.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/universes/crypto#09-Live-Trading-Considerations>

### Can I backtest an Equity CFD universe?

QuantConnect doesn't have historical data for Interactive Brokers CFD products. Select the underlying Equities with a fundamental, ETF constituents, or alternative data universe. In live mode, use `self.live_mode` (`LiveMode`) and `Symbol.create` (`Symbol.Create`) to swap to the CFD versions. Live CFD trading requires Interactive Brokers as a data provider.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/universes/equity-cfd>

### How do I set fee, fill, or other models for each security in a universe?

Universe settings apply one configuration to every security in the universe. To configure each security individually, call `self.set_security_initializer` (`SetSecurityInitializer`) with a function that sets the models on each `Security`. To keep the default initializer behavior, call `self.add_security_initializer` (`AddSecurityInitializer`) before you create the subscriptions.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/universes/settings#98-Configure-Universe-Securities>

### Why don't I get pre-market data for my universe even though I enabled extended market hours?

You only receive extended market hours data with an intraday resolution. Daily bars reflect regular trading hours only. Set `self.universe_settings.extended_market_hours` (`UniverseSettings.ExtendedMarketHours`) to `True` before you add a non-derivative universe, or pass `extended_market_hours=True` to a derivative universe method.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/universes/settings#05-Extended-Market-Hours>

## Datasets

### Why isn't the most recent live data in my backtest yet?

Data from the live feed usually becomes available for backtesting 24 to 48 hours later. If you need to monitor the most recent data closely, run the algorithm with QuantConnect Paper Trading instead of a backtest.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/datasets/overview>

### When does alternative data arrive in live trading compared to backtests?

QuantConnect receives most daily alternative data at 7 AM Eastern Time (ET) and starts processing it. In live trading, your algorithm receives the data within 30 minutes of 7 AM ET. In backtests, the same data has a midnight timestamp, so your algorithm receives it about seven hours earlier than in live trading.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/datasets/overview>

### Why do live bars arrive slightly later than the same bars in a backtest?

Backtests deliver data at perfect timing, so a minute bar arrives exactly at the top of each minute. In live trading, bars have a slight delay, so you can receive them milliseconds after the top of the minute.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/datasets/overview>

### Are QuantConnect datasets free of survivorship bias?

Yes. QuantConnect vets the datasets in the Dataset Market to ensure they are free of survivorship bias and have a reliable live feed. For example, the AlgoSeek US Equities dataset covers every stock in the US SIP feed since 1998, about 27,500 securities, including delisted ones. The US Equity Coarse Universe covers 30,000 US Equities in total, with about 8,000 trading on any given day.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/datasets/algoseek/us-equities>

### Does QuantConnect have data for OTC stocks?

No. The US Equities, US Equity Security Master, and US Equity Coarse Universe datasets don't include Over-the-Counter (OTC) stocks. The US Equities dataset collects trades and quotes from the full SIP feed and excludes OTC trades.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/datasets/algoseek/us-equities>

### Why does a stock have no fundamental data?

The Morningstar US Fundamental Data dataset doesn't include ETFs, ADRs, or OTC equities. It covers US Equities on the NYSE, NASDAQ, AMEX, and BATS exchanges. A security outside that coverage can still have price data from the US Equities dataset while its fundamental fields stay empty.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/datasets/morningstar/us-fundamental-data>

### Is Morningstar fundamental data point-in-time, or does it include restatements?

LEAN loads all Morningstar data with the "As Original Reported" figures, so later corrections to a reported figure don't change the history. When a filing date is present, the data uses it. For older symbols without a filing date, the file date is approximated as 45 days after the as-of date.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/datasets/morningstar/us-fundamental-data>

### When does fundamental data update in live trading?

In live trading, Morningstar data reaches your algorithm at approximately 6 AM each day. Most fundamental fields update once per month, and the financial ratios update daily. If a security has no update on a given day, your algorithm receives the same fundamental data as the previous day.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/datasets/morningstar/us-fundamental-data>

### How do I link an alternative dataset to a stock?

Call the `add_data` (`AddData`) method with the dataset class and the Equity `Symbol`, then save the dataset `Symbol` that it returns. To read the data, index the current `Slice` with that dataset `Symbol`.

```python
self.aapl = self.add_equity("AAPL").symbol
self.dataset_symbol = self.add_data(BenzingaNews, self.aapl).symbol
```

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/datasets/benzinga/benzinga-news-feed>

### Why is my alternative data missing from most time slices or my history request empty?

Many alternative datasets are sparse, so a `Slice` doesn't contain data for your dataset at every time step. Check that the `Slice` contains the dataset `Symbol` before you index it. A `history` (`History`) request returns an empty result when the dataset has no data in the period you request.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/datasets/benzinga/benzinga-news-feed>

### How do I select a universe of stocks from an alternative dataset?

Call the `add_universe` (`AddUniverse`) method with the dataset's universe class and a selection function. For example, pass `BrainSentimentIndicatorUniverse` to select US Equities by Brain sentiment. Each dataset page lists its universe class and when its universe runs in live trading. The Brain Sentiment Indicator universe runs at 7 AM ET.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/datasets/brain/brain-sentiment-indicator>

### Should I remove alternative data subscriptions when a stock leaves my universe?

Yes. If you subscribe to an alternative dataset for assets in a dynamic universe, remove the subscription when the asset leaves. Call the `remove_security` (`RemoveSecurity`) method with the dataset `Symbol`. The Track Security Changes design pattern in the Alpha model docs shows a common way to do this.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/datasets/benzinga/benzinga-news-feed>

### Why does futures daily volume differ from CME or Yahoo Finance?

The CME daily volume includes pit trades, and the AlgoSeek US Futures data excludes them because algorithms can't trade them. The daily bars also span different hours. CME and Yahoo Finance daily bars span 5 PM to 4 PM Central Time, while QuantConnect daily bars span midnight to midnight ET. To match CME volume, sum intraday bars from 5 PM to 4 PM Central Time.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/datasets/algoseek/us-futures>

### Why doesn't my VIX futures continuous contract work?

VIX Futures don't support continuous contract rolling with `DataMappingMode.OPEN_INTEREST` (`DataMappingMode.OpenInterest`) or `DataMappingMode.OPEN_INTEREST_ANNUAL` (`DataMappingMode.OpenInterestAnnual`). Use another data mapping mode, such as `DataMappingMode.LAST_TRADING_DAY` (`DataMappingMode.LastTradingDay`). The QuantConnect data provider also doesn't include a live data feed for the CFE market, where VIX Futures trade.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/datasets/quantconnect/us-futures-security-master>

### Does the US Futures dataset include ICE futures?

The AlgoSeek US Futures dataset includes only Sugar (SB) from the ICE exchange. It covers the 157 most liquid contracts on the CFE, CBOT, CME, COMEX, NYMEX, and ICE markets, starting in May 2009.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/datasets/algoseek/us-futures>

### Where do the Greeks and implied volatility in the option universe come from?

QuantConnect calculates them for the US Equity Option Universe dataset with a forward tree pricing model. The model accounts for the interest rate, dividend payments, and daily closing prices. The values match what you get from daily indicators with mirror Options. The dataset doesn't contain market data, which comes from the AlgoSeek US Equity Options dataset.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/datasets/quantconnect/us-equity-option-universe>

### Why can't I find daily SPX expirations before 2021?

Before 2021, SPX contracts traded with a limited set of expiration dates, and SPXW didn't have 0DTE contracts every day. The US Index Option Universe follows the contract specification in effect at each date, so earlier years have fewer expirations.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/datasets/quantconnect/us-index-option-universe>

### How up to date is the ETF constituents data?

The US ETF Constituents dataset updates daily from January 2015 and monthly before then, starting in June 2009. QuantConnect collects the holdings from the host ETF websites, so the data can be delayed by up to one week.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/datasets/quantconnect/us-etf-constituents>

### Can I get live SPX and VIX index data?

Yes. The QuantConnect data provider streams live SPX and VIX data from CBOE. To subscribe, call the `add_index` (`AddIndex`) method with the index ticker.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/datasets/quantconnect/cash-indices>

### Do I need to subscribe to interest rate data to get the risk-free rate?

No. `QCAlgorithm` automatically subscribes to the US Interest Rate dataset through its default risk-free interest rate model. To read the current rate, call the `get_interest_rate` (`GetInterestRate`) method of the `risk_free_interest_rate_model` (`RiskFreeInterestRateModel`) with the current time.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/datasets/federal-reserve-bank-of-st-louis/us-interest-rate>

### How do I see which assets a dataset supports?

Open the dataset in the Data Explorer. The Supported Assets section of a dataset page in the docs links to its Data Explorer listing.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/datasets/algoseek/us-equities>

## Importing Data

### How do I use my own data in a QuantConnect algorithm?

Import it as custom data, which is any external data that isn't from the Dataset Market. You can download the whole file at once with the `download` (`Download`) method, or stream it line by line with a custom data reader. If you use a custom data reader, LEAN passes each data point to the `on_data` (`OnData`) method of your algorithm.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/importing-data/key-concepts>

### Should I stream my custom data or download it in bulk?

Stream the data when you want LEAN to deliver each point to `on_data` (`OnData`) at its timestamp. Use a bulk download for data you load once, such as trained models, `Symbol` lists, parameters, or well-defined historical price datasets. LEAN doesn't control bulk downloads, so it can't enforce good practices like avoiding look-ahead bias for that data.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/importing-data/bulk-downloads#02-Recommended-Use-Cases>

### How do I stream a CSV file into on_data?

Define a class that extends `PythonData` (`BaseData`) and overrides the `get_source` (`GetSource`) and `reader` (`Reader`) methods. Then, in the `initialize` (`Initialize`) method, call `add_data` (`AddData`) with your class, a name for the data, and its resolution. The `get_source` method tells LEAN where the file is, and `reader` parses one line into one data object.

```python
self._symbol = self.add_data(MyCustomDataType, "MyCustomDataType", Resolution.DAILY).symbol
```

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/importing-data/streaming-data/custom-securities/csv-format-example>

### Where should I host my custom data files?

The Object Store is the fastest file provider because LEAN doesn't download the files on every execution. Dropbox, GitHub, and Google Sheets also work as remote providers. Remote providers apply their own rate limits, and each remote request adds about one second of overhead. To upload files to the Object Store, use the Algorithm Lab, the LEAN CLI, or the Research Environment.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/importing-data/key-concepts#02-File-Providers>

### How do I read custom data from the Object Store?

In the `get_source` (`GetSource`) method, return a `SubscriptionDataSource` with the Object Store key and the `SubscriptionTransportMedium.OBJECT_STORE` (`ObjectStore`) transport medium. Then parse each line in the `reader` (`Reader`) method as you would for a remote file.

```python
return SubscriptionDataSource("<your_csv_key>", SubscriptionTransportMedium.OBJECT_STORE, FileFormat.CSV)
```

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/importing-data/streaming-data/key-concepts#07-Object-Store>

### Why does my Dropbox file return an HTML page instead of my data?

Your Dropbox link points to the preview page of the file. Add `?dl=1` to the end of the shared file URL so the request downloads the file itself. For GitHub, use the raw file URL, such as `https://raw.githubusercontent.com/<organization>/<repo>/<path>`. For Google Sheets, publish the sheet as CSV, because Google Sheets doesn't export JSON.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/importing-data/key-concepts#02-File-Providers>

### Why is my algorithm slow when it imports data from Dropbox?

We don't rate-limit file downloads, but external providers often do. Dropbox caps download speeds to 10 kb/s after 3-4 download requests. To keep algorithms fast, use a small number of small files, bundle dates together into fewer files, or store the files in the Object Store.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/importing-data/key-concepts#98-Rate-Limits>

### What limits apply to remote files in a cloud backtest?

The number of remote files you can download during a single backtest or Research Environment session depends on your organization tier. There is no limit on the number of files you load from the Object Store. Remote files can be up to 200 MB in size, with names up to 200 characters long. In cloud algorithms, the download methods also have a 10-second timeout, after which LEAN throws an error.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/importing-data/key-concepts#97-File-Quotas>

### What does my reader method need to set on each data point?

Set the `symbol` (`Symbol`), `time` (`Time`), `end_time` (`EndTime`), and `value` (`Value`) properties. You can set `symbol` to `config.symbol`. LEAN adds each sample to a `Slice` at its `end_time`, and it calls `reader` repeatedly until the time advances or the file ends. When a line has no usable data, such as a header row, return `None` (`null`).

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/importing-data/streaming-data/key-concepts#05-Parse-Custom-Data>

### How do I avoid look-ahead bias with custom data?

Set the timestamp of each data point to the time the data would have been available in reality. Many external sources date daily bars by their open, while LEAN dates daily bars by their close. If you set `end_time` (`EndTime`) to the start of the bar, your backtest receives the bar earlier than live trading would.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/importing-data/streaming-data/custom-securities/key-concepts#09-Avoid-Look-Ahead-Bias>

### Can I trade a security that I import as custom data?

Yes, custom data lets you simulate trades on securities that QuantConnect doesn't support. Custom data doesn't get the security modeling of native data. Splits, dividends, delistings, mergers, and market hours don't apply to it, and the market is open 24/7 by default. To model trading hours, pass a `SecurityExchangeHours` object to `add_data` (`AddData`).

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/importing-data/streaming-data/custom-securities/key-concepts>

### How often does LEAN check my custom data source for new data in live trading?

The polling frequency depends on the resolution you pass to `add_data` (`AddData`). LEAN checks every 30 minutes for daily and hourly data, every minute for minute data, every second for second data, and constantly for tick data. The lowest reasonable resolution is one minute, because anything more frequent is very slow to execute.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/importing-data/streaming-data/custom-securities/key-concepts#05-Create-Subscriptions>

### How do I use one custom data class for both backtesting and live trading?

The `get_source` (`GetSource`) method receives an `is_live_mode` (`isLiveMode`) argument, so you can return a different source in each mode. For example, read a historical file from the Object Store in backtests and poll an endpoint in live trading. The `SubscriptionTransportMedium.REST` (`Rest`) medium polls a REST call that returns a single line or data point each time. In live trading, custom data arrives as soon as it's available, so it may not align with other data in the same slice.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/importing-data/streaming-data/key-concepts#04-Set-Data-Sources>

### How do I debug my custom data reader?

In backtests, set a breakpoint in the `reader` (`Reader`) method and inspect variables with the IDE debugger. In live trading, the debugger isn't available, so add logging statements. To log from inside the custom data class, set a class member that references the algorithm in `initialize`. Then call its `debug` (`Debug`) method from `reader`.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/importing-data/streaming-data/custom-securities/key-concepts#07-Debugging>

### How do I access the extra columns of my custom data in on_data?

Any property you add in the `reader` (`Reader`) method becomes a member of the data object in the `Slice`. LEAN normalizes the names by replacing `-` and `.` with whitespace, capitalizing the first letter, and removing whitespace. For example, a property named `some-property.name` becomes the `Somepropertyname` member.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/importing-data/streaming-data/custom-securities/key-concepts#06-Receive-Custom-Data>

### How do I return several data points from one line of my file?

In `get_source` (`GetSource`), pass `FileFormat.UNFOLDING_COLLECTION` (`UnfoldingCollection`) as the third argument to the `SubscriptionDataSource` constructor. In `reader` (`Reader`), order the objects by timestamp and return a `BaseDataCollection(end_time, config.symbol, objects)`. This pattern suits JSON files that hold many records on a single line.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/importing-data/streaming-data/custom-securities/json-format-example>

### How do I use precomputed machine learning predictions in a backtest?

Train the model and generate daily predictions in the Research Environment, using only trailing data as inputs. Save the predictions as JSON in the Object Store. In the algorithm, read them with a custom universe data type and rebalance with a Scheduled Event. Backtests then skip feature calculation and model inference, so they run faster and reproduce the same predictions.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/importing-data/streaming-data/precomputed-ml-predictions>

### How do I load a trained model file with the download method?

Serialize the model with `joblib` or `pickle`, encode the bytes as a Base64 string, and save that string to a file. The `download` (`Download`) method can't read raw binary files without corrupting them. After you download the file, decode the Base64 string and load the object from the bytes.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/importing-data/bulk-downloads#05-Transport-Binary-Data>

### Why do History and Slice.Get return nothing for my custom TradeBar type?

If your custom data type derives from `TradeBar` instead of `BaseData`, set its `DataType` to `MarketDataType.Base` in the constructor. Otherwise, LEAN treats the data points as regular trade bars, so `History<T>` and `Slice.Get<T>` return no data for your custom type.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/importing-data/streaming-data/key-concepts#05-Parse-Custom-Data>

## Consolidating Data

### How do I create 5-minute or 15-minute bars from minute data?

Subscribe to minute data and create a consolidator with the `consolidate` (`Consolidate`) helper method. The helper creates the consolidator, registers it for automatic updates, and calls your handler with each consolidated bar. Keep a reference to the returned consolidator if you need to read its bars or remove it later.

```python
self._symbol = self.add_equity("SPY", Resolution.MINUTE).symbol
self._consolidator = self.consolidate(self._symbol, timedelta(minutes=15), self._consolidation_handler)
```

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/consolidating-data/getting-started>

### Why does my consolidation handler never receive bars?

The most common error is to put parentheses after the method name when you set the event handler. With parentheses, the method executes and its result becomes the handler. Pass the method itself, such as `self._consolidation_handler`. Also confirm that you registered the consolidator with `add_consolidator` (`AddConsolidator`) or that you update it manually.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/consolidating-data/consolidator-types/time-period-consolidators#02-Consolidate-Trade-Bars>

### Why does my consolidation handler raise an argument error?

The handler signature depends on how you create the consolidator. A handler you attach to the `data_consolidated` (`DataConsolidated`) event receives a `sender` object and the consolidated bar. If you use the `consolidate` (`Consolidate`) helper method, the handler receives only the consolidated bar.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/consolidating-data/consolidator-types/time-period-consolidators#02-Consolidate-Trade-Bars>

### Which time zone do consolidated bars use?

Time period consolidators close bars based on the exchange time zone of the asset, regardless of the algorithm time zone. For example, daily Crypto bars close at midnight UTC. Daily US Equity bars built from regular-hours data close at market close or midnight, depending on your `daily_precise_end_time` (`DailyPreciseEndTime`) setting.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/consolidating-data/consolidator-types/time-period-consolidators>

### Why does my hourly consolidator close at 10:00 instead of 10:30?

Time period consolidators align bars to the clock, so an hourly bar ends at the top of the hour. To anchor bars to another time, pass the `start_time` (`startTime`) argument to the consolidator constructor. To anchor bars to the open of each trading session instead, use a `MarketHourAwareConsolidator`.

```python
self._consolidator = TradeBarConsolidator(timedelta(hours=1), start_time=timedelta(hours=9, minutes=30))
```

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/consolidating-data/consolidator-types/time-period-consolidators>

### How do I get weekly or monthly bars?

The `Resolution` enumeration has no weekly or monthly member, so build these bars with a calendar consolidator. Pass `Calendar.WEEKLY`, `Calendar.MONTHLY`, `Calendar.QUARTERLY`, or `Calendar.YEARLY` to the `consolidate` (`Consolidate`) helper method. Calendar bars always end on the same schedule, regardless of the first data point the consolidator receives.

```python
self._consolidator = self.consolidate(self._symbol, Calendar.WEEKLY, self._consolidation_handler)
```

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/consolidating-data/consolidator-types/calendar-consolidators>

### Why do my multi-day bars start on an unexpected day?

When a time period consolidator spans multiple days, its cycle starts from the timestamp of the first data point it receives. To produce bars that always end on the same schedule, use a calendar consolidator. For a custom schedule, pass a method that returns a `CalendarInfo` object with the bar start time and period in the exchange time zone.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/consolidating-data/getting-started#04-Consolidator-Types>

### Can I consolidate hourly data into 10-minute bars?

No. The consolidation period must be greater than or equal to the resolution of the security subscription. For example, you can aggregate minute bars into 10-minute bars, but you can't aggregate hour bars into 10-minute bars. Subscribe at a resolution that is equal to or finer than the bar size you need.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/consolidating-data/consolidator-types/time-period-consolidators>

### Why does a TradeBarConsolidator throw an exception on Forex or CFD data?

Forex pairs and CFD contracts have only quote data. Use a `QuoteBarConsolidator` to consolidate their data. If you pass a `TradeBarConsolidator` for one of these securities to `add_consolidator` (`AddConsolidator`), LEAN throws an exception.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/consolidating-data/consolidator-types/time-period-consolidators#02-Consolidate-Trade-Bars>

### Do consolidators produce trade bars or quote bars?

Consolidators usually produce the same format as their input. They aggregate `TradeBar` objects into a `TradeBar` and `QuoteBar` objects into a `QuoteBar`. They aggregate `Tick` objects into either format. For assets with both trades and quotes, consolidators produce `TradeBar` objects by default. Forex and CFD data have only quotes, so they produce `QuoteBar` objects.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/consolidating-data/getting-started#03-Data-Shapes-and-Sizes>

### How do I consolidate custom data or alternative data?

Use a `DynamicDataConsolidator`, which aggregates various types of data objects. Check the definition of the data class to see the data type the handler receives. Update it automatically with `add_consolidator` (`AddConsolidator`) or manually with the data from the `Slice`.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/consolidating-data/consolidator-types/time-period-consolidators#07-Consolidate-Other-Data>

### Why does my algorithm slow down and run out of memory with a dynamic universe?

If you register consolidators for securities in a dynamic universe and don't remove them, they compound internally. The algorithm slows down and eventually dies once it runs out of RAM. When a security leaves the universe, call `self.subscription_manager.remove_consolidator` (`SubscriptionManager.RemoveConsolidator`) with its `Symbol` and consolidator.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/consolidating-data/consolidator-types/time-period-consolidators#02-Consolidate-Trade-Bars>

### Why doesn't my Scheduled Event see the latest consolidated bar?

In each time step, LEAN runs Scheduled Events first, then consolidation handlers, and then `on_data` (`OnData`). If a consolidation handler appends bars to a `RollingWindow`, a Scheduled Event at the same time doesn't see the newest bar yet. Consolidators update in the order you register them.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/consolidating-data/getting-started#06-Execution-Sequence>

### How do I access previous consolidated bars?

Every consolidator keeps a built-in `RollingWindow` of the bars it produces. Use the `current` (`Current`) and `previous` (`Previous`) properties, or index the consolidator, where index 0 is the most recent bar. By default, the window holds 2 bars, so set `window.size` (`Window.Size`) to keep more. Before the first bar, `current` is `None` (`null`), so check the `is_ready` (`IsReady`) flag of the window first.

```python
self._consolidator.window.size = 10
previous_bar = self._consolidator.previous
```

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/consolidating-data/consolidator-history>

### How do I warm up a consolidator?

Update the consolidator manually with data from a history request. Call its `update` (`Update`) method with each bar or tick from the history result. Manual updates let you warm up a consolidator with data outside the warm-up period and control what data it receives.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/consolidating-data/consolidator-types/time-period-consolidators#02-Consolidate-Trade-Bars>

### Do I need to reset my consolidators after a stock split?

Yes, if you live trade Equities or backtest them without the adjusted data normalization mode. When a split or dividend occurs mid-bar, the open, high, and low may reflect pre-event prices. Call the consolidator's `reset` (`Reset`) method and warm it up again with `SCALED_RAW` (`ScaledRaw`) history data.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/consolidating-data/getting-started#08-Reset-Consolidators>

### How do I update an indicator with consolidated bars?

Create a manual indicator and call `register_indicator` (`RegisterIndicator`) with the `Symbol`, the indicator, and a `timedelta` (`TimeSpan`), `Resolution`, or consolidator. LEAN then updates the indicator with each consolidated bar. In a dynamic universe, pass a consolidator so you can remove it when the security leaves.

```python
self._indicator = SimpleMovingAverage(10)
self.register_indicator(self._symbol, self._indicator, timedelta(minutes=7))
```

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/consolidating-data/updating-indicators#03-Custom-Indicator-Periods>

### How do I calculate a daily indicator from minute data?

Pass the indicator resolution to the automatic indicator method, such as `self.ema("SPY", 14, Resolution.DAILY)`. LEAN creates a consolidator, registers it for automatic updates, and updates the indicator with the consolidated daily bars. This one-line approach suits static universes.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/consolidating-data/updating-indicators#02-Standard-Indicator-Periods>

### How do I update an indicator with the high price instead of the close?

Pass a `selector` argument to `register_indicator` (`RegisterIndicator`), such as `Field.HIGH`. By default, data point indicators use the close price. For `QuoteBar` data, the default is the mid-price of the bid close and the ask close. You can also define a custom selector function that calculates the value.

```python
self.register_indicator(self._symbol, self._rsi, Resolution.DAILY, Field.HIGH)
```

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/consolidating-data/updating-indicators#04-Custom-Indicator-Values>

### How do I create bars from a fixed number of trades or ticks?

Use a count consolidator by passing an integer to the consolidator constructor. The consolidator aggregates that many samples, regardless of the time they cover. For a mixed-mode consolidator, pass both a `timedelta` (`TimeSpan`) and an integer. It closes a bar after n samples or the time period, whichever comes first.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/consolidating-data/consolidator-types/count-consolidators>

### What is the difference between RenkoConsolidator and ClassicRenkoConsolidator?

The `RenkoConsolidator` produces traditional Renko bars with bodies that span the bar size. If the price jumps several bar sizes in one tick, it produces several bars in that time step. The `ClassicRenkoConsolidator` produces a bar when the price closes one bar size from the previous close. It produces at most one bar per time step, and each open matches the previous close.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/consolidating-data/getting-started#04-Consolidator-Types>

### How do I choose which price builds my Renko bars?

By default, the `ClassicRenkoConsolidator` builds bars from the `value` (`Value`) property of the data it receives. To use another field, pass a `selector` function that receives the data object and returns a decimal, such as the high or close. To add a non-zero volume to the bars, pass a `volume_selector` (`volumeSelector`) function.

```python
self._consolidator = ClassicRenkoConsolidator(1, selector=lambda data: data.high)
```

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/consolidating-data/consolidator-types/renko-consolidators/classic-renko-consolidators>

### How do I create volume bars?

Use a `VolumeRenkoConsolidator` with the volume per bar as the bar size. For example, a bar size of 10,000 produces a new bar every time 10,000 units of the security trade. If one time step trades more than two bar sizes, the consolidator produces several bars in that time step. This consolidator accepts `TradeBar` and `Tick` input.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/consolidating-data/consolidator-types/renko-consolidators/volume-renko-consolidators>

### How do I build bars on top of other consolidated bars?

Use a `SequentialConsolidator`, which feeds the output of a first consolidator into a second consolidator. For example, combine a 1-day `TradeBarConsolidator` with a 3-count `TradeBarConsolidator` to produce bars that span three daily bars.

```python
self._consolidator = SequentialConsolidator(TradeBarConsolidator(timedelta(days=1)), TradeBarConsolidator(3))
```

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/consolidating-data/consolidator-types/combining-consolidators>

### How do I include or exclude extended market hours in consolidated bars?

Use a `MarketHourAwareConsolidator` and set its `extended_market_hours` (`extendedMarketHours`) argument. If the argument is `False` (`false`), the consolidator ignores data while the market is closed and anchors bars to the regular open. If it's `True` (`true`), it anchors bars to the start of extended market hours, which the security subscription must also include.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/consolidating-data/consolidator-types/market-hour-aware-consolidators>

## Historical Data

### Why does my history request return fewer bars than I asked for, or no data at all?

A LEAN history request returns fewer data points when the asset had no trading activity in the period you request. You also get no data before an asset's IPO date, because that data doesn't exist. Trailing time periods and date ranges return nothing when the whole period falls outside the asset's market hours. A known data issue also leaves a gap until the Data Team resolves it.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/history-responses#05-Missing-Data-Points>

### What is the difference between requesting a number of bars and requesting a time period?

When you pass an integer to `self.history` (`History`), LEAN returns that many bars based on the asset's market hours. The bars can cross several trading days. When you pass a `timedelta` (`TimeSpan`), the days are calendar days. For example, a 3-day `timedelta` over a weekend returns fewer bars than 3 trading days. If the period falls outside market hours, the result is empty.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/history-requests#03-Trailing-Time-Periods>

### How much historical data can I get with the QuantConnect data provider?

The QuantConnect data provider streams data to live algorithms, and its trailing history depends on the resolution of your data subscriptions. Daily and hour resolution give all historical data. Minute resolution gives 1 year, second resolution gives 2 months, and tick resolution gives 1 month. The same limits apply when you run warm-up with this provider.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/common-errors#06-QC-Data-Provider-Limitations>

### Why is the previous day's data missing from my backtest?

Backtests have access to data up to 1 to 4 days before the current day. The exact delay depends on the data type. If your backtest end date includes today or the last few days, the most recent bars might not be available yet.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/live-trading#02-Data-Delays>

### What resolution and data type does a history request use if I don't specify them?

If you have a subscription with a matching `Symbol`, `self.history` (`History`) uses the resolution of that subscription. Without a matching subscription, the default resolution is `Resolution.MINUTE` (`Resolution.Minute`). The default data type is `TradeBar`. If the asset doesn't have `TradeBar` data, such as Forex or CFDs, request the `QuoteBar` type instead.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/history-requests#06-Default-Values>

### Why does my history request for options or an index return nothing at second or tick resolution?

Equity Options, Index Options, Future Options, and Index history requests only return data at minute, hour, or daily resolution. Request one of these resolutions to get data. For US Equity `QuoteBar` history, request second or minute resolution data instead.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/asset-classes/equity-options#02-Trades>

### Do history requests fill forward data and include extended market hours?

By default, history requests use the fill-forward and extended market hours settings of the security subscription. To override them for one request, pass the `fill_forward` (`fillForward`) and `extended_market_hours` (`extendedMarketHours`) arguments to `self.history` (`History`). Any option you omit takes its value from the subscription.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/history-requests#07-Additional-Options>

### How do I get raw, unadjusted prices from a history request?

Pass `data_normalization_mode=DataNormalizationMode.RAW` (`dataNormalizationMode: DataNormalizationMode.Raw`) to `self.history` (`History`). This argument sets the price scaling mode for US Equities and continuous Futures contracts. If you omit it, the request uses the normalization mode of the security subscription.

```python
history = self.history(symbol, 10, Resolution.DAILY, data_normalization_mode=DataNormalizationMode.RAW)
```

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/history-requests#07-Additional-Options>

### What time zone are the timestamps in a history result?

The timestamps in a history DataFrame use the exchange time zone of each asset. When the result is a list of `Slice` objects, the `time` (`Time`) of each slice uses the algorithm time zone. The `end_time` (`EndTime`) of each data object inside it uses the exchange time zone. The `datetime` arguments you pass for a date range use the algorithm time zone.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/history-responses#03-Slices>

### In what order does a history request return data?

History requests return data in ascending order, from oldest to newest. LEAN uses this order so you can feed the result into indicators to warm them up.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/history-responses>

### Should I request a DataFrame or a list of TradeBar objects?

In Python, `self.history` returns a DataFrame by default. If you plan to build `TradeBar` or `QuoteBar` objects from the result, request that type directly with `self.history[TradeBar]` (`History<TradeBar>`). Otherwise, LEAN spends extra computation populating a DataFrame you don't use.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/history-responses#04-Lists>

### How do I get history for all the securities in my algorithm at once?

Call `self.history` (`History`) without any `Symbol` objects, for example `self.history(3)`. The method returns `Slice` objects with data for every data subscription in your algorithm. If you omit the resolution, each security uses the resolution of its subscription.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/history-responses#03-Slices>

### Why does a universe history request return data for every asset instead of my selected ones?

A history request on a universe object doesn't run your filter function. It returns all the assets and data points in the universe dataset, so you filter the result yourself. In Python, pass `flatten=True` to get a DataFrame with one column for each data point attribute.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/universe-data>

### How do I get historical splits, dividends, or ticks?

Splits, dividends, delistings, and ticks are sparse datasets, so most time steps have no data. Request them over a trailing time period or between start and end dates instead of a number of bars. For example, `self.history(Split, symbol, timedelta(365))` returns the splits of the last year.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/asset-classes/us-equities#07-Splits>

### Why does my alternative dataset history return only a few data points?

Many alternative datasets are sparse, which means they don't have data for every time step of their resolution. For example, some EIA series have daily resolution but update once a week. A 30-day request then returns only a few points. To check a dataset's resolution and sparsity, see its page in the Dataset Market.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/alternative-data#06-Sparse-Datasets>

### Why does my custom data history request return fewer samples than I expect?

History requests for a trailing number of samples use the market hours of the dataset. If the custom dataset's market hours don't match when your data occurs, the request returns fewer samples. Set the market hours of the custom dataset to fix the count. You must also define the `get_source` (`GetSource`) and `reader` (`Reader`) methods before you request history.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/custom-data#05-Missing-Data-Points>

### How do I get history for a continuous Futures contract?

Use the `symbol` (`Symbol`) property of the `Future` object that `self.add_future` (`AddFuture`) returns. To control how the contract is built, pass the `data_mapping_mode`, `data_normalization_mode`, and `contract_depth_offset` arguments to `self.history` (`History`). Omitted arguments use the values from the subscription.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/asset-classes/futures#06-Continuous-Contract-Prices>

### How do I get historical indicator values?

Call `self.indicator_history` (`IndicatorHistory`) with an indicator, a `Symbol`, and a period. The method resets the indicator, makes a history request, and updates the indicator with the data. In Python, use the `data_frame` property of the result to get a DataFrame. If you already have a list of `Slice` objects, pass them to skip the internal history request.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/asset-classes/us-equities#13-Indicators>

### What does the "start date will be adjusted if there are numerical precision errors in the factor files" warning mean?

Some factor files have infinite split values because the stock has split so many times that LEAN can't compute adjusted prices precisely. LEAN then moves the start date of the history request forward. To get the full range, request the data with raw data normalization.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/common-errors#04-Numerical-Precision>

### What is a warm-up period?

A warm-up period feeds historical data through your algorithm before it starts trading, so indicators and state are ready. In a backtest, warm-up moves the start of the data feed to an earlier time. In live trading, LEAN fetches data from before your deployment and fast-forwards your strategy to the present. Call `self.set_warm_up` (`SetWarmUp`) in `initialize` (`Initialize`).

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/warm-up-periods>

### How does set_warm_up work when my securities have different resolutions?

When you pass an integer, LEAN finds the start of the warm-up for each security from the bar count, its resolution, and its trading calendar. It then uses the earliest start time for the whole algorithm. To use one resolution for all securities, pass a `resolution` argument, such as `self.set_warm_up(100, Resolution.DAILY)`. When you pass a `timedelta` (`TimeSpan`), warm-up covers that calendar period before the start date.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/warm-up-periods#02-Set-Warm-Up-Periods>

### Which resolution can I use for warm-up if I have automatic indicators or consolidators?

If you pass a `resolution` to `self.set_warm_up` (`SetWarmUp`), it must be less than or equal to the lowest resolution of your automatic indicators and consolidators. For example, if your indicators use minute data, you can warm up with tick, second, or minute data.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/warm-up-periods#02-Set-Warm-Up-Periods>

### Why do I get "This operation is not allowed in Initialize or during warm up: OrderRequest.Submit"?

You can't place orders during the warm-up period, because the data feed replays historical data to set the algorithm state. Check `self.is_warming_up` (`IsWarmingUp`) before you trade, or move the order code to the `on_warmup_finished` (`OnWarmupFinished`) event handler.

```python
def on_data(self, data: Slice) -> None:
    if self.is_warming_up:
        return
```

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/common-errors#03-Trading-During-Warm-Up>

### Why are my indicators still not ready after warm-up?

Indicators need enough historical data to produce a valid value. If they require more data than exists, such as data before an asset's IPO, warm-up can't make them ready. In that case, `self.indicator_history` (`IndicatorHistory`) also returns fewer data points than you request.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/common-errors#02-Indicator-Readiness>

### Should I use warm-up or history requests?

Warm-up takes one line of code and runs universe selection and updates all indicators with historical data. It provides data for every subscription in your algorithm, so it can supply more data than you need. If your algorithm has derivative assets such as Options, warm-up can be very slow. A history request returns only the data you ask for.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/warm-up-periods#07-Warm-Up-vs-History-Requests>

### Why does my live algorithm time out during warm-up or history requests?

History requests from third-party data providers and brokerages are much slower than from the QuantConnect data provider. For example, Interactive Brokers can time out if you warm up or request history for too many assets. Request small amounts of data from these providers, or the algorithm can take longer than 10 minutes and time out.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/live-trading#04-Data-Provider-Limitations>

### Why does my live algorithm pause while it makes a history request?

In live trading, history requests take 5 to 10 seconds to return data. While LEAN processes the request, the algorithm doesn't receive any new data points.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/live-trading#02-Data-Delays>

### Why does a live history request return different intraday data than the same request in a backtest?

In live trading, a minute history request at noon returns backtest data for previous days and live data collected this morning for today. The data vendor post-processes the data later. A backtest of the same period can therefore show slightly different values for that day.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/common-errors#05-Live-Intraday-Data-Source>

### Why don't live history requests for Index or Options include today's data?

In live trading with the QuantConnect data provider, Index and Options history requests only return data up to the end of the previous day. For example, a request at 1 PM Eastern Time returns no data for the current day.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/common-errors#06-QC-Data-Provider-Limitations>

### How does LEAN choose a data provider when I select more than one?

The order in which you select live data providers sets their precedence. For example, with QuantConnect first and Interactive Brokers second, LEAN only uses Interactive Brokers for securities that QuantConnect doesn't provide. This setup lets you use QuantConnect data for Equity universe selection and trade Options on the selected securities.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/live-trading#03-Multiple-Data-Providers>

### What data do I still get if I remove the QuantConnect data provider from my cloud deployment?

Without the QuantConnect data provider, you get no price data from QuantConnect. You still get the US Equity Security Master, US Futures Security Master, US Equity Option Universe, and US Index Option Universe. Universe selection datasets and non-streaming alternative datasets also remain available.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/common-errors#05-Live-Intraday-Data-Source>

### Is a RollingWindow better than calling history on every step?

Yes. A `RollingWindow` holds a fixed number of trailing data points, and you add only the latest point each step. A history request fetches all the data over the period every time, which costs more. To fill the window at the start, make one history request and add each result to the window.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/rolling-window#04-Warm-Up>

### Which index of a RollingWindow holds the most recent value?

Index 0 holds the most recent item, and the largest index holds the oldest item. Index -1 also refers to the latest item. When you cast a `RollingWindow` to a list, reverse the list so the most recent item is last. Use the `is_ready` (`IsReady`) flag to check if the window is full.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/rolling-window#07-Access-Data>

### How do I get the previous session's open, high, low, and close without a history request?

Use the `session` (`Session`) property of the `Security` object, which is a `RollingWindow` of trading sessions. Its size is zero by default, so set it to 2 to keep the previous session at index 1. Increase the size to store more sessions.

```python
session = self.securities[self._symbol].session
session.size = 2
if session.is_ready:
    yesterday_close = session[1].close
```

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/rolling-window#11-Market-Session>

## Trading and Orders

### Why doesn't `set_holdings` place an order or log anything?

The `self.set_holdings` (`SetHoldings`) method skips the order when the target weight translates to a zero quantity. It also skips orders smaller than the `minimum_order_margin_portfolio_percentage` (`MinimumOrderMarginPortfolioPercentage`) algorithm setting, which defaults to 0.1% of the portfolio. In both cases, LEAN places no order and writes no log message. To trade small positions, calculate the quantity with `self.calculate_order_quantity` (`CalculateOrderQuantity`) and place the order yourself, or lower the setting.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/position-sizing>

### Why is my market order converted to a market on open order?

LEAN converts a market order to a market on open (MOO) order when you place it while the market is closed. This most often happens with daily or hourly data, which your algorithm can receive after the close. To keep market orders as market orders, check `self.is_market_open(symbol)` (`IsMarketOpen`) before you place them. If you liquidate while the market is closed, LEAN also converts the liquidation orders to MOO orders.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/order-errors>

### Why do I get "Insufficient buying power" when I trade with daily data?

With daily data, `set_holdings` and `calculate_order_quantity` size the order from the closing price, but the order fills at the next open. If the price gaps up overnight, the order can cost more than your buying power. By default, `set_holdings` keeps a 0.25% cash buffer. Widen it with `self.settings.free_portfolio_value_percentage` (`FreePortfolioValuePercentage`) in `initialize`, or trade with intraday data while the market is open.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/position-sizing>

### Why does my order fail with a zero price or "security has no data" error right after I add the security?

LEAN needs the last known price of a security to check buying power. If you subscribe to a security and order it in the same time step, the algorithm hasn't received a price yet. Enable `self.settings.seed_initial_prices = True` (`SeedInitialPrices`) so LEAN seeds new securities with their last known price.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/order-errors>

### Why did my stop order become Invalid after another order filled?

When an order fills, LEAN checks that you still have enough capital to maintain your remaining open orders and cancels the ones you can't afford. For example, say you sell part of a position with a market order. An open stop order for the full original quantity can then become `OrderStatus.INVALID` (`Invalid`). Resize or cancel your exit orders when you change the position.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/key-concepts>

### How do I place a stop-loss and take-profit order together?

Call `self.bracket_order` (`BracketOrder`) with the quantity, the take-profit price, and the stop-loss price. When the entry order fills completely, LEAN places a take-profit limit order and a stop-loss stop market order, and cancels one when the other fills. For other combinations, such as one cancels other (OCO) or one triggers other (OTO) sets, build the orders with `self.order_factory` (`OrderFactory`).

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/order-types/contingent-orders/bracket-orders>

### Which brokerages support contingent orders like brackets and OCO orders?

Alpaca, Binance, Charles Schwab, Interactive Brokers, and TradeStation support contingent orders. Each brokerage can limit the contingency types, the number of orders in a set, and the securities in a set. If the brokerage model doesn't support a set, LEAN invalidates every order in the set.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/order-types/contingent-orders>

### In a backtest, which leg of a bracket order fills if the bar touches both the stop and the target?

LEAN fills the stop order first. If an OCO or OUO order and its siblings can all fill with the same data, LEAN fills the stop orders first. As a result, a bracket order exits at the stop loss. Child orders don't fill with prices from before they trigger, except market orders, which fill right away.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/order-types/contingent-orders/bracket-orders>

### Can I cancel a market order?

No. LEAN transmits market orders to the brokerage immediately, so you can't cancel them. You can cancel other open order types with the `cancel` (`Cancel`) method of the order ticket, except during warm-up and initialization.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/order-management/order-tickets>

### How do I update an order if my brokerage doesn't support order updates?

Cancel the order and wait for the order event that confirms it's no longer active, then place a new order. To update an order on brokerages that support it, call the `update` (`Update`) method of its `OrderTicket` with an `UpdateOrderFields` object. The Orders section of each brokerage model page lists what it supports.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/order-management/order-tickets>

### Why does my order method fail when I pass a tag?

The `asynchronous` parameter comes before the `tag` parameter in the order methods. If you pass the tag by position, it fills the `asynchronous` parameter and the call fails. Pass the tag as a named argument, such as `tag="Entry"`.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/position-sizing>

### Why doesn't an `OrderEvent` have a tag or a time?

The `OrderEvent` object doesn't store the tag or a local time. To get the tag, read `order_event.ticket.tag`. To get the event time, read `order_event.utc_time` (`UtcTime`), which is in UTC. The fee is in `order_event.order_fee.value`.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/order-events>

### When can I place market on close orders?

By default, you must place market on close (MOC) orders at least 15.5 minutes before the close. Otherwise, LEAN rejects them with the `MarketOnCloseOrderTooLate` error. To change the buffer, set `MarketOnCloseOrder.submission_time_buffer` (`SubmissionTimeBuffer`). Markets that trade 24/7, such as Crypto, don't support MOC or market on open orders.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/order-types/market-on-close-orders>

### Can I place market on open orders during regular trading hours?

No. If you place a market on open (MOO) order during regular trading hours, LEAN rejects it with the `MarketOnOpenNotAllowedDuringRegularHours` error. You can submit MOO orders for Equities, Equity Options, and Index Options outside regular hours. MOO orders aren't available for Futures, Future Options, or markets that trade 24/7.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/order-types/market-on-open-orders>

### How long does a market order block my algorithm?

Market orders are synchronous by default, so your algorithm waits for the fill. If the fill takes longer than five seconds, the algorithm continues without it. To change the timeout, set `self.transactions.market_order_fill_timeout` (`MarketOrderFillTimeout`). To skip the wait entirely, pass `asynchronous=True` to the order method.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/order-types/market-orders>

### Is a stop market or trailing stop order guaranteed to fill at the stop price?

No. Stop market and trailing stop orders fill as market orders once the price reaches the stop price. If the market gaps past your stop, the order fills at a worse price. To control the fill price, use a stop limit order, which might not fill at all.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/order-types/stop-market-orders>

### Why does LEAN round my limit price and log a precision warning?

Limit, stop, and trigger prices must be a multiple of the security's `minimum_price_variation` (`MinimumPriceVariation`). If a price isn't, LEAN rounds it and logs a warning such as "order LimitPrice was rounded to 6136.00 from 6136.125". To avoid the warning, round the price yourself before you place the order.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/order-errors>

### Why doesn't `TimeInForce.DAY` cancel my order when I use daily data?

In markets that trade 24 hours a day, a `DAY` order cancels at the market close time. With daily data, your algorithm receives the bar at midnight, so the order expires before you can act on it. The default time in force is `GOOD_TIL_CANCELED`. Market on open and market on close orders don't support `good_til_date` and switch to `GOOD_TIL_CANCELED`.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/order-properties>

### How do I check if I can afford an order before I place it?

Create the order request with `self.order_factory` (`OrderFactory`) and convert it to an `Order` with `Order.create_order`. Then compare its initial margin requirement with the buying power of your portfolio in the order direction. For Option strategies, pass the orders of all legs to `self.portfolio.has_sufficient_buying_power_for_order`, which accounts for position groups.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/order-management/order-factory>

### How do I estimate the fee of an order before I place it?

Create the order request with `self.order_factory` (`OrderFactory`), convert it to an `Order`, and pass it with its security to the fee model of the security. The result is a modeled fee that LEAN calculates inside your algorithm. In live trading, the fee your brokerage charges can differ, especially when it depends on the fill price or quantity.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/order-management/order-factory>

### What does the leverage mean in `calculate_order_quantity`?

The target weight you pass is unleveraged. If you have 2x leverage and request a 100% weight, `self.calculate_order_quantity` (`CalculateOrderQuantity`) returns the quantity that uses half of your available margin. The method also accounts for the lot size and the fee model of the security.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/position-sizing>

### In what order does `set_holdings` place orders for a list of targets?

If you pass a list of `PortfolioTarget` objects, LEAN places the orders that reduce positions before the orders that increase positions. This order frees buying power before you use it. Each `PortfolioTarget` value is a portfolio weight in this case, so don't use `PortfolioTarget.percent`. To liquidate assets that aren't in the list, pass `liquidate_existing_holdings=True`.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/position-sizing>

### Why do I get "Insufficient buying power" on a Crypto order?

Crypto trades need a balance of the base or quote currency in your cash book. By default, the account currency is USD with $100,000, and some exchanges don't support fiat currencies. In that case, set the account currency to the quote currency, such as `self.set_account_currency("USDT", 1000)` (`SetAccountCurrency`). To move all your capital into one pair, first convert your other assets into its quote currency.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/crypto-trades>

### Why doesn't `liquidate` close my whole Crypto position?

For a Crypto pair such as BTCUSDT, `self.liquidate` (`Liquidate`) only closes the quantity of the virtual pair, which might not equal your full BTC balance. Calculate the order quantity from your cash book and place the order yourself. Fees can also leave a balance smaller than the lot size, which you can't sell, while `invested` (`Invested`) reads `False`.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/crypto-trades>

### Should I use `set_holdings` for Crypto?

No. For Crypto, calculate the order quantity from the currency amounts in your cash book and place manual orders. Leave room for fees, such as a 0.1% buffer.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/crypto-trades>

### How do I stop my algorithm from liquidating positions?

Set `self.settings.liquidate_enabled = False` (`LiquidateEnabled`). When this setting is `False`, the `liquidate` method doesn't place orders.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/liquidating-positions>

### What happens to my open orders when a stock splits?

If a split occurs before your order fills, LEAN adjusts the unfilled part of the order. It multiplies the quantity by the split factor and divides the limit, stop, and trigger prices by the split factor. You can't submit an Equity Option order in a time slice that contains a split of the underlying Equity.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/key-concepts>

### Which assets can I trade with combo orders?

Combo market and combo leg limit orders only work for Option contracts. Combo limit orders work for Option contracts and their underlying Equities. A combo order needs at least one leg with a positive quantity and one with a negative quantity. Each leg must target a different contract.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/order-types/combo-limit-orders>

### Which iron condor method do I use, and do I buy or sell it?

Always pass the strategy to `self.buy` (`Buy`), because the strategy definition already contains the short and long legs. In LEAN, the Long Iron Condor from `OptionStrategies.iron_condor` sells the near-OTM call and put and buys the far-OTM wings. The Short Iron Condor from `OptionStrategies.short_iron_condor` does the reverse, so it buys the near strikes and sells the wings. Check the leg table of each strategy page before you trade.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/option-strategies/short-iron-condor>

### Does LEAN exercise my Options automatically at expiration?

Yes. If you hold a long Option position that expires in the money, LEAN exercises it automatically on the expiration date. You can exercise American-style contracts earlier with `self.exercise_option` (`ExerciseOption`). You can't exercise European-style contracts before their expiry date, and you can only exercise long positions.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/order-types/option-exercise-orders>

### Can I use brokerage-specific order options like extended-hours fills or post-only orders?

Yes, if your brokerage supports them. Pass a brokerage-specific order properties object, or set `self.default_order_properties` (`DefaultOrderProperties`). Examples include `outside_regular_trading_hours`, `post_only`, `hidden`, `reduce_only`, and Crypto fee currency options. The Orders section of each brokerage model page lists the supported properties.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/order-properties>

### How many orders can a backtest place?

The order quota of a backtest depends on your organization tier. When you exceed it, LEAN rejects new orders with the `ExceededMaximumOrders` error. To stay within the quota, reduce the number of orders or upgrade your organization.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/order-errors>

### Why do I get "Order exceeds maximum shortable quantity"?

The shortable provider of your brokerage model reports fewer shares available to borrow than your order needs. LEAN rejects the order with the `ExceedsShortableQuantity` error. Check the shortable quantity before you short. The default brokerage model allows unlimited shorting.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/short-availability/key-concepts>

### Why can't I cancel or update my live order right after I place it?

In live trading, you can't update or cancel an order while it still has the `OrderStatus.NEW` status. LEAN rejects the request with the `InvalidNewOrderStatus` error. Wait until the brokerage accepts the order and its status changes, then send the request.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/order-errors>

### Does LEAN apply risk controls across multiple brokerage accounts?

No. LEAN calculates buying power only for the portfolio your algorithm connects to. QuantConnect doesn't support multi-account risk controls.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/pre-trade-risk-control>

### How does LEAN define a trade in the trade statistics?

By default, the `TradeBuilder` groups fills with `FillGroupingMethod.FILL_TO_FILL` and matches offsetting fills with `FillMatchingMethod.FIFO`. To change how LEAN groups and matches fills, call `self.set_trade_builder` (`SetTradeBuilder`) in `initialize`. To get the closed trades, read `self.trade_builder.closed_trades`.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/trade-statistics>

### How do I place Financial Advisor group orders with Interactive Brokers?

Create Account Groups in Trader Workstation and set the `fa_group` and `fa_method` of an `InteractiveBrokersOrderProperties` object. Your IB account code must start with F, FA, or I. You must connect as a member of a Trading Firm or Institution organization. Your algorithm portfolio aggregates the positions of all the groups.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/financial-advisors>

## Reality Modeling

### Which fill, fee, and slippage models does a backtest use by default?

The brokerage model sets them, and the default is the `DefaultBrokerageModel`. It uses the `EquityFillModel` for Equities, the `FutureFillModel` for Futures, the `FutureOptionFillModel` for Future Options, and the `ImmediateFillModel` for other assets. It charges no fees for Forex, CFD, and Crypto, uses the `InteractiveBrokersFeeModel` for other assets, and models zero slippage. To match your live brokerage, call `self.set_brokerage_model` (`SetBrokerageModel`).

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/brokerages/supported-models/quantconnect-paper-trading>

### Why did my market order fill at the ask price instead of the last trade price?

The `EquityFillModel` fills buy market orders at the best effort ask price plus slippage. It fills sell market orders at the best effort bid price minus slippage. It takes these prices from the latest quote tick or `QuoteBar`. It falls back to trade data only when quote data isn't available. As a result, a fill price can differ from the trade price you see on the chart.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/trade-fills/supported-models/equity-model>

### Why did my limit order fill at a better price than my limit price?

The `EquityFillModel` fills a buy limit order at the lower of the bar open and the limit price. It fills a sell limit order at the higher of the two. If the price gaps through your limit, the order fills at the open. The order fills once the bar's low crosses below the buy limit or its high crosses above the sell limit.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/trade-fills/supported-models/equity-model>

### Why doesn't my limit or stop order fill on the bar I place it?

To avoid look-ahead bias, the fill model doesn't fill limit and stop orders with data that has the order timestamp or older. It waits for the next bar that ends after the order time. The `EquityFillModel` also doesn't fill these orders with stale data.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/trade-fills/supported-models/equity-model>

### Why did my stop limit order not fill when a stop market order would have?

A stop limit order fills in two steps. First the price must reach the stop price, and then the price must also satisfy the limit price. A bar can cross the stop price without meeting the limit, so the stop market order fills and the stop limit order stays open.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/trade-fills/supported-models/equity-model>

### Do my Equity orders fill in pre-market or after-hours in a backtest?

The `EquityFillModel` only fills market and stop market orders during regular trading hours. Limit orders can fill outside regular hours if your algorithm subscribes to extended market hours. For live orders outside regular hours, your brokerage must support it, for example through the `outside_regular_trading_hours` order property.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/trade-fills/supported-models/equity-model>

### What is a stale fill, and why do I see the "stale price" warning?

A stale fill uses price data that is one hour or more old. It usually happens when you trade illiquid assets or trade intraday with Scheduled Events while you subscribe to daily data. The fill price might not be realistic. To change the threshold, set `self.settings.stale_price_time_span` (`StalePriceTimeSpan`).

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/trade-fills/key-concepts>

### Does LEAN simulate partial fills in backtests?

No. The pre-built fill models fill each order completely. In live trading, your orders can fill partially. To simulate partial fills in backtests, create a custom fill model.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/trade-fills/key-concepts>

### Why are my custom fill, fee, or slippage models ignored?

If you call `set_brokerage_model` after you set security-level models, the brokerage model overrides them. Set your models in a security initializer with `self.add_security_initializer` (`AddSecurityInitializer`), and call it before you create subscriptions. If you call `set_security_initializer` instead, you replace the default initializer that applies the brokerage model's models.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/key-concepts>

### How do I add slippage to my backtest?

The default brokerage model uses the `NullSlippageModel`, which models zero slippage. To add slippage, call `security.set_slippage_model` (`SetSlippageModel`) with a model such as the `ConstantSlippageModel`, the `VolumeShareSlippageModel`, or the `MarketImpactSlippageModel`. A custom slippage model returns the slippage in units of the asset price.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/slippage/key-concepts>

### Does LEAN model market impact?

The default models don't. Backtests assume you trade liquid assets, so large orders can fill at better prices than they would live. To model market impact, use the `MarketImpactSlippageModel` or create a custom fill model.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/slippage/supported-models>

### Does my fee model affect live trading?

Yes, partly. In live trading, helpers such as `set_holdings` use the fee model to calculate order quantities. However, LEAN updates your cash book with the actual fee that your brokerage reports.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/transaction-fees/key-concepts>

### How do I run a backtest with zero transaction fees?

Set the `ConstantFeeModel` with a fee of zero and the fee currency on each security. To apply it to every security, set it in a security initializer.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/transaction-fees/key-concepts>

### Does LEAN read my account type and leverage from my brokerage?

No. LEAN doesn't infer the account type, leverage, or margin requirements from your brokerage account. If you trade a cash account, pass `AccountType.CASH` to `set_brokerage_model`. If your algorithm allows more leverage than your brokerage, LEAN can place orders that the brokerage rejects.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/brokerages/key-concepts>

### Why did my brokerage reject an order that LEAN accepted?

LEAN checks orders against the buying power model in your algorithm, which can differ from your real account. For example, the default leverage of US Equities is 2x, but brokerages can lower the leverage of leveraged ETFs such as TQQQ during volatile periods. Match your algorithm's leverage, account type, and brokerage model to your real account.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/buying-power>

### How do I let my brokerage decide whether to accept an order?

Disable the buying power checks in LEAN. Call `security.set_buying_power_model(BuyingPowerModel.NULL)` (`SetBuyingPowerModel`) to disable the security-level check. Call `self.portfolio.set_positions(SecurityPositionGroupModel.NULL)` (`SetPositions`) to disable the position group check that LEAN applies to Option strategies.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/buying-power>

### Why is the margin of my Option spread lower than the sum of its legs?

LEAN groups your holdings into position groups. A position group, such as a bull call spread, has a lower margin requirement than the sum of its individual positions. You can't change the position group buying power models, but you can disable them.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/buying-power>

### When does LEAN issue margin calls in a backtest?

The `DefaultMarginCallModel` issues a margin call warning when your remaining margin is 5% or less of the total portfolio value. When your margin used exceeds the portfolio value plus a 10% buffer, it creates liquidation orders. It executes them starting with the lowest unrealized profit. To react first, implement the `on_margin_call_warning` and `on_margin_call` event handlers. To disable margin calls, set the `NullMarginCallModel`.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/margin-calls>

### What happens if my brokerage disconnects during live trading?

The default brokerage message handler stops the algorithm if the brokerage doesn't reconnect within 15 minutes while the market is open. If the market is closed, it checks again five minutes after the market opens. You can change these delays with the `initial_delay` and `open_threshold` arguments of the `DefaultBrokerageMessageHandler`.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/brokerage-message-handler>

### Does my algorithm process orders I place on my brokerage website?

No, except with Quant Bridge. Most QuantConnect brokerage integrations don't support brokerage-side orders, so your algorithm doesn't process orders you create in the brokerage website or third-party software. With Quant Bridge, a custom brokerage message handler can accept them.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/brokerage-message-handler>

### Which Option price model does LEAN use, and how do I change it?

By default, LEAN uses `OptionPriceModels.binomial_cox_ross_rubinstein()` for American Options and `OptionPriceModels.black_scholes()` for European Options. To change it, set the `price_model` (`PriceModel`) property of the Option. The legacy QuantLib models remain available under `OptionPriceModels.QuantLib`. If you set a model with an incompatible exercise style, LEAN throws an exception.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/options-models/pricing>

### How do I turn off Option Greeks calculations to speed up my backtest?

Set the price model of the Option to `OptionPriceModels.null()`. This model sets the implied volatility and Greeks to 0 and sets the theoretical price to the current price.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/options-models/pricing>

### How does LEAN decide when to assign my short Options in a backtest?

The `DefaultOptionAssignmentModel` scans your portfolio every hour. It considers American-style Options within 4 days of expiration and European-style Options on their expiration day. It assigns a short Option that is 5% in the money when the exercise is profitable after fees. To disable early assignments, set the `NullOptionAssignmentModel`.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/options-models/assignment>

### What volatility model does the Option price model use?

For Equity Options and Index Options, the default underlying volatility model is the `StandardDeviationOfReturnsVolatilityModel` with 30 days of daily data. For Future Options, it's the `NullVolatilityModel`. To use the volatility model as the initial guess for implied volatility, warm it up with a warm-up period or in a security initializer.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/options-models/volatility/key-concepts>

### Which risk free interest rate does LEAN use?

By default, LEAN uses the `InterestRateProvider`, which provides the primary credit rate from the Federal Open Market Committee (FOMC). The rate affects Option pricing and statistics like the Sharpe and Sortino ratios. To use a fixed rate, call `self.set_risk_free_interest_rate_model` (`SetRiskFreeInterestRateModel`) with a `ConstantRiskFreeRateInterestRateModel`.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/risk-free-interest-rate/key-concepts>

### Which dividend yield do Option models use?

For US Equity Options, the default `DividendYieldProvider` calculates a continuous yield from the dividends of the underlying Equity over the previous 350 days. For other Option types, it uses the dividends of SPY. To set a fixed yield, use the `ConstantDividendYieldModel`.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/dividend-yield/key-concepts>

### Does LEAN charge margin interest or short borrowing costs in backtests?

Not by default. The default brokerage model uses the `NullMarginInterestRateModel` and the `NullShortableProvider`, which lets you short any quantity. To model borrowing costs, use the `ShortMarginInterestRateModel`. To get borrowing rates and shortable quantities, use the `InteractiveBrokersShortableProvider`.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/short-availability/key-concepts>

## Scheduled Events

### Why does my Scheduled Event run at a different time in backtests than I set?

In backtests, Scheduled Events run inside the main algorithm loop, so they run when the next data arrives. For example, with minute US Equity data and regular trading hours, an event set for 2:00 AM runs at 9:31 AM. In live trading, Scheduled Events run on a separate thread with a real-time clock, so they run at the exact time you set.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/scheduled-events>

### Why does my Scheduled Event see the previous bar's price in live trading?

In live trading, bars arrive slightly after the top of the minute, but a Scheduled Event runs at the exact time. An event at 10:00 AM can run before the 9:59-10:00 AM bar arrives, so the latest price is from the 9:58-9:59 AM bar. In backtests, the event sees the 9:59-10:00 AM bar because data arrives at perfect timing.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/scheduled-events>

### Why does my Scheduled Event trade at stale prices?

You likely subscribe to daily data and trade intraday with a Scheduled Event, or you trade illiquid assets right after the open. In both cases, the latest price can be old, so the fill is unrealistic. Subscribe to a resolution that matches the time you trade, and give illiquid assets a few minutes after the open.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/scheduled-events>

### Why does my algorithm time out inside a Scheduled Event?

A Scheduled Event that runs for more than 10 minutes makes the algorithm time out. Several events that LEAN runs together in a backtest share this limit too. To run long tasks such as model training, replace the Scheduled Event with a training session through `self.train` (`Train`).

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/scheduled-events>

### Why doesn't my Scheduled Event call my method?

Check that you didn't call the callback when you passed it, such as `self._rebalance()` instead of `self._rebalance`. Pass the method itself without the trailing parentheses, so the scheduler can call it later.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/scheduled-events>

### Why does my market-hours time rule run at the wrong time for Futures or Crypto?

The `before_market_open`, `after_market_open`, `before_market_close`, and `after_market_close` time rules don't work for Crypto or custom data. These rules and the symbol-based date rules also accept a ticker string. If your algorithm hasn't added the security, LEAN assumes the string is a US Equity, so `'ES'` refers to a stock. Pass a `Symbol` instead, such as one from `Symbol.create` (`Symbol.Create`).

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/scheduled-events>

### How do I schedule an event on the last trading day of the week or month, accounting for holidays?

Pass a `Symbol` to the date rule, such as `self.date_rules.week_end(symbol)` (`DateRules.WeekEnd`) or `self.date_rules.month_end(symbol)` (`DateRules.MonthEnd`). With a `Symbol`, the rule uses the tradable days of that security. Without one, it uses calendar days.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/scheduled-events>

### Does `time_rules.every` only run during market hours?

No. The `self.time_rules.every(interval)` (`TimeRules.Every`) rule runs every period, starting at midnight, even when the market is closed. To limit it to trading days, combine it with a symbol-based date rule, and check `self.is_market_open` (`IsMarketOpen`) inside the callback.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/scheduled-events>

### How do I remove a Scheduled Event?

Save the object that `self.schedule.on` (`Schedule.On`) returns, then pass it to `self.schedule.remove` (`Schedule.Remove`).

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/scheduled-events>

## Indicators

### Why don't my indicator values match TradingView or another platform?

Indicator values differ when the inputs or settings differ. Common causes include slightly different price data and bar timestamps, because LEAN timestamps bars at the end of the period. Other causes include different default arguments, such as the moving average type, and different warm-up lengths for long-memory indicators like the EMA. To test, feed the other platform's prices into the LEAN indicator.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/indicators/key-concepts>

### Why is my indicator not ready, or why are its first values wrong?

LEAN indicators update online, one data point at a time, so they need enough data before their values are reliable. Check the `is_ready` (`IsReady`) property before you use an indicator. To prime it, set an algorithm warm-up period with `self.set_warm_up` (`SetWarmUp`), call `self.warm_up_indicator` (`WarmUpIndicator`), or update it with a history request.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/indicators/automatic-indicators>

### Why does my indicator update twice per bar?

You created an automatic indicator with a helper method, such as `self.sma` (`SMA`), and also updated it yourself or registered it with `self.register_indicator` (`RegisterIndicator`). Helper methods already register the indicator for automatic updates. Either use the helper method alone, or create the indicator with its constructor and update it yourself.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/indicators/automatic-indicators>

### What resolution can an automatic indicator use?

The indicator resolution must be greater than or equal to the resolution of the security subscription. For example, with minute data, the indicator can use minute, hour, or daily resolution. Some helper methods have optional arguments before `resolution`, so pass it by name.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/indicators/automatic-indicators>

### Why doesn't `automatic_indicator_warm_up` fill my indicator's history window?

The `self.settings.automatic_indicator_warm_up` (`AutomaticIndicatorWarmUp`) setting warms up most indicators, but not the built-in `RollingWindow` that stores their past values. It also doesn't work for every indicator. To fill the window, call `self.warm_up_indicator` (`WarmUpIndicator`) or `self.indicator_history` (`IndicatorHistory`).

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/indicators/automatic-indicators>

### Why are the first values of my daily indicator wrong when I create it mid-day?

If you create and warm up a daily indicator during the day, the warm-up history doesn't include the current day. The consolidator also misses the data from before you created it. Indicators that use only the close, such as the SMA, aren't affected. Indicators that use the open, high, or low, such as the True Range, can get an incorrect first bar.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/indicators/automatic-indicators>

### Do I need to reset my indicators after a stock split?

Yes, if you trade Equities live or backtest without the adjusted data normalization mode. After a split or dividend, the indicator mixes prices from before and after the event. Call the indicator's `reset` (`Reset`) method, then warm it up with `SCALED_RAW` data from a history request.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/indicators/key-concepts>

### How do I get the previous value of an indicator?

Index the indicator itself, where index 0 is the current value and index 1 is the previous value. You can also read the `previous` (`Previous`) property. The built-in window holds 2 values by default, so increase `window.size` to keep more.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/indicators/key-concepts>

### Which price does an indicator use for Forex or other quote data?

Data point indicators use the close price by default. For assets with `TradeBar` data, that's the trade close price. For assets with `QuoteBar` data, it's the mid-price of the bid and ask closes. To use another field, pass a `selector` argument such as `Field.HIGH` to the helper method or to `register_indicator`.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/indicators/automatic-indicators>

### How do I feed one indicator into another?

Use the indicator extensions. For example, `IndicatorExtensions.of(second, first)` (`Of`) feeds the current value of `first` into `second`. The second indicator must be a manual indicator without automatic updates, or it updates twice. Other extensions add, subtract, multiply, divide, and average indicator values.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/indicators/combining-indicators>

### How do I create my own indicator in Python?

Subclass `PythonIndicator` and define an `update` method plus `name`, `time`, and `value` attributes. The `update` method receives an `IndicatorDataPoint`, `QuoteBar`, or `TradeBar` and returns whether the indicator is ready. If you update the indicator manually, set `current`, define `is_ready`, and call `on_updated` so update events run.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/indicators/custom-indicators>

### How do I use indicators to select a universe?

Define a class that holds manual indicators for each security, and update those indicators inside the universe selection function. Use manual indicators so they only update during selection. You need this class because you can't add custom attributes to `Fundamental` objects.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/indicators/indicator-universes>

### Why can't I plot some indicators together with `plot_indicator`?

Indicators don't all share the same input type. Some take data points and others take bars, so you can't always plot them in one call. In that case, call `self.plot` (`Plot`) for each indicator and plot its `current.value`.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/indicators/plotting-indicators>

### How do I keep a history of recent bars without calling History every time?

Use a `RollingWindow`, which keeps a fixed number of trailing items. Index 0 is the most recent item. Consolidators also keep a built-in window of their last 2 bars. To track daily sessions, set the size of the security's `session` (`Session`) window to 2 or more.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/indicators/rolling-window>

## Object Store

### What is the Object Store, and who can access it?

The Object Store is a key-value file system for your algorithms. It's organization-specific, so all projects in your organization can save and read the same data. You can access it from backtests, the Research Environment, and live algorithms. It stores strings, JSON, XML, and bytes.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/object-store>

### Do I need a paid plan to use the Object Store?

In QuantConnect Cloud, you need a paid organization to access the Object Store, and you must stay within your storage quota. To read the quota in code, use the `max_size` (`MaxSize`) and `max_files` (`MaxFiles`) attributes. If you run LEAN locally, you can store as much data as your hardware allows.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/object-store>

### Why does my algorithm read old data from the Object Store after I updated it elsewhere?

LEAN caches Object Store data when you read or write it. If another project or node writes new data under the same key, your algorithm can keep returning the cached copy. Call `self.object_store.clear()` (`Clear`) and then read the key again. This applies to live algorithms when you update a key from outside the live node.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/object-store>

### Why does deleting a key raise an exception?

The `delete` (`Delete`) method raises an exception if the key doesn't exist. Check `self.object_store.contains_key(key)` (`ContainsKey`) before you delete or read a key.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/object-store>

### How do I get a file path for a library that needs one, such as a model loader?

Call `self.object_store.get_file_path(key)` (`GetFilePath`). It returns the local file path of the key. If the key doesn't exist yet, the method adds it to the Object Store.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/object-store>

### How do I keep my live algorithm's state between deployments?

Save the state to the Object Store and read it back in `initialize`. For example, save your insights as JSON in `on_end_of_algorithm` (`OnEndOfAlgorithm`) and add them back to the Insight Manager at the end of `initialize`. QuantConnect doesn't manage live algorithm state for you.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/object-store>

### What is the `.assistant` folder in my Object Store?

The `.assistant` folder is a reserved folder that the QuantConnect AI agents read. Its `skills`, `memories`, and `templates` subfolders hold the instructions, per-project facts, and project scaffolds for your organization. Everyone in your organization shares these files.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/object-store>

## Optimization

### Why does `get_parameter` return a string?

The `self.get_parameter` (`GetParameter`) method returns a string unless you pass a default value. If you pass a default, it returns the parameter as the same data type as the default, for example `self.get_parameter("period", 20)` returns an integer. If the project has no parameter with that name, the method returns the default.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/optimization/parameters>

### Can I change parameter values while my live algorithm runs?

No. LEAN sends the parameter values to your algorithm when you deploy it. To change values while it runs, add a Scheduled Event that downloads a remote file and updates the values from its contents.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/optimization/parameters>

### Why does my backtest look great with the optimized parameters but fail out of sample?

If you backtest the same period that you optimized on, the results include look-ahead bias. The optimal values weren't knowable during that period. Optimize on older data and test the values on recent data, or use walk forward optimization. Many parameters or values that are sensitive to small changes also suggest overfitting.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/optimization/parameters>

### How do I re-optimize parameters inside a running algorithm?

Use walk forward optimization. Pass `DateRules` and `TimeRules` to `self.train` (`Train`) to run an optimization method on a schedule over a trailing window of data. Frequent sessions fit recent data better, while longer gaps run faster and reduce the chance of overfitting.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/optimization/walk-forward-optimization>

## Machine Learning

### Which machine learning libraries can I use?

LEAN supports several libraries, including TensorFlow, scikit-learn, PyTorch, Keras, hmmlearn, tsfresh, Stable-Baselines3, fastai, DEAP, and XGBoost in Python, and Accord in C#. The Supported Libraries table lists the installed version of each library.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/machine-learning/key-concepts>

### Can you add a library that isn't installed?

Yes, you can request it. Contact QuantConnect, and the library joins the queue for a security review and deployment. The process takes 2-4 weeks. The Issues list of the LEAN GitHub repository shows the libraries under review.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/machine-learning/key-concepts>

### How do I train a model without hitting the 10-minute timeout?

Call `self.train` (`Train`) with your training method. Algorithms usually must process each time slice within 10 minutes, but `train` extends the time up to your training quota. To schedule training, pass `DateRules` and `TimeRules` arguments. We recommend you schedule training while the market is closed.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/machine-learning/training-models>

### How much training time do I get?

Training time uses a leaky bucket. You can use up to a maximum number of minutes in one session, and the available minutes refill over time. In QuantConnect Cloud, the Training Quotas page lists the limits. Locally, the defaults are a 120-minute capacity that refills by one seventh per day, and you can raise them in the LEAN configuration file.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/machine-learning/training-models>

### Why does my live algorithm use the model before training finishes?

In live trading, the `train` method runs asynchronously on a separate thread. In backtests, it blocks until training completes. In live trading, set a flag, such as a semaphore, when training finishes and check it before you use the model.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/machine-learning/training-models>

### Where should I save my trained model?

Save it in the Object Store. In live algorithms, save the model at the end of the training method so you keep it if the algorithm stops. In backtests, save it in `on_end_of_algorithm` (`OnEndOfAlgorithm`) to avoid slowing the backtest with repeated saves.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/machine-learning/key-concepts>

### How do I use a model that I trained on my own computer?

Upload the model file to the Object Store with the `lean cloud object-store set` CLI command or the Upload Object Store Files API endpoint. Use the same key that your algorithm passes to `self.object_store.get_file_path` (`GetFilePath`). In `initialize`, check that the key exists, then load the model from the file path.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/machine-learning/popular-libraries/keras>

### How do I speed up backtests that retrain or rerun a model every time?

Precompute the predictions in the Research Environment and save them to the Object Store. Your algorithm then reads them as a custom universe dataset, which skips feature engineering and inference at runtime. To avoid look-ahead bias, train the model on different data than the period you test.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/machine-learning/key-concepts>

### Can I use Hugging Face models?

Yes. You can load public Hugging Face models, such as FinBERT and Chronos, and fine-tune them with your own data. QuantConnect Cloud caches some popular models to load them faster. The Hugging Face Key Concepts page lists the cached models.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/machine-learning/hugging-face/key-concepts>

## Algorithm Framework

### Should I use the Algorithm Framework or the classic algorithm design?

Use the Algorithm Framework when its modules can work in isolation and you can reuse existing modules. It works best for portfolio rebalancing, ranking, and multi-factor strategies. Use the classic design if your algorithm uses special order types or passes information between components. A hybrid algorithm can also mix classic code with framework modules.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/algorithm-framework/overview>

### Why does my framework algorithm re-enter a position right after the risk model liquidates it?

The Risk Management model sets the target to zero, but the Alpha model still has an active insight for the security. When the Portfolio Construction model rebalances, it creates a new target from that insight. When a risk model liquidates a security, cancel the security's insights. Alternatively, put the stop-loss logic in the Alpha model and emit a flat insight.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/algorithm-framework/insight-manager>

### Why do my insights change names between backtests?

By default, LEAN names an Alpha model with a new GUID string on every backtest and live deployment. LEAN names the insights after their Alpha model. Assign a unique, fixed name to your Alpha model so its insights stay consistent and Portfolio Construction models can tell your Alpha models apart.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/algorithm-framework/alpha/key-concepts>

### How do I end an insight early when my exit signal occurs?

Cancel the insight. Call its `cancel` (`Cancel`) method with the algorithm UTC time, or call `self.insights.cancel` (`Insights.Cancel`) with the insights or their `Symbol` objects. LEAN sets the insight's close time one second into the past, so the Portfolio Construction model treats it as expired.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/algorithm-framework/insight-manager>

### Why does the Portfolio Construction model rebalance so often?

By default, Portfolio Construction models rebalance when their rebalance function signals a rebalance. They also rebalance when insights change or expire, and when the universe changes. To stop rebalancing on insight changes, set `self.settings.rebalance_portfolio_on_insight_changes = False`. To stop rebalancing on universe changes, set `self.settings.rebalance_portfolio_on_security_changes = False`.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/algorithm-framework/portfolio-construction/key-concepts>

### How does the Portfolio Construction model combine insights from several Alpha models?

The base `PortfolioConstructionModel` doesn't combine insights for the same `Symbol`. It uses the most recent active insight. To combine insights, override `get_target_insights` to return all active insights and implement the combination in `determine_target_percent`.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/algorithm-framework/portfolio-construction/key-concepts>

### Why can't my framework model access attributes of my algorithm class?

The `algorithm` argument that framework model methods receive is an instance of the base `QCAlgorithm` class, not your subclass. To reach your own members, pass a reference to the model constructor or use a global variable. Both approaches break the separation of concerns between framework modules.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/algorithm-framework/alpha/key-concepts>

### How do several Risk Management models work together?

LEAN runs them in sequence. The first model receives the targets from the Portfolio Construction model, and each later model receives the adjusted targets of the previous one. Each model should return only the targets it adjusts.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/algorithm-framework/risk-management/key-concepts>

### Can I use framework models in a classic algorithm?

Yes. You can add Universe Selection, Alpha, Portfolio Construction, Risk Management, and Execution models to a classic algorithm. To receive insights without a Portfolio Construction model, implement the `insights_generated` (`InsightsGenerated`) event handler. To use a Portfolio Construction model without an Alpha model, call `self.emit_insights` (`EmitInsights`).

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/algorithm-framework/hybrid-algorithms>

### Why are securities from my brokerage account in my universe even though my Universe Selection model didn't select them?

In live trading, LEAN adds the securities in your brokerage account to your user-defined universe. The Universe Selection model doesn't remove them. After you liquidate them, you can remove them, and `remove_security` liquidates a position automatically. To see them, check `self.active_securities` in `on_warmup_finished` (`OnWarmupFinished`).

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/algorithm-framework/universe-selection/key-concepts>

## Charting

### Why do I get "Exceeded maximum chart series count" or "Exceeded maximum points per chart"?

Backtests in QuantConnect Cloud have charting quotas for the number of series and the data points per series, which depend on your organization tier. If you exceed the series quota, the algorithm stops executing. If you exceed the points quota, LEAN skips the extra data points. To plot more, sample less often or create the plots in the Research Environment.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/charting>

### Are there charting quotas in live trading?

No. Live trading has no charting quotas. LEAN samples live charts every one and ten minutes. After a certain number of samples, the results page shows 1-minute custom charts at 10-minute granularity.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/live-trading/charting-and-logging>

### Which chart and series names can't I use?

The chart names "Assets Sales Volume", "Exposure", and "Portfolio Margin" are reserved. Some series names in the default charts are also reserved. Examples include "Equity" and "Return" in "Strategy Equity", "Equity Drawdown" in "Drawdown", and "Benchmark" in "Benchmark".

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/charting>

### How do I put two series on separate panes of the same chart?

Give each series a different `index` when you create it. Series with the same index overlap each other, and series with different indexes display separately.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/charting>

### Do I need to create a chart before I call `plot`?

No. If the chart or series you name in `self.plot` (`Plot`) doesn't exist, LEAN creates it. The default series is a line with a "$" unit on index 0. To set another type, such as a scatter or candlestick series, create the `Chart` and `Series` objects in `initialize` and add them with `self.add_chart` (`AddChart`).

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/charting>

### Why do fill markers on the asset plot differ from the plotted price?

The asset plot shows fills at the actual fill price, which can differ from the displayed price. Slippage, fills at the bid or ask with quote data, and fills at the high or low of a bar all cause differences. When you zoom out, the plot also shows daily closing prices and groups the day's order events together.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/charting>

## Logging

### Why are some of my log messages missing?

LEAN only adds the first instance of identical log content to the log file. To log the same text repeatedly, add a timestamp to the message. LEAN also rate-limits debug and error messages when you send several within one second. In QuantConnect Cloud, the total log length is capped by your organization tier.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/logging>

### How much can I log?

In QuantConnect Cloud, your organization tier caps the logs per backtest and per day, and the Resources page lists the quotas. For live trading, each project's log files keep up to 100,000 lines for up to one year, and LEAN removes the oldest lines beyond that. If your organization hits the daily limit, contact QuantConnect.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/organizations/resources>

### How do I avoid using up my log quota?

Log the condition you investigate instead of every pass through `on_data` or a frequent Scheduled Event. Route diagnostics through one method that checks a verbosity level, and record the reason for each trade in the order `tag`. To log only in live trading, check `self.live_mode` (`LiveMode`).

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/logging>

### Why does my algorithm keep running after I call `quit`?

The `self.quit` (`Quit`) method stops the algorithm, but the current method runs to its end. To stop immediately, return right after you call `quit`.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/logging>

### Can I log dataset values?

No. Logging dataset information isn't permitted. Use log statements to debug the logic of your backtests and live algorithms.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/logging>

### Where do I find the logs of my backtests and live algorithms?

In QuantConnect Cloud, open the backtest or live results page, or read live logs with the `/live/read/log` API endpoint. For local runs, read the log file in the project's `backtests` or `live` folder, or run the `lean logs` CLI command.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/logging>

## Statistics

### Why doesn't my Sharpe ratio match my own calculation?

Some LEAN statistics depend on the risk free interest rate and the number of trading days per year. By default, LEAN uses the FOMC primary credit rate and 252 trading days. To match your calculation, set the same values with `self.set_risk_free_interest_rate_model` (`SetRiskFreeInterestRateModel`) and `self.settings.trading_days_per_year` (`TradingDaysPerYear`).

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/statistics/algorithm-statistics>

### How do I add my own statistic to the backtest results?

Call `self.set_summary_statistic` (`SetSummaryStatistic`) with a name and value to add it to the summary statistics. For a statistic that updates while the algorithm runs, call `self.set_runtime_statistic` (`SetRuntimeStatistic`). Don't set the default runtime statistics, because LEAN overwrites them.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/statistics/algorithm-statistics>

### Why is the statistic value I read in my algorithm a string?

The values in the `summary` and `runtime_statistics` (`RuntimeStatistics`) dictionaries are strings. Cast the value to the type you need, such as a float.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/statistics/runtime-statistics>

### How do I check how much memory and CPU my algorithm uses?

Read the server statistics. They show the CPU usage, used RAM, total RAM, LEAN version, and uptime of the node. Inside the algorithm, call `OS.get_server_statistics` (`GetServerStatistics`). Results from older LEAN versions don't include them.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/statistics/server-statistics>

## Live Trading

### Why do my live results differ from my backtest over the same period?

Backtests are simulations, so live and backtest results usually differ. The main sources are differences in data, reality modeling, brokerage behavior, and Scheduled Event timing. To measure the gap, QuantConnect runs an out-of-sample backtest in parallel to each live deployment and compares the two equity curves. Add log statements to your live algorithm so you can compare its behavior with the backtest.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/live-trading/reconciliation>

### Why does a condition on an exact time work in backtests but never in live trading?

In backtests, data arrives at predictable times for its resolution. In live trading, data arrives when it's available, with microsecond precision, so a condition such as `time == 9:30:15` never matches. Use a time range, a rounded time, or a Scheduled Event instead.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/live-trading/reconciliation>

### Why are my live fill prices different from the backtest?

In live trading, your brokerage sets the fill price and fee, and orders take about half a second to execute. In backtests, orders fill immediately at the best price in the data. A live limit order can fill at another price or not fill, depending on its position in the order book. The backtest also doesn't model market impact.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/live-trading/reconciliation>

### Why do my live indicators give different signals after a split or dividend?

Backtests use adjusted prices by default. In live trading, after a split or dividend, your indicators contain prices from before and after the event. Reset and warm up your indicators when your algorithm receives a corporate event.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/live-trading/reconciliation>

### Why does my live equity curve differ when my account already has positions?

Your live equity curve includes the existing positions in your brokerage account, but the backtest starts without them. Deploy to a separate account or subaccount without positions. With a small account, whole-share rounding also makes allocations inexact, so start the backtest with the same cash as the live deployment.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/live-trading/reconciliation>

### Does my live algorithm start with an empty portfolio?

No. LEAN loads the holdings and open orders from your brokerage account, so your algorithm can start with positions from earlier deployments. Paper trading starts without a portfolio and tries to restore the state of the previous deployment.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/live-trading/brokerages>

### Does my live algorithm keep its state when I redeploy or it restarts?

QuantConnect doesn't manage live algorithm state for you, and LEAN doesn't save orders or order tickets across deployments. When you deploy, LEAN loads your open orders from the brokerage. Rebuild indicators with warm-up and history requests, and save other state to the Object Store.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/live-trading/key-concepts>

### How do I add or withdraw cash while my live algorithm runs?

Deposit or withdraw through your brokerage account. LEAN syncs the algorithm's cash with your brokerage account every day at 7:45 AM Eastern Time.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/live-trading/brokerages>

### How do I handle a brokerage disconnection in my algorithm?

Implement the `on_brokerage_disconnect` (`OnBrokerageDisconnect`) and `on_brokerage_reconnect` (`OnBrokerageReconnect`) event handlers. When LEAN reconnects, it syncs your orders and portfolio. If an order filled while the connection was down, LEAN updates the portfolio, but you don't receive an order event for the fill.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/live-trading/brokerages>

### How do I react to warnings and errors from my brokerage?

Implement the `on_brokerage_message` (`OnBrokerageMessage`) event handler, which receives a `BrokerageMessageEvent`. For example, you can send yourself a notification from it. To handle messages outside your algorithm class, set a custom brokerage message handler.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/live-trading/brokerages>

### Why was my live order canceled?

Your brokerage can cancel an order before it fills completely. For example, if the price moves before the open so you can't afford a market on open order, the brokerage rejects it. If the brokerage rejects a new order or an update, LEAN sets the status to `OrderStatus.CANCELED`. Orders that fail LEAN's own checks get `OrderStatus.INVALID` and never reach the brokerage.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/live-trading/trading-and-orders>

### When does daily data arrive in live trading?

Most data arrives at the same time as in backtests. Daily data is often delayed by 4-8 hours, arriving between 4 AM and 8 AM. US Equity universe selection data is processed at 7-8 AM Eastern Time.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/live-trading/key-concepts>

### How much latency does live trading have, and can I use it for high-frequency trading?

QuantConnect data latency is 5-40 milliseconds, depending on the data provider. Brokerage order round trips take 100 milliseconds to 5 seconds, which matters more. QuantConnect isn't intended for high-frequency trading.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/live-trading/key-concepts>

### What data do I lose if I don't use the QuantConnect data provider?

You lose all QuantConnect price data. You keep the following auxiliary datasets:

- US Equity Security Master
- US Futures Security Master
- US Equity Option Universe
- US Index Option Universe
- Universe selection datasets
- Alternative datasets that don't stream

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/live-trading/data-providers>

### Why does my tick algorithm behave differently in live trading?

In backtests, LEAN groups ticks into slices that span 1 millisecond. In live trading, slices span up to 70 milliseconds. Make your logic work with both slice sizes.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/live-trading/reconciliation>

### How do I send myself an email or other alert from my algorithm?

Call a method of `self.notify` (`Notify`), such as `email`, `sms`, `telegram`, `web`, `ftp`, or `sftp`. For example, call `self.notify.email` from `on_order_event` to email each fill. Email bodies hold up to 10KB of text, and SMS messages hold up to 1,600 characters. Notifications are only available in QuantConnect Cloud live trading, and free quotas depend on your tier.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/live-trading/notifications>

### How do I send alerts to Discord or my own server?

Call `self.notify.web` (`Notify.Web`), which sends an HTTP POST request to your URL with a 300-second timeout. For a Discord webhook, send a dictionary with a `content` key. Your server can process the request however you want.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/live-trading/notifications>

### Can I use notifications to send data out of the platform?

No. The notification system can't be used for data distribution.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/live-trading/notifications>

### How do I send instructions to my running live algorithm?

Use commands. Define the `on_command` (`OnCommand`) event handler, or define a `Command` subclass with a `run` (`Run`) method and register it with `self.add_command` (`AddCommand`). Then send a payload through the QuantConnect REST API or the LEAN CLI. Add a `$type` key with the class name to call a `Command` subclass.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/live-trading/commands>

### Why are the keys of my command payload capitalized in my algorithm?

LEAN converts the first character of each payload key to uppercase before it injects the data into your algorithm. Account for this when you read the command data.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/live-trading/commands>

### Can I trigger a command from a link in an email?

Yes. Call `self.link` (`Link`) to create a URL for a command, and send it to yourself with an email notification. When you click the link, the event handler runs in your algorithm.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/live-trading/commands>

### How can one live algorithm send signals to my other live algorithms?

Call `self.broadcast_command` (`BroadcastCommand`). It sends the command to all live algorithms in your organization except the source algorithm. For arbitrage strategies, add the project Id to the command data so the receivers know the source.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/live-trading/arbitrage>

### How do I send my portfolio to Collective2, Numerai, or my fund?

Add a signal export provider in `initialize` with `self.signal_export.add_signal_export_provider` (`SignalExport.AddSignalExportProvider`). LEAN sends your targets five seconds after an order fills, which you can change with `automatic_export_time_span`. To send targets yourself, call `set_target_portfolio`. For your own destination, implement the `ISignalExportTarget` interface.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/live-trading/signal-exports/key-concepts>

### How do I get the deployment Id or account details inside my algorithm?

Read `self.algorithm_id` (`AlgorithmId`), which holds the live deployment Id, such as `L-6bf91128391608d0728ff90b81bfca41`. Read `self.deployment_details` (`DeploymentDetails`) for the details your brokerage shares, such as the account. The keys depend on the brokerage, and the dictionary is empty in backtests.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/live-trading/brokerages>

## Strategy Library

### What is the Strategy Library?

The Strategy Library is a collection of tutorials that the QuantConnect team and community members write. Each tutorial explains a trading strategy from the academic literature and shows how to implement it with LEAN. You can search the tutorials by strategy name.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/strategy-library>

## API Reference

### Can I write algorithms in Python if LEAN is written in C#?

Yes. LEAN is written in C#, but you can write algorithms in Python or C#. LEAN uses Python.NET to bridge your Python code and the C# engine. The API Reference documents every class, method, property, and event for both languages.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/api-reference>

## Migrations

### What are the QuantConnect equivalents of Quantopian concepts?

The `initialize` method matches Quantopian's `initialize`, and `self` replaces the `context` parameter. Universes replace Pipelines, and Scheduled Events replace `schedule_function`. Data arrives in `on_data` (`OnData`) as a `Slice`, which holds one point in time, so request past data with `self.history` (`History`).

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/migrations/zipline/initialization>

### How do I replace Quantopian's ordering methods?

Use `self.set_holdings` (`SetHoldings`) instead of `order_target_percent`, and `self.order` (`Order`) instead of `order`. Quantopian's `StopOrder` style is `stop_market_order` (`StopMarketOrder`) in LEAN. To replace `order_optimal_portfolio`, use a Portfolio Construction model.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/migrations/zipline/ordering>

### How do I set slippage and commissions like in Quantopian?

LEAN sets slippage and fees for each security. Set the slippage model and the fee model on the `Security` object that adding the data returns. To apply them to many securities, use a security initializer.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/migrations/zipline/initialization>

