<h4>Order Types</h4>
<p>The following table describes the available order types for each asset class that <?= $cloudPlatform ? "our Samco integration" : "the <code>SamcoBrokerageModel</code>" ?> supports:</p>

<table class="qc-table table" id='order-types-table'>
   <thead>
      <tr>
        <th style='width: 50%'>Order Type</th>
        <th>India Equity</th>
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
   </tbody>
</table>
<style>
#order-types-table td:not(:first-child), 
#order-types-table th:not(:first-child) {
    text-align: center;
}
</style>

<h4>Order Properties</h4>

<p><?= $writingAlgorithms ? "The <code>SamcoBrokerageModel</code> supports custom order properties." : "We model custom order properties from the Samco API." ?> The following table describes the members of the <code>IndiaOrderProperties</code> object that you can set to customize order execution:</p>

<table class="table qc-table">
    <thead>
        <tr>
         <th>Property</th>
         <th>Data Type</th>
         <th>Description</th>
         <th>Default Value</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code class='csharp'>Exchange</code><code class="python">exchange</code></td>
            <td><code>Exchange</code>
            <td>Select the exchange for sending the order to. The following instructions are available:
                <ul>
                    <li><code>Exchange.NSE</code></li>
                    <li><code>Exchange.BSE</code></li>
                </ul>
            </td>
            <td></td>
        </tr>
        <tr>
            <td><code class='csharp'>ProductType</code><code class="python">product_type</code></td>
            <td><code class='csharp'>string</code><code class="python">str</code></td>
            <td>
                A <code class='csharp'>ProductType</code><code class="python">product_type</code> instruction to apply to the order. The <code>IndiaOrderProperties.IndiaProductType</code> enumeration has the following members:
                <div data-tree='QuantConnect.Orders.IndiaOrderProperties.IndiaProductType'></div>
            </td>
            <td></td>
        </tr>
        <tr>
            <td><code class="csharp">TimeInForce</code><code class="python">time_in_force</code></td>
            <td><code>TimeInForce</code></td>
            <td>A <a href='/docs/v2/writing-algorithms/trading-and-orders/order-properties#03-Time-In-Force'>TimeInForce</a> instruction to apply to the order. The following instructions are available:
                <ul>
                    <li><code class="csharp">Day</code><code class="python">DAY</code></li>
                    <li><code class="csharp">GoodTilCanceled</code><code class="python">GOOD_TIL_CANCELED</code></li>
                    <li><code class="csharp">GoodTilDate</code><code class="python">good_til_date</code></li>
                </ul>
            </td>
            <td><code class='csharp'>TimeInForce.GoodTilCanceled</code><code class='python'>TimeInForce.GOOD_TIL_CANCELED</code></td>
        </tr>
    </tbody>
</table>

<?php if ($writingAlgorithms) { ?>
<div class="section-example-container">
    <pre class="csharp">public override void Initialize()
{
    // Set default order properties
    DefaultOrderProperties = new IndiaOrderProperties(Exchange.NSE, IndiaOrderProperties.IndiaProductType.NRML)
    {
        TimeInForce = TimeInForce.GoodTilCanceled,
    };
}

public override void OnData(Slice slice)
{
    // Use default order order properties
    LimitOrder(_symbol, quantity, limitPrice);
    
    // Override the default order properties
    LimitOrder(_symbol, quantity, limitPrice, 
               orderProperties: new IndiaOrderProperties(Exchange.BSE, IndiaOrderProperties.IndiaProductType.MIS)
               {
                   TimeInForce = TimeInForce.Day,
               };
    LimitOrder(_symbol, quantity, limitPrice, 
               orderProperties: new IndiaOrderProperties(Exchange.BSE, IndiaOrderProperties.IndiaProductType.CNC)
               {
                   TimeInForce = TimeInForce.GoodTilDate(new DateTime(year, month, day)),
               };
}</pre>
    <pre class="python">def initialize(self) -&gt; None:
    # Set the default order properties
    self.default_order_properties = IndiaOrderProperties(Exchange.NSE, IndiaOrderProperties.IndiaProductType.NRML)
    self.default_order_properties.time_in_force = TimeInForce.GOOD_TIL_CANCELED

def on_data(self, slice: Slice) -&gt; None:
    # Use default order order properties
    self.limit_order(self._symbol, quantity, limit_price)
    
    # Override the default order properties
    order_properties = IndiaOrderProperties(Exchange.BSE, IndiaOrderProperties.IndiaProductType.MIS)
    order_properties.time_in_force = TimeInForce.DAY
    self.limit_order(self._symbol, quantity, limit_price, order_properties=order_properties)

    order_properties = IndiaOrderProperties(Exchange.BSE, IndiaOrderProperties.IndiaProductType.CNC)
    order_properties.time_in_force = TimeInForce.good_til_date(datetime(year, month, day))
    self.limit_order(self._symbol, quantity, limit_price, order_properties=order_properties)</pre>
</div>
<?php } ?>

<h4>Updates</h4>
<p><?= $writingAlgorithms ? "The <code>SamcoBrokerageModel</code> supports" : "We model the Samco API by supporting" ?> <a href='/docs/v2/writing-algorithms/trading-and-orders/order-management/order-tickets#04-Update-Orders'>order updates</a>.</p>


<?php include(DOCS_RESOURCES."/brokerages/handling-splits.html"); ?>
