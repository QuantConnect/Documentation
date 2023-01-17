<?php 
include(DOCS_RESOURCES."/securities/extended-market-hours.php"); 
$cCode = "AddFutureOptionContract(_optionContractSymbol, extendedMarketHours: true);";
$pyCode = "self.AddFutureOptionContract(self.option_contract_symbol, extendedMarketHours=True)";
$supportedIntradayData = true;
$marketHoursLink = "/docs/v2/writing-algorithms/securities/asset-classes/future-options/market-hours";
$getExtMarketHoursText($cCode, $pyCode, $supportedIntradayData, $marketHoursLink);
?>
