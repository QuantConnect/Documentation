<p>The <code>ConfidenceWeightedPortfolioConstructionModel</code> generates target portfolio weights based on the <code>Insight.Confidence</code> for the last Insight of each Symbol. If the Insight has a direction of <code>InsightDirection.Up</code>, the model generates long targets. If the Insight has a direction of <code>InsightDirection.Down</code>, the model generates short targets. If the sum of all the last active Insight per Symbol is greater than 1, the model factors down each target percent holdings proportionally so the sum is 1. The model ignores <code>Insight</code> objects that have no <code>Confidence</code> value.</p>

<div class="section-example-container">
	<pre class="csharp">SetPortfolioConstruction(new ConfidenceWeightedPortfolioConstructionModel());</pre>
	<pre class="python">self.SetPortfolioConstruction(ConfidenceWeightedPortfolioConstructionModel())</pre>
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
        <tr>
            <td><code>portfolioBias</code></td>
	    <td><code>PortfolioBias</code></td>
            <td>The bias of the portfolio</td>
            <td><code>PortfolioBias.LongShort</code></td>
        </tr>
    </tbody>
</table>
<p>This model supports other data types for the rebalancing frequency argument. For more information about the supported types, see <a href='/docs/v2/writing-algorithms/algorithm-framework/portfolio-construction/key-concepts#07-Rebalance-Frequency'>Rebalance Frequency</a>.</p>

<p>To view the implementation of this model, see the <span class='csharp'><a target="_blank" rel='nofollow' href='https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Portfolio/ConfidenceWeightedPortfolioConstructionModel.cs'>LEAN GitHub repository</a></span><span class='python'><a target="_blank" rel='nofollow' href='https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Portfolio/ConfidenceWeightedPortfolioConstructionModel.py'>LEAN GitHub repository</a></span>.</p>
