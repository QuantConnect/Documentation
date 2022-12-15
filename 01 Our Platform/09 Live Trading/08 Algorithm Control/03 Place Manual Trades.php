<?php
include(DOCS_RESOURCES."/trading-and-orders/place-manual-trades.php");
$getManualTradesText(false);
?>

<p>Note that it's not currently possible to cancel manual orders.</p>

<p>Follow these steps to place manual orders:</p>

<ol>
    <li>Open your algorithm's <a href="/docs/v2/our-platform/live-trading/results#02-View-Live-Results">live results page</a>.</li>
    <li>In the <span class="tab-name">Holdings</span> tab, if the security you want to trade isn't listed, click <span class='button-name'>Show All Portfolio</span>.</li>
    <li>If the security you want to trade still isn't listed, <a href='/docs/v2/our-platform/live-trading/algorithm-control#02-Add-Security-Subscriptions'>subscribe to the security</a>.</li>
    <li>Click the security you want to trade.</li>
    <li>Click <span class='button-name'>Create Order</span> or <span class='button-name'>Liquidate</span>.</li>
    <li>If you clicked <span class='button-name'>Create Order</span>, enter an order quantity.</li>
    <li>Click the <span class='field-name'>Type</span> field and then click an order type from the drop-down menu.</li>
    <li>Click <span class='button-name'>Submit Order</span>.</li>
</ol>
