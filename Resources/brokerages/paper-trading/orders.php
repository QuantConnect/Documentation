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
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/market-orders'>Market</a></td>
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
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/limit-orders'>Limit</a></td>
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
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/limit-if-touched-orders'>Limit if touched</a></td>
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
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/stop-market-orders'>Stop market</a></td>
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
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/stop-limit-orders'>Stop limit</a></td>
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
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/market-on-open-orders'>Market on open</a></td>
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
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/market-on-close-orders'>Market on close</a></td>
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
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/combo-market-orders'>Combo market</a></td>
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
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/combo-limit-orders'>Combo limit</a></td>
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
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/combo-leg-limit-orders'>Combo leg limit</a></td>
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
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/option-exercise-orders'>Exercise Option</a></td>
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

<p>
   In live trading, Option orders require a third-party data provider that supports Options.
  <? if ($cloudPlatform) { To view the data providers that support Options, see <a href='/docs/v2/cloud-platform/datasets'>Datasets</a>. } ?>
  <? if ($writingAlgorithms) { To view the data providers that support Options, see their respective documentation in <a href='/docs/v2/cloud-platform/datasets'>Cloud Platform</a>, <a href='/docs/v2/local-platform/datasets/getting-started'>Local Platform</a>, or the <a href='/docs/v2/lean-cli/live-trading/data-providers'>CLI</a>. } ?>
</p>

<h4>Time In Force</h4>
<p>The <code>DefaultBrokerageModel</code> supports the following <a href='/docs/v2/writing-algorithms/trading-and-orders/order-properties#03-Time-In-Force'>TimeInForce</a> instructions:</p>

<ul>
    <li><code class="csharp">Day</code><code class="python">DAY</code></li>
    <li><code class="csharp">GoodTilCanceled</code><code class="python">good_til_canceled</code></li>
    <li><code class="csharp">GoodTilDate</code><code class="python">good_til_date</code></li>
</ul>

<h4>Updates</h4>
<p>The <code>DefaultBrokerageModel</code> supports <a href='/docs/v2/writing-algorithms/trading-and-orders/order-management/order-tickets#04-Update-Orders'>order updates</a>.</p>

<?php
include(DOCS_RESOURCES."/brokerages/handling-splits.html");
?>
