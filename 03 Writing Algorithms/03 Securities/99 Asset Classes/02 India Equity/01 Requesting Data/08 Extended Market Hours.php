<?php 
$cCode = "_symbol = AddEquity(\"YESBANK\", market: Market.India, extendedMarketHours: true).Symbol;";
$pyCode = "self._symbol = self.add_equity(\"YESBANK\", market=Market.India, extended_market_hours=True).Symbol";
$supportedIntradayData = true;
$marketHoursLink = "/docs/v2/writing-algorithms/securities/asset-classes/india-equity/market-hours";
include(DOCS_RESOURCES."/securities/extended-market-hours.php"); 
?>
