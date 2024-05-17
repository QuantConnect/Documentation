<?php
$cCode = "_symbol = AddCrypto(\"BTCUSD\", fillForward: false).Symbol;";
$pyCode = "self._symbol = self.add_crypto(\"BTCUSD\", fill_forward=False).symbol";
include(DOCS_RESOURCES."/securities/fill-forward.php");
?>
