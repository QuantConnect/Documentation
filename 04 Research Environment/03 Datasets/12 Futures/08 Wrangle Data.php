<?php
$assetClass = 'Futures';
$singularAssetClass = 'Futures contract';
$pluralAssetClass = 'Futures contracts';
$historicalDataLink = "/docs/v2/research-environment/datasets/futures#04-Get-Historical-Data";
$primarySymbolPy = 'contract_symbol';
$primarySymbolC = 'contractSymbol';
$primaryTicker = '';
$secondarySymbol = '';
$dataFrameImages = array(
    'https://cdn.quantconnect.com/i/tu/futures-research-data-1.jpg',
    'https://cdn.quantconnect.com/i/tu/futures-research-data-2.jpg',
    'https://cdn.quantconnect.com/i/tu/futures-research-data-3.jpg',
    'https://cdn.quantconnect.com/i/tu/futures-research-data-4.jpg',
    'https://cdn.quantconnect.com/i/tu/futures-research-data-5.jpg',
    'https://cdn.quantconnect.com/i/tu/futures-research-data-6.jpg'
);
$cSharpDataFrameImages = array(
    'https://cdn.quantconnect.com/i/tu/futures-research-data-c-1.png',
    'https://cdn.quantconnect.com/i/tu/futures-research-data-c-2.png'
);
$dataFrameColumnName = 'close';
$columnNameEnglish = 'close';
$supportsTrades = true;
$supportsQuotes = true;
$supportsTicks = true;
$supportsAltData = false;
$supportsOpenInterest = true;
$supportsOptionHistory = false;
$supportsFutureHistory = true;
$contractExpiryDate = 'datetime(2022, 3, 18, 13, 30)';
include(DOCS_RESOURCES."/datasets/wrangle-data.php");
?>
