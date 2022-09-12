<?php
include(DOCS_RESOURCES."/securities/fill-forward.php");
$cCode = "AddIndexOptionContract(_contractSymbol, fillDataForward: false);";
$pyCode = "self.AddIndexOptionContract(self.contract_symbol, fillDataForward=False)";
$getFillForwardText($cCode, $pyCode);
?>