<?php
$getBrokerageLatencyText = function($brokerageName, $brokerageFileName) {
   echo "<p>Brokerage latency is amount of time it takes for messages like orders to travel between your algorithm and the brokerage. The brokerage latency of $brokerageName is ";

   echo file_get_contents(DOCS_RESOURCES."/brokerages/latency-by-brokerage/$brokerageFileName.php");

   echo ".</p>";
}
?>
