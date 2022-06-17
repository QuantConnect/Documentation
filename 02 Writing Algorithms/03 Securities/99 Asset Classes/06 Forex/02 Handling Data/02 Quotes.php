<?php 
include(DOCS_RESOURCES."/securities/quotebar.php"); 
$securityName = "security";
$pythonVariable = "self.symbol";
$cSharpVariable = "_symbol";
$getQuoteBarText($securityName, $pythonVariable, $cSharpVariable);
?>