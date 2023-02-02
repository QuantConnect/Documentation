<p>To delete a backtest, call the <code>DeleteBacktest</code> method with the project ID, backtest ID.</p>
<div class="section-example-container">
    <pre class="csharp">var response = api.DeleteBacktest(projectId, backtestId);</pre>
    <pre class="python">response = api.DeleteBacktest(project_id, backtest_id)</pre>
</div>

<?php include(DOCS_RESOURCES."/qc-api/get-project-id-in-research.html"); ?>
<?php include(DOCS_RESOURCES."/qc-api/get-backtest-id-in-research.html"); ?>

<p>The <code>DeleteBacktest</code> method returns a <code>RestResponse</code> object, which have the following attributes:</p>

<div data-tree='QuantConnect.Api.RestResponse'></div>