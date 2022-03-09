<?php
$getStopExecutionText = function($livePerformanceLink) {
    echo "
        <p>Follow these steps to stop a live algorithm:</p>
        <ul>
            <li>Open the <a href='{$livePerformanceLink}'>live performance page</a> of the algorithm.</li>
            <li>Click <span class='button-name'>Stop</span>.</li>
            <li>Click <span class='button-name'>Yes</span></li>
        </ul>
        <p>When you stop a live algorithm, your portfolio holdings are retained.</p>
    ";
}
?>
