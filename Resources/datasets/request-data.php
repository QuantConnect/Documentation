<? 
$pyVar = $writingAlgorithms ? "self" : "qb";
$pyFutureVar = $writingAlgorithms ? "self." : "";
$cVar = $writingAlgorithms ? "" : "qb.";
$envName = $writingAlgorithms ? "algorithm" : "notebook";
$cPrintMethod = $writingAlgorithms ? "Log" : "Console.WriteLine";
$pyPrintMethod = $writingAlgorithms ? "self.Log" : "print";
?>

<p>
    The simplest form of history request is for a known set of <code>Symbol</code> objects.
    <? if ($writingAlgorithms) {?> This is common for fixed universes of securities or when you need to prepare new securities added to your algorithm.<? } ?>
    History requests return slightly different data depending on the overload you call. The data that returns is in ascending order from oldest to newest.
    <? if ($writingAlgorithms) {?> This order is necessary to use the data to warm up indicators. <? } ?>
</p>
    
<h4>Single Symbol History Requests</h4>

<p>To request history for a single asset, pass the asset <code>Symbol</code> to the <code class="csharp">History</code><code class="python">history</code> method. The return type of the method call depends on the history request <code class='python'>[Type]</code><code class='csharp'>&lt;Type&gt;</code>. The following table describes the return type of each request <code class='python'>[Type]</code><code class='csharp'>&lt;Type&gt;</code>:</p>

<table class='qc-table table'>
    <thead>
        <tr>
            <th>Request Type</th>
            <th>Return Data Type</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>No argument</td>
	    <td><code class='python'>DataFrame</code><code class='csharp'>List&lt;TradeBar&gt;</code></td>
        </tr>
        <tr>
	    <td><code>TradeBar</code></td>
	    <td><code class='python'>List[TradeBars]</code><code class='csharp'>List&lt;TradeBar&gt;</code></td>
        </tr>
        <tr>
	    <td><code>QuoteBar</code></td>
	    <td><code class='python'>List[QuoteBars]</code><code class='csharp'>List&lt;QuoteBar&gt;</code></td>
        </tr>
        <tr>
	    <td><code>Tick</code></td>
	    <td><code class='python'>List[Ticks]</code><code class='csharp'>List&lt;Tick&gt;</code></td>
        </tr>
	<tr>
	    <td><code class='placeholder-text'>alternativeDataClass</code><br>(ex: <code>CBOE</code>)</td>
	    <td><span class='python'><code>List[<span class='placeholder-text'>alternativeDataClass</span>]</code><br>(ex: <code>List[CBOE]</code>)</span><span class='csharp'><code>List&lt;<span class='placeholder-text'>alternativeDataClass</span>&gt;</code><br>(ex: <code>List&lt;CBOE&gt;</code>)</span></td>
        </tr>
    </tbody>
</table>
<p class='python'>Each row of the DataFrame represents the prices at a point in time. Each column of the DataFrame is a property of that price data (for example, open, high, low, and close (OHLC)). If you request a DataFrame object and pass <code>TradeBar</code> as the first argument, the DataFrame that returns only contains the OHLC and volume columns. If you request a DataFrame object and pass <code>QuoteBar</code> as the first argument, the DataFrame that returns contains the OHLC of the bid and ask and it contains OHLC columns, which are the respective means of the bid and ask OHLC values. If you request a DataFrame and don't pass <code>TradeBar</code> or <code>QuoteBar</code> as the first arugment, the DataFrame that returns contains columns for all of the data that's available for the given resolution.</p>

<div class='section-example-container'>
<pre class='python'><b># EXAMPLE 1: Requesting By Bar Count: 5 bars at the security resolution:</b>
vix_symbol = <?=$pyVar?>.add_data(CBOE, "VIX", Resolution.DAILY).symbol
cboe_data = <?=$pyVar?>.history[CBOE](vix_symbol, 5)

btc_symbol = <?=$pyVar?>.add_crypto("BTCUSD", Resolution.MINUTE).symbol
trade_bars = <?=$pyVar?>.history[TradeBar](btc_symbol, 5)
quote_bars = <?=$pyVar?>.history[QuoteBar](btc_symbol, 5)
trade_bars_df = <?=$pyVar?>.history(TradeBar, btc_symbol, 5)
quote_bars_df = <?=$pyVar?>.history(QuoteBar, btc_symbol, 5)
df = <?=$pyVar?>.history(btc_symbol, 5)   # Includes trade and quote data
<img class='img-responsive img-thumbnail' src='https://cdn.quantconnect.com/i/tu/history-request-single-symbol-1.png' alt="Historical minute data dataframe of BTCUSD">
</pre>
<pre class='csharp'><b>// EXAMPLE 1: Requesting By Bar Count: 5 bars at the security resolution:</b>
var vixSymbol = <?=$cVar?>AddData&lt;CBOE&gt;("VIX", Resolution.Daily).Symbol;
var cboeData = <?=$cVar?>History&lt;CBOE&gt;(vixSymbol, 5);

var btcSymbol = <?=$cVar?>AddCrypto("BTCUSD", Resolution.Minute).Symbol;
var tradeBars = <?=$cVar?>History&lt;TradeBar&gt;(btcSymbol, 5);
var quoteBars = <?=$cVar?>History&lt;QuoteBar&gt;(btcSymbol, 5);
var tradeBars2 = <?=$cVar?>History(btcSymbol, 5);</pre>

	
<pre class='python'><b># EXAMPLE 2: Requesting By Bar Count: 5 bars with a specific resolution:</b>
trade_bars = <?=$pyVar?>.history[TradeBar](btc_symbol, 5, Resolution.DAILY)
quote_bars = <?=$pyVar?>.history[QuoteBar](btc_symbol, 5, Resolution.MINUTE)
trade_bars_df = <?=$pyVar?>.history(TradeBar, btc_symbol, 5, Resolution.MINUTE)
quote_bars_df = <?=$pyVar?>.history(QuoteBar, btc_symbol, 5, Resolution.MINUTE)
df = <?=$pyVar?>.history(btc_symbol, 5, Resolution.MINUTE)  # Includes trade and quote data
<img class='img-responsive img-thumbnail' src='https://cdn.quantconnect.com/i/tu/history-request-single-symbol-1.png' alt="Historical minute data dataframe of BTCUSD">
</pre>
<pre class='csharp'><b>// EXAMPLE 2: Requesting By Bar Count: 5 bars with a specific resolution:</b>
var tradeBars = <?=$cVar?>History&lt;TradeBar&gt;(btcSymbol, 5, Resolution.Daily);
var quoteBars = <?=$cVar?>History&lt;QuoteBar&gt;(btcSymbol, 5, Resolution.Minute);
var tradeBars2 = <?=$cVar?>History(btcSymbol, 5, Resolution.Minute);</pre>


<pre class='python'><b># EXAMPLE 3: Requesting By a Trailing Period: 3 days of data at the security resolution:</b> 
eth_symbol = <?=$pyVar?>.add_crypto('ETHUSD', Resolution.TICK).symbol
ticks = <?=$pyVar?>.history[Tick](eth_symbol, timedelta(days=3))
ticks_df = <?=$pyVar?>.history(eth_symbol, timedelta(days=3))

vix_data = <?=$pyVar?>.history[CBOE](vix_symbol, timedelta(days=3)) 
trade_bars = <?=$pyVar?>.history[TradeBar](btc_symbol, timedelta(days=3)) 
quote_bars = <?=$pyVar?>.history[QuoteBar](btc_symbol, timedelta(days=3))
trade_bars_df = <?=$pyVar?>.history(TradeBar, btc_symbol, timedelta(days=3))
quote_bars_df = <?=$pyVar?>.history(QuoteBar, btc_symbol, timedelta(days=3))
df = <?=$pyVar?>.history(btc_symbol, timedelta(days=3))  # Includes trade and quote data
<img class='img-responsive img-thumbnail' src='https://cdn.quantconnect.com/i/tu/history-request-single-symbol-2.png' alt="Historical minute data dataframe of BTCUSD">
</pre>

<pre class='csharp'><b>// EXAMPLE 3: Requesting By a Trailing Period: 3 days of data at the security resolution:</b>
var ethSymbol = <?=$cVar?>AddCrypto("ETHUSD", Resolution.Tick).Symbol;
var ticks = <?=$cVar?>History&lt;Tick&gt;(ethSymbol, TimeSpan.FromDays(3));

var cboeData = <?=$cVar?>History&lt;CBOE&gt;(vixSymbol, TimeSpan.FromDays(3));
var tradeBars = <?=$cVar?>History&lt;TradeBar&gt;(btcSymbol, TimeSpan.FromDays(3));
var quoteBars = <?=$cVar?>History&lt;QuoteBar&gt;(btcSymbol, TimeSpan.FromDays(3));
var tradeBars2 = <?=$cVar?>History(btcSymbol, TimeSpan.FromDays(3));</pre>
	
	
	
<pre class='python'><b># EXAMPLE 4: Requesting By a Trailing Period: 3 days of data with a specific resolution:</b> 
trade_bars = <?=$pyVar?>.history[TradeBar](btc_symbol, timedelta(days=3), Resolution.DAILY) 
quote_bars = <?=$pyVar?>.history[QuoteBar](btc_symbol, timedelta(days=3), Resolution.MINUTE)
ticks = <?=$pyVar?>.history[Tick](eth_symbol, timedelta(days=3), Resolution.TICK)

trade_bars_df = <?=$pyVar?>.history(TradeBar, btc_symbol, timedelta(days=3), Resolution.DAILY)
quote_bars_df = <?=$pyVar?>.history(QuoteBar, btc_symbol, timedelta(days=3), Resolution.MINUTE)
ticks_df = <?=$pyVar?>.history(eth_symbol, timedelta(days=3), Resolution.TICK)
df = <?=$pyVar?>.history(btc_symbol, timedelta(days=3), Resolution.HOUR)  # Includes trade and quote data
<img class='img-thumbnail img-responsive' src='https://cdn.quantconnect.com/i/tu/history-request-single-symbol-3.png' alt="Historical hourly data dataframe of BTCUSD">
# Important Note: Period history requests are relative to "now" <?=$envName?> time.</pre>


<pre class='csharp'><b>// EXAMPLE 4: Requesting By a Trailing Period: 3 days of data with a specific resolution:</b>
var tradeBars = <?=$cVar?>History&lt;TradeBar&gt;(btcSymbol, TimeSpan.FromDays(3), Resolution.Daily);
var quoteBars = <?=$cVar?>History&lt;QuoteBar&gt;(btcSymbol, TimeSpan.FromDays(3), Resolution.Minute);
var ticks = <?=$cVar?>History&lt;Tick&gt;(ethSymbol, TimeSpan.FromDays(3), Resolution.Tick);
var tradeBars2 = <?=$cVar?>History(btcSymbol, TimeSpan.FromDays(3), Resolution.Minute);</pre>


<pre class='python'><b># EXAMPLE 5: Requesting By a Defined Period: 3 days of data at the security resolution:</b> 
start_time = datetime(2022, 1, 1)
end_time = datetime(2022, 1, 4)

vix_data = <?=$pyVar?>.history[CBOE](vix_symbol, start_time, end_time) 
trade_bars = <?=$pyVar?>.history[TradeBar](btc_symbol, start_time, end_time) 
quote_bars = <?=$pyVar?>.history[QuoteBar](btc_symbol, start_time, end_time)
ticks = <?=$pyVar?>.history[Tick](eth_symbol, start_time, end_time)

trade_bars_df = <?=$pyVar?>.history(TradeBar, btc_symbol, start_time, end_time)
quote_bars_df = <?=$pyVar?>.history(QuoteBar, btc_symbol, start_time, end_time)
ticks_df = <?=$pyVar?>.history(Tick, eth_symbol, start_time, end_time)
df = <?=$pyVar?>.history(btc_symbol, start_time, end_time)  # Includes trade and quote data
<img class='img-responsive img-thumbnail' src='https://cdn.quantconnect.com/i/tu/history-request-defined-period-default-resolution.jpg' alt="Historical minute data dataframe of BTCUSD">
</pre>

<pre class='csharp'><b>// EXAMPLE 5: Requesting By a Defined Period: 3 specific days of data at the security resolution:</b>
var startTime = new DateTime(2022, 1, 1);
var endTime = new DateTime(2022, 1, 4);

var cboeData = <?=$cVar?>History&lt;CBOE&gt;(vixSymbol, startTime, endTime);
var tradeBars = <?=$cVar?>History&lt;TradeBar&gt;(btcSymbol, startTime, endTime);
var quoteBars = <?=$cVar?>History&lt;QuoteBar&gt;(btcSymbol, startTime, endTime);
var ticks = <?=$cVar?>History&lt;Tick&gt;(ethSymbol, startTime, endTime);
var tradeBars2 = <?=$cVar?>History(btcSymbol, startTime, endTime);</pre>

	
<pre class='python'><b># EXAMPLE 6: Requesting By a Defined Period: 3 days of data with a specific resolution:</b> 
trade_bars = <?=$pyVar?>.history[TradeBar](btc_symbol, start_time, end_time, Resolution.DAILY) 
quote_bars = <?=$pyVar?>.history[QuoteBar](btc_symbol, start_time, end_time, Resolution.MINUTE)
ticks = <?=$pyVar?>.history[Tick](eth_symbol, start_time, end_time, Resolution.TICK)

trade_bars_df = <?=$pyVar?>.history(TradeBar, btc_symbol, start_time, end_time, Resolution.DAILY)
quote_bars_df = <?=$pyVar?>.history(QuoteBar, btc_symbol, start_time, end_time, Resolution.MINUTE)
ticks_df = <?=$pyVar?>.history(eth_symbol, start_time, end_time, Resolution.TICK)
df = <?=$pyVar?>.history(btc_symbol, start_time, end_time, Resolution.HOUR)  # Includes trade and quote data
<img class='img-thumbnail img-responsive' src='https://cdn.quantconnect.com/i/tu/history-request-defined-period.jpg' alt="Historical hourly data dataframe of BTCUSD">
</pre>


<pre class='csharp'><b>// EXAMPLE 6: Requesting By a Defined Period: 3 days of data with a specific resolution:</b>
var tradeBars = <?=$cVar?>History&lt;TradeBar&gt;(btcSymbol, startTime, endTime, Resolution.Daily);
var quoteBars = <?=$cVar?>History&lt;QuoteBar&gt;(btcSymbol, startTime, endTime, Resolution.Minute);
var ticks = <?=$cVar?>History&lt;Tick&gt;(ethSymbol, startTime, endTime, Resolution.Tick);
var tradeBars2 = <?=$cVar?>History(btcSymbol, startTime, endTime, Resolution.Minute);</pre>
</div>

<h4>Multiple Symbol History Requests</h4>
<p>To request history for multiple symbols at a time, pass an array of <code>Symbol</code> objects to the same API methods shown in the preceding section. The return type of the method call depends on the history request <code class='python'>[Type]</code><code class='csharp'>&lt;Type&gt;</code>. The following table describes the return type of each request <code class='python'>[Type]</code><code class='csharp'>&lt;Type&gt;</code>:</p>

<table class='qc-table table'>
    <thead>
        <tr>
            <th>Request Type</th>
            <th>Return Data Type</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>No argument</td>
	    <td><code class='python'>DataFrame</code><code class='csharp'>List&lt;Slice&gt;</code></td>
        </tr>
        <tr>
	    <td><code>TradeBar</code></td>
	    <td><code class='python'>List[TradeBars]</code><code class='csharp'>List&lt;TradeBars&gt;</code></td>
        </tr>
        <tr>
	    <td><code>QuoteBar</code></td>
	    <td><code class='python'>List[QuoteBars]</code><code class='csharp'>List&lt;QuoteBars&gt;</code></td>
        </tr>
        <tr>
	    <td><code>Tick</code></td>
	    <td><code class='python'>List[Ticks]</code><code class='csharp'>List&lt;Ticks&gt;</code></td>
        </tr>
	<tr>
	    <td><code class='placeholder-text'>alternativeDataClass</code><br>(ex: <code>CBOE</code>)</td>
	    <td><span class='python'><code>List[Dict[Symbol, <span class='placeholder-text'>alternativeDataClass</span>]]</code><br>(ex: <code>List[Dict[Symbol, CBOE]]</code>)</span><span class='csharp'><code>List&lt;Dictionary&lt;Symbol, <span class='placeholder-text'>alternativeDataClass</span>&gt;&gt;</code><br>(ex: <code>List&lt;Dictionary&lt;Symbol, CBOE&gt;&gt;</code>)</span></td>
        </tr>
    </tbody>
</table>

<p class='csharp'>The <code>Slice</code> return type provides a container that supports all data types. For example, a history request for Forex <code>QuoteBars</code> and Equity <code>TradeBars</code> has the Forex data under <code>slices.QuoteBars</code> and the Equity data under <code>slices.Bars</code>.</p>

<div class='section-example-container'>
<pre class='python'><b># EXAMPLE 7: Requesting By Bar Count for Multiple Symbols: 2 bars at the security resolution:</b>
vix = <?=$pyVar?>.add_data[CBOE]("VIX", Resolution.DAILY).symbol
v3m = <?=$pyVar?>.add_data[CBOE]("VIX3M", Resolution.DAILY).symbol
cboe_data = <?=$pyVar?>.history[CBOE]([vix, v3m], 2)

ibm = <?=$pyVar?>.add_equity("IBM", Resolution.MINUTE).symbol
aapl = <?=$pyVar?>.add_equity("AAPL", Resolution.MINUTE).symbol
trade_bars_list = <?=$pyVar?>.history[TradeBar]([ibm, aapl], 2)
quote_bars_list = <?=$pyVar?>.history[QuoteBar]([ibm, aapl], 2)

trade_bars_df = <?=$pyVar?>.history(TradeBar, [ibm, aapl], 2)
quote_bars_df = <?=$pyVar?>.history(QuoteBar, [ibm, aapl], 2)
df = <?=$pyVar?>.history([ibm, aapl], 2)  # Includes trade and quote data
<img class='img-responsive img-thumbnail' src='https://cdn.quantconnect.com/i/tu/history-request-single-symbol-4.png' alt="Historical minute data dataframe of IBM &amp; AAPL">
</pre>

<pre class='csharp'><b>// EXAMPLE 7: Requesting By Bar Count for Multiple Symbols: 2 bars at the security resolution:</b>
var vixSymbol = <?=$cVar?>AddData&lt;CBOE&gt;("VIX", Resolution.Daily).Symbol;
var v3mSymbol = <?=$cVar?>AddData&lt;CBOE&gt;("VIX3m", Resolution.Daily).Symbol;
var cboeData = <?=$cVar?>History&lt;CBOE&gt;(new[] { vix, v3m }, 2);

var ibm = <?=$cVar?>AddEquity("IBM", Resolution.Minute).Symbol;
var aapl = <?=$cVar?>AddEquity("AAPL", Resolution.Minute).Symbol;
var tradeBarsList = <?=$cVar?>History&lt;TradeBar&gt;(new[] { ibm, aapl }, 2);
var quoteBarsList = <?=$cVar?>History&lt;QuoteBar&gt;(new[] { ibm, aapl }, 2);
</pre>
	
<pre class='python'><b># EXAMPLE 8: Requesting By Bar Count for Multiple Symbols: 5 bars with a specific resolution:</b>
trade_bars_list = <?=$pyVar?>.history[TradeBar]([ibm, aapl], 5, Resolution.DAILY)
quote_bars_list = <?=$pyVar?>.history[QuoteBar]([ibm, aapl], 5, Resolution.MINUTE)

trade_bars_df = <?=$pyVar?>.history(TradeBar, [ibm, aapl], 5, Resolution.DAILY)
quote_bars_df = <?=$pyVar?>.history(QuoteBar, [ibm, aapl], 5, Resolution.MINUTE)
df = <?=$pyVar?>.history([ibm, aapl], 5, Resolution.DAILY)  # Includes trade data only. No quote for daily equity data
<img class='img-responsive img-thumbnail' src='https://cdn.quantconnect.com/i/tu/history-request-single-symbol-5.png' alt="Historical daily data dataframe of IBM &amp; AAPL">
</pre>

<pre class='csharp'><b>// EXAMPLE 8: Requesting By Bar Count for Multiple Symbols: 5 bars with a specific resolution:</b>
var tradeBarsList = <?=$cVar?>History&lt;TradeBar&gt;(new[] { ibm, aapl }, 5, Resolution.Minute);
var quoteBarsList = <?=$cVar?>History&lt;QuoteBar&gt;(new[] { ibm, aapl }, 5, Resolution.Minute);
</pre>
	
	
<pre class='python'><b># EXAMPLE 9: Requesting By Trailing Period: 3 days of data at the security resolution:</b> 
ticks = <?=$pyVar?>.history[Tick]([eth_symbol], timedelta(days=3))

trade_bars = <?=$pyVar?>.history[TradeBar]([btc_symbol], timedelta(days=3)) 
quote_bars = <?=$pyVar?>.history[QuoteBar]([btc_symbol], timedelta(days=3))
trade_bars_df = <?=$pyVar?>.history(TradeBar, [btc_symbol], timedelta(days=3))
quote_bars_df = <?=$pyVar?>.history(QuoteBar, [btc_symbol], timedelta(days=3))
df = <?=$pyVar?>.history([btc_symbol], timedelta(days=3))  # Includes trade and quote data 
<img class='img-responsive img-thumbnail' src='https://cdn.quantconnect.com/i/tu/history-request-single-symbol-6.png' alt="Historical minute data dataframe of BTCUSD">
</pre>
<pre class='csharp'><b>// EXAMPLE 9: Requesting By Trailing Period: 3 days of data at the security resolution:</b>
var ticks = <?=$cVar?>History&lt;Tick&gt;(new[] {ethSymbol}, TimeSpan.FromDays(3));

var tradeBars = <?=$cVar?>History&lt;TradeBar&gt;(new[] {btcSymbol}, TimeSpan.FromDays(3));
var quoteBars = <?=$cVar?>History&lt;QuoteBar&gt;(new[] {btcSymbol}, TimeSpan.FromDays(3));
var tradeBars2 = <?=$cVar?>History(new[] {btcSymbol}, TimeSpan.FromDays(3));</pre>	

<pre class='python'><b># EXAMPLE 10: Requesting By Defined Period: 3 days of data at the security resolution:</b> 
trade_bars = <?=$pyVar?>.history[TradeBar]([btc_symbol], start_time, end_time) 
quote_bars = <?=$pyVar?>.history[QuoteBar]([btc_symbol], start_time, end_time)
ticks = <?=$pyVar?>.history[Tick]([eth_symbol], start_time, end_time)
trade_bars_df = <?=$pyVar?>.history(TradeBar, btc_symbol, start_time, end_time)
quote_bars_df = <?=$pyVar?>.history(QuoteBar, btc_symbol, start_time, end_time)
ticks_df = <?=$pyVar?>.history(Tick, eth_symbol, start_time, end_time)
df = <?=$pyVar?>.history([btc_symbol], start_time, end_time)  # Includes trade and quote data
<img class='img-responsive img-thumbnail' src='https://cdn.quantconnect.com/i/tu/history-request-defined-period-default-resolution.jpg' alt="Historical minute data dataframe of BTCUSD">
</pre>
<pre class='csharp'><b>// EXAMPLE 10: Requesting By Defined Period: 3 days of data at the security resolution:</b>
var tradeBars = <?=$cVar?>History&lt;TradeBar&gt;(new[] {btcSymbol}, startTime, endTime);
var quoteBars = <?=$cVar?>History&lt;QuoteBar&gt;(new[] {btcSymbol}, startTime, endTime);
var ticks = <?=$cVar?>History&lt;Tick&gt;(new[] {ethSymbol}, startTime, endTime);
var tradeBars2 = <?=$cVar?>History(new[] {btcSymbol}, startTime, endTime);</pre>	

</div>

<p>If you request data for multiple securities and you use the <code class="csharp">Tick</code><code class="python">TICK</code> request type, each <code>Ticks</code> object in the list of results only contains the last tick of each security for that particular <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/timeslices'>timeslice</a>.</p>

<h4>All Symbol History Requests</h4>
 
<?=$writingAlgorithms ? "<p>You can request history for all active securities in your universe." : "<p>You can request history for all the securities you have created subscriptions for in your notebook session. "; ?> The parameters are very similar to other history method calls, but the return type is an array of <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/timeslices'>Slice</a> objects. The <code>Slice</code> object holds all of the results in a sorted enumerable collection that you can iterate over with a loop.</p>
    
<div class='section-example-container'>
<pre class='python'><b># EXAMPLE 11: Requesting 5 bars for all securities at their respective resolution:</b>

# Create subscriptions
<?=$pyVar?>.add_equity("IBM", Resolution.DAILY)
<?=$pyVar?>.add_equity("AAPL", Resolution.DAILY)

# Request history data and enumerate results
slices = <?=$pyVar?>.history(5)
for s in slices:
    <?=$pyPrintMethod?>(str(s.time) + " AAPL:" + str(s.bars["AAPL"].close) + " IBM:" + str(s.bars["IBM"].close))
<img class='img-responsive img-thumbnail' src='https://cdn.quantconnect.com/i/tu/history-request-single-symbol-7.png' alt="Historical daily close price output of IBM &amp; AAPL">
</pre>

<pre class='csharp'><b>// EXAMPLE 11: Requesting 5 bars for all securities at their respective resolution:</b>

// Set up the universe
<?=$cVar?>AddEquity("IBM", Resolution.Daily);
<?=$cVar?>AddEquity("AAPL", Resolution.Daily);

// Request history data and enumerate results:
var slices = <?=$cVar?>History(5);
foreach (var s in slices) {
    var aaplClose = s.Bars["AAPL"].Close;
    var ibmClose = s.Bars["IBM"].Close;
    <?=$cPrintMethod?>($"{s.Time} AAPL: {aaplClose} IBM: {ibmClose}");
}
<img class='img-responsive img-thumbnail' src='https://cdn.quantconnect.com/i/tu/history-request-single-symbol-9.png' alt="Historical daily close price output of IBM &amp; AAPL">
</pre>
</div>

<div class='section-example-container'>  
<pre class='python'><b># EXAMPLE 12: Requesting 5 minutes for all securities:</b>

slices = <?=$pyVar?>.history(timedelta(minutes=5), Resolution.MINUTE)
for s in slices:
    <?=$pyPrintMethod?>(str(s.time) + " AAPL:" + str(s.bars["AAPL"].close) + " IBM:" + str(s.bars["IBM"].close))
<img class='img-responsive img-thumbnail' src='https://cdn.quantconnect.com/i/tu/history-request-single-symbol-8.png' alt="Historical minute close price output of IBM &amp; AAPL">
# timedelta history requests are relative to "now" in <?=$envName?> Time. If you request this data at 16:05, it returns an empty array because the market is closed.</pre>
    
<pre class='csharp'><b>// EXAMPLE 12: Requesting 24 hours of hourly data for all securities:</b>

var slices = <?=$cVar?>History(TimeSpan.FromHours(24), Resolution.Hour);
foreach (var s in slices) {
    var aaplClose = s.Bars["AAPL"].Close;
    var ibmClose = s.Bars["IBM"].Close;
    <?=$cPrintMethod?>($"{s.Time} AAPL: {aaplClose} IBM: {ibmClose}");
}
<img class='img-responsive img-thumbnail' src='https://cdn.quantconnect.com/i/tu/history-request-single-symbol-10.png' alt="Historical hourly close price output of IBM &amp; AAPL">
// TimeSpan history requests are relative to "now" in <?=$envName?> Time.</pre>

</div>   

<h4>Assumed Default Values</h4>
<p>The following table describes the assumptions of the History API:</p>
<table class='table qc-table'>
    <thead>
        <tr>
            <th>Argument</th>
            <th>Assumption</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Resolution</td>
            <td>LEAN guesses the resolution you request by looking at the securities you already have in your <?=$envName?>. If you have a security subscription in your <?=$envName?> with a matching <code>Symbol</code>, the history request uses the same resolution as the subscription. If you don't have a security subscription in your <?=$envName?> with a matching <code>Symbol</code>, <code class="csharp">Resolution.Minute</code><code class="python">Resolution.MINUTE</code> is the default.</td>
        </tr>
        <tr class='csharp'>
            <td>Bar type</td>
            <td>If you don't specify a type for the history request, <code>TradeBar</code> is the default. If the asset you request data for doesn't have <code>TradeBar</code> data, specify the <code>QuoteBar</code> type to receive history.</td>
        </tr>
    </tbody>
</table>

<h4>Additional Options</h4>
<p>The <code class="csharp">History</code><code class="python">history</code> method accepts the following additional arguments:</p>

<table class='qc-table table'>
    <thead>
        <tr>
            <th>Argument</th>
            <th>Data Type</th>
            <th>Description</th>
            <th>Default Value</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code class="csharp">fillForward</code><code class="python">fill_forward</code></td>
	        <td><code class='csharp'>bool?</code><code class='python'>bool/NoneType</code></td>
            <td>True to <a href='/docs/v2/writing-algorithms/securities/requesting-data#05-Fill-Forward'>fill forward</a> missing data. Otherwise, false. If you don't provide a value, it uses the fill forward mode of the security subscription.</td>
            <td><code class='csharp'>null</code><code class='python'>None</code></td>
        </tr>
        <tr>
            <td><code class="csharp">extendedMarketHours</code><code class="python">extended_market_hours</code></td>
	        <td><code class='csharp'>bool?</code><code class='python'>bool/NoneType</code></td>
            <td>True to include extended market hours data. Otherwise, false.</td>
            <td><code class='csharp'>null</code><code class='python'>None</code></td>
        </tr>
        <tr>
            <td><code class="csharp">dataMappingMode</code><code class="python">data_mapping_mode</code></td>
	        <td><code class='csharp'>DataMappingMode?</code><code class='python'>DataMappingMode/NoneType</code></td>
            <td>The <a href='/docs/v2/writing-algorithms/universes/futures#12-Continous-Contracts'>contract mapping mode</a> to use for the security history request.</td>
            <td><code class='csharp'>null</code><code class='python'>None</code></td>
        </tr>
        <tr>
            <td><code class="csharp">dataNormalizationMode</code><code class="python">data_normalization_mode</code></td>
            <td><code class='csharp'>DataNormalizationMode?</code><code class='python'>DataNormalizationMode/NoneType</code></td>
            <td>The price scaling mode to use for <a href='/docs/v2/writing-algorithms/securities/asset-classes/us-equity/requesting-data#11-Data-Normalization'>US Equities</a> or <a href='/docs/v2/writing-algorithms/universes/futures#12-Continous-Contracts'>continuous Futures contracts</a>. If you don't provide a value, it uses the data normalization mode of the security subscription.</td>
            <td><code class='csharp'>null</code><code class='python'>None</code></td>
        </tr>
        <tr>
            <td><code class="csharp">contractDepthOffset</code><code class="python">contract_depth_offset</code></td>
            <td><code class='csharp'>int?</code><code class='python'>int/NoneType</code></td>
            <td>The desired offset from the current front month for <a href='/docs/v2/writing-algorithms/universes/futures#12-Continous-Contracts'>continuous Futures contracts</a>.</td>
            <td><code class='csharp'>null</code><code class='python'>None</code></td>
        </tr>
    </tbody>
</table>

<div class='section-example-container'>
    <pre class='python'><?=$pyFutureVar?>future = <?=$pyVar?>.add_future(Futures.Currencies.BTC)
history = <?=$pyVar?>.history(
    tickers=[<?=$pyFutureVar?>future.symbol], 
    start=<?=$pyVar?>.time - timedelta(days=15), 
    end=<?=$pyVar?>.time, 
    resolution=Resolution.MINUTE, 
    fill_forward=False, 
    extended_market_hours=False, 
    dataMappingMode=DataMappingMode.OPEN_INTEREST, 
    dataNormalizationMode=DataNormalizationMode.RAW, 
    contractDepthOffset=0)</pre>
    <pre class='csharp'>var future = <?=$cVar?>AddFuture(Futures.Currencies.BTC);
var history = <?=$cVar?>History(
    symbols: new[] {future.Symbol}, 
    start: <?=$cVar?>Time - TimeSpan.FromDays(15),
    end: <?=$cVar?>Time,
    resolution: Resolution.Minute,
    fillForward: false,
    extendedMarketHours: false,
    dataMappingMode: DataMappingMode.OpenInterest,
    dataNormalizationMode: DataNormalizationMode.Raw,
    contractDepthOffset: 0);</pre>
</div>
