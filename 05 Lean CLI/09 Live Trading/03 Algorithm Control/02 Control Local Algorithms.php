<h4>Add Security Subscriptions</h4>
<p>In local deployments, you can manually create security subscriptions for your algorithm instead of calling the <code>Add<span class="placeholder-text">securityType</span></code> methods in your code files. If you add security subscriptions to your algorithm, you can place manual trades without having to edit and redeploy the algorithm. To add security subscriptions, open a terminal in your <a href='/docs/v2/lean-cli/initialization/directory-structure#02-lean-init'>CLI root directory</a> and then run <code>lean live add-security "My Project"</code>.</p>

<div class="cli section-example-container">
<pre>$ lean live add-security "My Project" --ticker "SPY" --market "usa" --security-type "equity"</pre>
</div>

<p>For more information about the command options, see <a href='/docs/v2/lean-cli/api-reference/lean-live-add-security#04-Options'>Options</a>.</p>

<p>You can't manually remove security subscriptions.</p>


<h4>Submit Orders</h4>
<?php
include(DOCS_RESOURCES."/trading-and-orders/place-manual-trades.php");
$getManualTradesText(true);
?>

<p>To submit orders, open a terminal in your <a href='/docs/v2/lean-cli/initialization/directory-structure#02-lean-init'>CLI root directory</a> and then run <code>lean live submit-order "My Project"</code>.</p>

<div class="cli section-example-container">
<pre>$ lean live submit-order "My Project" --ticker "SPY" --market "usa" --security-type "equity" --order-type "market" --quantity 10</pre>
</div>

<p>For more information about the command options, see <a href='/docs/v2/lean-cli/api-reference/lean-live-submit-order#04-Options'>Options</a>.</p>


<h4>Update Orders</h4>

<h4>Cancel Orders</h4>

<h4>Liquidate Positions</h4>

<h4>Stop the Algorithm</h4>
