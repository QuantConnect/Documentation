<?php
$cCode = "_symbol = AddIndex(\"VIX\", fillForward: false).Symbol;";
$pyCode = "self._symbol = self.add_index(\"VIX\", fill_forward=False).symbol";
include(DOCS_RESOURCES."/securities/fill-forward.php");
?>
