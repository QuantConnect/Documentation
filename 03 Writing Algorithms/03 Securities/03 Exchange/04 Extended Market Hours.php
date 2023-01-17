<?php 
include(DOCS_RESOURCES."/securities/extended-market-hours.php"); 
$cCode = "AddEquity(\"SPY\", extendedMarketHours: true);";
$pyCode = "self.AddEquity(\"SPY\", extendedMarketHours=True)";
$supportedIntradayData = true;
$getExtMarketHoursText($cCode, $pyCode, $supportedIntradayData);
?>
