<h4>Order Types</h4>

<p>The following table describes the available order types for the Equity asset class that the <code>Trading212BrokerageModel</code> supports:</p>

<table class="qc-table table" id='order-types-table'>
   <thead>
      <tr>
        <th style='width: 50%'>Order Type</th>
        <th>Equity</th>
      </tr>
   </thead>
   <tbody>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/market-orders'>Market</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/limit-orders'>Limit</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/stop-market-orders'>Stop market</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/stop-limit-orders'>Stop limit</a></td>
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

<p>The <code>Trading212BrokerageModel</code> doesn't support <a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/trailing-stop-orders'>trailing-stop</a>, <a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/market-on-open-orders'>market-on-open</a>, <a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/market-on-close-orders'>market-on-close</a>, <a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/limit-if-touched-orders'>limit-if-touched</a>, or <a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/combo-limit-orders'>combo</a> orders.</p>

<h4>Long Only</h4>

<p>The <code>Trading212BrokerageModel</code> is long only, so a sell order can only reduce an existing long position. You can sell the shares that you already hold, and the model rejects an order that would open or increase a short position.</p>

<h4>Contingent Orders</h4>

<p>The <code>Trading212BrokerageModel</code> doesn't support <a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/contingent-orders'>contingent orders</a>, such as one-cancels-the-other (OCO), one-triggers-the-other (OTO), and bracket orders.</p>

<h4>Updates</h4>

<p>The <code>Trading212BrokerageModel</code> supports <a href='/docs/v2/writing-algorithms/trading-and-orders/order-management/order-tickets#04-Update-Orders'>order updates</a>.</p>
