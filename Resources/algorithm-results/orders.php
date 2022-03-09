<?php
$getOrdersText = function($isLiveMode) {
    $pageName = $isLiveMode ? "live" : "backtest";
    
    echo "
        <p>The {$pageName} results page displays the orders of your algorithm and you can download them to your local machine.</p>

        <h4>View in the GUI</h4>
        <p>To see the orders that your algorithm created, open the {$pageName} results page and then click the <span class='tab-name'>Orders</span> tab. If there are more than 10 orders, use the pagination tools at the bottom of the Orders Summary table to see all of the orders. Click on an individual order in the Orders Summary table to reveal additional information regarding the following:</p>

        <ul>
            <li>Submissions<br></li>
            <li>Fills</li>
            <li>Partial fills</li>
            <li>Updates</li>
            <li>Cancellations</li>
            <li>Option contract exercising</li>
        </ul>

        <h4>Download CSV</h4>
        <p>To download the orders in CSV format, open the {$pageName} results page, click the <span class='tab-name'>Orders</span> tab, and then click <span class='button-name'>Download Orders</span>. The content of the CSV file is the content displayed in the Orders Summary table when the table rows are collapsed.
    ";

    if (!$isLiveMode) {
        echo "
             To retrieve all of the content in the Orders Summary table, <a href='/docs/v2/our-platform/backtesting/results#15-Download-Results'>download the backtest results JSON file</a>.
        ";
    }

    echo "</p>";
}
?>