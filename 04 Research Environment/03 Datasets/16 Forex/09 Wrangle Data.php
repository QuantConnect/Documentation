<?
$assetClass = 'Forex';
$singularAssetClass = 'Forex pair';
$pluralAssetClass = 'Forex pairs';
$historicalDataLink = "/docs/v2/research-environment/datasets/forex#04-Get-Historical-Data";
$primarySymbolPy = 'eurusd';
$primarySymbolC = 'eurusd';
$primaryTicker = 'EURUSD';
$secondarySymbol = 'gbpusd';
$dataFrameImages = array(
    'https://cdn.quantconnect.com/i/tu/forex-research-data-1.jpg',
    'https://cdn.quantconnect.com/i/tu/forex-research-data-2.jpg',
    'https://cdn.quantconnect.com/i/tu/forex-research-data-3.jpg',
    'https://cdn.quantconnect.com/i/tu/forex-research-data-4.jpg'
);
$dataFrameColumnName = 'close';
$columnNameEnglish = 'close';
$supportsTrades = false;
$supportsQuotes = true;
$supportsTicks = true;
$supportsAltData = false;
$supportsOpenInterest = false;
$supportsOptionHistory = false;
$supportsFutureHistory = false;
$contractExpiryDate = '';
include(DOCS_RESOURCES."/datasets/wrangle-data.php");
?>


