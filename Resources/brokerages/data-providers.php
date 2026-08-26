<?
// The caller sets $brokerageName, $brokerageDataLink, and $quantConnectAssets, which lists the
// asset classes the QuantConnect data provider supplies for this brokerage. When the QuantConnect
// data provider doesn't cover everything the brokerage trades, the caller also sets $missingAssets
// and $affectedAssets.
include(DOCS_RESOURCES."/brokerages/quantconnect-asset-links.php");
$missingAssets = $missingAssets ?? "";
?>
<p>
  We recommend the <a href='/docs/v2/cloud-platform/datasets/quantconnect'>QuantConnect data provider</a>, which is free and supplies <?=$quantConnectAssetLinks?> data during live trading.
<? if ($missingAssets) { ?>
  It doesn't supply <?=$missingAssets?> data, so combine it with the <a href='<?=$brokerageDataLink?>'><?=$brokerageName?> data provider</a> to trade <?=$affectedAssets?>.
  Index Options need data for the underlying index, such as SPX, VIX, and NDX.
  To receive that data, you might need to purchase a market data subscription from <?=$brokerageName?>.
<? } else { ?>
  If you'd rather trade on <?=$brokerageName?> prices, you might need to purchase a <a href='<?=$brokerageDataLink?>'>market data subscription from <?=$brokerageName?></a>.
<? } ?>
  For more information about live data providers, see <a href='/docs/v2/cloud-platform/datasets'>Datasets</a>.
</p>
