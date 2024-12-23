<?
$imgLink = "https://cdn.quantconnect.com/i/tu/history-tradebar-dataframe-us-equities.png";
$dataTypeLink = "/docs/v2/writing-algorithms/securities/asset-classes/us-equity/handling-data#03-Trades";
?>

<p class='csharp'>
  To get historical <a href='<?=$dataTypeLink?>'>trade data</a>, call the <code>History&lt;TradeBar&gt;</code> method with an asset's <code>Symbol</code>.
</p>

<p class='python'>
  To get historical <a href='<?=$dataTypeLink?>'>trade data</a>, call the <code>history</code> method with the <code>TradeBar</code> type and an asset's <code>Symbol</code>.
  This method returns a DataFrame with columns for the open, high, low, close, and volume.
</p>

<div class="section-example-container">
    <pre class="csharp">// Get the Symbol of an asset.
var symbol = AddEquity("SPY").Symbol;
// Get the 5 trailing daily TradeBar objects of the asset. 
var history = History&lt;TradeBar&gt;(symbol, 5, Resolution.Daily);</pre>
    <pre class="python"># Get the Symbol of an asset.
symbol = self.add_equity('SPY').symbol
# Get the 5 trailing daily TradeBar objects of the asset in DataFrame format. 
history = self.history(TradeBar, symbol, 5, Resolution.DAILY)</pre>
</div>

<img class='python docs-image' src='<?=$imgLink?>' alt='DataFrame of open, high, low, close, and volume for an asset.'>

<p class='python'>To get a list of <code>TradeBar</code> objects instead of a DataFrame, call the <code>history[TradeBar]</code> method.</p>

<div class="python section-example-container">
    <pre class="python"># Get the 5 trailing daily TradeBar objects of an asset in TradeBar format. 
history = self.history[TradeBar](symbol, 5, Resolution.DAILY)</pre>
</div>
