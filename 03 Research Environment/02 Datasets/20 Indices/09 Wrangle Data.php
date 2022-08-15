<?php
include(DOCS_RESOURCES."/datasets/wrangle-data.php");
$assetClass = 'Index';
$singularAssetClass = 'Index';
$pluralAssetClass = 'Indices';
$historicalDataLink = "https://www.quantconnect.com/docs/v2/research-environment/datasets/indices#04-Get-Historical-Data";
$primarySymbolPy = 'spx';
$primarySymbolC = 'spx';
$primaryTicker = 'SPX';
$secondarySymbol = 'vix';
$dataFrameImages = array(
    'https://cdn.quantconnect.com/i/tu/index-research-data-1.jpg',
    'https://cdn.quantconnect.com/i/tu/index-research-data-2.jpg',
    'https://cdn.quantconnect.com/i/tu/index-research-data-3.jpg',
    'https://cdn.quantconnect.com/i/tu/index-research-data-4.jpg'
);
$dataFrameColumnName = 'close';
$columnNameEnglish = 'close';
$supportsTrades = true;
$supportsQuotes = false;
$supportsTicks = true;
$getWrangleDataText($assetClass, $singularAssetClass, $pluralAssetClass, $historicalDataLink, $primarySymbolPy, $primarySymbolC, $primaryTicker, $secondarySymbol, $dataFrameImages, $dataFrameColumnName, $columnNameEnglish, $supportsTrades, $supportsQuotes, $supportsTicks);
?>

