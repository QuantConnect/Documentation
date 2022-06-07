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

<p>If you need something more specific than the preceding time periods, define a method to set the start and end time of consolidated bars. The method receives the current time and returns a <code>CalendarInfo</code> object that contains the start time of the bar and the consolidation period.</p>

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




<h4>Creating Time Period Consolidators</h4>

<p>
Follow these steps to manually create a consolidator:</p><p>1) Create a consolidator object (TradeBarConsolidator, QuoteBarConsolidator, TickConsolidator, or TickQuoteConsolidator)</p><p>2) Define the consolidation event handler</p><p>3) Add the event handler to the consolidator</p>

<div class="section-example-container">
<pre class="python">def Initialize(self):
    self.consolidator = TradeBarConsolidator(timedelta(minutes=30))
    self.consolidator.DataConsolidated += self.consolidation_handler
    
def consolidation_handler(self, sender, consolidated_bar):
    # Bar period is now 30 min from the consolidator above.
    self.Debug(str(consolidated_bar.EndTime - consolidated_bar.Time) + " " + consolidated_bar.ToString())</pre>
<pre class="csharp">public override void Initialize()
{ 
    _consolidator = new TradeBarConsolidator(TimeSpan.FromMinutes(30));
    _consolidator.DataConsolidated += ConsolidationHandler;
}

private void ConsolidationHandler(object sender, TradeBar consolidatedBar) {
    // Bar period is 30 min from the consolidator above.
    Debug((consolidatedBar.EndTime - consolidatedBar.Time).ToString() + " " + consolidatedBar.ToString());
}</pre>
</div>

<p>if you consolidate minute-resolution crypto data, you receive the consolidated bar at midnight UTC. If you consolidated minute-resolution Equity data, you receive the consolidated bar at 9:31AM the next day because 4:00pm (closing time) isn't the end of the day.</p>

<h4>Shortcut Method</h4>
<p>The <code class="csharp">Consolidate</code><code class="python">self.Consolidate</code> method is a helper method to create time period consolidators for algorithms with static universes. With just one line of code, you can create data in any time period with a timedelta/TimeSpan, Resolution, or Calendar object. To create a consolidator with the shortcut method, call <code class="csharp">Consolidate</code><code class="python">self.Consolidate</code> with a <code>Symbol</code>, time period, and event handler. If you don't pass the method a <code>Symbol</code>, it looks up the <code>Symbol</code> internally.</p>
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

<p>The <code class="csharp">Consolidate</code><code class="python">self.Consolidate</code> method usually produces TradeBars by default, but it produces QuoteBars for Forex since Forex data doesn't have TradeBars. If your data subscription provides both trades and quotes, you can pass a <code>TickType</code> to the <code class="csharp">Consolidate</code><code class="python">self.Consolidate</code> method to specify the data format you want to consolidate.</p>

<div class="section-example-container">
<pre class="csharp">var symbol = AddEquity("SPY", Resolution.Minute).Symbol;
Consolidate(symbol, Resolution.Hour, TickType.Quote, ConsolidationHandler);</pre>
<pre class="python">symbol = self.AddEquity("SPY", Resolution.Minute).Symbol
self.Consolidate(symbol, Resolution.Hour, TickType.Quote, self.ConsolidationHandler)</pre>
</div>

<p>The <code class="csharp">Consolidate</code><code class="python">self.Consolidate</code> method creates a consolidator for the time period you provide and passes the consolidated bars to the function event handler. The event handler function accepts one argument, the consolidated TradeBar or QuoteBar.<br></p>

<div class="section-example-container">
<pre class="csharp">// Example event handler from Consolidate helper.
void ConsolidationHandler(TradeBar consolidatedBar) {
    Log($"{consolidatedBar.EndTime:o} 45 minute consolidated.");
}
</pre>
<pre class="python"># Example event handler from Consolidate helper.
def ConsolidationHandler(self, consolidated_bar):
      self.Log(f"{consolidated_bar.EndTime}: {consolidated_bar.Close}")
</pre>
</div>

<p>If you create a consolidator with the shortcut method, you can't remove the consolidator.
</p>
