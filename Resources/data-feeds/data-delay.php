<?php
$getDataFeedDataDelayText = function($prependText=null) {
	echo "<p>";
	if ($prependText) {
		echo $prependText;
		echo " ";
	}
    echo "Live trading algorithms run on co-located servers racked in Equinix, but QuantConnect is not designed for high-frequency trading. Co-location reduces several factors that can interfere with your algorithm, including downtime from internet outages, equipment repairs, and natural disasters.</p>";
}
?>
