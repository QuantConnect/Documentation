<?
$imgLink = "https://cdn.quantconnect.com/i/tu/history-split-dataframe-us-equities.png";
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
    <pre class="csharp">// Get the Symbol of an asset.
var symbol = AddEquity("AAPL").Symbol;
// Get the splits that occured for the stock over the last 5 years. 
var history = History&lt;Split&gt;(symbol, TimeSpan.FromDays(5*365));
// Select the dates when splits occurred.
var splitDates = history.Where(split => split.Type == SplitType.SplitOccurred).Select(split => split.EndTime);
</pre>
    <pre class="python"># Get the Symbol of an asset.
symbol = self.add_equity('AAPL').symbol
# Get the splits that occured for the stock over the last 5 years in DataFrame format. 
history = self.history(Split, symbol, timedelta(5*365))</pre>
</div>

<img class='python docs-image' src='<?=$imgLink?>' alt='DataFrame of historical stock splits for AAPL.'>

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
  If you request a DataFrame, LEAN unpacks the data from <code>Slice</code> objects to populate the DataFrame. 
  If you intend to use the data in the DataFrame to create <code><?=$dataType?></code> objects, request that the history request returns the data type you need. 
  Otherwise, LEAN will consume computational resources populating the DataFrame.  
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
