
<h4>Order Types</h4>

<p>The following table describes the available order types for each asset class that <?= $cloudPlatform ? "our TT integration" : "the <code>TradingTechnologiesBrokerageModel</code>" ?> supports:</p>

<table class="qc-table table" id='order-types-table'>
   <thead>
      <tr>
        <th style='width: 50%'>Order Type</th>
        <th>Futures</th>
      </tr>
   </thead>
   <tbody>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/market-orders'>MarketOrder</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/limit-orders'>LimitOrder</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/stop-market-orders'>StopMarketOrder</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/stop-limit-orders'>StopLimitOrder</a></td>
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

<p><?= $cloudPlatform ? "TT" : "The <code>TradingTechnologiesBrokerageModel</code>" ?> enforces the following order rules:</p>
<ul>
    <li>If you are buying (selling) with a <code>StopMarketOrder</code> or a <code>StopLimitOrder</code>, the stop price of the order must be greater (less) than the current security price.</li>
    <li>If you are buying (selling) with a <code>StopLimitOrder</code>, the limit price of the order must be greater (less) than the stop price.</li>
</ul>

<h4>Time In Force</h4>
<p><?= $writingAlgorithms ? "The <code>TradingTechnologiesBrokerageModel</code> supports" : "We model the TT API by supporting" ?> the <code>Day</code> and <code>GoodTilCanceled</code> <a href='/docs/v2/writing-algorithms/trading-and-orders/order-properties#03-Time-In-Force'>TimeInForce</a> order properties.</p>

<?php if ($writingAlgorithms) { ?>
<div class="section-example-container">
    <pre class="csharp">public override void Initialize()
{
    // Set the default order properties
    DefaultOrderProperties.TimeInForce = TimeInForce.GoodTilCanceled;
}

public override void OnData(Slice slice)
{
    // Use default order order properties
    LimitOrder(_symbol, quantity, limitPrice);
    
    // Override the default order properties
    LimitOrder(_symbol, quantity, limitPrice, 
               orderProperties: new OrderProperties
               { 
                   TimeInForce = TimeInForce.Day 
               });
}</pre>
    <pre class="python">def initialize(self) -&gt; None:
    # Set the default order properties
    self.default_order_properties.time_in_force = TimeInForce.good_til_canceled

def on_data(self, slice: Slice) -&gt; None:
    # Use default order order properties
    self.limit_order(self.symbol, quantity, limit_price)
    
    # Override the default order properties
    order_properties = OrderProperties()
    order_properties.time_in_force = TimeInForce.day
    self.limit_order(self.symbol, quantity, limit_price, orderProperties=order_properties)</pre>
</div>
<?php } ?>

<h4>Updates</h4>
<p><?= $writingAlgorithms ? "The <code>TradingTechnologiesBrokerageModel</code> supports" : "We model the TT API by supporting" ?> <a href='/docs/v2/writing-algorithms/trading-and-orders/order-management/order-tickets#04-Update-Orders'>order updates</a>.</p>

