<?
$assetClass = 'Crypto';
$singularAssetClass = 'Crypto pair';
$pluralAssetClass = 'Crypto pairs';
$historicalDataLink = "/docs/v2/research-environment/datasets/crypto#04-Get-Historical-Data";
$primarySymbolPy = 'btcusd';
$primarySymbolC = 'btcusd';
$primaryTicker = 'BTCUSD';
$secondarySymbol = 'ethusd';
$dataFrameImages = array(
    'https://cdn.quantconnect.com/i/tu/crypto-research-data-1.jpg',
    'https://cdn.quantconnect.com/i/tu/crypto-research-data-2.jpg',
    'https://cdn.quantconnect.com/i/tu/crypto-research-data-3.jpg',
    'https://cdn.quantconnect.com/i/tu/crypto-research-data-4.jpg'
);
$cSharpDataFrameImages = array(
    'https://cdn.quantconnect.com/i/tu/crypto-research-data-c-1.png',
    'https://cdn.quantconnect.com/i/tu/crypto-research-data-c-2.png'
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
include(DOCS_RESOURCES."/datasets/wrangle-data.php");
?>
