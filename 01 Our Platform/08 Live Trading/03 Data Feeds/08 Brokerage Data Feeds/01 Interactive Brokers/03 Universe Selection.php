<?php
include(DOCS_RESOURCES."/data-feeds/universe-selection.php");
$getUniverseSelectionText("IB", true);
?>

<p>Universe selection with the IB data feed occurs around 6-7 AM Eastern Time (ET) on Tuesday to Friday and at 2 AM ET on Sunday. The universe selection data comes from our Dataset Market, not the <a rel='nofollow' target='_blank' href='https://interactivebrokers.github.io/tws-api/market_scanners.html'>TWS market scanners</a>. Universe selection data isn't available when the IB servers are closed. To check the IB server status, see the <a rel='nofollow' target='_blank' href='https://www.interactivebrokers.com/en/software/systemStatus.php'>Current System Status</a> page on the IB website.</p>

<p>The IB data feed can stream data for up to 100 assets by default, but IB may let you stream more than 100 assets based on your commissions and equity value. For more information about data feed limits from IB, see the <a rel='nofollow' target='_blank' href='https://www.interactivebrokers.com/en/pricing/research-news-marketdata.php'>Market Data Pricing Overview</a> page on the IB website. If IB lets you stream more than 100 assets, set the <code>DataSubscriptionLimit</code> of your algorithm to the new limit from IB.</p>

<div class="section-example-container">
    <pre class="csharp">Settings.DataSubscriptionLimit = 150;</pre>
    <pre class="python">self.Settings.DataSubscriptionLimit = 150</pre>
</div>