<p>If you trade Equities with the <code>DefaultBrokerageModel</code>, the <code>EquityFillModel</code> is the default fill model. This fill model fills trades completely and immediately. The fill logic of each order depends on the order type, the
 data format of the security subscription, and the order direction. Sometimes, this model uses a best effort bid or ask price to fill some orders.</p>

<p>To get the bid price, the model uses the following procedure:</p>

<ol>
    <li>If the subscription provides <code>Tick</code> data and the most recent batch of ticks contains a buy quote, use the bid price of the most recent quote tick.</li>
    <li>If the subscription provides <code>QuoteBar</code> data, use the closing bid price of the most recent <code>QuoteBar</code>.</li>
</ol>

<p>To get the ask price, the model uses the following procedure:</p>

<ol>
    <li>If the subscription provides <code>Tick</code> data and the most recent batch of ticks contains a sell quote, use the ask price of the most recent quote tick.</li>
    <li>If the subscription provides <code>QuoteBar</code> data, use the closing ask price of the most recent <code>QuoteBar</code>.</li>
</ol>

<p>If neither of the preceding procedures yield a result, the model uses the following procedure to get the bid or ask price:</p>
<ol>
    <li>If the subscription provides <code>Tick</code> data and the most recent batch of ticks contains a tick of type <code>TickType.Trade</code>, use the last trade price.</li>
    <li>If the subscription provides <code>TradeBar</code> data, use the closing bid price of the most recent <code>QuoteBar</code>.</li>
</ol>


<p>To view the implementation of this model, see the <a target="_blank" rel="nofollow" href="https://github.com/QuantConnect/Lean/blob/master/Common/Orders/Fills/EquityFillModel.cs">LEAN GitHub repository</a>.</p>

<h4>Market Orders</h4>
<p>The model fills buy market orders at the best effort ask price and fills sell market orders at the best effort bid price. The model only fills market orders during regular trading hours.</p><p></p>

<h4>Limit Orders</h4>
<?php echo file_get_contents(DOCS_RESOURCES."/reality-modeling/trade-fills/limit-orders.html"); ?>

<h4>Limit if Touched Orders</h4>


<p>The model converts a limit if touched order to a limit order when the trigger condition is met. The following table describes the trigger condition of limit if touched orders for each data format and order direction:</p>



<table class="qc-table table">
    <thead>
        <tr>
            <th>Data Format</th>
            <th><code>TickType</code></th>
            <th>Order Direction</th>
            <th>Trigger Condition</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Quote</code></td>
            <td>Buy</td>
            <td>trade price &lt;= trigger price<br></td>
        </tr>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Quote</code></td>
            <td>Sell</td>
            <td>trade price &gt;= trigger price</td>
        </tr>
        <tr>
            <td><code>TradeBar</code></td>
            <td><br></td>
            <td>Buy</td>
            <td>low price &lt;= trigger price</td>
        </tr>
        <tr>
            <td><code>TradeBar</code></td>
            <td><br></td>
            <td>Sell</td>
            <td>high price &gt;= trigger price</td>
        </tr>
    </tbody>
</table>


<p>Once the limit if touched order triggers, to fill the order, the model checks the fill condition. The following table describes the fill condition and price of each order direction.<br></p>

<table class="qc-table table">
    <thead>
        <tr>
            <th>Order Direction</th>
            <th>Fill Condition</th>
            <th>Fill Price</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Buy</td>
            <td>best effort ask price &lt;= limit price<br></td>
            <td>min(best effort ask price, limit price)</td>
        </tr>
        <tr>
            <td>Sell</td>
            <td>best effort bid price &gt;= limit price<br></td>
            <td>max(best effort bid price, limit price)</td>
        </tr>
    </tbody>
</table>The model won't trigger or fill limit if touched orders with stale data.<p></p>

<h4>Stop Market Orders</h4>
<?php echo file_get_contents(DOCS_RESOURCES."/reality-modeling/trade-fills/stop-market-orders.html"); ?>

<h4>Stop Limit Orders</h4>
<?php echo file_get_contents(DOCS_RESOURCES."/reality-modeling/trade-fills/stop-limit-orders.html"); ?>

<h4>Market on Open Orders</h4>
<p>The following table describes the fill price of market on open orders for each data format and order direction:<br></p>


<table class="qc-table table">
    <thead>
        <tr>
            <th>Data Format</th>
            <th>Order Direction</th>
            <th>Fill Price<br></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>Tick</code></td>
            <td>Buy</td>
            <td>If the model receives the official opening auction price within one minute, the order fills at official open price + slippage. After one minute, the order fills at the most recent trade price + slippage. If the security doesn't trade within the first two minutes, the order fills at the best effort ask price + slippage.</td>
        </tr>
        <tr>
            <td><code>Tick</code></td>
            <td>Sell</td>
            <td>If the model receives the official opening auction price within one minute, the order fills at the official open price - slippage. After one minute, the order fills at the most recent trade price - slippage. If the security doesn't trade within the first two minutes, the order fills at the best effort bid price - slippage.</td>
        </tr>
        <tr>
            <td><code>TradeBar</code></td>
            <td>Buy</td>
            <td>open price + slippage<br></td>
        </tr>
        <tr>
            <td><code>TradeBar</code></td>
            <td>Sell</td>
            <td>open price - slippage<br></td>
        </tr>
        <tr>
            <td><code>QuoteBar</code></td>
            <td>Buy</td>
            <td>best effort ask price + slippage<br></td>
        </tr>
        <tr>
            <td><code>QuoteBar</code></td>
            <td>Sell</td>
            <td>best effort bid price - slippage<br></td>
        </tr>
    </tbody>
</table>


<p>The model checks the data format in the following order:</p>

<ol>
    <li><code>Tick</code></li>
    <li><code>TradeBar</code></li>
    <li><code>QuoteBar</code></li>
</ol>



<h4>Market on Close Orders</h4>

<p>The following table describes the fill price of market on close orders for each data format and order direction:<br></p>


<table class="qc-table table">
    <thead>
        <tr>
            <th>Data Format</th>
            <th>Order Direction</th>
            <th>Fill Price<br></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>Tick</code></td>
            <td>Buy</td>
            <td>If the model receives the official opening auction price within one minute after the close, the order fills at official close price + slippage. After one minute, the order fills at the most recent trade price + slippage. If the security doesn't trade within the first two minutes, the order fills at the best effort ask price + slippage.</td>
        </tr>
        <tr>
            <td><code>Tick</code></td>
            <td>Sell</td>
            <td>If the model receives the official opening auction price within one minute after the close, the order fills at the official close price - slippage. After one minute, the order fills at the most recent trade price - slippage. If the security doesn't trade within the first two minutes after the close, the order fills at the best effort bid price - slippage.</td>
        </tr>
        <tr>
            <td><code>TradeBar</code></td>
            <td>Buy</td>
            <td>open price + slippage<br></td>
        </tr>
        <tr>
            <td><code>TradeBar</code></td>
            <td>Sell</td>
            <td>open price - slippage<br></td>
        </tr>
        <tr>
            <td><code>QuoteBar</code></td>
            <td>Buy</td>
            <td>best effort ask price + slippage<br></td>
        </tr>
        <tr>
            <td><code>QuoteBar</code></td>
            <td>Sell</td>
            <td>best effort bid price - slippage<br></td>
        </tr>
    </tbody>
</table>


<p>The model checks the data format in the following order:</p>

<ol>
    <li><code>Tick</code></li>
    <li><code>TradeBar</code></li>
    <li><code>QuoteBar</code></li>
</ol>
