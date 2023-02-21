<?
$assetClass = 'India Equity';
$singularAssetClass = 'India Equity';
$pluralAssetClass = 'India Equities';
$historicalDataLink = "/docs/v2/research-environment/datasets/india-equity#04-Get-Historical-Data";
$primarySymbolPy = 'icicibank';
$primarySymbolC = 'icicibank';
$primaryTicker = 'ICICIBANK';
$secondarySymbol = 'yesbank';
$dataFrameImages = array(
    'https://cdn.quantconnect.com/i/tu/india-equity-research-data-1.png',
    'https://cdn.quantconnect.com/i/tu/india-equity-research-data-2.png',
    'https://cdn.quantconnect.com/i/tu/india-equity-research-data-3.png',
    'https://cdn.quantconnect.com/i/tu/india-equity-research-data-4.png'
);
$cSharpDataFrameImages = array(
    'https://cdn.quantconnect.com/i/tu/india-equity-research-data-c-1.png',
    'https://cdn.quantconnect.com/i/tu/india-equity-research-data-c-2.png'
);
$dataFrameColumnName = 'close';
$columnNameEnglish = 'close';
$supportsTrades = true;
$supportsQuotes = false;
$supportsTicks = false;
$supportsAltData = false;
$supportsOpenInterest = false;
$supportsOptionHistory = false;
$supportsFutureHistory = false;
$contractExpiryDate = '';
include(DOCS_RESOURCES."/datasets/wrangle-data.php");
?>
