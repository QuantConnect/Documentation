<?php
$getMarketsText = function($isWritingAlgorithms)
{
    $cVariableName = $isWritingAlgorithms ? "" : "qb." ;
    $pyVariableName = $isWritingAlgorithms ? "self" : "qb" ;
    $cAssignmentName = $isWritingAlgorithms ? "_symbol" : "var btcusd" ;
    $pyAssignmentName = $isWritingAlgorithms ? "self.symbol" : "btcusd" ;
    
    echo "
<p>The following <code>Market</code> enumeration members are available for Crypto:</p>

<div data-tree='QuantConnect.Market' data-fields='Bitfinex,GDAX,Kraken,Binance,FTX,FTXUS,BinanceUS'></div>

<p>To set the market for a security, pass a <code>market</code> argument to the <code>AddCrypto</code> method.</p>

<div class='section-example-container'>
    <pre class='csharp'>{$cAssignmentName} = {$cVariableName}AddCrypto(\"BTCUSD\", market: Market.GDAX).Symbol;</pre>
    <pre class='python'>{$pyAssignmentName} = {$pyVariableName}.AddCrypto(\"BTCUSD\", market=Market.GDAX).Symbol</pre>
</div>    
    
";  
}
?>


