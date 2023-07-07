<p>Runtime statistics show the performace of your algorithm at a single moment in time.</p>

<?php 
$pageName = "backtest";
include(DOCS_RESOURCES."/algorithm-results/runtime-statistics-table.php");
?>

<p>The capacity statistic is only available for backtests.</p>

<?php 
include(DOCS_RESOURCES."/algorithm-results/create-custom-runtime-statistic.php"); 
?>
