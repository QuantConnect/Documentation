<?
$cCode = "AddOptionContract(_contractSymbol, extendedMarketHours: true);";
$pyCode = "self.AddOptionContract(self.contract_symbol, extendedMarketHours=True)";
$supportedIntradayData = true;
$marketHoursLink = "/docs/v2/writing-algorithms/securities/asset-classes/equity-options/market-hours";
include(DOCS_RESOURCES."/securities/extended-market-hours.php"); 
?>
