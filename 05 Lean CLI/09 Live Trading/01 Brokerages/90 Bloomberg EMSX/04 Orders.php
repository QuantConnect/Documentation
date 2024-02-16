<p>Terminal Link enables you to create and manage Bloomberg™ orders. You can also use the LEAN CLI with the Terminal Link integration to test paper trading with LEAN. In this case, LEAN models order fills using the live tick data feed from Bloomberg™.</p>

<h4>Order Types</h4>
<p>The following table describes the available order types for each asset class that Terminal Link supports:</p>

<table class="qc-table table" id="order-types-table">
   <thead>
      <tr>
        <th>Order Type</th>
        <th>Equity</th>
        <th>Equity Options</th>
        <th>Futures</th>
        <th>Index Options</th>
      </tr>
   </thead>
   <tbody>
      <tr>
        <td><a href="https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/order-types/market-orders">MarketOrder</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
      </tr>
      <tr>
        <td><a href="https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/order-types/limit-orders">LimitOrder</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
      </tr>
      <tr>
        <td><a href="https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/order-types/stop-market-orders">StopMarketOrder</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
      </tr>
      <tr>
        <td><a href="https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/order-types/stop-limit-orders">StopLimitOrder</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
      </tr>
   </tbody>
</table>

<style>
#order-types-table td:not(:first-child), 
#order-types-table th:not(:first-child) {
    text-align: center;
}
</style>


<h4>Time In Force</h4>
<p>Terminal Link supports the following <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/order-properties#03-Time-In-Force'>TimeInForce</a> instructions:</p>
<ul>
    <li><code>Day</code></li>
    <li><code>GoodTilCanceled</code></li>
    <li><code>GoodTilDate</code></li>
</ul>

<h4>Get Open Orders</h4>
<p>Terminal Link lets you <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/order-management/transaction-manager'>access open orders</a>.</p>

<h4>Monitor Fills</h4>
<p>Terminal Link allows you to monitor orders as they fill through <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/order-events'>order events</a>.</p>

<h4>Updates</h4>
<p>Terminal Link doesn't support <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/order-management/order-tickets#04-Update-Orders'>order updates</a>.</p>

<h4>Cancellations</h4>
<p>Terminal Link enables you to <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/order-management/order-tickets#05-Cancel-Orders'>cancel open orders</a>.</p>

<? include(DOCS_RESOURCES."/brokerages/handling-splits.html"); ?>
