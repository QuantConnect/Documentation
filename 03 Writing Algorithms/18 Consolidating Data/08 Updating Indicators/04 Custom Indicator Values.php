<p>Data point indicators use only a single price data in their calculations. By default, those indicators use the closing price. For 
assets with <code>TradeBar</code> data, that price is the <code>TradeBar</code> close price. For assets with <code>QuoteBar</code> data, that price is the mid-price of the bid closing price and the ask closing price. To create an indicator with the other fields like the <code>Open</code>, <code>High</code>, <code>Low</code>, or <code>Close</code>, provide a <code>selector</code> argument to the <code>RegisterIndicator</code> method.</p>

<div class="section-example-container">
<pre class="csharp">// Define a 10-period RSI with indicator the constructor
_rsi = new RelativeStrengthIndex(10, MovingAverageType.Simple);

// Register the daily High price data to automatically update the indicator
RegisterIndicator(symbol, _rsi, Resolution.Daily, Field.High);</pre>
<pre class="python"># Define a 10-period RSI with indicator constructor
self.rsi = RelativeStrengthIndex(10, MovingAverageType.simple)

# Register the daily High price data to automatically update the indicator
self.register_indicator(self.symbol, self.rsi, Resolution.DAILY, Field.high)</pre>
</div>

<p>The <code>RegisterIndicator</code> method can accept a <code class="python">timedelta</code><code class="csharp">TimeSpan</code>, <code>Resolution</code>, or an unregistered consolidator. If you apply the indicator to a security in a dynamic universe, provide a consolidator so that you can remove it when your algorithm removes the security from the universe.</p>

<p>The <code>Field</code> class has the following <code>selector</code> properties:</p>
<div data-tree='QuantConnect.Field'></div>


<p class='csharp'>To create a custom <code>selector</code>, define a function that calculates the value.</p>

<div class='csharp'>
    <div class="section-example-container">
        <pre class="csharp">RegisterIndicator(_symbol, _rsi,  Resolution.Daily, x =>
{
    var bar = x as IBaseDataBar;
    return (bar.Low + bar.High) / 2;
});</pre>
    </div>
</div>
