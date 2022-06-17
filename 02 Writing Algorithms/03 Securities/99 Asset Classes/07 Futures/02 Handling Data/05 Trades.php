<?php 
include(DOCS_RESOURCES."/securities/tradebar.php"); 
$securityName = "contract";
$pythonVariable = "self.contract_symbol
data[future.Symbol].Price # raw contract price
data[future.Mapped] # continous contract price w/ adjustments";
$cSharpVariable = "_contractSymbol";
$getTradeBarText($securityName, $pythonVariable, $cSharpVariable);
?>
