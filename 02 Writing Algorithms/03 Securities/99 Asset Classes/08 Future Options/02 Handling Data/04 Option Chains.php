<?php 
include(DOCS_RESOURCES."/securities/option-chains.php"); 
$path = "regular";
$cSharpMemberName = "_optionContractSymbol.Canonical";
$pythonMemberName = "self.option_contract_symbol.Canonical";
$cSharpVariableName = "underlyingFutureContractSymbol";
$pythonVariableName = "underlying_future_contract_symbol";
$getOptionChainsText($regular, $cSharpMemberName, $pythonMemberName, $cSharpVariableName, $pythonVariableName);
?>
