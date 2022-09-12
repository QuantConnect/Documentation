<?php
include(DOCS_RESOURCES."/securities/fill-forward.php");
$cCode = "_symbol = AddForex(\"EURUSD\", fillDataForward: false).Symbol;";
$pyCode = "self.symbol = self.AddForex(\"EURUSD\", fillDataForward=False).Symbol";
$getFillForwardText($cCode, $pyCode);
?>