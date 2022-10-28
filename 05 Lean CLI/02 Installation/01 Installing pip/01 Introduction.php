<?php
include(DOCS_RESOURCES."/cli/install/pip/introduction-1.php");
$isCLIDocs = true;
$getIntroText($isCLIDocs);

echo file_get_contents(DOCS_RESOURCES."/cli/install/pip/introduction-2.html");
?>