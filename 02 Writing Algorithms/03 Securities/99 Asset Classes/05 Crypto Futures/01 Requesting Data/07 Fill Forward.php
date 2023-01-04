<?php
include(DOCS_RESOURCES."/securities/fill-forward.php");
$cCode = "_symbol = AddCryptoFuture(\"BTCUSD\", fillDataForward: false).Symbol;";
$pyCode = "self.symbol = self.AddCryptoFuture(\"BTCUSD\", fillDataForward=False).Symbol";
$getFillForwardText($cCode, $pyCode);
?>