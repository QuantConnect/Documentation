<p>Runtime statistics show the performace of your algorithm at a single moment in time.</p>

<h4>Default Runtime Statistics</h4>

<?php 
include(DOCS_RESOURCES."/algorithm-results/runtime-statistics-table.php");
$getRuntimeStatisticsTable(false);
?>

<p>The capacity statistic is only available for live algorithms.</p>

<h4>View Runtime Statistics</h4>
<p>If you execute algorithms in the Algorithm Lab or view the performance of your algorithm with the local GUI, you can access the runtime statistics of your algorithm on the <a href='/docs/v2/our-platform/backtesting/results#03-Runtime-Statistics'>backtest results page</a> on the <a href='/docs/v2/our-platform/live-trading/results#03-Runtime-Statistics'>live results page</a>. If you execute cloud algorithms, you can access the runtime statistics the <a href='/docs/v2/our-platform/api-reference/backtest-management/read-backtest/backtest-statistics'>ReadBacktest</a> or <a href='/docs/v2/our-platform/api-reference/live-management/read-live-algorithm/live-algorithm-statistics'>ReadLive</a> API calls. If you execute local algorithms with the CLI, you can access the runtime statistics in the <span class="private-file-name">&lt;projectName&gt;/backtests/&lt;timestamp&gt;</span> or <span class="private-file-name">&lt;projectName&gt;/live/&lt;timestamp&gt;</span> directories.</p>

<h4>Add Runtime Statistics</h4>

<?php 
echo file_get_contents(DOCS_RESOURCES."/algorithm-results/create-custom-runtime-statistic.html"); 
?>
