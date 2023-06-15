<p>Follow these steps to view all of the optimization results of a project:</p>

<?
$imgLink = $cloudPlatform ? "https://cdn.quantconnect.com/i/tu/backtest-results-view-page.png" : "https://cdn.quantconnect.com/i/tu/local-platform-view-all-backtests.png";
$openProjectLink = $cloudPlatform ? "/docs/v2/cloud-platform/projects/getting-started#02-View-All-Projects" : "/docs/v2/local-platform/projects/getting-started#04-Open-Projects";
$backtestResultsLink = $cloudPlatform ? "/docs/v2/cloud-platform/backtesting/results" : "/docs/v2/local-platform/backtesting/results";
$optimizationResults = $cloudPlatform ? "<a href='/docs/v2/cloud-platform/optimization/results'>optimization result</a>" : "optimization results";
?>

<ol>
    <li><a href="<?=$openProjectLink?>">Open the project</a> that contains the optimization results you want to view.</li>
    <li>At the top of the IDE, click the <?php if ($localPlatform) {?><img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/local-platform-backtest-results-icon.png'> / <?php } ?><img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/cloud-platform-backtest-results-icon.png'> <span class="icon-name">Results</span> icon.</li>
    <p>A table containing all of the backtest and optimization results for the project is displayed. If there is a <span class="icon-name">play</span> icon to the left of the name, it's a <a href="<?=$backtestResultsLink?>">backtest result</a>. If there is a <span class="icon-name">fast-forward</span> icon next to the name, it's an <?=$optimizationResults?>.<br></p>
    <img class="docs-image" src="<?=$imgLink?>" alt="All backtest table view">
    <li><span class="qualifier">(Optional)</span> In the top-right corner, select the <span class="field-name">Show</span> field and then select one of the options from the drop-down menu to filter the table by backtest or optimization results.</li>
    <li><span class="qualifier">(Optional)</span> In the bottom-right corner, click the <span class="box-name">Hide Error</span>&nbsp;check box to remove backtest and optimization results from the table that had a runtime error.</li>
    <li><span class="qualifier">(Optional)</span> Use the pagination tools at the bottom to change the page.</li>
    <li><span class="qualifier">(Optional)</span> Click a column name to sort the table by that column.</li>
    <li>Click a row in the table to open the results page of that backtest or optimization.</li>
</ol>

<h4>Rename Optimizations</h4>
<p>We give an arbitrary name (for example, "Smooth Apricot Chicken") to your optimization result files, but you can follow these steps to rename them:</p>

<ol>
    <li>Hover over the optimization you want to rename and then click the <span class="icon-name">
pencil</span> icon that appears.</li>
    <img class="docs-image" src="https://cdn.quantconnect.com/i/tu/rename-optimization-result.png" alt="Rename optimization instance">
    <li>Enter the new name and then press <span class="key-combinations">Enter</span>.</li>
</ol>

<h4>Delete Optimizations</h4>
<p>Hover over the optimization you want to delete and then click the <span class="icon-name">trash can</span> icon that appears to delete the optimization result.</p>
<img class="docs-image" src="https://cdn.quantconnect.com/i/tu/delete-optimization-result.png" alt="Delete optimization result">
