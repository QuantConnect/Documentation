<?php
$cCode = "_symbol = AddIndex(\"VIX\", fillForward: false).Symbol;";
$pyCode = "self._symbol = self.AddIndex(\"VIX\", fillForward=False).Symbol";
include(DOCS_RESOURCES."/securities/fill-forward.php");
?>