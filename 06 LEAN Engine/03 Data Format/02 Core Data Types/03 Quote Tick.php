<p><code>Tick</code> of <code>TickType.</code><code class="csharp">Quote</code><code class="python">QUOTE</code> represents an individual record of quote updates for an asset. Tick data does not have a period.</p>

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
        <tr><td>Bid Price</td><td>Best bid price</td></tr>
        <tr><td>Ask Price</td><td>Best ask price</td></tr>
        <tr><td>Bid Size</td><td>Best bid price's size/quantity</td></tr>
        <tr><td>Ask Size</td><td>Best ask price's size/quantity</td></tr>
        <tr><td>Exchange</td><td>Location of the sale</td></tr>
        <tr><td>Quote Sale Condition</td><td>Notes on the sale.</td></tr>
        <tr><td>Suspicious</td><td>Boolean indicating the tick is flagged as suspicious according to AlgoSeek's algorithms. This generally indicates the quote is far from other market prices and may be reversed. Each quote tick contains either bid or ask data only. <a href="">QuoteBar data</a> data excludes suspicious ticks.</td></tr>
    </tbody>
</table>

<p>The quote has one of the following <code>QuoteConditionFlags</code>:</p>
<? echo file_get_contents(DOCS_RESOURCES."/data-feeds/quote-condition-flags-table.html"); ?>

<p>See more information in the <a rel="nofollow" target="_blank" href="https://us-equity-market-data-docs.s3.amazonaws.com/algoseek.US.Equity.TAQ.pdf">AlgoSeek whitepaper</a>.</p>