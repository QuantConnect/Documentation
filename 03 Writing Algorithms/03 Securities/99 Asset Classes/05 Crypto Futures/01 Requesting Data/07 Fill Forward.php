<?php
$cCode = "_symbol = AddCryptoFuture(\"BTCUSD\", fillForward: false).Symbol;";
$pyCode = "self._symbol = self.add_crypto_future(\"BTCUSD\", fill_forward=False).symbol";
include(DOCS_RESOURCES."/securities/fill-forward.php");
?>
