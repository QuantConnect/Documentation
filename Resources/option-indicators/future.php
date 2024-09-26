<?
$memberDeclarationsAutomaticC = "private Future _future;
";
$memberDeclarationsManualC = "private Future _future;
    private ConstantDividendYieldModel _dividendYieldProvider;
";
$underlyingSubscriptionC = "_future = AddFuture(Futures.Indices.SP500EMini, 
            dataNormalizationMode: DataNormalizationMode.BackwardsRatio,
            dataMappingMode: DataMappingMode.OpenInterest,
            contractDepthOffset: 0);
        _future.SetFilter(0, 182);
";
$dividendYieldProviderConstructorC = "new ConstantDividendYieldModel(0m);";
$scheduleSymbolC = "_future.Symbol";
$underlyingSymbolC = "_future.Mapped";
$addContractMethodC = "AddFutureOptionContract";
$mirrorExtensionC = ".Value";
$underlyingSubscriptionPy = "self._future = self.add_future(
            Futures.Indices.SP_500_E_MINI,
            data_mapping_mode=DataMappingMode.OPEN_INTEREST,
            data_normalization_mode=DataNormalizationMode.BACKWARDS_RATIO,
            contract_depth_offset=0
        )
        self._future.set_filter(0, 182)
";
$dividendYieldProviderConstructorPy = "ConstantDividendYieldModel(0)";
$scheduleSymbolPy = "self._future.symbol";
$underlyingSymbolPy = "self._future.mapped";
$addContractMethodC = "add_future_option_contract";
$mirrorExtensionPy = ".value";
?>
