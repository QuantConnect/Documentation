<?php
$cCode = "_symbol = AddForex(\"EURUSD\", fillForward: false).Symbol;";
$pyCode = "self.symbol = self.AddForex(\"EURUSD\", fillForward=False).Symbol";
include(DOCS_RESOURCES."/securities/fill-forward.php");
?>