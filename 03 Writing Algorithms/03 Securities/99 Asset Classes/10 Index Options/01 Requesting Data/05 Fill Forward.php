<?php
$cCode = "AddIndexOptionContract(_contractSymbol, fillDataForward: false);";
$pyCode = "self.AddIndexOptionContract(self.contract_symbol, fillDataForward=False)";
include(DOCS_RESOURCES."/securities/fill-forward.php");
?>