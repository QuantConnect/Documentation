<p>To update the name and note of a backtest, call the <code>UpdateBacktest</code> method with the project ID, backtest ID, the new backtest name, and the new note.</p>
<div class="section-example-container">
    <pre class="csharp">var response = api.UpdateBacktest(projectId, backtestId, ""New Name", "New Note");</pre>
    <pre class="python">response = api.UpdateBacktest(project_id, backtest_id, "New Name", "New Note")</pre>
</div>

<?php include(DOCS_RESOURCES."/qc-api/get-project-id-in-research.html"); ?>
<?php include(DOCS_RESOURCES."/qc-api/get-backtest-id-in-research.html"); ?>

<p>The <code>UpdateBacktest</code> method returns a <code>RestResponse</code> object, which have the following attributes:</p>

<div data-tree="QuantConnect.Api.RestResponse"></div>