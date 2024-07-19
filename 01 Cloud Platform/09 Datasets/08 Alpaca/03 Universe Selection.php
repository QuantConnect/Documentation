<?
$availability=true;
$dataFeedName= "Alpaca";
include(DOCS_RESOURCES."/data-feeds/universe-selection.php");
?>

<p>The Alpaca data provider can stream data for up to 30 assets by default. If your algorithm adds more than the your quota, LEAN logs an error message from Alpaca. To increase the quota, <a href="/docs/v2/cloud-platform/datasets/alpaca#99-Pricing">purchase</a> a unlimited market data plan from Alpaca.</p>