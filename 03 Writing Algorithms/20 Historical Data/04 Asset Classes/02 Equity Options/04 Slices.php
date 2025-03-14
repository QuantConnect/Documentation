<?
$symbolC = "var equity = AddEquity(\"SPY\", dataNormalizationMode: DataNormalizationMode.Raw);
        var contract = OptionChain(equity.Symbol).OrderBy(c => c.OpenInterest).Last();
        AddOptionContract(contract.Symbol);";
$symbolPy = "equity = self.add_equity('SPY', data_normalization_mode=DataNormalizationMode.RAW)
        contract = sorted(self.option_chain(equity.symbol), key=lambda c: c.open_interest)[-1]
        self.add_option_contract(contract.symbol)";
$dataType = "TradeBar";

include(DOCS_RESOURCES."/history/slices.php");
?>
