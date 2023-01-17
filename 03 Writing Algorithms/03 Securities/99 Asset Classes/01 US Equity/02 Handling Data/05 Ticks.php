<?php 
include(DOCS_RESOURCES."/securities/tick.php"); 
$securityName = "security";
$pythonVariable = "self.symbol";
$cSharpVariable = "_symbol";
$getTickText($securityName, $pythonVariable, $cSharpVariable);
?>

<p>If <a href='/docs/v2/our-platform/live-trading/data-feeds/us-equities#05-Suspicious-Ticks'>we detect a tick that may be suspicious</a>, we set its <code>Suspicious</code> flag to true.</p>
