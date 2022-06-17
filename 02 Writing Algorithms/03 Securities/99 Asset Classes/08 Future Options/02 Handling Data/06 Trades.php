<?php 
include(DOCS_RESOURCES."/securities/tradebar.php"); 
$securityName = "Option contract";
$pythonVariable = "self.option_contract_symbol";
$cSharpVariable = "_optionContractSymbol";
$getTradeBarText($securityName, $pythonVariable, $cSharpVariable);
?>
