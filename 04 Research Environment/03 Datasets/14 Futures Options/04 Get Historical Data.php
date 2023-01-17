<?php
include(DOCS_RESOURCES."/datasets/research-environment/get-historical-data.php");
$createSubscriptionsLink = "/docs/v2/research-environment/datasets/futures-options#03-Create-Subscriptions";
$assetClass = "Futures Option";
$underlyingSymbolVariable = "futuresContractSymbol";
$underlyingAssetClass = "Futures contract";
$supportsTicks = false;
$contractVariablePy ="fop_contract_symbol";
$contractVariableC ="fopContractSymbol";
$getHistoricalDataText($createSubscriptionsLink, $assetClass, $underlyingSymbolVariable, $underlyingAssetClass, $supportsTicks, $contractVariablePy, $contractVariableC);
?>
