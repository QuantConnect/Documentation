<p>With automatic updates, your indicators automatically update with the security data on a schedule you set. To configure automatic updates, create a <a href='/docs/v2/writing-algorithms/consolidating-data/getting-started'>consolidator</a> and then call the <code class="csharp">RegisterIndicator</code><code class="python">register_indicator</code> method. If you register an indicator for automatic updates, don't call the indicator's <code class="csharp">Update</code><code class="python">update</code> method or else the indicator will receive double updates.</p>

<div class="section-example-container">
	<pre class="python"># Create a security subscription 
self._symbol = self.add_equity("SPY", Resolution.MINUTE).symbol

# Create a manual indicator
self.indicator = RelativeStrengthIndex(10, MovingAverageType.SIMPLE)

# Create a consolidator
consolidator = TradeBarConsolidator(1)
consolidator = QuoteBarConsolidator(1)
consolidator = RenkoConsolidator(1)     # Renko consolidator that emits a bar when the price moves $1

# Register the indicator to update with the consolidated data
self.register_indicator(self._symbol, self.indicator, consolidator)</pre>
	<pre class="csharp">// Create a security subscription 
_symbol = AddEquity("SPY", Resolution.Hour);

// Create a manual indicator
_indicator = new RelativeStrengthIndex(10, MovingAverageType.Simple);

// Create a consolidator
var consolidator = new TradeBarConsolidator(1);
consolidator = new QuoteBarConsolidator(1);
consolidator = new RenkoConsolidator(1);    // Renko consolidator that emits a bar when the price moves $1

// Register the indicator to update with the consolidated data
RegisterIndicator(_symbol, _indicator, consolidator);</pre>
</div>

<p>Data point indicators use only a single price data in their calculations. By default, those indicators use the closing price. For assets with <code>TradeBar</code> data, that price is the <code>TradeBar</code> close price. For assets with <code>QuoteBar</code> data, that price is the mid-price of the bid closing price and the ask closing price. To create an indicator with the other fields like the <code>Open</code>, <code>High</code>, <code>Low</code>, or <code>Close</code>, provide a <code>selector</code> argument to the <code class="csharp">RegisterIndicator</code><code class="python">register_indicator</code> method.</p><div class="section-example-container">
	<pre class="python">self.register_indicator(self._symbol, self.indicator, consolidator, Field.HIGH)
</pre>
	<pre class="csharp">RegisterIndicator(_symbol, _rsi, consolidator, Field.High);
</pre>
</div>

<p>The <code>Field</code> class has the following <code>selector</code> properties:</p>
<div data-tree='QuantConnect.Field'></div>

<p class='csharp'>To create a custom <code>selector</code>, define a function that calculates the value.</p>

<div class='csharp'>
    <div class="section-example-container">
        <pre class="csharp">RegisterIndicator(_symbol, _indicator, _consolidator, x =>
{
    var bar = x as IBaseDataBar;
    return (bar.Low + bar.High) / 2;
});</pre>
    </div>
</div>

<p>To stop automatically updating an indicator, pass the indicator to the <code>DeregisterIndicator</code> method.</p>
<div class="section-example-container">
    <pre class="csharp">DeregisterIndicator(_indicator);
// Alias:
// UnregisterIndicator(_indicator);</pre>
    <pre class="python">self.deregister_indicator(self.indicator)
# Alias:
# self.unregister_indicator(self.indicator)</pre>
</div>
