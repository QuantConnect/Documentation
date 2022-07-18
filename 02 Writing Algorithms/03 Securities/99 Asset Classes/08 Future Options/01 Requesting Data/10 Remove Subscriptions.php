<?php 
include(DOCS_RESOURCES."/securities/remove-option-contract.php"); 
$pythonVariableName = "self.option_contract_symbol";
$cSharpVariableName = "_optionContractSymbol";
$addContractMethodName = "AddFutureOptionContract";
$getRemoveOptionContractText($pythonVariableName, $cSharpVariableName, $addContractMethodName); 
?>
