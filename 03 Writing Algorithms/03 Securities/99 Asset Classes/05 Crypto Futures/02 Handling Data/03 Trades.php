<?php 
include(DOCS_RESOURCES."/securities/tradebar.php"); 
$securityName = "security";
$pythonVariable = "self.symbol";
$cSharpVariable = "_symbol";
$getTradeBarText($securityName, $pythonVariable, $cSharpVariable);
?>
