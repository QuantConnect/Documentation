<?php
include(DOCS_RESOURCES."/securities/fill-forward.php");
$cCode = "AddOptionContract(_contractSymbol, fillDataForward: false);";
$pyCode = "self.AddOptionContract(self.contract_symbol, fillDataForward=False)";
$getFillForwardText($cCode, $pyCode);
?>