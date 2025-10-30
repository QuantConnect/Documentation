<p>The <code>EqualWeightingPortfolioConstructionModel</code> assigns an equal share of the portfolio to the securities with active insights. This weighting scheme is useful for universe rotation based on simple portfolio strategies.</p>

<div class="section-example-container">
	<pre class="csharp">// Set portfolio construction to EqualWeightingPortfolioConstructionModel to allocate capital equally across selected securities, ensuring balanced exposure and reducing the influence of any single asset on the portfolio.
SetPortfolioConstruction(new EqualWeightingPortfolioConstructionModel());</pre>
	<pre class="python"># Set portfolio construction to EqualWeightingPortfolioConstructionModel to allocate capital equally across selected securities, ensuring balanced exposure and reducing the influence of any single asset on the portfolio.
self.set_portfolio_construction(EqualWeightingPortfolioConstructionModel())</pre>
</div>

<p>The following table describes the arguments the model accepts:</p>

<table class="qc-table table">
    <thead>
        <tr>
            <th>Argument</th>
            <th>Data Type</th>
            <th>Description</th>
            <th>Default Value</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code class="python">rebalance</code><code class="csharp">resolution</code></td>
	    <td><code>Resolution</code></td>
	    <td>Rebalancing frequency</td>
            <td><code class="csharp">Resolution.Daily</code><code class="python">Resolution.DAILY</code></td>
        </tr>
    </tbody>
</table>

<p>This model supports other data types for the rebalancing frequency argument. For more information about the supported types, see <a href='/docs/v2/writing-algorithms/algorithm-framework/portfolio-construction/key-concepts#07-Rebalance-Frequency'>Rebalance Frequency</a>.</p>

<p>This model removes expired insights from the <a href='/docs/v2/writing-algorithms/algorithm-framework/insight-manager'>Insight Manager</a> during each rebalance. It also removes all insights for a security when the security is removed from the <a href='/docs/v2/writing-algorithms/algorithm-framework/universe-selection/key-concepts'>universe</a>.</p>

<p class='csharp'>For more information about this model, see the <a target="_blank" rel="nofollow" href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1Framework_1_1Portfolio_1_1EqualWeightingPortfolioConstructionModel.html">class reference</a> and <a target="_blank" rel="nofollow" href="https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Portfolio/EqualWeightingPortfolioConstructionModel.cs">implementation</a>.</p>
<p class='python'>For more information about this model, see the <a target="_blank" rel="nofollow" href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/Framework/Portfolio/EqualWeightingPortfolioConstructionModel/">class reference</a> and <a target="_blank" rel="nofollow" href="https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Portfolio/EqualWeightingPortfolioConstructionModel.py">implementation</a>.</p>
