<?
$assetClass = "US Equities";
$imgLink = "https://cdn.quantconnect.com/i/tu/history-quotebar-dataframe-us-equities.png";
$resolutionLink = "/docs/v2/writing-algorithms/securities/asset-classes/us-equity/requesting-data#03-Resolutions";
$dataTypeLink = "/docs/v2/writing-algorithms/securities/asset-classes/us-equity/handling-data#04-Quotes";
?>

<p class='csharp'>
  To get historical <a href='<?=$dataTypeLink?>'>quote data</a>, call the <code>History&lt;QuoteBar&gt;</code> method with an asset's <code>Symbol</code>.
</p>

<p class='python'>
  To get historical <a href='<?=$dataTypeLink?>'>quote data</a>, call the <code>history</code> method with the <code>QuoteBar</code> type and an asset's <code>Symbol</code>.
  This method returns a DataFrame with columns for the open, high, low, close, and size of the bid and ask quotes.
  The columns that don't start with "bid" or "ask" are the mean of the quote prices on both sides of the market.
</p>

<div class="section-example-container">
    <pre class="csharp">// Get the 5 trailing minute QuoteBar objects of an asset. 
var history = History&lt;TradeBar&gt;(symbol, 5, Resolution.Minute);</pre>
    <pre class="python"># Get the 5 trailing minute QuoteBar objects of an asset in DataFrame format. 
history = self.history(QuoteBar, symbol, 5, Resolution.MINUTE)</pre>
</div>

<img class='python docs-image' src='<?=$imgLink?>' alt='DataFrame of historical quotes for an asset.'>

<p class='python'>To get a list of <code>QuoteBar</code> objects instead of a DataFrame, call the <code>history[QuoteBar]</code> method.</p>

<div class="python section-example-container">
    <pre class="python"># Get the 5 trailing minute QuoteBar objects of an asset in QuoteBar format. 
history = self.history[QuoteBar](symbol, 5, Resolution.MINUTE)</pre>
</div>

<p>The resolution of data you request must support <code>QuoteBar</code> data. Otherwise, the history request won't return any data. To check which resolutions for <?=$assetClass?> support <code>QuoteBar</code> data, see <a href='<?=$resolutionLink?>'>Resolutions</a>.</p>
