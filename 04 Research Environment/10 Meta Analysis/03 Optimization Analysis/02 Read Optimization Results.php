<p>To get the results of an optimization, call the <code>ReadOptimization</code> method with the optimization Id.</p>

<div class="section-example-container">
    <pre class="csharp">var optimization = api.ReadOptimization(optimizationId);</pre>
    <pre class="python">optimization = api.ReadOptimization(optimization_id)</pre>
</div>

<p>To get the optimization Id, check the <a href='/docs/v2/cloud-platform/projects/ide#06-Cloud-Terminal'>Cloud Terminal</a> when you <a href='/docs/v2/cloud-platform/optimization/getting-started#04-View-All-Optimizations'>run an optimization in the Algorithm Lab</a>. An example optimization Id is O-696d861d6dbbed45a8442659bd24e59f.</p>

<p>The <code>ReadOptimization</code> method returns an <code>Optimization</code> object, which have the following attributes:</p>
<div data-tree='QuantConnect.Api.Optimization'></div>