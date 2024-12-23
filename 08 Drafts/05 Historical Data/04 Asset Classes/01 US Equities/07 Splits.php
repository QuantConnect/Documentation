<?
$imgLink = "https://cdn.quantconnect.com/i/tu/history-split-dataframe-us-equities.png";
$dataTypeLink = "/docs/v2/writing-algorithms/securities/asset-classes/us-equity/corporate-actions#02-Splits";
?>

<p class='csharp'>
  To get historical <a href='<?=$dataTypeLink?>'>split data</a>, call the <code>History&lt;Split&gt;</code> method with an asset's <code>Symbol</code>.
</p>

<p class='python'>
  To get historical <a href='<?=$dataTypeLink?>'>split data</a>, call the <code>history</code> method with the <code>Split</code> type and an asset's <code>Symbol</code>.
  This method returns a DataFrame with columns for the reference price, split factor, and split type.
</p>

<div class="section-example-container">
    <pre class="csharp">// Get the Symbol of an asset.
var symbol = AddEquity("AAPL").Symbol;
// Get the splits that occured for the stock over the last 5 years. 
var history = History&lt;Split&gt;(symbol, TimeSpan.FromDays(5*365);</pre>
    <pre class="python"># Get the Symbol of an asset.
symbol = self.add_equity('AAPL').symbol
# Get the splits that occured for the stock over the last 5 years in DataFrame format. 
history = self.history(Split, symbol, timedelta(5*365))</pre>
</div>

<img class='python docs-image' src='<?=$imgLink?>' alt='DataFrame of historical stock splits for AAPL.'>

<p class='python'>To get a list of <code>Split</code> objects instead of a DataFrame, call the <code>history[Split]</code> method.</p>

<div class="python section-example-container">
    <pre class="python"># Get the splits that occured for a stock over the last 5 years in Split format. 
history = self.history[Split](symbol, timedelta(5*365))</pre>
</div>
