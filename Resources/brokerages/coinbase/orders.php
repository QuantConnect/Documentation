<h4>Order Types</h4>

<p>The following table describes the available order types for each asset class that <?= $cloudPlatform ? "our Coinbase integration" : "the <code>CoinbaseBrokerageModel</code>" ?> supports:</p>

<table class="qc-table table" id='order-types-table'>
   <thead>
      <tr>
        <th style='width: 50%'>Order Type</th>
        <th>Crypto</th>
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
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"><?php if ($writingAlgorithms) { ?><br>Supported after 2019-03-23 in backtests. For reference, see the <a rel="nofollow" target="_blank" href="https://blog.coinbase.com/coinbase-pro-market-structure-update-fbd9d49f43d7">Coinbase Market Structure Update</a> on the Coinbase website.<?php } ?></td>
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

<h4>Order Properties</h4>
<p><?= $writingAlgorithms ? "The <code>CoinbaseBrokerageModel</code> supports custom order properties." :  "We model custom order properties from the Coinbase API." ?> The following table describes the members of the <code>CoinbaseOrderProperties</code> object that you can set to customize order execution:</p>

<table class="table qc-table">
    <thead>
        <tr>
            <th style="width: 25%">Property</th>
            <th style="width: 75%">Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>TimeInForce</code></td>
            <td>A <a href='/docs/v2/writing-algorithms/trading-and-orders/order-properties#03-Time-In-Force'>TimeInForce</a> instruction to apply to the order. The <code>GoodTilCanceled</code> <code>TimeInForce</code> is supported.</td>
        </tr>
        <tr>
            <td><code>PostOnly</code></td>
            <td>A flag that signals the order must only add liquidity to the order book and not take liquidity from the order book. If part of the order results in taking liquidity rather than providing liquidity, the order is rejected without any part of it being filled.</td>
        </tr>
    </tbody>
</table>

<?php if ($writingAlgorithms) { ?>
<div class="section-example-container">
    <pre class="csharp">public override void Initialize()
{
    // Set the default order properties
    DefaultOrderProperties = new CoinbaseOrderProperties
    {
        TimeInForce = TimeInForce.GoodTilCanceled,
        PostOnly = false
    };
}

public override void OnData(Slice slice)
{
    // Use default order order properties
    LimitOrder(_symbol, quantity, limitPrice);
    
    // Override the default order properties
    LimitOrder(_symbol, quantity, limitPrice, 
               orderProperties: new CoinbaseOrderProperties
               { 
                   TimeInForce = TimeInForce.GoodTilCanceled,
                   PostOnly = true
               });
}</pre>
    <pre class="python">def initialize(self) -&gt; None:
    # Set the default order properties
    self.default_order_properties = CoinbaseOrderProperties()
    self.default_order_properties.time_in_force = TimeInForce.GOOD_TIL_CANCELED
    self.default_order_properties.post_only = False

def on_data(self, slice: Slice) -&gt; None:
    # Use default order order properties
    self.limit_order(self._symbol, quantity, limit_price)
    
    # Override the default order properties
    order_properties = CoinbaseOrderProperties()
    order_properties.time_in_force = TimeInForce.GOOD_TIL_CANCELED
    order_properties.post_only = True
    self.limit_order(self._symbol, quantity, limit_price, order_properties=order_properties)</pre>
</div>
<?php } ?>

<h4>Updates</h4>
<p><?= $writingAlgorithms ? "The <code>CoinbaseBrokerageModel</code> doesn't support order updates" : "We model the Coinbase API by not supporting order updates" ?>, but you can cancel an existing order and then create a new order with the desired arguments. For more information about this workaround, see the <a href='/docs/v2/writing-algorithms/trading-and-orders/order-management/order-tickets#workaround-for-brokerages-that-dont-support-updates'>Workaround for Brokerages That Don’t Support Updates</a>.</p>


