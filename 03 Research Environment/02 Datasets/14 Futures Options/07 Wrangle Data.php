<?php
include(DOCS_RESOURCES."/datasets/wrangle-data.php");
$assetClass = 'Futures Option';
$singularAssetClass = 'Futures Option contract';
$pluralAssetClass = 'Futures Option contracts';
$historicalDataLink = "/docs/v2/research-environment/datasets/futures-options#04-Get-Historical-Data";
$primarySymbolPy = 'fop_contract_symbol';
$primarySymbolC = 'fopContractSymbol';
$primaryTicker = '';
$secondarySymbol = '';
$dataFrameImages = array(
    'https://cdn.quantconnect.com/i/tu/fop-research-data-1.jpg',
    'https://cdn.quantconnect.com/i/tu/fop-research-data-2.jpg',
    'https://cdn.quantconnect.com/i/tu/fop-research-data-3.jpg',
    'https://cdn.quantconnect.com/i/tu/fop-research-data-4.jpg',
    'https://cdn.quantconnect.com/i/tu/fop-research-data-5.jpg',
    'https://cdn.quantconnect.com/i/tu/fop-research-data-6.jpg'
);
$dataFrameColumnName = 'close';
$columnNameEnglish = 'close';
$supportsTrades = true;
$supportsQuotes = true;
$supportsTicks = false;
$supportsAltData = false;
$supportsOpenInterest = true;
$supportsOptionHistory = true;
$supportsFutureHistory = false;
$contractExpiryDate = 'datetime(2022, 3, 18)';
$getWrangleDataText($assetClass, $singularAssetClass, $pluralAssetClass, $historicalDataLink, $primarySymbolPy, $primarySymbolC, $primaryTicker, $secondarySymbol, $dataFrameImages, $dataFrameColumnName, $columnNameEnglish, $supportsTrades, $supportsQuotes, $supportsTicks, $supportsAltData, $supportsOpenInterest, $supportsOptionHistory, $supportsFutureHistory, $contractExpiryDate);
?>
