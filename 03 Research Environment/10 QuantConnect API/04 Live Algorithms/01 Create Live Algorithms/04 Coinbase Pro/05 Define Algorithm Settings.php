<p>To use the Coinbase Pro brokerage, create a <code>GDAXLiveAlgorithmSettings</code> object and then set its <code>Environment</code> property.</p>

<div class="section-example-container">
    <pre class="csharp">var liveSettings = new GDAXLiveAlgorithmSettings(key: "", secret: "", passphrase: "");
liveSettings.Environment = BrokerageEnvironment.Live;</pre>
    <pre class="python">live_settings = GDAXLiveAlgorithmSettings(key="", secret="", passphrase="")
live_settings.Environment = BrokerageEnvironment.Live</pre>
</div>

<?php echo file_get_contents(DOCS_RESOURCES."/brokerages/create-credentials/coinbase-pro.html"); ?>

<p>The <code>BrokerageEnvironment</code> enumeration has the following members:</p>
<div data-tree='QuantConnect.BrokerageEnvironment'></div>