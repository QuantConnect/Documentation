<? if ($includeIntro) { ?><p>The following table describes the fill logic of trailing stop orders for each data format and order direction. Once the stop condition is met, the model fills the orders and sets the fill price.</p><? } ?>

<table class="qc-table table">
    <thead>
        <tr>
            <th>Data Format</th>
            <th><code>TickType</code></th>
            <th>Order Direction
            </th><th>Stop Condition</th>
            <th>Fill Price</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Quote</code></td>
            <td>Buy</td>
            <td>quote price &gt; stop price</td>
            <td>max(stop price, quote price + slippage)</td>
        </tr>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Quote</code></td>
            <td>Sell</td>
            <td>quote price &lt; stop price</td>
            <td>min(stop price, quote price - slippage)</td>
        </tr>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Trade</code></td>
            <td>Buy</td>
            <td>trade price &gt; stop price</td>
            <td>max(stop price, last trade price + slippage)</td>
        </tr>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Trade</code></td>
            <td>Sell</td>
            <td>trade price &lt; stop price</td>
            <td>min(stop price, last trade price - slippage)</td>
        </tr>
        <tr>
            <td><code>QuoteBar</code></td>
            <td><br></td>
            <td>Buy</td>
            <td>ask high price &gt; stop price</td>
            <td>max(stop price, ask close price + slippage)</td>
        </tr>
        <tr>
            <td><code>QuoteBar</code></td>
            <td><br></td>
            <td>Sell</td>
            <td>bid low price &lt; stop price</td>
            <td>min(stop price, bid close price - slippage)</td>
        </tr>
        <tr>
            <td><code>TradeBar</code></td>
            <td><br></td>
            <td>Buy</td>
            <td>high price &gt; stop price</td>
            <td>max(stop price, close price + slippage)</td>
        </tr>
        <tr>
            <td><code>TradeBar</code></td>
            <td><br></td>
            <td>Sell</td>
            <td>low price &lt; stop price</td>
            <td>min(stop price, close price - slippage)</td>
        </tr>
    </tbody>
</table>

<? if ($includeIntro) { ?><p>While the stop condition is not met, the model updates the stop price under certain conditions. The following table shows the update condition and stop price value for the nominal trailing amount:</p><? } ?>

<table class="qc-table table">
    <thead>
        <tr>
            <th>Data Format</th>
            <th><code>TickType</code></th>
            <th>Order Direction</th>
            <th>Update Condition</th>
            <th>Stop Price</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Quote</code></td>
            <td>Buy</td>
            <td>stop price - quote price &lt;= trailing amount</td>
            <td>quote price + trailing amount</td>
        </tr>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Quote</code></td>
            <td>Sell</td>
            <td>quote price - stop price &lt;= trailing amount</td>
            <td>quote price - trailing amount</td>
        </tr>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Trade</code></td>
            <td>Buy</td>
            <td>stop price - trade price &lt;= trailing amount</td>
            <td>trade price + trailing amount</td>
        </tr>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Trade</code></td>
            <td>Sell</td>
            <td>trade price - stop price &lt;= trailing amount</td>
            <td>trade price - trailing amount</td>
        </tr>
        <tr>
            <td><code>QuoteBar</code></td>
            <td><br></td>
            <td>Buy</td>
            <td>stop price - ask low price &lt;= trailing amount</td>
            <td>ask low price + trailing amount</td>
        </tr>
        <tr>
            <td><code>QuoteBar</code></td>
            <td><br></td>
            <td>Sell</td>
            <td>bid high price - stop price &lt;= trailing amount</td>
            <td>bid high price - trailing amount</td>
        </tr>
        <tr>
            <td><code>TradeBar</code></td>
            <td><br></td>
            <td>Buy</td>
            <td>stop price - low price &lt;= trailing amount</td>
            <td>low price + trailing amount</td>
        </tr>
        <tr>
            <td><code>TradeBar</code></td>
            <td><br></td>
            <td>Sell</td>
            <td>high price - stop price &lt;= trailing amount</td>
            <td>high price - trailing amount</td>
        </tr>
    </tbody>
</table>

<? if ($includeIntro) { ?><p>The following table shows the update condition and stop price value for the percentage trailing amount.</p><? } ?>

<table class="qc-table table">
    <thead>
        <tr>
            <th>Data Format</th>
            <th><code>TickType</code></th>
            <th>Order Direction</th>
            <th>Update Condition</th>
            <th>Stop Price</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Quote</code></td>
            <td>Buy</td>
            <td>stop price - quote price &lt;= quote price * trailing amount</td>
            <td>quote price * (1 + trailing amount)</td>
        </tr>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Quote</code></td>
            <td>Sell</td>
            <td>quote price - stop price &lt;= quote price * trailing amount</td>
            <td>quote price * (1 - trailing amount)</td>
        </tr>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Trade</code></td>
            <td>Buy</td>
            <td>stop price - trade price &lt;= trade price * trailing amount</td>
            <td>trade price * (1 + trailing amount)</td>
        </tr>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Trade</code></td>
            <td>Sell</td>
            <td>trade price - stop price &lt;= trade price * trailing amount</td>
            <td>trade price * (1 - trailing amount)</td>
        </tr>
        <tr>
            <td><code>QuoteBar</code></td>
            <td><br></td>
            <td>Buy</td>
            <td>stop price - ask low price &lt;= ask low price * trailing amount</td>
            <td>ask low price * (1 + trailing amount)</td>
        </tr>
        <tr>
            <td><code>QuoteBar</code></td>
            <td><br></td>
            <td>Sell</td>
            <td>bid high price - stop price &lt;= bid high price * trailing amount</td>
            <td>bid high price * (1 - trailing amount)</td>
        </tr>
        <tr>
            <td><code>TradeBar</code></td>
            <td><br></td>
            <td>Buy</td>
            <td>stop price - low price &lt;= stop price * trailing amount</td>
            <td>low price * (1 + trailing amount)</td>
        </tr>
        <tr>
            <td><code>TradeBar</code></td>
            <td><br></td>
            <td>Sell</td>
            <td>high price - stop price &lt;= high price * trailing amount</td>
            <td>high price * (1 - trailing amount)</td>
        </tr>
    </tbody>
</table>

<p>The model only fills trailing stop orders when the exchange is open.</p>
<?
$orderType = "trailing stop";
include(DOCS_RESOURCES."/reality-modeling/trade-fills/fill-with-stale-data.php");
?>
