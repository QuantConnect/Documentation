<?php
include(DOCS_RESOURCES."/datasets/research-environment/get-historical-options-data.php");
$createSubscriptionsLink = "/docs/v2/research-environment/datasets/index-options#03-Create-Subscriptions";
$optionType = "Index";
$underlyingSymbolVariable = "index_symbol";
$getHistoricalDataText($createSubscriptionsLink, $optionType, $underlyingSymbolVariable);
?>