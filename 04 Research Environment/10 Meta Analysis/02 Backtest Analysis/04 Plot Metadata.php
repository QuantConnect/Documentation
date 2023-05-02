<p>Follow these steps to plot the equity curve, benchmark, and drawdown of a backtest:</p>

<ol>
    <li>Get the backtest instance.</li>
    <div class="section-example-container">
	    <pre class="python">backtest = api.ReadBacktest(project_id, backtest_id)</pre>
	</div>
	<?php include(DOCS_RESOURCES."/qc-api/get-project-id-in-research.html"); ?>
	<?php include(DOCS_RESOURCES."/qc-api/get-backtest-id-in-research.html"); ?>
	
    <?
    $isLive = false;
    include(DOCS_RESOURCES."/qc-api/plot-metadata.php");
    ?>
</ol>

<p>Apart from the attributes of the above example, the below shows the complete list of items that is available to use the above method to visualize:</p>

<?include(DOCS_RESOURCES."/qc-api/metadata-table.html");?>
