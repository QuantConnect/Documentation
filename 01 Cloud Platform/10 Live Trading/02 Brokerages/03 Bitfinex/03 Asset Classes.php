<?php include(DOCS_RESOURCES."/brokerages/bitfinex/asset-classes.php"); ?>

<div class="section-example-container">
    <pre class="csharp">AddCrypto("BTCUSDT", Resolution.Minute, Market.Bitfinex);</pre>
    <pre class="python">self.add_crypto("BTCUSDT", Resolution.MINUTE, Market.bitfinex)</pre>
</div>

<p>If you call the <code>SetBrokerageModel</code> method with the correct <code>BrokerageName</code>, then you don't need to pass a <code>Market</code> argument to the <code>AddCrypto</code> method because the brokerage model has a <a href='/docs/v2/writing-algorithms/reality-modeling/brokerages/supported-models/bitfinex#17-Default-Markets'>default market</a>.</p>
