<?php 
include(DOCS_RESOURCES."/securities/option-chains.php"); 
$path = "regular";
$cSharpVariableName = "_contractSymbol.Canonical";
$pythonVariableName = "self.contract_symbol.Canonical";
$getOptionChainsText($path, $cSharpVariableName, $pythonVariableName);
?>
