<?
$streamOfText = "SPX and VIX index values";
include(DOCS_RESOURCES."/data-feeds/introductions.php");
?>

<p>The index values are the underlying prices of SPX and VIX <a href='/docs/v2/cloud-platform/datasets/quantconnect/us-index-options'>US Index Options</a>, so you can trade those contracts without another data provider. The QuantConnect data provider doesn't stream other indices, such as NDX. To trade other indices or their Index Options, add a data provider that supplies the index.</p>
