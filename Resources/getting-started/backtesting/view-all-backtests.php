<?php
$openProjectLink = $cloudPlatform ? "/docs/v2/cloud-platform/projects/getting-started#02-View-All-Projects" : "/docs/v2/local-platform/projects/getting-started#02-View-All-Projects";
$imgLink = $cloudPlatform ? "https://cdn.quantconnect.com/i/tu/backtest-results-view-page.png" : "https://cdn.quantconnect.com/i/tu/local-platform-view-all-backtests.png";
?>

<p>Follow these steps to view all of the backtests of a project:</p>

<ol>
    <li><a href="<?=$openProjectLink?>">Open the project</a> that contains the backtests you want to view.</li>
    <li>In the top-right corner of the IDE, click the <?php if ($localPlatform) {?><img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/local-platform-backtest-results-icon.png'> / <?php } ?><img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/cloud-platform-backtest-results-icon.png'> <span class="icon-name">Backtest Results</span> icon.<br></li>
    <p>A table containing all of the backtest results for the project is displayed. If there is a <span class="icon-name">play</span> icon to the left of the name, it's a <a href="https://www.quantconnect.com/docs/v2/cloud-platform/backtesting/results">backtest result</a>. If there is a <span class="icon-name">fast-forward</span> icon next to the name, it's an <a href="/docs/v2/cloud-platform/optimization/results">optimization result</a>.<br></p>
    <img class="docs-image" src="<?=$imgLink?>" alt="All backtest table view">
    <li><span class="qualifier">(Optional)</span> In the top-right corner, select the <span class="field-name">Show</span> field and then select one of the options from the drop-down menu to filter the table by backtest or optimization results.</li>
    <li><span class="qualifier">(Optional)</span> In the bottom-right corner, click the <span class="box-name">Hide Error</span> check box to remove backtest and optimization results from the table that had a runtime error.</li>
    <li><span class="qualifier">(Optional)</span> Use the pagination tools at the bottom to change the page.</li>
    <li><span class="qualifier">(Optional)</span> Click a column name to sort the table by that column.</li>
    <li>Click a row in the table to open the results page of that backtest or optimization.</li>
</ol>
