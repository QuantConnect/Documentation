<?php 
include(DOCS_RESOURCES."/securities/tick.php"); 
$securityName = "CFD";
$pythonVariable = "self.symbol";
$cSharpVariable = "_symbol";
$getTickText($securityName, $pythonVariable, $cSharpVariable);
?>
