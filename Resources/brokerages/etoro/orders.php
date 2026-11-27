<h4>Order Types</h4>

<p>The following table describes the available order types for each asset class that <?= $cloudPlatform ? "our eToro integration" : "the <code>EtoroBrokerageModel</code>" ?> supports:</p>

<table class="qc-table table" id='order-types-table'>
   <thead>
      <tr>
        <th style='width: 40%'>Order Type</th>
        <th style='width: 20%'>Equity</th>
        <th style='width: 20%'>Crypto</th>
        <th style='width: 20%'>Forex</th>
      </tr>
   </thead>
   <tbody>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/market-orders'>Market</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/contingent-orders/bracket-orders'>Bracket</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/contingent-orders/one-triggers-other-orders'>One triggers other</a></td>
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

<p>If you submit a market order while the market is closed, eToro holds the order and fills it when the market opens.</p>

<h4>Contingent Orders</h4>
<p>eToro sets a stop loss and a take profit on the position that an entry order opens. <?= $writingAlgorithms ? "The <code>EtoroBrokerageModel</code> accepts" : "Our eToro integration accepts" ?> <a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/contingent-orders'>contingent orders</a> with the following rules:</p>
<ul>
    <li>The entry order is a market order that opens a position.</li>
    <li>The entry order triggers a stop market order (stop loss), a limit order (take profit), or both as a one cancels other pair (bracket).</li>
    <li>The exit orders are on the opposite side of the entry order and have the same quantity, since eToro closes the whole position.</li>
    <li>The stop loss of a long position is below the current price, and the stop loss of a short position is above it. The take profit is on the other side of the price.</li>
    <li>The stop loss of a Crypto position is at least 10% away from the current price.</li>
</ul>

<p>To open a short position or a position with leverage above 1, attach a stop loss to the entry order. To reverse a position, close it with one order and then open the new position with another order.</p>

<? if ($writingAlgorithms) { ?>
<div class="section-example-container">
    <pre class="csharp">public override void OnData(Slice slice)
{
    // Open a short position with a take profit and a stop loss.
    var price = Securities[_symbol].Price;
    BracketOrder(_symbol, -10, takeProfitPrice: price * 0.95m, stopLossPrice: price * 1.05m);
}</pre>
    <pre class="python">def on_data(self, slice: Slice) -&gt; None:
    # Open a short position with a take profit and a stop loss.
    price = self.securities[self._symbol].price
    self.bracket_order(self._symbol, -10, take_profit_price=price * 0.95, stop_loss_price=price * 1.05)</pre>
</div>
<? } ?>

<h4>Updates</h4>
<p><?= $writingAlgorithms ? "The <code>EtoroBrokerageModel</code> supports" : "We model the eToro API by supporting" ?> <a href='/docs/v2/writing-algorithms/trading-and-orders/order-management/order-tickets#04-Update-Orders'>order updates</a> that change the stop price of a stop loss or the limit price of a take profit. To change any other order, cancel it and place a new order.</p>

<? include(DOCS_RESOURCES."/brokerages/handling-splits.html"); ?>
