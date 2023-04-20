<?php
$cCode = "_symbol = AddCfd(\"XAUUSD\", fillForward: false).Symbol;";
$pyCode = "self.symbol = self.AddCfd(\"XAUUSD\", fillForward=False).Symbol";
include(DOCS_RESOURCES."/securities/fill-forward.php");
?>