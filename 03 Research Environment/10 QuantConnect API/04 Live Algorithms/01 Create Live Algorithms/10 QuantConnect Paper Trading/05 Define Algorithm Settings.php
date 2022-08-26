<p>To use the QuantConnect Paper Trading brokerage, create a <code>DefaultLiveAlgorithmSettings</code> object.</p>

<div class="section-example-container">
    <pre class="csharp">var liveSettings = new DefaultLiveAlgorithmSettings(user: "",
                                                    password: "",
                                                    environment: BrokerageEnvironment.Paper,
                                                    account: "");</pre>
    <pre class="python">live_settings = DefaultLiveAlgorithmSettings(user="",
                                             password="",
                                             environment=BrokerageEnvironment.Paper,
                                             account="")</pre>
</div>
