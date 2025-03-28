<?
$symbolC = "var future = AddFuture(Futures.Indices.SP500EMini);
        var symbol = FuturesChain(future.Symbol).First().Symbol;";
$symbolPy = "future = self.add_future(Futures.Indices.SP_500_E_MINI)
        symbol = list(self.futures_chain(future.symbol))[0].symbol";
$dataType = "TradeBar";

include(DOCS_RESOURCES."/history/slices.php");
?>
