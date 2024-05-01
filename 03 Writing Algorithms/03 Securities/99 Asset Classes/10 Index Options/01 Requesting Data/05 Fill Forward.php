<?php
$cCode = "AddIndexOptionContract(_contractSymbol, fillForward: false);";
$pyCode = "self.add_index_option_contract(self.contract_symbol, fill_forward=False)";
include(DOCS_RESOURCES."/securities/fill-forward.php");
?>