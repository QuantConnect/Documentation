<p>Follow these steps to plot the equity curve, benchmark, and drawdown of a live algorithm:</p>

<ol>
	<li>Get the live algorithm instance.</li>	
	<div class="section-example-container">
	    <pre class="python">live_algorithm = api.read_live_algorithm(project_id, deploy_id)</pre>
	</div>
<p>The following table provides links to documentation that explains how to get the project Id and deployment Id, depending on the platform you use:</p>

<table class="qc-table table">
    <thead>
        <tr>
            <th>Platform</th>
            <th>Project Id</th>
            <th>Deployment Id</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Cloud Platform</td>
            <td><a href='/docs/v2/cloud-platform/projects/getting-started#13-Get-Project-Id'>Get Project Id</a></td>
            <td><a href='/docs/v2/cloud-platform/live-trading/getting-started#08-Get-Deployment-Id'>Get Deployment Id</a></td>
        </tr>
        <tr>
            <td>Local Platform</td>
            <td><a href='/docs/v2/local-platform/projects/getting-started#14-Get-Project-Id'>Get Project Id</a></td>
            <td><a href='/docs/v2/local-platform/live-trading/getting-started#10-Get-Deployment-Id'>Get Deployment Id</a></td>
        </tr>
        <tr>
            <td>CLI</td>
            <td><a href='/docs/v2/lean-cli/projects/project-management#07-Get-Project-Id'>Get Project Id</a></td>
            <td></td>
        </tr>
    </tbody>
</table>
	
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
