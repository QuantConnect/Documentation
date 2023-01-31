<?php
$cCode = "_symbol = AddCrypto(\"BTCUSD\", fillDataForward: false).Symbol;";
$pyCode = "self.symbol = self.AddCrypto(\"BTCUSD\", fillDataForward=False).Symbol";
include(DOCS_RESOURCES."/securities/fill-forward.php");
?>