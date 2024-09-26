<?
$memberDeclarationsAutomaticC = "private Symbol _underlying;
";
$memberDeclarationsManualC = "private Symbol _underlying;
    private DividendYieldProvider _dividendYieldProvider;
";
$underlyingSubscriptionC = "_underlying = AddEquity(\"SPY\", dataNormalizationMode: DataNormalizationMode.Raw).Symbol;
";
$dividendYieldProviderConstructorC = "new(_underlying);";
$scheduleSymbolC = "_underlying";
$underlyingSymbolC = "_underlying";
$addContractMethodC = "AddOptionContract";
$mirrorExtensionC = "";
$underlyingSubscriptionPy = "self._underlying = self.add_equity('SPY', data_normalization_mode=DataNormalizationMode.RAW).symbol
";
$dividendYieldProviderConstructorPy = "DividendYieldProvider(self._underlying)";
$scheduleSymbolPy = "self._underlying";
$underlyingSymbolPy = "self._underlying";
$addContractMethodPy = "add_option_contract";
$mirrorExtensionPy = "";
?>
