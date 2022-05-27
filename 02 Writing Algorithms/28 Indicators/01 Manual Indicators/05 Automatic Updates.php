<p>With automatic updates, your indicators automatically update with the security data on a schedule you set. To configure automatic updates, create a consolidator and then call the <code>RegisterIndicator</code> method. If your algorithm has a dynamic universe, save a reference to the consolidator so you can <a href="/docs/v2/writing-algorithms/consolidating-data/updating-indicators#05-Remove-Indicator-Consolidators">remove it</a> when the universe removes the security. If you register an indicator for automatic updates, don't call the indicator's <code>Update</code> method or else the indicator will receive double updates.</p>

<div class="section-example-container">
	<pre class="python"># Create a security subscription 
self.symbol = self.AddEquity("SPY", Resolution.Minute).Symbol

# Create a manual indicator
self.indicator = RelativeStrengthIndex(10, MovingAverageType.Simple)

# Create a consolidator
self.consolidator = TradeBarConsolidator(1)

# Add the consolidator to the SubscriptionManager
self.SubscriptionManager.AddConsolidator(self.symbol, self.consolidator)

# Register the indicator to update with the consolidated data
self.RegisterIndicator(self.symbol, self.indicator, self.consolidator)</pre>
	<pre class="csharp">// Create a security subscription 
_symbol = AddEquity("SPY", Resolution.Hour);

// Create a manual indicator
_indicator = new RelativeStrengthIndex(10, MovingAverageType.Simple);

// Create a consolidator
_consolidator = new TradeBarConsolidator(1);

// Add the consolidator to the SubscriptionManager
SubscriptionManager.AddConsolidator(_symbol, _consolidator);

// Register the indicator to update with the consolidated data
RegisterIndicator(_symbol, _indicator, _consolidator);</pre>
</div>

<p>Data point indicators use only a single price data in their calculations. By default, those indicators use the closing price. For assets with <code>TradeBar</code> data, that price is the <code>TradeBar</code> close price. For assets with <code>QuoteBar</code> data, that price is the mid-price of the bid closing price and the ask closing price. To create an indicator with the other fields like the <code>Open</code>, <code>High</code>, <code>Low</code>, or <code>Close</code>, provide a <code>selector</code> argument to the <code>RegisterIndicator</code> method.</p><div class="section-example-container">
	<pre class="python">self.RegisterIndicator(self.symbol, self.indicator, self.consolidator, Field.High)
</pre>
	<pre class="csharp">RegisterIndicator(_symbol, _rsi, _consolidator, Field.High);
</pre>
</div>

<?php 
echo file_get_contents(DOCS_RESOURCES."/enumerations/field.html"); 
?>

<p class='csharp'></p>

<div class='csharp'>
    <div class="section-example-container">
        <pre class="csharp">RegisterIndicator(_symbol, _indicator, _consolidator, x =>
{
    var bar = x as IBaseDataBar;
    return (bar.Low + bar.High) / 2;
});
       </pre>
    </div>
</div>