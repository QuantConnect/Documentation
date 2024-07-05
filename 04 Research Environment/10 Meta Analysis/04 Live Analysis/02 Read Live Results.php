<p>To get the results of a live algorithm, call the <code class="csharp">ReadLiveAlgorithm</code><code class="python">read_live_algorithm</code> method with the project Id and deployment ID.</p>

<div class="section-example-container">
    <pre class="csharp">#load "../Initialize.csx"
#load "../QuantConnect.csx"

using QuantConnect;
using QuantConnect.Api;

var liveAlgorithm = api.ReadLiveAlgorithm(projectId, deployId);</pre>
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
            <td><a href='/docs/v2/cloud-platform/live-trading/getting-started#06-Get-Deployment-Id'>Get Deployment Id</a></td>
        </tr>
        <tr>
            <td>Local Platform</td>
            <td><a href='/docs/v2/local-platform/projects/getting-started#14-Get-Project-Id'>Get Project Id</a></td>
            <td><a href='/docs/v2/local-platform/live-trading/getting-started#08-Get-Deployment-Id'>Get Deployment Id</a></td>
        </tr>
        <tr>
            <td>CLI</td>
            <td><a href='/docs/v2/lean-cli/projects/project-management#07-Get-Project-Id'>Get Project Id</a></td>
            <td></td>
        </tr>
    </tbody>
</table>

<p>The <code class="csharp">ReadLiveAlgorithm</code><code class="python">read_live_algorithm</code> method returns a <code>LiveAlgorithmResults</code> object, which have the following attributes:</p>
<div data-tree='QuantConnect.Api.LiveAlgorithmResults'></div>
