<?php echo file_get_contents(DOCS_RESOURCES."/getting-started/backtesting/view-all-backtests.html"); ?>


<h4>Rename Backtests</h4>
<?php 
include(DOCS_RESOURCES."/getting-started/backtesting/rename-backtests.php"); 
$getRenameBacktestsText();
?>


<p>We give an arbitrary name (for example, "Smooth Apricot Chicken") to your backtest result files, but you can follow these steps to rename them:</p>

<ol>
    <li>Hover over the backtest you want to rename and then click the <span class="icon-name">
pencil</span> icon that appears.</li>
    <img class="docs-image" src="https://cdn.quantconnect.com/i/tu/rename-backtest-result.png">
    <li>Enter the new backtest name and then click <span class="button-name">OK</span>.</li>
</ol>

<h4>Clone Backtests</h4>
<p>Hover over the backtest you want to clone, and then click the <span class="icon-name">clone</span> icon that appears to clone the backtest.</p>
<img class="docs-image" src="https://cdn.quantconnect.com/i/tu/clone-backtest-result.png">
<p>A new project is created with the backtest code files.</p>

<h4>Delete Backtests</h4>
<p>Hover over the backtest you want to delete, and then click the <span class="icon-name">trash can</span> icon that appears to delete the backtest.</p>
<img class="docs-image" src="https://cdn.quantconnect.com/i/tu/delete-backtest-icon.png">