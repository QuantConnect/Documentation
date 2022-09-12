<?php
include(DOCS_RESOURCES."/securities/fill-forward.php");
$cCode = "_symbol = AddCfd(\"XAUUSD\", fillDataForward: false).Symbol;";
$pyCode = "self.symbol = self.AddCfd(\"XAUUSD\", fillDataForward=False).Symbol";
$getFillForwardText($cCode, $pyCode);
?>