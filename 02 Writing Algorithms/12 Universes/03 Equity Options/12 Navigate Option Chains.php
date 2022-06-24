<?php 
include(DOCS_RESOURCES."/securities/option-chains.php"); 
$isFutureOptionUniverse = false;
$pythonMemberName = "self.symbol";
$cSharpMemberName = "_symbol";
$getOptionChainsText($isFutureOptionUniverse, $pythonMemberName, $cSharpMemberName);
?>
