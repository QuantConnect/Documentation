<?php
include(DOCS_RESOURCES."/datasets/wrangle-data.php");
$assetClass = 'Crypto';
$singularAssetClass = 'Crypto pair';
$pluralAssetClass = 'Crypto pairs';
$historicalDataLink = "https://www.quantconnect.com/docs/v2/research-environment/datasets/crypto#04-Get-Historical-Data";
$primarySymbol = 'btcusd';
$primaryTicker = 'BTCUSD';
$secondarySymbol = 'ethusd';
$dataFrameImages = array(
    'https://cdn.quantconnect.com/i/tu/crypto-research-data-1.jpg',
    'https://cdn.quantconnect.com/i/tu/crypto-research-data-2.jpg',
    'https://cdn.quantconnect.com/i/tu/crypto-research-data-3.jpg',
    'https://cdn.quantconnect.com/i/tu/crypto-research-data-4.jpg'
);
$dataFrameColumnName = 'close';
$columnNameEnglish = 'close';
$supportsTrades = true;
$supportsQuotes = true;
$supportsTicks = true;
$getWrangleDataText($assetClass, $singularAssetClass, $pluralAssetClass, $historicalDataLink, $primarySymbol, $primaryTicker, $secondarySymbol, $dataFrameImages, $dataFrameColumnName, $columnNameEnglish, $supportsTrades, $supportsQuotes, $supportsTicks);
?>
