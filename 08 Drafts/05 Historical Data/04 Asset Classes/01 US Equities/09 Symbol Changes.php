<?
$imgLink = "https://cdn.quantconnect.com/i/tu/history-symbol-change-event-dataframe-us-equities.png";
$dataTypeLink = "/docs/v2/writing-algorithms/securities/asset-classes/us-equity/corporate-actions#04-Symbol-Changes";
?>

<p class='csharp'>
  To get historical <a href='<?=$dataTypeLink?>'>symbol change data</a>, call the <code>History&lt;SymbolChangedEvent&gt;</code> method with an asset's <code>Symbol</code>.
</p>

<p class='python'>
  To get historical <a href='<?=$dataTypeLink?>'>symbol change data</a>, call the <code>history</code> method with the <code>SymbolChangedEvent</code> type and an asset's <code>Symbol</code>.
  This method returns a DataFrame with columns for the old symbol and new symbol during each change.
</p>

<div class="section-example-container">
    <pre class="csharp">// Get the Symbol of an asset.
var symbol = AddEquity("META").Symbol;
// Get the symbol changes of the stock over the last 10 years. 
var history = History&lt;SymbolChangedEvent&gt;(symbol, TimeSpan.FromDays(10*365);</pre>
    <pre class="python"># Get the Symbol of an asset.
symbol = self.add_equity('META').symbol
# Get the symbol changes of the stock over the last 10 years in DataFrame format. 
history = self.history(SymbolChangedEvent, symbol, timedelta(10*365))</pre>
</div>

<img class='python docs-image' src='<?=$imgLink?>' alt='DataFrame of historical symbol changes for a stock.'>

<p class='python'>To get a list of <code>SymbolChangedEvent</code> objects instead of a DataFrame, call the <code>history[SymbolChangedEvent]</code> method.</p>

<div class="python section-example-container">
    <pre class="python"># Get the symbol changes of a stock over the last 10 years in SymbolChangedEvent format. 
history = self.history[SymbolChangedEvent](symbol, timedelta(10*365))</pre>
</div>
