<?
$availability=true;
$dataFeedName= "IB";
include(DOCS_RESOURCES."/data-feeds/universe-selection.php");
?>

<p>The universe selection data comes from our Dataset Market, not the <a rel='nofollow' target='_blank' href='https://interactivebrokers.github.io/tws-api/market_scanners.html'>TWS market scanners</a>. Universe selection with the IB data provider occurs around 6-7 AM Eastern Time (ET) on Tuesday to Friday and at 2 AM ET on Sunday. Universe selection data isn't available when the IB servers are closed. To check the IB server status, see the <a rel='nofollow' target='_blank' href='https://www.interactivebrokers.com/en/software/systemStatus.php'>Current System Status</a> page on the IB website.</p>

<p>The IB data provider can stream data for up to 100 assets by default, but IB may let you stream more than 100 assets based on your commissions and equity value. For more information about quotas from IB, see the <a rel='nofollow' target='_blank' href='https://www.interactivebrokers.com/en/pricing/research-news-marketdata.php'>Market Data Pricing Overview</a> page on the IB website. If your algorithm adds more than the your quota, LEAN logs an error message from IB. To increase the quota, purchase a Quote Booster from IB.</p>
