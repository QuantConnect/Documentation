<?php 
include(DOCS_RESOURCES."/securities/option-chains.php"); 
$isFutureOptionUniverse = false;
$cSharpVariableName = "_optionContractSymbol.Canonical";
$pythonVariableName = "self.option_contract_symbol.Canonical";
$getOptionChainsText($isFutureOptionUniverse, $cSharpVariableName, $pythonVariableName);
?>