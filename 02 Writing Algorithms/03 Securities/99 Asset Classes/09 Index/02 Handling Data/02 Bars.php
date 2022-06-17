Indexes are not tradable, but TradeBars are provided for insights<br><div></div>

<p>You can't trade Indices, but <code>TradeBar</code> objects are bars that represent the open, high, low, close of an Index price over a period of time.</p>
<img src='https://cdn.quantconnect.com/docs/i/dataformat-tradebar.png' class='img-responsive'>
<p><code>TradeBar</code> objects have the following properties:</p>    
<div data-tree='QuantConnect.Data.Market.TradeBar'></div>  
  
<p>To get the <code>TradeBar</code> objects in the <code>Slice</code>, index the <code>Slice</code> or index the <code>Bars</code> property of the <code>Slice</code> with the Index <code>Symbol</code>. The <code>Slice</code> may not contain data for your <code>Symbol</code> at every time step. To avoid issues, check if the <code>Slice</code> contains data for your Index before you index the <code>Slice</code> with the Index <code>Symbol</code>.</p>
    
<div class='section-example-container'>
    <pre class='csharp'>// Example of accessing TradeBar objects in OnData
// The examples on this page should check if the slice contains the data before indexing it.
// Maybe the C# version should show an example of OnData(TradeBar) in addition to OnData(Slice)</pre>
    <pre class='python'># Example of accessing TradeBar objects in OnData
# The examples on this page should check if the slice contains the data before indexing it.</pre>
</div>