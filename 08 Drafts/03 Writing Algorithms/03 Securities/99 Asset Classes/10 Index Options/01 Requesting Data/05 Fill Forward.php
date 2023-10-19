<?php
$cCode = "AddIndexOptionContract(_contractSymbol, fillForward: false);";
$pyCode = "self.AddIndexOptionContract(self.contract_symbol, fillForward=False)";
include(DOCS_RESOURCES."/securities/fill-forward.php");
?>