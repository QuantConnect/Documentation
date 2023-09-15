<?php
include(DOCS_RESOURCES."/data-feeds/universe-selection.php");
$getUniverseSelectionText("IB", true);
?>

<p>The universe selection data comes from our Dataset Market, not the <a rel='nofollow' target='_blank' href='https://interactivebrokers.github.io/tws-api/market_scanners.html'>TWS market scanners</a>. Universe selection with the IB data feed occurs around 6-7 AM Eastern Time (ET) on Tuesday to Friday and at 2 AM ET on Sunday. Universe selection data isn't available when the IB servers are closed. To check the IB server status, see the <a rel='nofollow' target='_blank' href='https://www.interactivebrokers.com/en/software/systemStatus.php'>Current System Status</a> page on the IB website.</p>

<p>The IB data feed can stream data for up to 100 assets by default, but IB may let you stream more than 100 assets based on your commissions and equity value. For more information about data feed limits from IB, see the <a rel='nofollow' target='_blank' href='https://www.interactivebrokers.com/en/pricing/research-news-marketdata.php'>Market Data Pricing Overview</a> page on the IB website. If your algorithm adds more than the your limit, LEAN logs the following message:</p>

<div class='error-messages'>Error - Code: 101 - Max number of tickers has been reached - The current number of active market data subscriptions in TWS and the API altogether has been exceeded (102). This number is calculated based on a formula which is based on the equity, commissions, and quote booster packs in an account.</div>

<p>where 102 is the number of the subscription LEAN tried to add when it received the error message.</p>
