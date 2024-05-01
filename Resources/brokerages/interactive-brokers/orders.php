<h4>Order Types</h4>
<p>The following table describes the order types that <?= $cloudPlatform ? "our IB integration" : "the <code>InteractiveBrokersBrokerageModel</code>" ?> supports: supports. For specific details about each order type, refer to the IB documentation.<br></p>


<table class="qc-table table">
  <thead>
    <tr>
      <th style="width: 50%">Order Type</th>
      <th style="width: 50%">IB Documentation Page</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/market-orders'>MarketOrder</a></td>
      <td><a rel="nofollow" target="_blank" href="https://www.interactivebrokers.com/en/index.php?f=602">Market Orders</a></td>
    </tr>
    <tr>
      <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/limit-orders'>LimitOrder</a></td>
      <td><a rel="nofollow" target="_blank" href="https://www.interactivebrokers.com/en/index.php?f=593">Limit Orders</a></td>
    </tr>
    <tr>
      <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/limit-if-touched-orders'>LimitIfTouchedOrder</a></td>
      <td><a rel="nofollow" target="_blank" href="https://www.interactivebrokers.com/en/index.php?f=592">Limit if Touched Orders</a></td>
    </tr>
    <tr>
      <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/stop-market-orders'>StopMarketOrder</a></td>
      <td><a rel="nofollow" target="_blank" href="https://www.interactivebrokers.com/en/index.php?f=609">Stop Orders</a></td>
    </tr>
    <tr>
      <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/stop-limit-orders'>StopLimitOrder</a></td>
      <td><a rel="nofollow" target="_blank" href="https://www.interactivebrokers.com/en/index.php?f=608">Stop-Limit Orders</a></td>
    </tr>
    <tr>
      <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/trailing-stop-orders'>TrailingStopOrder</a></td>
      <td><a rel="nofollow" target="_blank" href="https://www.interactivebrokers.com/en/trading/orders/trailing-stops.php">Trailing Stop Orders</a></td>
    </tr>
    <tr>
      <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/market-on-open-orders'>MarketOnOpenOrder</a></td>
      <td><a rel="nofollow" target="_blank" href="https://www.interactivebrokers.com/en/index.php?f=598">Market-on-Open (MOO) Orders</a></td>
    </tr>
    <tr>
      <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/market-on-close-orders'>MarketOnCloseOrder</a></td>
      <td><a rel="nofollow" target="_blank" href="https://www.interactivebrokers.com/en/index.php?f=599">Market-on-Close (MOC) Orders</a></td>
    </tr>
    <tr>
      <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/combo-market-orders'>ComboMarketOrder</a></td>
      <td><a rel="nofollow" target="_blank" href="https://www.interactivebrokers.com/en/trading/orders/spread.php">Spread Orders</a></td>
    </tr>
    <tr>
      <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/combo-limit-orders'>ComboLimitOrder</a></td>
      <td><a rel="nofollow" target="_blank" href="https://www.interactivebrokers.com/en/trading/orders/spread.php">Spread Orders</a></td>
    </tr>
    <tr>
      <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/combo-leg-limit-orders'>ComboLegLimitOrder</a></td>
      <td><a rel="nofollow" target="_blank" href="https://www.interactivebrokers.com/en/trading/orders/spread.php">Spread Orders</a></td>
    </tr>
    <tr>
      <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/option-exercise-orders'>ExerciseOption</a></td>
      <td><a rel="nofollow" target="_blank" href="https://www.interactivebrokers.ca/en/trading/exerciseCloseout.php">Options Exercise</a></td>
    </tr>
  </tbody>
</table>


<p>The following table describes the available order types for each asset class that <?= $cloudPlatform ? "IB" : "the <code>InteractiveBrokersBrokerageModel</code>" ?> supports:</p>

<table class="qc-table table" id='order-types-table'>
   <thead>
      <tr>
        <th>Order Type</th>
        <th>US Equity</th>
        <th>Equity Options</th>
        <th>Forex</th>
        <th>Futures</th>
        <th>Futures Options</th>
        <th>Index Options</th>
        <th>CFD</th>
      </tr>
   </thead>
   <tbody>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/market-orders'>MarketOrder</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/limit-orders'>LimitOrder</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/limit-if-touched-orders'>LimitIfTouchedOrder</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/stop-market-orders'>StopMarketOrder</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/stop-limit-orders'>StopLimitOrder</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/trailing-stop-orders'>TrailingStopOrder</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/market-on-open-orders'>MarketOnOpenOrder</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td></td>
        <td></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/market-on-close-orders'>MarketOnCloseOrder</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td></td>
        <td></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/combo-market-orders'>ComboMarketOrder</a></td>
        <td></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td></td>
        <td></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td></td>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/combo-limit-orders'>ComboLimitOrder</a></td>
        <td></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td></td>
        <td></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td></td>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/combo-leg-limit-orders'>ComboLegLimitOrder</a></td>
        <td></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td></td>
        <td></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td></td>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/option-exercise-orders'>ExerciseOption</a></td>
        <td></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"><br>Not supported for cash-settled Options</td></td>
        <td></td>
        <td></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td></td>
        <td></td>
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

<p><?=  $writingAlgorithms ? "The <code>InteractiveBrokersBrokerageModel</code> supports custom order properties." : "We model custom order properties from the IB API." ?> The following table describes the members of the <code>InteractiveBrokersOrderProperties</code> object that you can set to customize order execution. The table does not include the preceding methods for FA accounts.</p>

<table class="table qc-table">
    <thead>
        <tr>
            <th style="width: 25%">Property</th>
            <th style="width: 75%">Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code class="csharp">TimeInForce</code><code class="python">time_in_force</code></td>
            <td>A <a href='/docs/v2/writing-algorithms/trading-and-orders/order-properties#03-Time-In-Force'>TimeInForce</a> instruction to apply to the order. The following instructions are supported:
                <ul>
                    <li><code class="csharp">Day</code><code class="python">DAY</code></li>
                    <li><code class="csharp">GoodTilCanceled</code><code class="python">GOOD_TIL_CANCELED</code></li>
                    <li><code class="csharp">GoodTilDate</code><code class="python">GOOD_TIL_DATE</code></li>
                </ul>
            </td>
        </tr>
        <tr>
            <td><code class="csharp">OutsideRegularTradingHours</code><code class="python">outside_regular_trading_hours</code></td>
            <td>A flag to signal that the order may be triggered and filled outside of regular trading hours.</td>
        </tr>
    </tbody>
</table>

<?php if ($writingAlgorithms) { ?>
<div class="section-example-container">
    <pre class="csharp">public override void Initialize()
{
    // Set the default order properties
    DefaultOrderProperties = new InteractiveBrokersOrderProperties
    {
        TimeInForce = TimeInForce.GoodTilCanceled,
        OutsideRegularTradingHours = false
    };
}

public override void OnData(Slice slice)
{
    // Use default order order properties
    LimitOrder(_symbol, quantity, limitPrice);
    
    // Override the default order properties
    LimitOrder(_symbol, quantity, limitPrice, 
               orderProperties: new InteractiveBrokersOrderProperties
               { 
                   TimeInForce = TimeInForce.Day,
                   OutsideRegularTradingHours = false
               });
    LimitOrder(_symbol, quantity, limitPrice, 
               orderProperties: new InteractiveBrokersOrderProperties
               { 
                   TimeInForce = TimeInForce.GoodTilDate(new DateTime(year, month, day)),
                   OutsideRegularTradingHours = true
               });
}</pre>
    <pre class="python">def initialize(self) -&gt; None:
    # Set the default order properties
    self.default_order_properties = InteractiveBrokersOrderProperties()
    self.default_order_properties.time_in_force = TimeInForce.GOOD_TIL_CANCELED
    self.default_order_properties.outside_regular_trading_hours = False

def on_data(self, slice: Slice) -&gt; None:
    # Use default order order properties
    self.limit_order(self._symbol, quantity, limit_price)
    
    # Override the default order properties
    order_properties = InteractiveBrokersOrderProperties()
    order_properties.time_in_force = TimeInForce.DAY
    order_properties.outside_regular_trading_hours = True
    self.limit_order(self._symbol, quantity, limit_price, order_properties=order_properties)

    order_properties.time_in_force = TimeInForce.good_til_date(datetime(year, month, day))
    self.limit_order(self._symbol, quantity, limit_price, order_properties=order_properties)</pre>
</div>
<?php } ?>

<h4>Updates</h4>
<p><?= $writingAlgorithms ? "The <code>InteractiveBrokersBrokerageModel</code> supports" : "We model the IB API by supporting" ?> <a href='/docs/v2/writing-algorithms/trading-and-orders/order-management/order-tickets#04-Update-Orders'>order updates</a>.</p>


<h4>Financial Advisor Group Orders</h4>
<p>To place FA group orders, see <a href='/docs/v2/writing-algorithms/reality-modeling/brokerages/supported-models/interactive-brokers#18-Financial-Advisors'>Financial Advisors</a>.</p>

<h4>Fractional Trading</h4>
<p>The <?= $writingAlgorithms ? "<code>InteractiveBrokersBrokerageModel</code> doesn't" : "IB API and FIX/CTCI don't" ?> support <a rel="nofollow" target="_blank" href='https://www.interactivebrokers.com/en/trading/fractional-trading.php'>fractional trading</a>.</p>

<?php include(DOCS_RESOURCES."/brokerages/handling-splits.html"); ?>
