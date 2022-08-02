<?php
include(DOCS_RESOURCES."/datasets/wrangle-data.php");
$assetClass = 'CFD';
$singularAssetClass = 'CFD contract';
$pluralAssetClass = 'CFD contracts';
$historicalDataLink = "https://www.quantconnect.com/docs/v2/research-environment/datasets/cfd#04-Get-Historical-Data";
$primarySymbol = 'spx';
$primaryTicker = 'SPX500USD';
$secondarySymbol = 'usb';
$dataFrameImages = array(
    'https://cdn.quantconnect.com/i/tu/cfd-research-data-1.jpg',
    'https://cdn.quantconnect.com/i/tu/cfd-research-data-2.jpg',
    'https://cdn.quantconnect.com/i/tu/cfd-research-data-3.jpg',
    'https://cdn.quantconnect.com/i/tu/cfd-research-data-4.jpg'
);
$dataFrameColumnName = 'close';
$columnNameEnglish = 'close';
$supportsTrades = false;
$supportsQuotes = true;
$supportsTicks = true;
$getWrangleDataText($assetClass, $singularAssetClass, $pluralAssetClass, $historicalDataLink, $primarySymbol, $primaryTicker, $secondarySymbol, $dataFrameImages, $dataFrameColumnName, $columnNameEnglish, $supportsTrades, $supportsQuotes, $supportsTicks);
?>
