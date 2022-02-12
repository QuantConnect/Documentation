<?php
$getDataFeedLatencyText = function($dataFeedName) {
    echo "<p>Live data takes time to travel from the source to your algorithm. The $dataFeedName data feed has a latency of 20-50 milliseconds. QuantConnect is not designed for high-frequency trading.</p>";
}
?>
