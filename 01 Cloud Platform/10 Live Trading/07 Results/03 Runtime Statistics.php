<p>The banner at the top of the live results page displays the performance statistics of your algorithm.</p>

<img class='docs-image' src="https://cdn.quantconnect.com/i/tu/runtime-statistics-live-1.png" alt="Live runtime statistics">

<?php 
$pageName = "live";
include(DOCS_RESOURCES."/algorithm-results/runtime-statistics-table.php");
include(DOCS_RESOURCES."/algorithm-results/create-custom-runtime-statistic.php"); 
?>

<p>If you <a href='/docs/v2/cloud-platform/live-trading/algorithm-control#05-Stop-the-Algorithm'>stop</a> and redeploy a live algorithm, the runtime statistics are reset.</p>
