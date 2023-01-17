<p>Runtime statistics show the performace of your algorithm at a single moment in time.</p>

<?php 
include(DOCS_RESOURCES."/algorithm-results/runtime-statistics-table.php");
$getRuntimeStatisticsTable(false);
?>

<p>The capacity statistic is only available for live algorithms.</p>

<?php 
echo file_get_contents(DOCS_RESOURCES."/algorithm-results/create-custom-runtime-statistic.html"); 
?>
