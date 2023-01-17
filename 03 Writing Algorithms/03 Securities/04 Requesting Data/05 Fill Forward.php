<?php
include(DOCS_RESOURCES."/securities/fill-forward.php");
$cCode = "AddEquity(\"SPY\", fillDataForward: false);";
$pyCode = "self.AddEquity(\"SPY\", fillDataForward=False)";
$getFillForwardText($cCode, $pyCode);
?>