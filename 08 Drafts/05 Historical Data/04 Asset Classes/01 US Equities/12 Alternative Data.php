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

<!-- This part is only for US Equities. Not all asset classes -->
<div class='python'>
  <p>
    Some alternative datasets provide multiple entries per asset per day. 
    For example, in each asset's daily update, the <a href='/datasets/extractalpha-true-beats'>True Beats dataset</a> provides EPS and revenue predictions for several upcoming quarters.
    In this case, to organize the data into a DataFrame, set the <code>flatten</code> argument to <code>True</code>.
  </p>

  <div class="section-example-container">
    <pre class="python"># Get the ExtractAlphaTrueBeats data for AAPL on 01/02/2024 organized in a flat DataFrame.
aapl = self.add_equity("AAPL", Resolution.DAILY)
aapl.true_beats = self.add_data(ExtractAlphaTrueBeats, aapl.symbol).symbol
history = self.history(aapl.true_beats, datetime(2024, 1, 2), datetime(2024, 1, 3), Resolution.DAILY, flatten=True)</pre>
  </div>

  <img src='https://cdn.quantconnect.com/i/tu/history-request-number-of-samples-different-resolutions.png' class='docs-image' alt='DataFrame of ExtractAlphaTrueBeats data for AAPL on 01/02/2024.'>
</div>

<p>For information on historical data for other alternative datasets, see the documentation in the <a href='/datasets/'>Dataset Market</a>.</p>
