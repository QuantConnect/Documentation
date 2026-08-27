<?
$brokerageName = "IB";
$brokerageDataLink = "/docs/v2/cloud-platform/datasets/interactive-brokers";
$quantConnectAssets = ["US Equities", "US Equity Options", "US Index Options", "Futures", "Forex", "CFD"];
$missingAssets = "Index or Future Options";
$affectedAssets = "Indices, Index Options, or Future Options";
include(DOCS_RESOURCES."/brokerages/data-providers.php");
?>

<p>
  If you route orders through <a href='/docs/v2/cloud-platform/live-trading/brokerages/interactive-brokers#03-FIX-Integration'>FIX</a>, use the QuantConnect data provider on its own.
  The IB data provider streams data through the IB API instead of your FIX session, so it brings back the weekly logins that FIX removes.
  To trade <?=$affectedAssets?>, you need the IB data provider, so you have to accept those logins.
</p>

