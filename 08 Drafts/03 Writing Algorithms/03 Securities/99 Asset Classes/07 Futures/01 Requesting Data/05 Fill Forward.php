<?php
$cCode = "AddFutureContract(_contractSymbol, fillForward: false);";
$pyCode = "self.AddFutureContract(self.contract_symbol, fillForward=False)";
include(DOCS_RESOURCES."/securities/fill-forward.php");
?>