<?php
$cCode = "_symbol = AddCryptoFuture(\"BTCUSD\", fillForward: false).Symbol;";
$pyCode = "self.symbol = self.AddCryptoFuture(\"BTCUSD\", fillForward=False).Symbol";
include(DOCS_RESOURCES."/securities/fill-forward.php");
?>