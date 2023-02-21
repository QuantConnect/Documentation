<?
$assetClass = 'Index';
$singularAssetClass = 'Index';
$pluralAssetClass = 'Indices';
$historicalDataLink = "/docs/v2/research-environment/datasets/indices#04-Get-Historical-Data";
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
$cSharpDataFrameImages = array(
    'https://cdn.quantconnect.com/i/tu/index-research-data-c-1.png',
    'https://cdn.quantconnect.com/i/tu/index-research-data-c-2.png'
);
$dataFrameColumnName = 'close';
$columnNameEnglish = 'close';
$supportsTrades = true;
$supportsQuotes = false;
$supportsTicks = true;
$supportsAltData = false;
$supportsOpenInterest = false;
$supportsOptionHistory = false;
$supportsFutureHistory = false;
$contractExpiryDate = '';
include(DOCS_RESOURCES."/datasets/wrangle-data.php");
?>

