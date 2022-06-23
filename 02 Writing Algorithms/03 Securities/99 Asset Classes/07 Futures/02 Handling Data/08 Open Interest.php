<?php 
include(DOCS_RESOURCES."/securities/open-interest.php"); 
$contractTypeName = "Future";
$chainTypeName = "FuturesChains";
$variableName = "futuresChains";
$getOpenInterestText($contractTypeName, $chainTypeName, $variableName);
?>



<p class='csharp'>If you define an <code>OnData</code> method that accepts a <code>FuturesChains</code> objects, LEAN doesn't currently call it. We have an open GitHub Issue to add this functionality. Subscribe to <a rel='nofollow' target="_blank" href='https://github.com/QuantConnect/Lean/issues/6419'>GitHub Issue #6419</a> to track the feature progress.</p>
