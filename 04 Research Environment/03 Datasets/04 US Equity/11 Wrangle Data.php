<?
$assetClass = 'Equity';
$singularAssetClass = 'Equity';
$pluralAssetClass = 'Equities';
$historicalDataLink = "/docs/v2/research-environment/datasets/us-equity#04-Get-Historical-Data";
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
$cSharpDataFrameImages = array(
    'https://cdn.quantconnect.com/i/tu/us-equity-research-data-c-1.jpg',
    'https://cdn.quantconnect.com/i/tu/us-equity-research-data-c-2.jpg'
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
