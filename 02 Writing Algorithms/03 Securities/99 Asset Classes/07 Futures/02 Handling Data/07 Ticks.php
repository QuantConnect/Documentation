<?php 
include(DOCS_RESOURCES."/securities/tick.php"); 
$securityName = "contract";
$pythonVariable = "self.contract_symbol";
$cSharpVariable = "_contractSymbol";
$getTickText($securityName, $pythonVariable, $cSharpVariable);
?>
