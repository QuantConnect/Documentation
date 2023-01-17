<?php 
include(DOCS_RESOURCES."/securities/quotebar.php"); 
$securityName = "Forex pair";
$pythonVariable = "self.symbol";
$cSharpVariable = "_symbol";
$getQuoteBarText($securityName, $pythonVariable, $cSharpVariable);
?>
