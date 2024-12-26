<?
$imgLink = "https://cdn.quantconnect.com/i/tu/custom-dataset-dataframe-history.png";
?>

<p class='csharp'>
  To get a list of historical data points for a custom dataset, call the <code>History&lt;<span class='placeholder-text'>customDatasetClass</span>&gt;</code> method with the dataset <code>Symbol</code>.
  For an example definition of a custom data class, see the <a href='/docs/v2/writing-algorithms/importing-data/streaming-data/custom-securities/csv-format-example'>CSV Format Example</a>.
</p>

<p class='python'>
  To get historical data points for a custom dataset, call the <code>history</code> method with the dataset <code>Symbol</code>.
  This method returns a DataFrame that contains the data point attributes of the dataset class.
  For an example definition of a custom data class, see the <a href='/docs/v2/writing-algorithms/importing-data/streaming-data/custom-securities/csv-format-example'>CSV Format Example</a>.
</p>

<div class="section-example-container">
    <pre class="csharp">// Add a custom dataset and save a reference to it's Symbol.
var datasetSymbol = AddData&lt;MyCustomDataType&gt;("MyCustomDataType", Resolution.Daily).Symbol;
// Get the trailing 5 days of MyCustomDataType data.
var history = History&lt;MyCustomDataType&gt;(datasetSymbol, 5, Resolution.Daily);</pre>
    <pre class="python"># Add a custom dataset and save a reference to it's Symbol.
dataset_symbol = self.add_data(MyCustomDataType, "MyCustomDataType", Resolution.DAILY).symbol
# Get the trailing 5 days of MyCustomDataType data in DataFrame format.
history = self.history(dataset_symbol, 5, Resolution.DAILY)</pre>
</div>

<img class='python docs-image' src='<?=$imgLink?>' alt='DataFrame of MyCustomDataType data.'>

<p class='python'>
  If you request a DataFrame, LEAN unpacks the data from <code>Slice</code> objects to populate the DataFrame. 
  If you intend to use the data in the DataFrame to create <code><span class='placeholder-text'>customDatasetClass</span></code> objects, request that the history request returns the data type you need. 
  Otherwise, LEAN will consume computational resources populating the DataFrame.  
  To get a list of dataset objects instead of a DataFrame, call the <code>history[<span class='placeholder-text'>customDatasetClass</span>]</code> method.
</p>

<div class="python section-example-container">
    <pre class="python"># Get the trailing 5 days of MyCustomDataType data for an asset in MyCustomDataType format. 
history = self.history[MyCustomDataType](dataset_symbol, 5, Resolution.DAILY)</pre>
</div>


<div class='python'>
  <p>
    If the dataset provides multiple entries per <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/timeslices'>time step</a>, in the <code class='python'>get_source</code><code class='csharp'>GetSource</code> method of your custom data class, return a <code>SubscriptionDataSource</code> that uses <code class='python'>FileFormat.UNFOLDING_COLLECTION</code><code class='csharp'>FileFormat.UnfoldingCollection</code>. 
    To get the historical data of this custom data type in a DataFrame, set the <code>flatten</code> argument to <code>True</code>.
  </p>

  <div class="section-example-container">
    <pre class="python">history = self.history(dataset_symbol, 1, Resolution.DAILY, flatten=True)</pre>
  </div>
</div>
