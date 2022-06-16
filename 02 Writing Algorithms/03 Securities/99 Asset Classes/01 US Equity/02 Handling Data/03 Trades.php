<p><code>TradeBar</code> objects are price bars that consolidate individual trades from the exchanges. They contain the open, high, low, close, and volume of trading activity over a period of time.</p>

<img src="https://cdn.quantconnect.com/docs/i/dataformat-tradebar.png" class="img-responsive">

<p><code>TradeBar</code> objects have the following properties:</p>
<div data-tree="QuantConnect.Data.Market.TradeBar"></div>

<p>To get the <code>TradeBar</code> objects in the <code>Slice</code>, index the <code>Slice</code> or index the <code>Bars</code> property of the <code>Slice</code> with a <code>Symbol</code>. If the security doesn't actively trade or you are in the same time step as when you add the security subscription, the <code>Slice</code> may not contain data for your <code>Symbol</code>. To avoid issues, check if the <code>Slice</code> contains data for your security before you index the <code>Slice</code> with the security <code>Symbol</code>.</p>

<div class="section-example-container">
    <pre class="csharp">// Example of accessing TradeBar objects in OnData
// The examples on this page should check if the slice contains the data before indexing it.
// Maybe the C# version should show an example of OnData(TradeBar) in addition to OnData(Slice)</pre>
    <pre class="python"># Example of accessing TradeBar objects in OnData
# The examples on this page should check if the slice contains the data before indexing it.</pre>
</div>

<p>We adjust the daily open and close price of bars to reflect the official <a href='/docs/v2/writing-algorithms/securities/asset-classes/us-equity/data-preparation#05-Market-Auction-Prices'>auction prices</a>.</p>
