<?php
include(DOCS_RESOURCES."/datasets/research-environment/get-historical-options-data.php");
$createSubscriptionsLink = "/docs/v2/research-environment/datasets/equity-options#03-Create-Subscriptions";
$optionType = "Equity";
$underlyingSymbolVariable = "equity_symbol";
$getHistoricalDataText($createSubscriptionsLink, $optionType, $underlyingSymbolVariable);
?>