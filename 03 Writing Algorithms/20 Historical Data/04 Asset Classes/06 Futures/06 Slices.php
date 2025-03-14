<?
$symbolC = "var future = AddFuture(Futures.Indices.SP500EMini);
        var symbol = FutureChainProvider.GetFutureContractList(future.Symbol, Time)
            .OrderBy(symbol => symbol.ID.Date).First();
        AddFutureContract(symbol);";
$symbolPy = "future = self.add_future(Futures.Indices.SP_500_E_MINI)
        contract_symbols = self.future_chain_provider.get_future_contract_list(future.symbol, self.time)
        symbol = sorted(contract_symbols, key=lambda symbol: symbol.id.date)[0]
        self.add_future_contract(symbol)";
$dataType = "TradeBar";

include(DOCS_RESOURCES."/history/slices.php");
?>
