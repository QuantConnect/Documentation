<p>The banner at the top of the backtest results page displays the runtime statistics of your backtest.</p>

<img class='docs-image' src="https://cdn.quantconnect.com/i/tu/runtime-statistics-backtest.png">

<?php 
include(DOCS_RESOURCES."/algorithm-results/runtime-statistics-table.php");
$getRuntimeStatisticsTable(false);

echo file_get_contents(DOCS_RESOURCES."/algorithm-results/create-custom-runtime-statistic.html"); 
?>
