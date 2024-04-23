<?
$cCode = "AddOptionContract(_contractSymbol, extendedMarketHours: true);";
$pyCode = "self.add_option_contract(self.contract_symbol, extended_market_hours=True)";
$supportedIntradayData = true;
$marketHoursLink = "/docs/v2/writing-algorithms/securities/asset-classes/equity-options/market-hours";
include(DOCS_RESOURCES."/securities/extended-market-hours.php"); 
?>
