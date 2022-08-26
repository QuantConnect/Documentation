<p>To use the Tradier brokerage, create a <code>TradierLiveAlgorithmSettings</code> object and then set its <code>Environment</code> property.</p>

<div class="section-example-container">
    <pre class="csharp">var liveSettings = new TradierLiveAlgorithmSettings(accessToken: "",
                                                    dateIssued: "",
                                                    refreshToken: "",
                                                    account: "");
liveSettings.Environment = BrokerageEnvironment.Live;</pre>
    <pre class="python">live_settings = TradierLiveAlgorithmSettings(accessToken="",
                                             dateIssued="",
                                             refreshToken="",
                                             account="")
live_settings.Environment = BrokerageEnvironment.Live</pre>
</div>

<?php echo file_get_contents(DOCS_RESOURCES."/brokerages/create-credentials/tradier.html"); ?>

<p>The <code>BrokerageEnvironment</code> enumeration has the following members:</p>
<div data-tree='QuantConnect.BrokerageEnvironment'></div>