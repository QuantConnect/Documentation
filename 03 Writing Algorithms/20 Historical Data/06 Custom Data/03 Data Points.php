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
    <pre class="csharp">public class CustomSecurityHistoryAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2014, 7, 10);
        // Add a custom dataset and save a reference to it's Symbol.
        var datasetSymbol = AddData&lt;MyCustomDataType&gt;("MyCustomDataType", Resolution.Daily).Symbol;
        // Get the trailing 5 days of MyCustomDataType data.
        var history = History&lt;MyCustomDataType&gt;(datasetSymbol, 5, Resolution.Daily);
    }
}</pre>
    <pre class="python">class CustomSecurityHistoryAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2014, 7, 10)
        # Add a custom dataset and save a reference to it's Symbol.
        dataset_symbol = self.add_data(MyCustomDataType, "MyCustomDataType", Resolution.DAILY).symbol
        # Get the trailing 5 days of MyCustomDataType data in DataFrame format.
        history = self.history(dataset_symbol, 5, Resolution.DAILY)</pre>
</div>

<div class="dataframe-wrapper">
  <table class="dataframe python">
    <thead>
      <tr style="text-align: right;">
        <th></th>
        <th></th>
        <th>close</th>
        <th>high</th>
        <th>low</th>
        <th>open</th>
        <th>value</th>
      </tr>
      <tr>
        <th>symbol</th>
        <th>time</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th rowspan="3" valign="top">MYCUSTOMDATATYPE.MyCustomDataType</th>
        <th>2014-07-08</th>
        <td>7787.15</td>
        <td>7792.00</td>
        <td>7755.10</td>
        <td>7780.40</td>
        <td>7787.15</td>
      </tr>
      <tr>
        <th>2014-07-09</th>
        <td>7623.20</td>
        <td>7808.85</td>
        <td>7595.90</td>
        <td>7804.05</td>
        <td>7623.20</td>
      </tr>
      <tr>
        <th>2014-07-10</th>
        <td>7585.00</td>
        <td>7650.10</td>
        <td>7551.65</td>
        <td>7637.95</td>
        <td>7585.00</td>
      </tr>
    </tbody>
  </table>
</div>

<p class='python'>
  If you intend to use the data in the DataFrame to create <code><span class='placeholder-text'>customDatasetClass</span></code> objects, request that the history request returns the data type you need. 
  Otherwise, LEAN consumes unnecessary computational resources populating the DataFrame.  
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
