<?php 
include(DOCS_RESOURCES."/securities/tick.php"); 
$securityName = "security";
$pythonVariable = "self.symbol";
$cSharpVariable = "_symbol";
$getTickText($securityName, $pythonVariable, $cSharpVariable);
?>
