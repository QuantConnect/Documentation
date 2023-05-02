<p>Follow these steps to plot the equity curve of a live algorithm:</p>

<ol>
	<li>Get the live algorithm instance.</li>	
	<div class="section-example-container">
	    <pre class="python">live_algorithm = api.ReadLiveAlgorithm(project_id, deploy_id)</pre>
	</div>
	<?php include(DOCS_RESOURCES."/qc-api/get-project-id-in-research.html"); ?>
	<?php include(DOCS_RESOURCES."/qc-api/get-deployment-id-in-research.html"); ?>
	
    <li>Get the results of the live algorithm.</li>
    <div class="section-example-container">
	    <pre class="python">results = live_algorithm.LiveResults.Results</pre>
	</div>

    <?
    $isLive = true;
    include(DOCS_RESOURCES."/qc-api/plot-metadata.php");
    ?>
</ol>

<p>Apart from the attributes of the above example, the below shows the complete list of items that is available to use the above method to visualize:</p>

<?include(DOCS_RESOURCES."/qc-api/metadata-table.html");?>