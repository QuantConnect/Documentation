<?php
echo file_get_contents(DOCS_RESOURCES."/cli/install/docker/windows-1.html");

include(DOCS_RESOURCES."/cli/install/docker/windows-2.php");
$isCLIDocs = true;
$getInstallWindowsText($isCLIDocs);
?>