<?php
include(DOCS_RESOURCES."/brokearges/cli-deployment/deploy-local-algorithms.php");


$brokerageDetails = "
<li>Enter your <a href='https://kite.trade/' target='_blank'>Kite Connect</a> API key and access token.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
API key: hp9erb9ct0lqaxpm
Access token: ********************</pre>
</div>
</li>

<li>Enter the product type. This property must be <code>MIS</code> if you are targeting intraday products, <code>CNC</code> if you are targeting delivery products, or <code>NRML</code> if you are targeting carry forward products.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Product type (MIS, CNC, NRML): MIS</pre>
</div>
</li>

<li>Enter the trading segment. This property must be <code>EQUITY</code> if you are trading equities on NSE or BSE, or <code>COMMODITY</code> if you are trading commodities on MCX.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Trading segment (EQUITY, COMMODITY): EQUITY</pre>
</div>
</li>
";

$dataFeedDetails = "
<li>Follow the steps of configuring Zerodha as brokerage above if you chose paper trading as the brokerage.</li>
<li>Enter whether you have a history API subscription.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Do you have a history API subscription? [y/N]: y</pre>
</div>
</li>
";

$supportsIQFeed = false;

$getDeployLocalAlgorithmsText("Zerodha", $brokerageDetails, $dataFeedDetails, $supportsIQFeed);
?>