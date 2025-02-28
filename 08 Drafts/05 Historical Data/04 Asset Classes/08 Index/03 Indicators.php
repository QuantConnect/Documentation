
<?
$symbolC = "AddIndex(\"SPX\").Symbol";
$targetSymbolC = "AddIndex(\"NDX\").Symbol";
$symbolPy = "self.add_index('SPX').symbol";
$targetSymbolPy = "self.add_index('NDX').symbol";
$assetClass = "Index";
$supportsTradeData = true;
$dataFrame = "<div class='dataframe-wrapper'>

</div>";

include(DOCS_RESOURCES."/history/indicators.php");
?>
