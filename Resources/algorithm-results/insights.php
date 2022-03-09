<?php
$getInsightsText = function($isLiveMode) {
    $pageName = $isLiveMode ? "live" : "backtest";

    echo "
        <p>The {$pageName} results page displays the insights of your algorithm and you can download them to your local machine.</p>

        <h4>View in the GUI</h4>
        <p>To see the insights you algorithm has emit, open the {$pageName} result page and then click the <span class='tab-name'>Insights</span> tab. If there are more than 10 insights, use the pagination tools at the bottom of the Insights Summary table to see all of the insights.</p>


        <h4>Download JSON</h4>
        <p>To download the insights in JSON format, open the {$pageName} result page, click the <span class='tab-name'>Insights</span> tab, and then click <span class='button-name'>Download Insights</span>.</p>

    ";
}
?>