<p>The <code>EqualWeightingPortfolioConstructionModel</code> assigns an equal share of the portfolio to the securities with active insights. This weighting scheme is useful for universe rotation based on simple portfolio strategies.</p>

<div class="section-example-container">
	<pre class="csharp">SetPortfolioConstruction(new EqualWeightingPortfolioConstructionModel());</pre>
	<pre class="python">self.set_portfolio_construction(EqualWeightingPortfolioConstructionModel())</pre>
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
            <td><code>Resolution.Daily</code></td>
        </tr>
    </tbody>
</table>

<p>This model supports other data types for the rebalancing frequency argument. For more information about the supported types, see <a href='/docs/v2/writing-algorithms/algorithm-framework/portfolio-construction/key-concepts#07-Rebalance-Frequency'>Rebalance Frequency</a>.</p>

<p>This model removes expired insights from the <a href='/docs/v2/writing-algorithms/algorithm-framework/insight-manager'>Insight Manager</a> during each rebalance. It also removes all insights for a security when the security is removed from the <a href='/docs/v2/writing-algorithms/algorithm-framework/universe-selection/key-concepts'>universe</a>.</p>

<p>To view the implementation of this model, see the <span class="csharp"><a target="_blank" rel="nofollow" href="https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Portfolio/EqualWeightingPortfolioConstructionModel.cs">LEAN GitHub repository</a></span><span class="python"><a target="_blank" rel="nofollow" href="https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Portfolio/EqualWeightingPortfolioConstructionModel.py">LEAN GitHub repository</a></span>.</p>
