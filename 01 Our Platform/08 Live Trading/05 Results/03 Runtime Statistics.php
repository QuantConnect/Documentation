<p>The banner at the top of the live results page displays the performance statistics of your algorithm.</p>

<img class='docs-image' src="https://cdn.quantconnect.com/i/tu/runtime-statistics-live-1.png">

<?php 
include(DOCS_RESOURCES."/algorithm-results/runtime-statistics-table.php");
$getRuntimeStatisticsTable(true);

echo file_get_contents(DOCS_RESOURCES."/algorithm-results/create-custom-runtime-statistic.html"); 
?>
