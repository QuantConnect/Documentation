<?php
$cCode = "AddFutureOptionContract(_optionContractSymbol, fillDataForward: false);";
$pyCode = "self.AddFutureOptionContract(self.option_contract_symbol, fillDataForward=False)";
include(DOCS_RESOURCES."/securities/fill-forward.php");
?>