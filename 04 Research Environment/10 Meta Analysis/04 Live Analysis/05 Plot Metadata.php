<p>Follow these steps to plot the equity curve, benchmark, and drawdown of a live algorithm:</p>

<ol>
	<li>Get the live algorithm instance.</li>	
	<div class="section-example-container">
	    <pre class="python">live_algorithm = api.read_live_algorithm(project_id, deploy_id)</pre>
	</div>
	<?php include(DOCS_RESOURCES."/qc-api/get-project-id-in-research.html"); ?>
	<?php include(DOCS_RESOURCES."/qc-api/get-deployment-id-in-research.html"); ?>
	
    <li>Get the results of the live algorithm.</li>
    <div class="section-example-container">
	    <pre class="python">results = live_algorithm.live_results.results</pre>
	</div>

    <?
    $isLive = true;
    include(DOCS_RESOURCES."/qc-api/plot-metadata.php");
    ?>
</ol>

<p>The following table shows all the chart series you can plot:</p>

<?include(DOCS_RESOURCES."/qc-api/metadata-table.html");?>
