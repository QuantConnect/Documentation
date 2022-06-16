<p><code>QuoteBar</code> objects are bars that consolidate NBBO quotes from the exchanges. They contains the open, high, low, close of the bid and ask. The <code>Open</code>, <code>High</code>, <code>Low</code>, and <code>Close</code> properties of the <code>QuoteBar</code> object are the mean of the respective bid and ask prices. If the bid or ask portion of the <code>QuoteBar</code> has no data, the <code>QuoteBar</code> properties copy the values of the <code>Bid</code> or <code>Ask</code>.</p>

<img src="https://cdn.quantconnect.com/docs/i/dataformat-quotebar.png" class="img-responsive">

<p><code>QuoteBar</code> objects have the following properties:</p>
<div data-tree="QuantConnect.Data.Market.QuoteBar"></div>

<p><code>QuoteBar</code> objects are only available for second and minute resolution data. They make simulated trade fills more realistic by accounting for spread costs.</p>

<p>To get the <code>QuoteBar</code> objects in the <code>Slice</code>, index <code>QuoteBars</code> property of the <code>Slice</code> with a <code>Symbol</code>. If the security doesn't actively get quotes or you are in the same time step as when you added the security subscription, the <code>Slice</code> may not contain data for your <code>Symbol</code>. To avoid issues, check if the <code>Slice</code> contains data for your security before you index the <code>Slice</code> with the security <code>Symbol</code>.</p>
<div class="section-example-container">
    <pre class="csharp">// Example of accessing QuoteBar objects in OnData
// The examples on this page should check if the slice contains the data before indexing it.</pre>
    <pre class="python"># Example of accessing QuoteBar objects in OnData
# The examples on this page should check if the slice contains the data before indexing it.</pre>
</div>
