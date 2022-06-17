<?php 
include(DOCS_RESOURCES."/securities/quotebar.php"); 
$securityName = "contract";
$pythonVariable = "self.contract_symbol";
$cSharpVariable = "_contractSymbol";
$getQuoteBarText($securityName, $pythonVariable, $cSharpVariable);
?>
