<?php
$cCode = "_symbol = AddIndex(\"VIX\", fillDataForward: false).Symbol;";
$pyCode = "self.symbol = self.AddIndex(\"VIX\", fillDataForward=False).Symbol";
include(DOCS_RESOURCES."/securities/fill-forward.php");
?>