<?php
include(DOCS_RESOURCES."/cli/install/docker/introduction-1.php");
$isCLIDocs = false;
$getIntroText($isCLIDocs);

echo file_get_contents(DOCS_RESOURCES."/cli/install/docker/introduction-2.html");
?>