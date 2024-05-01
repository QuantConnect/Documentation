<?php
$cCode = "_symbol = AddCfd(\"XAUUSD\", fillForward: false).Symbol;";
$pyCode = "self._symbol = self.add_cfd(\"XAUUSD\", fill_forward=False).Symbol";
include(DOCS_RESOURCES."/securities/fill-forward.php");
?>