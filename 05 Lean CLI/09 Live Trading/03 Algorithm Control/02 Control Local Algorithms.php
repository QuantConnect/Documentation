<p>While your local algorithms run, you can add security subscriptions, submit orders, adjust orders, and stop their execution.</p>

<h4>Add Security Subscriptions</h4>
<p>You can manually create security subscriptions for your algorithm instead of calling the <code>Add<span class="placeholder-text">securityType</span></code> methods in your code files. If you add security subscriptions to your algorithm, you can place manual trades without having to edit and redeploy the algorithm. To add security subscriptions, open a terminal in the <a href='/docs/v2/lean-cli/initialization/organization-workspaces'>organization workspace</a> that contains the project and then run <code>lean live add-security "My Project"</code>.</p>

<div class="cli section-example-container">
<pre>$ lean live add-security "My Project" --ticker "SPY" --market "usa" --security-type "equity"</pre>
</div>

<p>For more information about the command options, see <a href='/docs/v2/lean-cli/api-reference/lean-live-add-security#04-Options'>Options</a>.</p>

<p>You can't manually remove security subscriptions.</p>


<h4>Submit Orders</h4>
<?
include(DOCS_RESOURCES."/trading-and-orders/place-manual-trades.php");
?>

<p>To submit orders, open a terminal in the <a href='/docs/v2/lean-cli/initialization/organization-workspaces'>organization workspace</a> that contains the project and then run <code>lean live submit-order "My Project"</code>.</p>

<div class="cli section-example-container">
<pre>$ lean live submit-order "My Project" --ticker "SPY" --market "usa" --security-type "equity" --order-type "market" --quantity 10</pre>
</div>

<p>For more information about the command options, see <a href='/docs/v2/lean-cli/api-reference/lean-live-submit-order#04-Options'>Options</a>.</p>


<h4>Update Orders</h4>
<p>To update an existing order, open a terminal in the <a href='/docs/v2/lean-cli/initialization/organization-workspaces'>organization workspace</a> that contains the project and then run <code>lean live update-order "My Project"</code>.</p>
<div class="cli section-example-container">
<pre>$ lean live update-order "My Project" --order-id 1 --quantity 5</pre>
</div>

<p>For more information about the command options, see <a href='/docs/v2/lean-cli/api-reference/lean-live-update-order#04-Options'>Options</a>.</p>

<h4>Cancel Orders</h4>

<p>To cancel an existing order, open a terminal in the <a href='/docs/v2/lean-cli/initialization/organization-workspaces'>organization workspace</a> that contains the project and then run <code>lean live cancel-order "My Project"</code>.</p>
<div class="cli section-example-container">
<pre>$ lean live cancel-order "My Project" --order-id 1</pre>
</div>

<p>For more information about the command options, see <a href='/docs/v2/lean-cli/api-reference/lean-live-cancel-order#04-Options'>Options</a>.</p>

<h4>Liquidate Positions</h4>
<p>To liquidate a specific asset in your algorithm, open a terminal in the <a href='/docs/v2/lean-cli/initialization/organization-workspaces'>organization workspace</a> that contains the project and then run <code>lean live liquidate "My Project"</code>.</p>

<div class="cli section-example-container">
<pre>$ lean live liquidate "My Project" --ticker "SPY" --market "usa" --security-type "equity"</pre>
</div>

<p>When you run the command, if the market is open for the asset, the algorithm liquidates it with market orders. If the market is not open, the algorithm places market on open orders.</p>

<p>For more information about the command options, see <a href='/docs/v2/lean-cli/api-reference/lean-live-liquidate#04-Options'>Options</a>.</p>

<h4>Stop Algorithms</h4>
<? 
include(DOCS_RESOURCES."/trading-and-orders/stop-algorithm.php");
?>

<p>To stop an algorithm, open a terminal in the <a href='/docs/v2/lean-cli/initialization/organization-workspaces'>organization workspace</a> that contains the project and then run <code>lean live stop "My Project"</code>.</p>
<div class="cli section-example-container">
<pre>$ lean live stop "My Project"</pre>
</div>

<p>For more information about the command options, see <a href='/docs/v2/lean-cli/api-reference/lean-live-stop#04-Options'>Options</a>.</p>
