<?php
$cCode = "_symbol = AddEquity(\"YESBANK\", market: Market.India, fillForward: false).Symbol;";
$pyCode = "self.symbol = self.AddEquity(\"YESBANK\", market=Market.India, fillForward=False).Symbol";
include(DOCS_RESOURCES."/securities/fill-forward.php");
?>