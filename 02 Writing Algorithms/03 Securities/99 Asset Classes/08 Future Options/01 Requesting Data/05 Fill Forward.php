<?php
include(DOCS_RESOURCES."/securities/fill-forward.php");
$cCode = "AddFutureOptionContract(_optionContractSymbol, fillDataForward: false);";
$pyCode = "self.AddFutureOptionContract(self.option_contract_symbol, fillDataForward=False)";
$getFillForwardText($cCode, $pyCode);
?>