<?php 
include(DOCS_RESOURCES."/scheduled-events/execution-sequence.php"); 
$linkScheduledEvents = true;
$getExecutionText($linkScheduledEvents);
?>

<p>The consolidators are called in the order that they are updated. If you register them for automatic updates, they are updated in the order that you register them.</p>