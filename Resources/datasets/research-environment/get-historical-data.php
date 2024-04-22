<p>You need a <a href='<?=$createSubscriptionsLink?>'>subscription</a> before you can request historical data for <?=$assetClass?> contracts. On the time dimension, you can request an amount of historical data based on a trailing number of bars, a trailing period of time, or a defined period of time. On the contract dimension, you can request historical data for a single contract, a subset of the contracts you created subscriptions for in your notebook, or all of the contracts in your notebook.</p>

<p>Before you request historical data, call the <code class="csharp">SetStartDate</code><code class="python">set_start_date</code> method with a <code class='python'>datetime</code><code class='csharp'>DateTime </code> to reduce the risk of <a href='/docs/v2/writing-algorithms/key-concepts/glossary#17-look-ahead-bias'>look-ahead bias</a>.</p>
<div class='section-example-container'>
    <pre class='csharp'>qb.SetStartDate(startDate);</pre>
    <pre class='python'>qb.set_start_date(start_date)</pre>
</div>
<p>If you call the <code class="csharp">SetStartDate</code><code class="python">set_start_date</code> method, the date that you pass to the method is the latest date for which your history requests will return data.</p>

<h4>Trailing Number of Bars</h4>
<p>To get historical data for a number of trailing bars, call the <code>History</code> method with the contract <code>Symbol</code> object(s) and an integer.</p>
<div class='section-example-container'>
    <pre class='csharp'>// Slice objects
var singleHistorySlice = qb.History(<?=$contractVariableC?>, 10);
var subsetHistorySlice = qb.History(new[] {<?=$contractVariableC?>}, 10);
var allHistorySlice = qb.History(10);

// TradeBar objects
var singleHistoryTradeBars = qb.History&lt;TradeBar&gt;(<?=$contractVariableC?>, 10);
var subsetHistoryTradeBars = qb.History&lt;TradeBar&gt;(new[] {<?=$contractVariableC?>}, 10);
var allHistoryTradeBars = qb.History&lt;TradeBar&gt;(qb.Securities.Keys, 10);

// QuoteBar objects
var singleHistoryQuoteBars = qb.History&lt;QuoteBar&gt;(<?=$contractVariableC?>, 10);
var subsetHistoryQuoteBars = qb.History&lt;QuoteBar&gt;(new[] {<?=$contractVariableC?>}, 10);
var allHistoryQuoteBars = qb.History&lt;QuoteBar&gt;(qb.Securities.Keys, 10);

// OpenInterest objects
var singleHistoryOpenInterest = qb.History&lt;OpenInterest&gt;(<?=$contractVariableC?>, 400);
var subsetHistoryOpenInterest = qb.History&lt;OpenInterest&gt;(new[] {<?=$contractVariableC?>}, 400);
var allHistoryOpenInterest = qb.History&lt;OpenInterest&gt;(qb.Securities.Keys, 400);</pre>
    <pre class='python'># DataFrame of trade and quote data
single_history_df = qb.history(<?=$contractVariablePy?>, 10)
subset_history_df = qb.history([<?=$contractVariablePy?>], 10)
all_history_df = qb.history(qb.securities.keys, 10)

# DataFrame of trade data
single_history_trade_bar_df = qb.history(TradeBar, <?=$contractVariablePy?>, 10)
subset_history_trade_bar_df = qb.history(TradeBar, [<?=$contractVariablePy?>], 10)
all_history_trade_bar_df = qb.history(TradeBar, qb.securities.keys, 10)

# DataFrame of quote data
single_history_quote_bar_df = qb.history(QuoteBar, <?=$contractVariablePy?>, 10)
subset_history_quote_bar_df = qb.history(QuoteBar, [<?=$contractVariablePy?>], 10)
all_history_quote_bar_df = qb.history(QuoteBar, qb.securities.keys, 10)

# DataFrame of open interest data
single_history_open_interest_df = qb.history(OpenInterest, <?=$contractVariablePy?>, 400)
subset_history_open_interest_df = qb.history(OpenInterest, [<?=$contractVariablePy?>], 400)
all_history_open_interest_df = qb.history(OpenInterest, qb.securities.keys, 400)

# Slice objects
all_history_slice = qb.history(10)

# TradeBar objects
single_history_trade_bars = qb.history[TradeBar](<?=$contractVariablePy?>, 10)
subset_history_trade_bars = qb.history[TradeBar]([<?=$contractVariablePy?>], 10)
all_history_trade_bars = qb.history[TradeBar](qb.securities.keys, 10)

# QuoteBar objects
single_history_quote_bars = qb.history[QuoteBar](<?=$contractVariablePy?>, 10)
subset_history_quote_bars = qb.history[QuoteBar]([<?=$contractVariablePy?>], 10)
all_history_quote_bars = qb.history[QuoteBar](qb.securities.keys, 10)

# OpenInterest objects
single_history_open_interest = qb.history[OpenInterest](<?=$contractVariablePy?>, 400)
subset_history_open_interest = qb.history[OpenInterest]([<?=$contractVariablePy?>], 400)
all_history_open_interest = qb.history[OpenInterest](qb.securities.keys, 400)</pre>
</div>
<p>The preceding calls return the most recent bars, excluding periods of time when the exchange was closed.</p>
<? if ($assetClass == "Futures") { ?>
<p>To get historical data for the continous Futures contract, in the preceding history requests, replace <code class='python'><?=$contractVariablePy?></code><code class='csharp'><?=$contractVariableC?></code> with <code>future.Symbol</code>.</p>
<? } ?>
 
<h4>Trailing Period of Time</h4>
<p>To get historical data for a trailing period of time, call the <code>History</code> method with the contract <code>Symbol</code> object(s) and a <code class='csharp'>TimeSpan</code><code class='python'>timedelta</code>.</p>
<div class='section-example-container'>
    <pre class='csharp'>// Slice objects
var singleHistorySlice = qb.History(<?=$contractVariableC?>, TimeSpan.FromDays(3));
var subsetHistorySlice = qb.History(new[] {<?=$contractVariableC?>}, TimeSpan.FromDays(3));
var allHistorySlice = qb.History(10);

// TradeBar objects
var singleHistoryTradeBars = qb.History&lt;TradeBar&gt;(<?=$contractVariableC?>, TimeSpan.FromDays(3));
var subsetHistoryTradeBars = qb.History&lt;TradeBar&gt;(new[] {<?=$contractVariableC?>}, TimeSpan.FromDays(3));
var allHistoryTradeBars = qb.History&lt;TradeBar&gt;(TimeSpan.FromDays(3));

// QuoteBar objects
var singleHistoryQuoteBars = qb.History&lt;QuoteBar&gt;(<?=$contractVariableC?>, TimeSpan.FromDays(3), Resolution.Minute);
var subsetHistoryQuoteBars = qb.History&lt;QuoteBar&gt;(new[] {<?=$contractVariableC?>}, TimeSpan.FromDays(3), Resolution.Minute);
var allHistoryQuoteBars = qb.History&lt;QuoteBar&gt;(qb.Securities.Keys, TimeSpan.FromDays(3), Resolution.Minute);

<? if ($supportsTicks) { ?>
// Tick objects
var singleHistoryTicks = qb.History<Tick>(<?=$contractVariableC?>, TimeSpan.FromDays(3), Resolution.Tick);
var subsetHistoryTicks = qb.History<Tick>(new[] {<?=$contractVariableC?>}, TimeSpan.FromDays(3), Resolution.Tick);
var allHistoryTicks = qb.History<Tick>(qb.Securities.Keys, TimeSpan.FromDays(3), Resolution.Tick);
<? } ?>

// OpenInterest objects
var singleHistoryOpenInterest = qb.History&lt;OpenInterest&gt;(<?=$contractVariableC?>, TimeSpan.FromDays(2));
var subsetHistoryOpenInterest = qb.History&lt;OpenInterest&gt;(new[] {<?=$contractVariableC?>}, TimeSpan.FromDays(2));
var allHistoryOpenInterest = qb.History&lt;OpenInterest&gt;(qb.Securities.Keys, TimeSpan.FromDays(2));</pre>
    <pre class='python'># DataFrame of trade and quote data
single_history_df = qb.history(<?=$contractVariablePy?>, timedelta(days=3))
subset_history_df = qb.history([<?=$contractVariablePy?>], timedelta(days=3))
all_history_df = qb.history(qb.securities.keys, timedelta(days=3))

# DataFrame of trade data
single_history_trade_bar_df = qb.history(TradeBar, <?=$contractVariablePy?>, timedelta(days=3))
subset_history_trade_bar_df = qb.history(TradeBar, [<?=$contractVariablePy?>], timedelta(days=3))
all_history_trade_bar_df = qb.history(TradeBar, qb.securities.keys, timedelta(days=3))

# DataFrame of quote data
single_history_quote_bar_df = qb.history(QuoteBar, <?=$contractVariablePy?>, timedelta(days=3))
subset_history_quote_bar_df = qb.history(QuoteBar, [<?=$contractVariablePy?>], timedelta(days=3))
all_history_quote_bar_df = qb.history(QuoteBar, qb.securities.keys, timedelta(days=3))

# DataFrame of open interest data
single_history_open_interest_df = qb.history(OpenInterest, <?=$contractVariablePy?>, timedelta(days=3))
subset_history_open_interest_df = qb.history(OpenInterest, [<?=$contractVariablePy?>], timedelta(days=3))
all_history_open_interest_df = qb.history(OpenInterest, qb.securities.keys, timedelta(days=3))

# Slice objects
all_history_slice = qb.history(timedelta(days=3))

# TradeBar objects
single_history_trade_bars = qb.history[TradeBar](<?=$contractVariablePy?>, timedelta(days=3))
subset_history_trade_bars = qb.history[TradeBar]([<?=$contractVariablePy?>], timedelta(days=3))
all_history_trade_bars = qb.history[TradeBar](qb.securities.keys, timedelta(days=3))

# QuoteBar objects
single_history_quote_bars = qb.history[QuoteBar](<?=$contractVariablePy?>, timedelta(days=3), Resolution.MINUTE)
subset_history_quote_bars = qb.history[QuoteBar]([<?=$contractVariablePy?>], timedelta(days=3), Resolution.MINUTE)
all_history_quote_bars = qb.history[QuoteBar](qb.securities.keys, timedelta(days=3), Resolution.MINUTE) 

<? if ($supportsTicks) { ?>
# Tick objects
single_history_ticks = qb.history[Tick](<?=$contractVariablePy?>, timedelta(days=3), Resolution.TICK)
subset_history_ticks = qb.history[Tick]([<?=$contractVariablePy?>], timedelta(days=3), Resolution.TICK)
all_history_ticks = qb.history[Tick](qb.securities.keys, timedelta(days=3), Resolution.TICK)
<? } ?>

# OpenInterest objects
single_history_open_interest = qb.history[OpenInterest](<?=$contractVariablePy?>, timedelta(days=2))
subset_history_open_interest = qb.history[OpenInterest]([<?=$contractVariablePy?>], timedelta(days=2))
all_history_open_interest = qb.history[OpenInterest](qb.securities.keys, timedelta(days=2))</pre>
</div>
<p>The preceding calls return the most recent bars, excluding periods of time when the exchange was closed.</p>

<? if ($assetClass == "Futures") { ?>
<p>To get historical data for the continous Futures contract, in the preceding history requests, replace <code class='python'><?=$contractVariablePy?></code><code class='csharp'><?=$contractVariableC?></code> with <code>future.Symbol</code>.</p>
<? } ?>

<h4>Defined Period of Time</h4>
<p>To get historical data for individual <?=$assetClass?> contracts during a specific period of time, call the <code>History</code> method with the <?=$assetClass?> contract <code>Symbol</code> object(s), a start  <code class='csharp'>DateTime</code><code class='python'>datetime</code>, and an end <code class='csharp'>DateTime</code><code class='python'>datetime</code>.  The start and end times you provide are based in the <a href='/docs/v2/research-environment/initialization#04-Set-Time-Zone'>notebook time zone</a>.</p>

<div class='section-example-container'>
    <pre class='csharp'>var startTime = new DateTime(2021, 12, 1);
var endTime = new DateTime(2021, 12, 31);

// Slice objects
var singleHistorySlice = qb.History(<?=$contractVariableC?>, startTime, endTime);
var subsetHistorySlice = qb.History(new[] {<?=$contractVariableC?>}, startTime, endTime);
var allHistorySlice = qb.History(startTime, endTime);

// TradeBar objects
var singleHistoryTradeBars = qb.History&lt;TradeBar&gt;(<?=$contractVariableC?>, startTime, endTime);
var subsetHistoryTradeBars = qb.History&lt;TradeBar&gt;(new[] {<?=$contractVariableC?>}, startTime, endTime);
var allHistoryTradeBars = qb.History&lt;TradeBar&gt;(qb.Securities.Keys, startTime, endTime);

// QuoteBar objects
var singleHistoryQuoteBars = qb.History&lt;QuoteBar&gt;(<?=$contractVariableC?>, startTime, endTime, Resolution.Minute);
var subsetHistoryQuoteBars = qb.History&lt;QuoteBar&gt;(new[] {<?=$contractVariableC?>}, startTime, endTime, Resolution.Minute);
var allHistoryQuoteBars = qb.History&lt;QuoteBar&gt;(qb.Securities.Keys, startTime, endTime, Resolution.Minute);

<? if ($supportsTicks) { ?>
// Tick objects
var singleHistoryTicks = qb.History<Tick>(<?=$contractVariableC?>, startTime, endTime, Resolution.Tick);
var subsetHistoryTicks = qb.History<Tick>(new[] {<?=$contractVariableC?>}, startTime, endTime, Resolution.Tick);
var allHistoryTicks = qb.History<Tick>(qb.Securities.Keys, startTime, endTime, Resolution.Tick);
<? } ?>

// OpenInterest objects
var singleHistoryOpenInterest = qb.History&lt;OpenInterest&gt;(<?=$contractVariableC?>, startTime, endTime);
var subsetHistoryOpenInterest = qb.History&lt;OpenInterest&gt;(new[] {<?=$contractVariableC?>}, startTime, endTime);
var allHistoryOpenInterest = qb.History&lt;OpenInterest&gt;(qb.Securities.Keys, startTime, endTime);</pre>
    <pre class='python'>start_time = datetime(2021, 12, 1)
end_time = datetime(2021, 12, 31)

# DataFrame of trade and quote data
single_history_df = qb.history(<?=$contractVariablePy?>, start_time, end_time)
subset_history_df = qb.history([<?=$contractVariablePy?>], start_time, end_time)
all_history_df = qb.history(qb.securities.keys, start_time, end_time)

# DataFrame of trade data
single_history_trade_bar_df = qb.history(TradeBar, <?=$contractVariablePy?>, start_time, end_time)
subset_history_trade_bar_df = qb.history(TradeBar, [<?=$contractVariablePy?>], start_time, end_time)
all_history_trade_bar_df = qb.history(TradeBar, qb.securities.keys, start_time, end_time)

# DataFrame of quote data
single_history_quote_bar_df = qb.history(QuoteBar, <?=$contractVariablePy?>, start_time, end_time)
subset_history_quote_bar_df = qb.history(QuoteBar, [<?=$contractVariablePy?>], start_time, end_time)
all_history_quote_bar_df = qb.history(QuoteBar, qb.securities.keys, start_time, end_time)

# DataFrame of open interest data
single_history_open_interest_df = qb.history(OpenInterest, <?=$contractVariablePy?>, start_time, end_time)
subset_history_open_interest_df = qb.history(OpenInterest, [<?=$contractVariablePy?>], start_time, end_time)
all_history_trade_open_interest_df = qb.history(OpenInterest, qb.securities.keys, start_time, end_time)

# TradeBar objects
single_history_trade_bars = qb.history[TradeBar](<?=$contractVariablePy?>, start_time, end_time)
subset_history_trade_bars = qb.history[TradeBar]([<?=$contractVariablePy?>], start_time, end_time)
all_history_trade_bars = qb.history[TradeBar](qb.securities.keys, start_time, end_time)

# QuoteBar objects
single_history_quote_bars = qb.history[QuoteBar](<?=$contractVariablePy?>, start_time, end_time, Resolution.MINUTE)
subset_history_quote_bars = qb.history[QuoteBar]([<?=$contractVariablePy?>], start_time, end_time, Resolution.MINUTE)
all_history_quote_bars = qb.history[QuoteBar](qb.securities.keys, start_time, end_time, Resolution.MINUTE)

<? if ($supportsTicks) { ?>
# Tick objects
single_history_ticks = qb.history[Tick](<?=$contractVariablePy?>, start_time, end_time, Resolution.TICK)
subset_history_ticks = qb.history[Tick]([<?=$contractVariablePy?>], start_time, end_time, Resolution.TICK)
all_history_ticks = qb.history[Tick](qb.securities.keys, start_time, end_time, Resolution.TICK)
<? } ?>

# OpenInterest objects
single_history_open_interest = qb.history[OpenInterest](<?=$contractVariablePy?>, start_time, end_time)
subset_history_open_interest = qb.history[OpenInterest]([<?=$contractVariablePy?>], start_time, end_time)
all_history_open_interest = qb.history[OpenInterest](qb.securities.keys, start_time, end_time)</pre>
</div>

<? if ($assetClass == "Futures") { ?> 
<p>To get historical data for the continous Futures contract, in the preceding history requests, replace <code class='python'><?=$contractVariablePy?></code><code class='csharp'><?=$contractVariableC?></code> with <code>future.Symbol</code>.</p>

<p>To get historical data for all of the <?=$assetClass?> contracts that pass your <a href='<?=$createSubscriptionsLink?>'>filter</a> during a specific period of time, call the <code>FutureHistory</code> method with the <code>Symbol</code> object of the continuous Future, a start <code class='csharp'>DateTime</code><code class='python'>datetime</code>, and an end <code class='csharp'>DateTime</code><code class='python'>datetime</code>.</p>

<div class='section-example-container'>
    <pre class='python'>future_history = qb.future_history(<?=$underlyingSymbolVariablePy?>, end_time-timedelta(days=2), end_time, Resolution.MINUTE, fillForward=False, extendedMarketHours=False)</pre>
    <pre class='csharp'>var futureHistory = qb.FutureHistory(<?=$underlyingSymbolVariableC?>, endTime-TimeSpan.FromDays(2), endTime, Resolution.Minute, fillForward: False, extendedMarketHours: False);</pre>
</div>
<? } elseif ($assetClass == "Futures Option") { ?>
<p>To get historical data for all of the <?=$assetClass?> contracts that traded during a specific period of time, call the <code>OptionHistory</code> method with the underlying <?=$underlyingAssetClass?> <code>Symbol</code> object, a start <code class='csharp'>DateTime</code><code class='python'>datetime</code>, and an end <code class='csharp'>DateTime</code><code class='python'>datetime</code>.</p>
<? } elseif ($assetClass == "Index Option") { ?>
<p>To get historical data for all of the <?=$assetClass?> contracts that pass your <a href='<?=$createSubscriptionsLink?>'>filter</a> during a specific period of time, call the <code>OptionHistory</code> method with the canonical Index Option <code>Symbol</code> object, a start <code class='csharp'>DateTime</code><code class='python'>datetime</code>, and an end <code class='csharp'>DateTime</code><code class='python'>datetime</code>.</p>
<? } else { ?>
<p>To get historical data for all of the <?=$assetClass?> contracts that pass your <a href='<?=$createSubscriptionsLink?>'>filter</a> during a specific period of time, call the <code>OptionHistory</code> method with the underlying <?=$underlyingAssetClass?> <code>Symbol</code> object, a start <code class='csharp'>DateTime</code><code class='python'>datetime</code>, and an end <code class='csharp'>DateTime</code><code class='python'>datetime</code>.</p>
<? } ?>

<? if ($assetClass != "Futures") { ?> 
<div class='section-example-container'>
    <pre class='python'>option_history = qb.option_history(<?=$underlyingSymbolVariablePy?>, end_time-timedelta(days=2), end_time, Resolution.MINUTE, fillForward=False, extendedMarketHours=False)</pre>
    <pre class='csharp'>var optionHistory = qb.OptionHistory(<?=$underlyingSymbolVariableC?>, endTime-TimeSpan.FromDays(2), endTime, Resolution.Minute, fillForward: False, extendedMarketHours: False);</pre>
</div>
<? } ?> 

<p>The preceding calls return data that have a timestamp within the defined period of time.</p>
