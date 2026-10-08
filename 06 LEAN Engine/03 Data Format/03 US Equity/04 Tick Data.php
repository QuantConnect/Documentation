<p>
    US Equity tick files have trade ticks and quote ticks. The following table describes the columns of trade ticks, with an example row from <span class='public-file-name'>equity / usa / tick / aig / 20131007_trade.zip</span>:
</p>

<table class="qc-table table">
    <thead>
        <tr>
            <th>Column</th>
            <th>Description</th>
            <th>Example</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Time</td>
            <td>Milliseconds since midnight</td>
            <td>14400688</td>
        </tr>
        <tr>
            <td>Price</td>
            <td>Trade price multiplied by 10,000</td>
            <td>491800</td>
        </tr>
        <tr>
            <td>Quantity</td>
            <td>Number of shares traded</td>
            <td>300</td>
        </tr>
        <tr>
            <td>Exchange</td>
            <td>Exchange code, such as P for ARCA or Q for NASDAQ</td>
            <td>P</td>
        </tr>
        <tr>
            <td>Sale Condition</td>
            <td>Hexadecimal value of the <code>TradeConditionFlags</code></td>
            <td>2000</td>
        </tr>
        <tr>
            <td>Suspicious</td>
            <td>1 if the tick is <a href='/docs/v2/writing-algorithms/securities/asset-classes/us-equity/data-preparation#04-Suspicious-Ticks'>suspicious</a> and 0 otherwise</td>
            <td>0</td>
        </tr>
    </tbody>
</table>

<p>
    The following table describes the columns of quote ticks, with an example row from <span class='public-file-name'>equity / usa / tick / aig / 20131007_quote.zip</span>:
</p>

<table class="qc-table table">
    <thead>
        <tr>
            <th>Column</th>
            <th>Description</th>
            <th>Example</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Time</td>
            <td>Milliseconds since midnight</td>
            <td>14400152</td>
        </tr>
        <tr>
            <td>Bid Price</td>
            <td>Bid price multiplied by 10,000</td>
            <td>0</td>
        </tr>
        <tr>
            <td>Bid Size</td>
            <td>Number of shares at the bid</td>
            <td>0</td>
        </tr>
        <tr>
            <td>Ask Price</td>
            <td>Ask price multiplied by 10,000</td>
            <td>494100</td>
        </tr>
        <tr>
            <td>Ask Size</td>
            <td>Number of shares at the ask</td>
            <td>300</td>
        </tr>
        <tr>
            <td>Exchange</td>
            <td>Exchange code, such as P for ARCA or Q for NASDAQ</td>
            <td>Q</td>
        </tr>
        <tr>
            <td>Sale Condition</td>
            <td>Hexadecimal value of the <code>QuoteConditionFlags</code></td>
            <td>1</td>
        </tr>
        <tr>
            <td>Suspicious</td>
            <td>1 if the tick is <a href='/docs/v2/writing-algorithms/securities/asset-classes/us-equity/data-preparation#04-Suspicious-Ticks'>suspicious</a> and 0 otherwise</td>
            <td>0</td>
        </tr>
    </tbody>
</table>

<p>
    Each quote tick has data for one side of the book, and the other side has a price and size of 0. The bar-building process excludes the prices of suspicious ticks and of ticks with some condition flags. For the rules, see <a href='/docs/v2/writing-algorithms/securities/asset-classes/us-equity/data-preparation#03-Bar-Building'>Bar Building</a>.
</p>

<p>
    The Sale Condition column holds a bit mask of flags. For example, the trade tick value 2000 is the <code class="csharp">TradeConditionFlags.ExtendedHours</code><code class="python">TradeConditionFlags.EXTENDED_HOURS</code> flag. The following table describes the <code>TradeConditionFlags</code> of trade ticks:
</p>

<? echo file_get_contents(DOCS_RESOURCES."/data-feeds/trade-condition-flags-table.html"); ?>

<p>
    Quote bars use quotes that have at least one of the following <code>QuoteConditionFlags</code>:
</p>

<? echo file_get_contents(DOCS_RESOURCES."/data-feeds/quote-condition-flags-included-table.html"); ?>

<p>
    Quote bars exclude quotes that have any of the following <code>QuoteConditionFlags</code>:
</p>

<? echo file_get_contents(DOCS_RESOURCES."/data-feeds/quote-condition-flags-excluded-table.html"); ?>

<p>
    For more information about the exchange codes and condition flags, see the <a rel="nofollow" target="_blank" href="https://us-equity-market-data-docs.s3.amazonaws.com/algoseek.US.Equity.TAQ.pdf">AlgoSeek whitepaper</a>.
</p>
