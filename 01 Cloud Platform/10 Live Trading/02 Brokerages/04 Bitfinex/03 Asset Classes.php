<?php include(DOCS_RESOURCES."/brokerages/bitfinex/asset-classes.php"); ?>

<div class="section-example-container">
    <pre class="csharp">// Add BTC/USDT crypto pair at minute resolution from the Bitfinex market
    AddCrypto("BTCUSDT", Resolution.Minute, Market.Bitfinex);</pre>
    <pre class="python"># Add BTC/USDT crypto pair at minute resolution from the Bitfinex market
    self.add_crypto("BTCUSDT", Resolution.MINUTE, Market.BITFINEX)</pre>
</div>

<p>If you call the <code class="csharp">SetBrokerageModel</code><code class="python">set_brokerage_model</code> method with the correct <code>BrokerageName</code>, then you don't need to pass a <code>Market</code> argument to the <code class="csharp">AddCrypto</code><code class="python">add_crypto</code> method because the brokerage model has a <a href='/docs/v2/writing-algorithms/reality-modeling/brokerages/supported-models/bitfinex#17-Default-Markets'>default market</a>.</p>
