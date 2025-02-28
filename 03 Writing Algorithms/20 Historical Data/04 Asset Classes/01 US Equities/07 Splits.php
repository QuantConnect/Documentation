<?
$dataTypeLink = "/docs/v2/writing-algorithms/securities/asset-classes/us-equity/corporate-actions#02-Splits";
$dataType = "Split";
?>

<p class='csharp'>
  To get historical <a href='<?=$dataTypeLink?>'>split data</a>, call the <code>History&lt;Split&gt;</code> method with an asset's <code>Symbol</code>.
  Splits are a sparse dataset, so use a time period history request since most days have no data.
</p>

<p class='python'>
  To get historical <a href='<?=$dataTypeLink?>'>split data</a>, call the <code>history</code> method with the <code>Split</code> type and an asset's <code>Symbol</code>.
  This method returns a DataFrame with columns for the reference price, split factor, and split type.
  Splits are a sparse dataset, so use a time period history request since most days have no data.
</p>

<div class="section-example-container">
    <pre class="csharp">public class USEquitySplitHistoryAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2024, 12, 1);
        // Get the Symbol of an asset.
        var symbol = AddEquity("AAPL").Symbol;
        // Get the splits that occured for the stock over the last 5 years. 
        var history = History&lt;Split&gt;(symbol, TimeSpan.FromDays(5*365));
        // Select the dates when splits occurred.
        var splitDates = history.Where(split => split.Type == SplitType.SplitOccurred).Select(split => split.EndTime);
    }
}</pre>
    <pre class="python">class USEquitySplitHistoryAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 12, 1)
        # Get the Symbol of an asset.
        symbol = self.add_equity('AAPL').symbol
        # Get the splits that occured for the stock over the last 5 years in DataFrame format. 
        history = self.history(Split, symbol, timedelta(5*365))</pre>
</div>

<div class="dataframe-wrapper">
  <table class="dataframe python">
    <thead>
      <tr style="text-align: right;">
        <th></th>
        <th></th>
        <th>referenceprice</th>
        <th>splitfactor</th>
        <th>type</th>
        <th>value</th>
      </tr>
      <tr>
        <th>symbol</th>
        <th>time</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th rowspan="2" valign="top">AAPL</th>
        <th>2020-08-28</th>
        <td>0.00</td>
        <td>0.25</td>
        <td>0</td>
        <td>0.00</td>
      </tr>
      <tr>
        <th>2020-08-31</th>
        <td>499.23</td>
        <td>0.25</td>
        <td>1</td>
        <td>499.23</td>
      </tr>
    </tbody>
  </table>
</div>


<p class='python'>In the preceding DataFrame, the <code>type</code> column represents the <code>SplitType</code> enumeration, where 0=<code>SplitType.WARNING</code> and 1=<code>SplitType.SPLIT_OCCURRED</code>.</p>

<div class="python section-example-container">
    <pre class="python"># Select the prices where splits occurred.
split_prices = history[history.type == SplitType.SPLIT_OCCURRED].value</pre>
</div>

<div class="python section-example-container">
    <pre>symbol  time      
AAPL    2020-08-31    499.23
Name: value, dtype: float64</pre>
</div>

<p class='python'>
  If you intend to use the data in the DataFrame to create <code><?=$dataType?></code> objects, request that the history request returns the data type you need. 
  Otherwise, LEAN consumes unnecessary computational resources populating the DataFrame.  
  To get a list of <code><?=$dataType?></code> objects instead of a DataFrame, call the <code>history[<?=$dataType?>]</code> method.
</p>


<div class="python section-example-container">
    <pre class="python"># Get the splits that occured for a stock over the last 5 years in Split format. 
history = self.history[Split](symbol, timedelta(5*365))
# Iterate through each Split object.
for split in history:
    # Select the time when each split occurred.
    if split.type == SplitType.SPLIT_OCCURRED:
        t = split.end_time</pre>
</div>
