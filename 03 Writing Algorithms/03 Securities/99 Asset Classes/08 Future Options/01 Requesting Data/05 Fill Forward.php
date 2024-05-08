<?php
$cCode = "AddFutureOptionContract(_optionContractSymbol, fillForward: false);";
$pyCode = "self.add_future_option_contract(self._option_contract_symbol, fill_forward=False)";
include(DOCS_RESOURCES."/securities/fill-forward.php");
?>
