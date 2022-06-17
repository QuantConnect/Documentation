<?php 
include(DOCS_RESOURCES."/securities/tradebar.php"); 
$securityName = "contract";
$pythonVariable = "self.contract_symbol";
$cSharpVariable = "_contractSymbol";
$getTradeBarText($securityName, $pythonVariable, $cSharpVariable);
?>
