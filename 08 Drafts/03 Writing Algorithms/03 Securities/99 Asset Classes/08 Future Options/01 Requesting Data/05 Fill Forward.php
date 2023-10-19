<?php
$cCode = "AddFutureOptionContract(_optionContractSymbol, fillForward: false);";
$pyCode = "self.AddFutureOptionContract(self.option_contract_symbol, fillForward=False)";
include(DOCS_RESOURCES."/securities/fill-forward.php");
?>