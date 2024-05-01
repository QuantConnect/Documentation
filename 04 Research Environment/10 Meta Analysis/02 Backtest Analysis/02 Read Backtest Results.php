<p>To get the results of a backtest, call the <code class="csharp">ReadBacktest</code><code class="python">read_backtest</code> method with the project Id and backtest ID.</p>

<div class="section-example-container">
    <pre class="csharp">#load "../Initialize.csx"
#load "../QuantConnect.csx"

using QuantConnect;
using QuantConnect.Api;

var backtest = api.ReadBacktest(projectId, backtestId);</pre>
    <pre class="python">backtest = api.read_backtest(project_id, backtest_id)</pre>
</div>

<?php include(DOCS_RESOURCES."/qc-api/get-project-id-in-research.html"); ?>
<?php include(DOCS_RESOURCES."/qc-api/get-backtest-id-in-research.html"); ?>

<p>Note that this method returns a snapshot of the backtest at the current moment. If the backtest is still executing, the result won't include all of the backtest data.</p>

<p>The <code class="csharp">ReadBacktest</code><code class="python">read_backtest</code> method returns a <code>Backtest</code> object, which have the following attributes:</p>
<div data-tree="QuantConnect.Api.Backtest"></div>