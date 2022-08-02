<?php
include(DOCS_RESOURCES."/datasets/wrangle-data.php");
$assetClass = 'dataset';
$singularAssetClass = 'ticker';
$pluralAssetClass = 'tickers';
$historicalDataLink = "/docs/v2/research-environment/datasets/alternative-data#04-Get-Historical-Data";
$primarySymbol = 'vix';
$primaryTicker = 'VIX';
$secondarySymbol = 'v3m';
$dataFrameImages = array(
    'https://cdn.quantconnect.com/i/tu/alt-data-research-data-1.jpg',
    'https://cdn.quantconnect.com/i/tu/alt-data-research-data-2.jpg',
    'https://cdn.quantconnect.com/i/tu/alt-data-research-data-3.jpg',
    'https://cdn.quantconnect.com/i/tu/alt-data-research-data-4.jpg'
);
$dataFrameColumnName = 'close';
$columnNameEnglish = 'close';
$supportsTrades = false;
$supportsQuotes = false;
$supportsTicks = false;
$supportsAltData = true;
$getWrangleDataText($assetClass, $singularAssetClass, $pluralAssetClass, $historicalDataLink, $primarySymbol, $primaryTicker, $secondarySymbol, $dataFrameImages, $dataFrameColumnName, $columnNameEnglish, $supportsTrades, $supportsQuotes, $supportsTicks, $supportsAltData);
?>
