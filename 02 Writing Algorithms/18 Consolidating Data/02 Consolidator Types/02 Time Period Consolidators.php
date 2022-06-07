<p>Time period consolidators aggregate data based on a period of time. The consolidator time period must be greater than or equal to the resolution of the security subscription. For instance, you can aggregate minute bars into 10-minute bars, but you can't aggregate hour bars into 10-minute bars. To set the time period for the consolidator, you can use either a <code class='python'>timedelta</code><code class='csharp'>TimeSpan</code>, <code>Resolution</code>, or <code>CalendarInfo</code> object.</p>

<h4 class='python'>timedelta Periods</h4>
<h4 class='csharp'>TimeSpan Periods</h4>

<p>If you define the time period with a <code class='python'>timedelta</code><code class='csharp'>TimeSpan</code> object, the time starts from the beginning of the day, not the beginning of the market open or the first data point. For example, if you use <code class='python'>timedelta(minutes=7)</code><code class='csharp'>TimeSpan.FromMinutes(7)</code>, the 7-minute counter starts at midnight. Additionally, the time period is relative to the <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/time-zones#05-Data-Time-Zone'>data time zone</a>, not the <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/time-zones#04-Algorithm-Time-Zone'>algorithm time zone</a>. If you consolidate Crypto data into daily bars, the event handler receives the consolidated bars at midnight 12:00 AM Coordinated Universal Time (UTC), regardless of the algorithm time zone.</p>

<h4>Resolution Periods</h4>

<?php echo file_get_contents(DOCS_RESOURCES."//enumerations/resolution.html"); ?>

<p>If you do hourly consolidation, the consolidator ends at the top of the hour, not every hour after the market open. For US Equities, that's 10 AM Eastern Standard Time (EST), not 10:30 AM.</p>

<h4>CalendarInfo Periods</h4>

<p>You can use the built-in <code>CalendarInfo</code> objects or create your own. The follow table describes the helper methods that the <code>Calendar</code> class provides to create the built-in <code>CalendarInfo</code> objects:</p>

<table class="qc-table table">
    <thead>
        <tr>
            <th>Method</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>Calendar.Weekly</code></td>
	    <td>Computes the start of week (previous Monday) of the given date/time</td>
        </tr>
        <tr>
            <td><code>Calendar.Monthly</code></td>
	    <td>Computes the start of month (1st of the current month) of the given date/time</td>
        </tr>
        <tr>
            <td><code>Calendar.Quarterly</code></td>
	    <td>Computes the start of quarter (1st of the starting month of the current quarter) of the given date/time</td>
        </tr>
        <tr>
            <td><code>Calendar.Yearly</code></td>
	    <td>Computes the start of year (1st of the current year) of the given date/time</td>
        </tr>
    </tbody>
</table>

<p>If you need something more specific than the preceding time periods, define a method to set the start and end time of consolidated bars. The method should receive the current time and return a <code>CalendarInfo</code> object, which contains the start time of the bar and the duration of the consolidation period.</p>

<br>
## TODO: Add example of Custom Consolidator period for weekly bars in Forex

<div class="section-example-container">
<pre class="python">def CustomPeriod(self, dt):
    period = timedelta(7)
    dt = dt.replace(hour=17, minute=0, second=0, microsecond=0)
    delta = 1+dt.weekday()
    if delta &gt; 6:
        delta = 0
    start = dt-timedelta(delta)
    return CalendarInfo(start, period)</pre>
</div>

^ Add C# Example
<br><br>


Another example: https://www.quantconnect.com/terminal/processCache/?request=embedded_backtest_cbb86a544cd77a7e1fd72d7581719041.html




<h4>Create Time Period Consolidators</h4>

<p>To create a time period consolidator, pass the time period to the consolidator constructor.</p>
<div class="section-example-container">
<pre class="python"># timedelta argument
self.timedelta_trade_bar = TradeBarConsolidator(timedelta(days=1))
self.timedelta_quote_bar = QuoteBarConsolidator(timedelta(days=1))
self.timedelta_trade_tick = TickConsolidator(timedelta(seconds=1))
self.timedelta_quote_tick = TickQuoteBarConsolidator(timedelta(seconds=1))

# Resolution argument (only works for TradeBarConsolidator)
self.resolution_trade_bar = TradeBarConsolidator.FromResolution(Resolution.Daily)

# Calendar method argument
self.calendar_trade_bar = TradeBarConsolidator(Calendar.Yearly)
self.calendar_quote_bar = QuoteBarConsolidator(Calendar.Quarterly)
self.calendar_trade_tick = TickConsolidator(Calendar.Monthly)
self.calendar_quote_tick = TickQuoteBarConsolidator(Calendar.Weekly)

# Custom method argument
self.custom_consolidator = TradeBarConsolidator(self.my_calendarinfo_method)</pre>
<pre class="csharp"></pre>
</div>

<h4>Shortcut Method</h4>
<p>The <code class="csharp">Consolidate</code><code class="python">self.Consolidate</code> method is a helper method to create time period consolidators for algorithms with static universes. With just one line of code, you can create data in any time period with a <code class='python'>timedelta</code><code class='csharp'>TimeSpan</code>, <code>Resolution</code>, or <code>CalendarInfo</code> object. To create a consolidator with the shortcut method, call <code>Consolidate</code> with a <code>Symbol</code>, time period, and <a href='/docs/v2/writing-algorithms/consolidating-data/key-concepts#04-Receive-Consolidated-Bars'>event handler</a>. If you don't pass the method a <code>Symbol</code>, it looks up the <code>Symbol</code> internally.</p>

<div class="section-example-container">
<pre class="csharp">// Consolidate 1min SPY -&gt; 45min Bars
Consolidate("SPY", TimeSpan.FromMinutes(45), FortyFiveMinuteBarHandler)

// Consolidate 1min SPY -&gt; 1-Hour Bars
Consolidate("SPY", Resolution.Hour, HourBarHandler)

// Consolidate 1min SPY -&gt; 1-Week Bars
Consolidate("SPY", Calendar.Weekly, WeekBarHandler)
</pre>
<pre class="python"># Consolidate 1min SPY -&gt; 45min Bars
self.Consolidate("SPY", timedelta(minutes=45), self.FortyFiveMinuteBarHandler)

# Consolidate 1min SPY -&gt; 1-Hour Bars
self.Consolidate("SPY", Resolution.Hour, self.HourBarHandler)

# Consolidate 1min SPY -&gt; 1-Week Bars
self.Consolidate("SPY", Calendar.Weekly, self.WeekBarHandler)
</pre>
</div>

<p>If your data subscription provides both trades and quotes, you can pass a <code>TickType</code> to the <code>Consolidate</code> method to specify the data format you want to consolidate.</p>

<div class="section-example-container">
<pre class="csharp">var symbol = AddEquity("SPY", Resolution.Minute).Symbol;

// Create QuoteBar objects
Consolidate(symbol, Resolution.Hour, TickType.Quote, ConsolidationHandler);</pre>
<pre class="python">symbol = self.AddEquity("SPY", Resolution.Minute).Symbol

# Create QuoteBar objects
self.Consolidate(symbol, Resolution.Hour, TickType.Quote, self.ConsolidationHandler)</pre>
</div>

<p>When the consolidator receives a bar that reaches or passes the consolidation period, it passes the consolidated bar to the <a href='/docs/v2/writing-algorithms/consolidating-data/key-concepts#04-Receive-Consolidated-Bars'>event handler</a>. If you create a consolidator with the <code>Consolidate</code> method, you can't remove the consolidator.</p>