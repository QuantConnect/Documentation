<p>You can access current and historical indicator values.</p>

<h4>Current Indicator Values</h4>

<p>To access the indicator value, use the <code>.Current.Value</code> property. Some indicators have one output and some indicators have multiple outputs. The <code>SimpleMovingAverage</code> indicator only has one output, the average price over the last n periods, so the <code>.Current.Value</code> property returns this value. The <code>BollingerBand</code> indicator has multiple outputs because it has a simple moving average, an upper band, and a lower band. For indicators that have multiple outputs, refer to the Indicator Reference to see how to access the output values.</p>

<div class="section-example-container">
	<pre class="python">sma = self.sma.Current.Value

current_price = self.bb.Current.Value
bb_upper_band = self.bb.UpperBand.Current.Value
bb_lower_band = self.bb.LowerBand.Current.Value</pre>
	<pre class="csharp fsharp">var sma = _sma.Current.Value

var currentPrice = _bb.Current.Value
var bbUpperBand = _bb.UpperBand.Current.Value
var bbLowerBand = _bb.LowerBand.Current.Value</pre>
</div>

<p>You can implicitly cast indicators to the decimal version of their <code>.Current.Value</code> property.</p>

<div class="section-example-container">
	<pre class="python">if self.sma &gt; self.bb.UpperBand:
    self.SetHoldings(self.symbol, -0.1)</pre>
	<pre class="csharp fsharp">if (_sma &gt; _bb.UpperBand)
{
    SetHoldings(_symbol, -0.1);
}</pre>
</div>

<h4>Historical Indicator Values</h4>
<?php 
include(DOCS_RESOURCES."/indicators/historical-values.php "); 
$linkToIndicatorsPage = false;
$getHistoricalValuesText($linkToIndicatorsPage);
?>


<br>-Add section on RollingWindow to this chapter, need to cover PandasConverter.GetIndicatorDataFrame
