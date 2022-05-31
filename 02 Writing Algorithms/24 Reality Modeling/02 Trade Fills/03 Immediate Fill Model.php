<p>If you trade non-Equity assets with the <code>DefaultBrokerageModel</codeL, the <code>ImmediateFillModel</code> is the default fill model. This fill model fills trades completely and immediately. The fill logic of each order depends on the order type, the data format of the security subscription, and the order direction. To view the implementation of this model, see the <a rel='nofollow' target='_blank' href='https://github.com/QuantConnect/Lean/blob/master/Common/Orders/Fills/FillModel.cs'>LEAN GitHub repository</a>.</p>

<h4>Market Orders</h4>

<p>The following table describes the fill price of market orders for each data format and order direction:<br></p>

<table class="qc-table table">
    <thead>
        <tr>
            <th>Data Format</th>
            <th><code>TickType</code></th>
            <th>Order Direction</th>
            <th>Fill Price</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Quote</code></td>
            <td>Buy</td>
            <td>quote price + slippage</td>
        </tr>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Quote</code></td>
            <td>Sell</td>
            <td>quote price - slippage</td>
        </tr>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Trade</code></td>
            <td>Buy</td>
            <td>trade price + slippage</td>
        </tr>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Trade</code></td>
            <td>Sell</td>
            <td>trade price - slippage</td>
        </tr>
        <tr>
            <td><code>QuoteBar</code></td>
            <td><br></td>
            <td>Buy</td>
            <td>ask close price + slippage</td>
        </tr>
        <tr>
            <td><code>QuoteBar</code></td>
            <td><br></td>
            <td>Sell</td>
            <td>bid close price - slippage</td>
        </tr>
        <tr>
            <td><code>TradeBar</code></td>
            <td><br></td>
            <td>Buy</td>
            <td>close price + slippage</td>
        </tr>
        <tr>
            <td><code>TradeBar</code></td>
            <td><br></td>
            <td>Sell</td>
            <td>close price - slippage</td>
        </tr>
    </tbody>
</table>

<p>The model only fills market orders if the exchange is open.</p>


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
            <td>quote price &lt;= trigger price<br></td>
        </tr>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Quote</code></td>
            <td>Sell</td>
            <td>quote price &gt;= trigger price</td>
        </tr>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Trade</code></td>
            <td>Buy</td>
            <td>trade price &lt;= trigger price</td>
        </tr>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Trade</code></td>
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


<p>Once the limit if touched order triggers, to fill the order, the model checks the bid price for buy orders and the ask price for sell orders.</p>

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


<p>Buy orders fill when the bid price &lt;= limit price and sell orders fill when the ask price &gt;= limit price. The order fills at the limit price. The model won't trigger or fill limit if touched orders with stale data.<br></p>


<h4>Stop Market Orders</h4>

<?php echo file_get_contents(DOCS_RESOURCES."/reality-modeling/trade-fills/stop-market-orders.html"); ?>


<h4>Stop Limit Orders</h4>

<?php echo file_get_contents(DOCS_RESOURCES."/reality-modeling/trade-fills/stop-limit-orders.html"); ?>

<h4>Market on Open Orders</h4>

<p>The following table describes the fill price of market on open orders for each data format and order side.<br></p>



<table class="qc-table table">
    <thead>
        <tr>
            <th>Data Format</th>
            <th><code>TickType</code></th>
            <th>Order Direction</th>
            <th>Fill Price</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Quote</code></td>
            <td>Buy</td>
            <td>quote price + slippage</td>
        </tr>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Quote</code></td>
            <td>Sell</td>
            <td>quote price - slippage</td>
        </tr>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Trade</code></td>
            <td>Buy</td>
            <td>trade price + slippage</td>
        </tr>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Trade</code></td>
            <td>Sell</td>
            <td>trade price - slippage</td>
        </tr>
        <tr>
            <td><code>QuoteBar</code></td>
            <td><br></td>
            <td>Buy</td>
            <td>ask open price + slippage</td>
        </tr>
        <tr>
            <td><code>QuoteBar</code></td>
            <td><br></td>
            <td>Sell</td>
            <td>bid open price - slippage</td>
        </tr>
        <tr>
            <td><code>TradeBar</code></td>
            <td><br></td>
            <td>Buy</td>
            <td>open price + slippage</td>
        </tr>
        <tr>
            <td><code>TradeBar</code></td>
            <td><br></td>
            <td>Sell</td>
            <td>open price - slippage</td>
        </tr>
    </tbody>
</table>

<p>The model won't fill market on open orders during pre-market hours.</p>


<h4>Market on Closed Orders</h4>

<p>The following table describes the fill price of market on close orders for each data format and order side:<br></p>



<table class="qc-table table">
    <thead>
        <tr>
            <th>Data Format</th>
            <th><code>TickType</code></th>
            <th>Order Direction</th>
            <th>Fill Price</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Quote</code></td>
            <td>Buy</td>
            <td>quote price + slippage</td>
        </tr>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Quote</code></td>
            <td>Sell</td>
            <td>quote price - slippage</td>
        </tr>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Trade</code></td>
            <td>Buy</td>
            <td>trade price + slippage</td>
        </tr>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Trade</code></td>
            <td>Sell</td>
            <td>trade price - slippage</td>
        </tr>
        <tr>
            <td><code>QuoteBar</code></td>
            <td><br></td>
            <td>Buy</td>
            <td>ask close price + slippage</td>
        </tr>
        <tr>
            <td><code>QuoteBar</code></td>
            <td><br></td>
            <td>Sell</td>
            <td>bid close price - slippage</td>
        </tr>
        <tr>
            <td><code>TradeBar</code></td>
            <td><br></td>
            <td>Buy</td>
            <td>close price + slippage</td>
        </tr>
        <tr>
            <td><code>TradeBar</code></td>
            <td><br></td>
            <td>Sell</td>
            <td>close price - slippage</td>
        </tr>
    </tbody>
</table>
