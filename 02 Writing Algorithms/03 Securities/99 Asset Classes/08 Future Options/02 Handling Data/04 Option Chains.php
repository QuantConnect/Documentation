<?php 
include(DOCS_RESOURCES."/securities/option-chains.php"); 
$isFutureOptionUniverse = false;
$cSharpMemberName = "_optionContractSymbol.Canonical";
$pythonMemberName = "self.option_contract_symbol.Canonical";
$cSharpVariableName = "underlyingFutureContractSymbol";
$cSharpVariableName = "underlying_future_contract_symbol";
$getOptionChainsText($isFutureOptionUniverse, $cSharpVariableName, $pythonVariableName);
?>
