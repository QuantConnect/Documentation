<?php include(DOCS_RESOURCES."/brokerages/kraken/asset-classes.php"); ?>

<div class="section-example-container">
    <pre class="csharp">AddCrypto("BTCUSDT", Resolution.Minute, Market.Kraken);</pre>
    <pre class="python">self.AddCrypto("BTCUSDT", Resolution.Minute, Market.Kraken)</pre>
</div>

<p>If you call the <code>SetBrokerageModel</code> method with the correct <code>BrokerageName</code>, then you don't need to pass a <code>Market</code> argument to the <code>AddCrypto</code> method because the brokerage model has a <a href='/docs/v2/writing-algorithms/reality-modeling/brokerages/supported-models/kraken#17-Default-Markets'>default market</a>.</p>
