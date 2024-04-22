<?php include(DOCS_RESOURCES."/brokerages/binance/asset-classes.php"); ?>

<p>If you call the <code class="csharp">SetBrokerageModel</code><code class="python">set_brokerage_model</code> method with the correct <code>BrokerageName</code>, then you don't need to pass a <code>Market</code> argument to the <code class="csharp">AddCrypto</code><code class="python">add_crypto</code> method because the brokerage model has a <a href='/docs/v2/writing-algorithms/reality-modeling/brokerages/supported-models/binance#17-Default-Markets'>default market</a>.</p>
