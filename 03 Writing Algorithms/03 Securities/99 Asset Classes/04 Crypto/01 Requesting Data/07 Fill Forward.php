<?php
$cCode = "_symbol = AddCrypto(\"BTCUSD\", fillForward: false).Symbol;";
$pyCode = "self._symbol = self.add_crypto(\"BTCUSD\", fill_forward=False).Symbol";
include(DOCS_RESOURCES."/securities/fill-forward.php");
?>