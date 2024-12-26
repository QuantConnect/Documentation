<?
$imgLink = "https://cdn.quantconnect.com/i/tu/history-dividend-dataframe-us-equities.png";
$dataTypeLink = "/docs/v2/writing-algorithms/securities/asset-classes/us-equity/corporate-actions#03-Dividends";
$dataType = "Dividend";
?>

<p class='csharp'>
  To get historical <a href='<?=$dataTypeLink?>'>dividend data</a>, call the <code>History&lt;Dividend&gt;</code> method with an asset's <code>Symbol</code>.
</p>

<p class='python'>
  To get historical <a href='<?=$dataTypeLink?>'>dividend data</a>, call the <code>history</code> method with the <code>Dividend</code> type and an asset's <code>Symbol</code>.
  This method returns a DataFrame with columns for the dividend payment and reference price.
</p>

<div class="section-example-container">
    <pre class="csharp">// Get the Symbol of an asset.
var symbol = AddEquity("AAPL").Symbol;
// Get the dividends that the stock paid over the last 2 years. 
var history = History&lt;Dividend&gt;(symbol, TimeSpan.FromDays(2*365);</pre>
    <pre class="python"># Get the Symbol of an asset.
symbol = self.add_equity('AAPL').symbol
# Get the dividends that the stock paid over the last 2 years in DataFrame format. 
history = self.history(Dividend, symbol, timedelta(2*365))</pre>
</div>

<img class='python docs-image' src='<?=$imgLink?>' alt='DataFrame of historical dividend payments for a stocks.'>

<p class='python'>
  If you request a DataFrame, LEAN unpacks the data from <code>Slice</code> objects to populate the DataFrame. 
  If you intend to use the data in the DataFrame to create <code><?=$dataType?></code> objects, request that the history request returns the data type you need. 
  Otherwise, LEAN will consume computational resources populating the DataFrame.  
  To get a list of <code><?=$dataType?></code> objects instead of a DataFrame, call the <code>history[<?=$dataType?>]</code> method.
</p>


<div class="python section-example-container">
    <pre class="python"># Get the dividends that a stock paid over the last 2 years in Dividend format. 
history = self.history[Dividend](symbol, timedelta(2*365))</pre>
</div>
