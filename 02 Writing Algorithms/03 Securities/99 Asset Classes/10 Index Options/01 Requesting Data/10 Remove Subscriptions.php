<?php 
include(DOCS_RESOURCES."/securities/remove-option-contract.php"); 
$pythonVariableName = "self.contract_symbol";
$cSharpVariableName = "_contractSymbol";
$addContractMethodName = "AddIndexOptionContract";
$getRemoveOptionContractText($pythonVariableName, $cSharpVariableName, $addContractMethodName); 
?>
