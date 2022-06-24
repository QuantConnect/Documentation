<?php 
include(DOCS_RESOURCES."/securities/option-chains.php"); 
$isFutureOptionUniverse = false;
$cSharpMemberName = "_optionContractSymbol.Canonical";
$pythonMemberName = "self.option_contract_symbol.Canonical";
$cSharpVariableName = "underlyingFutureContractSymbol";
$pythonVariableName = "underlying_future_contract_symbol";
$getOptionChainsText($isFutureOptionUniverse, $cSharpMemberName, $pythonMemberName, $cSharpVariableName, $pythonVariableName);
?>
