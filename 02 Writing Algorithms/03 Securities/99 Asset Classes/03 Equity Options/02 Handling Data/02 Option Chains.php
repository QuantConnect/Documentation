<?php 
include(DOCS_RESOURCES."/securities/option-chains.php"); 
$isFutureOptionUniverse = false;
$cSharpVariableName = "_contractSymbol.Canonical";
$pythonVariableName = "self.contract_symbol.Canonical";
$getOptionChainsText($isFutureOptionUniverse, $cSharpVariableName, $pythonVariableName);
?>
