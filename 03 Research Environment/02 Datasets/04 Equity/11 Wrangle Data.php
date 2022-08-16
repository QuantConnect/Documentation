<?php
include(DOCS_RESOURCES."/datasets/wrangle-data.php");
$assetClass = 'Equity';
$singularAssetClass = 'Equity';
$pluralAssetClass = 'Equities';
$historicalDataLink = "/docs/v2/research-environment/datasets/equity#04-Get-Historical-Data";
$primarySymbolPy = 'spy';
$primarySymbolC = 'spy';
$primaryTicker = 'SPY';
$secondarySymbol = 'tlt';
$dataFrameImages = array(
    'https://cdn.quantconnect.com/i/tu/us-equity-research-data-1.jpg',
    'https://cdn.quantconnect.com/i/tu/us-equity-research-data-2.jpg',
    'https://cdn.quantconnect.com/i/tu/us-equity-research-data-3.jpg',
    'https://cdn.quantconnect.com/i/tu/us-equity-research-data-4.jpg'
);
$dataFrameColumnName = 'close';
$columnNameEnglish = 'close';
$supportsTrades = true;
$supportsQuotes = true;
$supportsTicks = true;
$supportsAltData = false;
$supportsOpenInterest = false;
$supportsOptionHistory = false;
$supportsFutureHistory = false;
$contractExpiryDate = '';
$getWrangleDataText($assetClass, $singularAssetClass, $pluralAssetClass, $historicalDataLink, $primarySymbolPy, $primarySymbolC, $primaryTicker, $secondarySymbol, $dataFrameImages, $dataFrameColumnName, $columnNameEnglish, $supportsTrades, $supportsQuotes, $supportsTicks, $supportsAltData, $supportsOpenInterest, $supportsOptionHistory, $supportsFutureHistory, $contractExpiryDate);
?>
