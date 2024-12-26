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
    <pre class="csharp">// Get the Symbol of a dataset.
var datasetSymbol = AddData&lt;<?=$datasetClass?>&gt;("RVXCLS").Symbol;
// Get the trailing 5 days of <?=$datasetClass?> data.
var history = History&lt;<?=$datasetClass?>&gt;(datasetSymbol, 5, Resolution.Daily);</pre>
    <pre class="python"># Get the Symbol of a dataset.
dataset_symbol = self.add_data(Fred, 'RVXCLS').symbol
# Get the trailing 5 days of <?=$datasetClass?> data in DataFrame format.
history = self.history(dataset_symbol, 5, Resolution.DAILY)</pre>
</div>

<img class='python docs-image' src='<?=$imgLink?>' alt='DataFrame of <?=$datasetClass?> data.'>

<div class="python section-example-container">
    <pre class="python"># Calculate the dataset's rate of change.
roc = history.pct_change().iloc[1:]</pre>
</div>

<p class='python'>
  If you request a DataFrame, LEAN unpacks the data from <code>Slice</code> objects to populate the DataFrame. 
  If you intend to use the data in the DataFrame to create <code><span class='placeholder-text'>alternativeDataClass</span></code> objects, request that the history request returns the data type you need. 
  Otherwise, LEAN will consume computational resources populating the DataFrame.  
  To get a list of dataset objects instead of a DataFrame, call the <code>history[<span class='placeholder-text'>alternativeDataClass</span>]</code> method.
</p>

<div class="python section-example-container">
    <pre class="python"># Get the trailing 5 days of <?=$datasetClass?> data for an asset in <?=$datasetClass?> format. 
history = self.history[<?=$datasetClass?>](dataset_symbol, 5, Resolution.DAILY)
# Iterate through the historical data points.
for data_point in history:
    t = data_point.end_time
    value = data_point.value</pre>
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

