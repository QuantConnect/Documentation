<?php
include(DOCS_RESOURCES."/trading-and-orders/place-manual-trades.php");
$getManualTradesText(true);
?>

<p>To submit orders, open a terminal in your <a href='/docs/v2/lean-cli/initialization/directory-structure#02-lean-init'>CLI root directory</a> and then run <code>lean live submit-order "My Project"</code>.</p>

<div class="cli section-example-container">
<pre>$ lean live submit-order "My Project" --ticker "SPY" --market "usa" --security-type "equity" --order-type "market" --quantity 10</pre>
</div>

<p>For more information about the command options, see <a href='/docs/v2/lean-cli/api-reference/lean-live-add-security#04-Options'>Options</a>.</p>
