<p>Data point indicators use only a single price data in their calculations. By default, those indicators use the closing price. For 
assets with <code>TradeBar</code> data, that price is the <code>TradeBar</code> close price. For assets with <code>QuoteBar</code> data, that price is the mid-price of the bid closing price and the ask closing price. To create an indicator with the other fields like the <code>Open</code>, <code>High</code>, <code>Low</code>, or <code>Close</code>, provide a <code>selector</code> argument to the indicator helper method.</p>

<div class="section-example-container">
	<pre class="python"># Define a 10-period daily RSI indicator with shortcut helper method
# Select the Open price to update the indicator
self._rsi = self.rsi("SPY", 10,  MovingAverageType.SIMPLE, Resolution.DAILY, Field.OPEN)</pre>
	<pre class="csharp">// Define a 10-period daily RSI indicator with shortcut helper method
// Select the Open price to update the indicator
_rsi = RSI("SPY", 10,  MovingAverageType.Simple, Resolution.Daily, Field.Open);</pre>
</div>

<p>The <code>Field</code> class has the following <code>selector</code> properties:</p>
<div data-tree='QuantConnect.Field'></div>

<p class='csharp'>To create a custom <code>selector</code>, define a function that calculates the value.</p>

<div class='csharp'>
    <div class="section-example-container">
        <pre class="csharp">_rsi = RSI("SPY", 10,  MovingAverageType.Simple, Resolution.Daily, x =>
{
    var bar = x as IBaseDataBar;
    return (bar.Low + bar.High) / 2;
});</pre>
    </div>
</div>