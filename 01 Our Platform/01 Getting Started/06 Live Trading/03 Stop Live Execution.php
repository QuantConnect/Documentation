<?php
echo file_get_contents(DOCS_RESOURCES."/brokerages/view-live-performance.php");
include(DOCS_RESOURCES."/brokerages/stop-execution.php");
$getStopExecutionText(false);
?>
