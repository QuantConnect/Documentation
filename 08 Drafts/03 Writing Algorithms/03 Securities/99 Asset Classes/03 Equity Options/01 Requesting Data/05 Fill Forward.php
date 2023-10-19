<?php
$cCode = "AddOptionContract(_contractSymbol, fillForward: false);";
$pyCode = "self.AddOptionContract(self.contract_symbol, fillForward=False)";
include(DOCS_RESOURCES."/securities/fill-forward.php");
?>