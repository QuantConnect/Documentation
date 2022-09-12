<?php
include(DOCS_RESOURCES."/securities/fill-forward.php");
$cCode = "AddFutureContract(_contractSymbol, fillDataForward: false);";
$pyCode = "self.AddFutureContract(self.contract_symbol, fillDataForward=False)";
$getFillForwardText($cCode, $pyCode);
?>