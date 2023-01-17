<?php 
include(DOCS_RESOURCES."/securities/tick.php"); 
$securityName = "Forex pair";
$pythonVariable = "self.symbol";
$cSharpVariable = "_symbol";
$getTickText($securityName, $pythonVariable, $cSharpVariable);
?>
