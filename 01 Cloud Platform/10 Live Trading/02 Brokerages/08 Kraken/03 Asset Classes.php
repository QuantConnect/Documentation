<?php include(DOCS_RESOURCES."/brokerages/kraken/asset-classes.php"); ?>

<div class="section-example-container">
    <pre class="csharp">// Add the BTC/USDT Crypto pair at minute resolution from the Kraken market.
    AddCrypto("BTCUSDT", Resolution.Minute, Market.Kraken);</pre>
    <pre class="python"># Add the BTC/USDT Crypto pair at minute resolution from the Kraken market.
    self.add_crypto("BTCUSDT", Resolution.MINUTE, Market.KRAKEN)</pre>
</div>

<p>If you call the <code class="csharp">SetBrokerageModel</code><code class="python">set_brokerage_model</code> method with the correct <code>BrokerageName</code>, then you don't need to pass a <code>Market</code> argument to the <code class="csharp">AddCrypto</code><code class="python">add_crypto</code> method because the brokerage model has a <a href='/docs/v2/writing-algorithms/reality-modeling/brokerages/supported-models/kraken#17-Default-Markets'>default market</a>.</p>
