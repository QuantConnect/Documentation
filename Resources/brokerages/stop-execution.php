<?php
$getStopExecutionText = function($includeLink=true) {
    echo "<p>";
    if ($includeLink) {
    	echo "<a href='#04-View-Live-Performance'>";
    }
    echo "Open the live performance page";
    if ($includeLink) {
    	echo "</a>";
    }

    echo " of the algorithm that you want to stop, click <span class='button-name'>Stop</span>, and then click <span class='button-name'>OK</span> to stop the live execution. When you stop a live algorithm, your portfolio holdings are retained.</p>";
}
?>
