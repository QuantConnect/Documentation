<p><?= $leanCli ? "In local deployments, you can " : "The live results page lets you " ?>manually place orders instead of calling the automated methods in your project files. You can use any order type that is supported by the brokerage that you used when deploying the algorithm. To view the supported order types of your brokerage, see the <span class='page-section-name'>Orders</span> section of your <a href='/docs/v2/writing-algorithms/reality-modeling/brokerages/key-concepts'>brokerage model</a>. Some example situations where it may be helpful to place manual orders instead of stopping and redeploying the algorithm include the following:</p>

<ul>
    <li>Your brokerage account had holdings in it before you deployed your algorithm</li>
    <li>Your algorithm had bugs in it that caused it to purchase the wrong security</li>
    <li>You want to add a hedge to your portfolio without adjusting the algorithm code</li>
    <li>You want to rebalance your portfolio before the rebalance date</li>
</ul>