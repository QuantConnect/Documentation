<p>To backtest a project, <a href='/docs/v2/research-environment/quantconnect-api/backtests/managing-backtests#03-Compile-Projects'>compile the project</a> and then call the <code>CreateBacktest</code> method with the project ID, compile ID, and a backtest name.</p>

<div class="section-example-container">
    <pre class="csharp">var backtest = api.CreateBacktest(projectId, compile.CompileId, backtestName);</pre>
    <pre class="python">backtest = api.CreateBacktest(project_id, compile.CompileId, backtest_name)</pre>
</div>

<?php include(DOCS_RESOURCES."/qc-api/get-project-id-in-research.html"); ?>

<p>The <code>CreateBacktest</code> method returns a <code>Backtest</code> object, which have the following attributes:</p>
<div data-tree='QuantConnect.Api.Backtest'></div>