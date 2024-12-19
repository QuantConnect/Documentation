<?
$imgLink = "https://cdn.quantconnect.com/i/tu/history-tick-dataframe-us-equities.png";
$dataTypeLink = "/docs/v2/writing-algorithms/securities/asset-classes/us-equity/handling-data#05-Ticks";
?>

<p class='csharp'>
  To get historical <a href='<?=$dataTypeLink?>'>tick data</a>, call the <code>History&lt;Tick&gt;</code> method with an asset's <code>Symbol</code> and <code>Resolution.Tick</code>.
  This history request works when you request ticks over a trailing period of time or between start and end times.
</p>

<p class='python'>
  To get historical <a href='<?=$dataTypeLink?>'>tick data</a>, call the <code>history</code> method with an asset's <code>Symbol</code> and <code>Resolution.TICK</code>.
  This method returns a DataFrame that contains data on bids, asks, and trades.
</p>

<div class="section-example-container">
    <pre class="csharp">// Get the trailing 2 days of ticks for an asset.
var history = History&lt;Tick&gt;(symbol, TimeSpan.FromDays(2), Resolution.Tick);</pre>
    <pre class="python"># Get the trailing 2 days of ticks for an asset in DataFrame format.
history = self.history(symbol, timedelta(2), Resolution.TICK)</pre>
</div>

<img class='python docs-image' src='<?=$imgLink?>' alt='DataFrame of tick data for an asset.'>

<p class='python'>To get a list of <code>Tick</code> objects instead of a DataFrame, call the <code>history[Tick]</code> method.</p>

<div class="python section-example-container">
    <pre class="python"># Get the trailing 2 days of ticks for an asset in Tick format. 
history = self.history[Tick](symbol, timedelta(2), Resolution.TICK)</pre>
</div>

<p>Tick history requests only accept a trialing period of time or start and end dates. These history requests don't work if you provide a <code>period</code> argument, requesting a specific nubmer of trailing ticks.</p>
