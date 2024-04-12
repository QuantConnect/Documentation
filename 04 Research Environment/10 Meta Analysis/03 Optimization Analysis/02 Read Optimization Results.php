<p>To get the results of an optimization, call the <code>ReadOptimization</code> method with the optimization Id.</p>

<div class="section-example-container">
    <pre class="csharp">var optimization = api.ReadOptimization(optimizationId);</pre>
    <pre class="python">optimization = api.read_optimization(optimization_id)</pre>
</div>

<p>To get the optimization Id, check the <a href='/docs/v2/cloud-platform/optimization/results#07-Individual-Backtest-Results'>individual backtest results table on the optimization results page</a>. An example optimization Id is O-696d861d6dbbed45a8442659bd24e59f.</p>

<p>The <code>ReadOptimization</code> method returns an <code>Optimization</code> object, which have the following attributes:</p>
<div data-tree='QuantConnect.Api.Optimization'></div>
