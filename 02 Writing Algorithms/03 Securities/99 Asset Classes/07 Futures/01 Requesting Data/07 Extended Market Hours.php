<?php 
include(DOCS_RESOURCES."/securities/extended-market-hours.php"); 
$cCode = "AddFutureContract(_contractSymbol, extendedMarketHours: true);";
$pyCode = "self.AddFutureContract(self.contract_symbol, extendedMarketHours=True)";
$supportedIntradayData = true;
$marketHoursLink = "/docs/v2/writing-algorithms/securities/asset-classes/futures/market-hours";
$getExtMarketHoursText($cCode, $pyCode, $supportedIntradayData, $marketHoursLink);
?>
