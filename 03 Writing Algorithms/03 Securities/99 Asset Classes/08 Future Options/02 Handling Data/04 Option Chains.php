<?php 
include(DOCS_RESOURCES."/securities/option-chains.php"); 
$path = "regular";
$cSharpMemberName = "_optionContractSymbol.Canonical";
$pythonMemberName = "self.option_contract_symbol.Canonical";
$cSharpVariableName = "canonicalFOPSymbol";
$pythonVariableName = "canonical_fop_symbol";
$getOptionChainsText($path, $cSharpMemberName, $pythonMemberName, $cSharpVariableName, $pythonVariableName);
?>
