<p>The following sections describe how the <code>DefaultBrokerageModel</code> handles orders. <?= $cloudPlatform ? "If you set the brokerage model to a different model, the new brokerage model defines how orders are handled." : "" ?></p>

<h4>Order Types</h4>
<p>The following table describes the available order types for each asset class that the <code>DefaultBrokerageModel</code> supports:</p>

<table class="qc-table table" id='order-types-table'>
   <thead>
      <tr>
        <th>Order Type</th>
        <th>US Equity</th>
        <?= $writingAlgorithms ? "<th>Equity Options</th>" : "" ?>
        <th>Crypto</th>
        <th>Crypto Futures</th>
        <th>Forex</th>
        <th>CFD</th>
        <th>Futures</th>
        <th>Futures Options</th>
        <?= $writingAlgorithms ? "<th>Index Options</th>" : "" ?>
      </tr>
   </thead>
   <tbody>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/market-orders'>MarketOrder</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <?php if ($writingAlgorithms) { ?><td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td><?php } ?>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <?php if ($writingAlgorithms) { ?><td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td><?php } ?>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/limit-orders'>LimitOrder</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <?php if ($writingAlgorithms) { ?><td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td><?php } ?>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <?php if ($writingAlgorithms) { ?><td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td><?php } ?>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/limit-if-touched-orders'>LimitIfTouchedOrder</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <?php if ($writingAlgorithms) { ?><td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td><?php } ?>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <?php if ($writingAlgorithms) { ?><td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td><?php } ?>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/stop-market-orders'>StopMarketOrder</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <?php if ($writingAlgorithms) { ?><td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td><?php } ?>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <?php if ($writingAlgorithms) { ?><td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td><?php } ?>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/stop-limit-orders'>StopLimitOrder</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <?php if ($writingAlgorithms) { ?><td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td><?php } ?>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <?php if ($writingAlgorithms) { ?><td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td><?php } ?>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/market-on-open-orders'>MarketOnOpenOrder</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <?php if ($writingAlgorithms) { ?><td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td><?php } ?>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td></td>
        <td></td>
        <?php if ($writingAlgorithms) { ?><td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td><?php } ?>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/market-on-close-orders'>MarketOnCloseOrder</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <?php if ($writingAlgorithms) { ?><td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td><?php } ?>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <?php if ($writingAlgorithms) { ?><td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td><?php } ?>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/combo-market-orders'>ComboMarketOrder</a></td>
        <td></td>
        <?php if ($writingAlgorithms) { ?><td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td><?php } ?>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <?php if ($writingAlgorithms) { ?><td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td><?php } ?>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/combo-limit-orders'>ComboLimitOrder</a></td>
        <td></td>
        <?php if ($writingAlgorithms) { ?><td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td><?php } ?>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <?php if ($writingAlgorithms) { ?><td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td><?php } ?>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/combo-leg-limit-orders'>ComboLegLimitOrder</a></td>
        <td></td>
        <?php if ($writingAlgorithms) { ?><td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td><?php } ?>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <?php if ($writingAlgorithms) { ?><td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td><?php } ?>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/option-exercise-orders'>ExerciseOption</a></td>
        <td></td>
        <?php if ($writingAlgorithms) { ?><td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"><br>Not supported for cash-settled Options</td><?php } ?>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <?php if ($writingAlgorithms) { ?><td></td><?php } ?>
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
<p>The <code>DefaultBrokerageModel</code> supports the following <a href='/docs/v2/writing-algorithms/trading-and-orders/order-properties#03-Time-In-Force'>TimeInForce</a> instructions:</p>

<ul>
    <li><code class="csharp">Day</code><code class="python">DAY</code></li>
    <li><code class="csharp">GoodTilCanceled</code><code class="python">GOOD_TIL_CANCELED</code></li>
    <li><code class="csharp">GoodTilDate</code><code class="python">GOOD_TIL_DATE</code></li>
</ul>

<h4>Updates</h4>
<p>The <code>DefaultBrokerageModel</code> supports <a href='/docs/v2/writing-algorithms/trading-and-orders/order-management/order-tickets#04-Update-Orders'>order updates</a>.</p>

<?php
include(DOCS_RESOURCES."/brokerages/handling-splits.html");
?>
