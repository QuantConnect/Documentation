<?php
$cCode = "AddFutureContract(_contractSymbol, fillForward: false);";
$pyCode = "self.add_future_contract(self._contract_symbol, fill_forward=False)";
include(DOCS_RESOURCES."/securities/fill-forward.php");
?>
