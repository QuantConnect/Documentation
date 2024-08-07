<?php include(DOCS_RESOURCES."/brokerages/coinbase/asset-classes.php"); ?>

<div class="section-example-container">
    <pre class="csharp">AddCrypto("BTCUSD", Resolution.Minute, Market.Coinbase);</pre>
    <pre class="python">self.add_crypto("BTCUSD", Resolution.MINUTE, Market.COINBASE)</pre>
</div>

<p>If you call the <code class="csharp">SetBrokerageModel</code><code class="python">set_brokerage_model</code> method with the correct <code>BrokerageName</code>, then you don't need to pass a <code>Market</code> argument to the <code class="csharp">AddCrypto</code><code class="python">add_crypto</code> method because the brokerage model has a <a href='/docs/v2/writing-algorithms/reality-modeling/brokerages/supported-models/coinbase#17-Default-Markets'>default market</a>.</p>
