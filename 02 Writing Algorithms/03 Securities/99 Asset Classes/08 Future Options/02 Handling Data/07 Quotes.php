<?php 
include(DOCS_RESOURCES."/securities/quotebar.php"); 
$securityName = "Option contract";
$pythonVariable = "self.option_contract_symbol";
$cSharpVariable = "_optionContractSymbol";
$getQuoteBarText($securityName, $pythonVariable, $cSharpVariable);
?>
