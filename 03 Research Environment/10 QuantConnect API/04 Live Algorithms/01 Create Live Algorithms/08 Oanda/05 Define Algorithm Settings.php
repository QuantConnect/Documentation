<p>To use the Oanda brokerage, create a <code>OandaLiveAlgorithmSettings</code> object.</p>

<div class="section-example-container">
    <pre class="csharp">var liveSettings = new OandaLiveAlgorithmSettings(accessToken: "",
                                                    environment: BrokerageEnvironment.Live,
                                                    account: "&lt;account_id&gt;");</pre>
    <pre class="python">live_settings = OandaLiveAlgorithmSettings(accessToken="",
                                             environment=BrokerageEnvironment.Live,
                                             account="&lt;account_id&gt;")</pre>
</div>

<?php echo file_get_contents(DOCS_RESOURCES."/brokerages/create-credentials/oanda.html"); ?>

<p>The <code>BrokerageEnvironment</code> enumeration has the following members:</p>
<div data-tree='QuantConnect.BrokerageEnvironment'></div>
