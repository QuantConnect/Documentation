<?
$datasetClass = "Fred";
$imgLink = "https://cdn.quantconnect.com/i/tu/fred-dataframe-history.png";
?>

<p class='csharp'>
  To get a list of historical alternative data, call the <code>History&lt;<span class='placeholder-text'>alternativeDataClass</span>&gt;</code> method with the dataset <code>Symbol</code>.
</p>

<p class='python'>
  To get historical alternative data points, call the <code>history</code> method with the dataset <code>Symbol</code>.
  This method returns a DataFrame that contains the data point attributes.
</p>

<div class="section-example-container">
    <pre class="csharp">// Get the trailing 5 days of <?=$datasetClass?> data.
var history = History&lt;<?=$datasetClass?>&gt;(datasetSymbol, 5, Resolution.Daily);</pre>
    <pre class="python"># Get the trailing 5 days of <?=$datasetClass?> data in DataFrame format.
history = self.history(dataset_symbol, 5, Resolution.DAILY)</pre>
</div>

<img class='python docs-image' src='<?=$imgLink?>' alt='DataFrame of <?=$datasetClass?> data.'>

<p class='python'>To get a list of dataset objects instead of a DataFrame, call the <code>history[<span class='placeholder-text'>alternativeDataClass</span>]</code> method.</p>

<div class="python section-example-container">
    <pre class="python"># Get the trailing 5 days of <?=$datasetClass?> data for an asset in <?=$datasetClass?> format. 
history = self.history[<?=$datasetClass?>](symbol, 5, Resolution.DAILY)</pre>
</div>


<div class='python'>
  <p>
    Some alternative datasets provide multiple entries per <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/timeslices'>time step</a>. 
    For example, the <a href='/datasets/regalytics-us-regulatory-alerts'>US Regulatory Alerts</a> dataset can provide multiple alerts per day.
    In this case, to organize the data into a DataFrame, set the <code>flatten</code> argument to <code>True</code>.
  </p>

  <div class="section-example-container">
    <pre class="python"># Get the all the Regalytics articles that were published over the last day, organized in a DataFrame.
dataset_symbol = self.add_data(RegalyticsRegulatoryArticles, "REG").symbol
history = self.history(dataset_symbol, 1, Resolution.DAILY, flatten=True)</pre>
  </div>

  <img src='https://cdn.quantconnect.com/i/tu/regalytics-dataframe-history.png' class='docs-image' alt='DataFrame of regulatory alerts.'>
</div>

