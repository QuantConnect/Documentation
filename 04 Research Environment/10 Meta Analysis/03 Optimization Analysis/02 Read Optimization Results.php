<p>To get the results of an optimization, call the <code class="csharp">ReadOptimization</code><code class="python">read_optimization</code> method with the optimization Id.</p>

<div class="section-example-container">
    <pre class="csharp">var optimization = api.ReadOptimization(optimizationId);</pre>
    <pre class="python">optimization = api.read_optimization(optimization_id)</pre>
</div>

<p>The following table provides links to documentation that explains how to get the optimization Id, depending on the platform you use:</p>

<table class="qc-table table">
    <thead>
        <tr>
            <th style='width: 50%'>Platform</th>
            <th>Optimziation Id</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Cloud Platform</td>
            <td><a href='/docs/v2/cloud-platform/optimization/getting-started#06-Get-Optimization-Id'>Get Optimization Id</a></td>
        </tr>
        <tr>
            <td>Local Platform</td>
            <td><a href='/docs/v2/local-platform/optimization/getting-started#07-Get-Optimization-Id'>Get Optimization Id</a></td>
        </tr>
        <tr>
            <td>CLI</td>
            <td> </td>
        </tr>
    </tbody>
</table>

<p>To get the optimization Id, check the <a href='/docs/v2/cloud-platform/optimization/results#07-Individual-Backtest-Results'>individual backtest results table on the optimization results page</a>. An example optimization Id is O-696d861d6dbbed45a8442659bd24e59f.</p>

<p>The <code class="csharp">ReadOptimization</code><code class="python">read_optimization</code> method returns an <code>Optimization</code> object, which have the following attributes:</p>
<div data-tree='QuantConnect.Api.Optimization'></div>
