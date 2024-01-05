
<p>The results page shows the custom charts that you create.</p>
<h4>Supported Chart Types</h4>
<p>We support the following types of charts:</p>
<div data-tree='QuantConnect.SeriesType'></div>
<p>If you use <code>SeriesType.Candle</code> and plot enough values, the plot displays candlesticks. However, the <code>Plot</code> method only accepts one numerical value per time step, so you can't plot candles that represent the open, high, low, and close values of each bar in your algorithm. The charting software automatically groups the data points you provide to create the candlesticks, so you can't control the period of time that each candlestick represents.</p>

<? if ($backtest) { ?>
<p>To create other types of charts, save the plot data in the Object Store and then load it into the Research Environment. In the Research Environment, you can <a href='/docs/v2/research-environment/charting'>create other types of charts with third-party charting packages</a>.</p>
<? } ?>

<h4>Supported Markers</h4>
<p>When you create scatter plots, you can set a marker symbol. We support the following marker symbols:</p>
<div data-tree='QuantConnect.ScatterMarkerSymbol'></div>

<h4>Chart Quotas</h4>
<? include(DOCS_RESOURCES."/plotting/quotas.php"); ?>
    
<h4>Demonstration</h4>
<p>For more information about creating custom charts, see <a href='/docs/v2/writing-algorithms/charting'>Charting</a>.
