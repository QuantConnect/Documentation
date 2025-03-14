<?
$symbolC = "var index = AddIndex(\"SPX\");
        var contract = OptionChain(index.Symbol).OrderBy(c => c.OpenInterest).Last();
        AddOptionContract(contract.Symbol);";
$symbolPy = "index = self.add_index('SPX')
        contract = sorted(self.option_chain(index.symbol), key=lambda c: c.open_interest)[-1]
        self.add_option_contract(contract.symbol)";
$dataType = "TradeBar";

include(DOCS_RESOURCES."/history/slices.php");
?>
