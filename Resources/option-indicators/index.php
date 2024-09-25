<?
$memberDeclarationsAutomaticC = "private Symbol _underlying;
";
$memberDeclarationsManualC = "private Symbol _underlying;
    private DividendYieldProvider _dividendYieldProvider;
";
$underlyingSubscriptionC = "_underlying = AddIndex(\"SPX\").Symbol;
";
$dividendYieldProviderConstructorC = "new(_underlying);";
$scheduleSymbolC = "_underlying";
$underlyingSymbolC = "_underlying";
$mirrorExtensionC = "";
$underlyingSubscriptionPy = "self._underlying = self.add_index('SPX').symbol
";
$dividendYieldProviderConstructorPy = "DividendYieldProvider(self._underlying)";
$scheduleSymbolPy = "self._underlying";
$underlyingSymbolPy = "self._underlying";
$mirrorExtensionPy = "";
?>
