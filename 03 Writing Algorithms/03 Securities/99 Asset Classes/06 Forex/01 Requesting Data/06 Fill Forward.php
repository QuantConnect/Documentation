<?php
$cCode = "_symbol = AddForex(\"EURUSD\", fillForward: false).Symbol;";
$pyCode = "self._symbol = self.add_forex(\"EURUSD\", fill_forward=False).Symbol";
include(DOCS_RESOURCES."/securities/fill-forward.php");
?>