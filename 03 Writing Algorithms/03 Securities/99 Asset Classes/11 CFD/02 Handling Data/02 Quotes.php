<?php 
include(DOCS_RESOURCES."/securities/quotebar.php"); 
$securityName = "CFD";
$pythonVariable = "self.symbol";
$cSharpVariable = "_symbol";
$getQuoteBarText($securityName, $pythonVariable, $cSharpVariable);
?>
