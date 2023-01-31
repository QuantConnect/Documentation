<?php
$cCode = "_symbol = AddEquity(\"YESBANK\", market: Market.India, fillDataForward: false).Symbol;";
$pyCode = "self.symbol = self.AddEquity(\"YESBANK\", market=Market.India, fillDataForward=False).Symbol";
include(DOCS_RESOURCES."/securities/fill-forward.php");
?>