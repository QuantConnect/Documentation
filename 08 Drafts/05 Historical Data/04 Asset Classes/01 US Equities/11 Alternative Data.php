<?
$datasetClass = "QuiverWallStreetBets";
$imgLink = "https://cdn.quantconnect.com/i/tu/history-alt-data-dataframe-us-equities.png";
?>

<p class='csharp'>
  To get historical alternative data, call the <code>History&lt;<span class='placeholder-text'>alternativeDataClass</span>&gt;</code> method with the dataset <code>Symbol</code>.
</p>

<p class='python'>
  To get historical alternative data, call the <code>history</code> method with the alternative data type and the dataset <code>Symbol</code>.
  This method returns a DataFrame that contains the data point attributes.
</p>

<div class="section-example-container">
    <pre class="csharp">// Get the trailing 5 days of <?=$datasetClass?> data for an asset.
var history = History&lt;<?=$datasetClass?>&gt;(datasetSymbol, 5, Resolution.Daily);</pre>
    <pre class="python"># Get the trailing 5 days of <?=$datasetClass?> data for an asset in DataFrame format.
history = self.history(<?=$datasetClass?>, dataset_symbol, 5, Resolution.DAILY)</pre>
</div>

<img class='python docs-image' src='<?=$imgLink?>' alt='DataFrame of <?=$datasetClass?> data for an asset.'>

<p class='python'>To get a list of <code><?=$datasetClass?></code> objects instead of a DataFrame, call the <code>history[<?=$datasetClass?>]</code> method.</p>

<div class="python section-example-container">
    <pre class="python"># Get the trailing 5 days of <?=$datasetClass?> data for an asset in <?=$datasetClass?> format. 
history = self.history[<?=$datasetClass?>](symbol, 5, Resolution.DAILY)</pre>
</div>

<p>For information on historical data for other alternative datasets, see the documentation in the <a href='/datasets/'>Dataset Market</a>.</p>
