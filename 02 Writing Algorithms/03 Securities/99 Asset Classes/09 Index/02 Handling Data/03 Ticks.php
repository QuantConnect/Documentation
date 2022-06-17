<p><code>Tick</code> objects represent a price for the Index at a moment in time. <code>Tick</code> objects have the following properties:</p>
<div data-tree='QuantConnect.Data.Market.Tick'></div>

<p>Index ticks have a non-zero value for the <code>Price</code> property, but they have a zero value for the <code>BidPrice</code>, <code>BidSize</code>, <code>AskPrice</code>, and <code>AskSize</code> properties.</p>

<p> In backtests, LEAN groups ticks into one millisecond buckets. In live trading, LEAN groups ticks into ~70 millisecond buckets. To get the <code>Tick</code> objects in the <code>Slice</code>, index the <code>Ticks</code> property of the <code>Slice</code> with a <code>Symbol</code>. The <code>Slice</code> may not contain data for your <code>Symbol</code> at every time step. To avoid issues, check if the <code>Slice</code> contains data for your Index before you index the <code>Slice</code> with the Index <code>Symbol</code>.</p>

<div class='section-example-container'>
    <pre class='csharp'>// Example of accessing Tick objects in OnData
// The examples on this page should check if the slice contains the data before indexing it.
// Maybe the C# version should show an example of OnData(TradeBar) in addition to OnData(Slice)</pre>
    <pre class='python'># Example of accessing Tick objects in OnData
# The examples on this page should check if the slice contains the data before indexing it.</pre>
</div>