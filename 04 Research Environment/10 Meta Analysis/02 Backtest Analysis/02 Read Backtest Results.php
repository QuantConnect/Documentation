<p>To get the results of a backtest, call the <code class="csharp">ReadBacktest</code><code class="python">read_backtest</code> method with the project Id and backtest ID.</p>

<div class="section-example-container">
    <pre class="csharp">#load "../Initialize.csx"
#load "../QuantConnect.csx"

using QuantConnect;
using QuantConnect.Api;

var backtest = api.ReadBacktest(projectId, backtestId);</pre>
    <pre class="python">backtest = api.read_backtest(project_id, backtest_id)</pre>
</div>

<p>The following table provides links to documentation that explains how to get the project Id and backtest Id, depending on the platform you use:</p>

<table class="qc-table table">
    <thead>
        <tr>
            <th>Platform</th>
            <th>Project Id</th>
            <th>Backtest Id</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Cloud Platform</td>
            <td><a href='/docs/v2/cloud-platform/projects/getting-started#13-Get-Project-Id'>Get Project Id</a></td>
            <td><a href='/docs/v2/cloud-platform/backtesting/getting-started#07-Get-Backtest-Id'>Get Backtest Id</a></td>
        </tr>
        <tr>
            <td>Local Platform</td>
            <td><a href='/docs/v2/local-platform/projects/getting-started#14-Get-Project-Id'>Get Project Id</a></td>
            <td><a href='/docs/v2/local-platform/backtesting/getting-started#07-Get-Backtest-Id'>Get Backtest Id</a></td>
        </tr>
        <tr>
            <td>CLI</td>
            <td><a href='/docs/v2/lean-cli/projects/project-management#07-Get-Project-Id'>Get Project Id</a></td>
            <td><a href='/docs/v2/lean-cli/backtesting/deployment#05-Get-Backtest-Id'>Get Backtest Id</a></td>
        </tr>
    </tbody>
</table>

<p>Note that this method returns a snapshot of the backtest at the current moment. If the backtest is still executing, the result won't include all of the backtest data.</p>

<p>The <code class="csharp">ReadBacktest</code><code class="python">read_backtest</code> method returns a <code>Backtest</code> object, which have the following attributes:</p>
<div data-tree="QuantConnect.Api.Backtest"></div>