<?php
$cCode = "AddOptionContract(_contractSymbol, fillForward: false);";
$pyCode = "self.add_option_contract(self._contract_symbol, fill_forward=False)";
include(DOCS_RESOURCES."/securities/fill-forward.php");
?>
