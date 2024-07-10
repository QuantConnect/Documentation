<p><code>Tick</code> of <code>TickType.</code><code class="csharp">Trade</code><code class="python">Quote</code> represents an individual record of trades for an asset. Tick data does not have a period.</p>

<p>The file schema is as follows:</p>

<table class="qc-table table">
    <thead>
        <tr>
            <th>Column</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr><td>Time</td><td>Milliseconds since midnight in the timezone of the data format</td></tr>
        <tr><td>Trade Sale</td><td>Most recent trade price</td></tr>
        <tr><td>Quantity</td><td>Amount of asset purchased or sold</td></tr>
        <tr><td>Exchange</td><td>Location of the sale</td></tr>
        <tr><td>Trade Sale Condition</td><td>Notes on the sale</td></tr>
        <tr><td>Suspicious</td><td>Boolean indicating the tick is flagged as suspicious according to AlgoSeek's algorithms. This generally indicates the trade is far from other market prices and may be reversed. <a href="/docs/v2/lean-engine/data-format/core-data-types#04-Trade-Bar">TradeBar data</a> excludes suspicious ticks.</td></tr>
    </tbody>
</table>

<p>The trade has one of the following <code>QuoteConditionFlags</code>:</p>
<? echo file_get_contents(DOCS_RESOURCES."/data-feeds/trade-condition-flags-table.html"); ?>

<p>See more information in the <a rel="nofollow" target="_blank" href="https://us-equity-market-data-docs.s3.amazonaws.com/algoseek.US.Equity.TAQ.pdf">AlgoSeek whitepaper</a>.</p>

