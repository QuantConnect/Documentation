<p>Each brokerage has a set of assets and order types they support. To avoid issues with <?=$orderType ?>, <a href='/docs/v2/writing-algorithms/initialization#05-Set-Brokerage-and-Cash-Model'>set the brokerage model</a> to a brokerage that supports them.</p>

<div class='section-example-container'>
    <pre class='csharp'>SetBrokerageModel(BrokerageName.<?=$brokerageName?>);</pre>
    <pre class='python'>self.SetBrokerageModel(BrokerageName.<?=$brokerageName?>)</pre>
</div>

<p>To check if your brokerage has any special requirements for <?=$orderType ?>, see the <span class='page-section-name'>Orders</span> section of the <a href='/docs/v2/cloud-platform/live-trading/brokerages'>brokerage integration documentation</a>.</p>
