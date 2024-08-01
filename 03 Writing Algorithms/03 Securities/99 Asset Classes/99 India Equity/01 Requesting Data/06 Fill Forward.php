<?php
$cCode = "_symbol = AddEquity(\"YESBANK\", market: Market.India, fillForward: false).Symbol;";
$pyCode = "self._symbol = self.add_equity(\"YESBANK\", market=Market.INDIA, fill_forward=False).symbol";
include(DOCS_RESOURCES."/securities/fill-forward.php");
?>
