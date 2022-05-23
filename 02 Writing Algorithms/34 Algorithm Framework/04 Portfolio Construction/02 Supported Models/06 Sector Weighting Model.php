<p>The <code>SectorWeightingPortfolioConstructionModel</code> generates target portfolio weights based on the <code>CompanyReference.IndustryTemplateCode</code> provided by the <a href="/datasets/morning-star-us-fundamentals">US Fundamental dataset</a>. The target percent holdings of each sector is 1/S where S is the number of sectors and the target percent holdings of each security is 1/N where N is the number of securities of each sector. If the insight has a direction of <code>InsightDirection.Up</code>, the model generates long targets. If the insight has a direction of <code>InsightDirection.Down</code>, the model generates short targets. The model ignores <code>Insight</code> objects for Symbols that have no <code>CompanyReference.IndustryTemplateCode</code>.</p>

<div class="section-example-container">
	<pre class="csharp">SetPortfolioConstruction(new SectorWeightingPortfolioConstructionModel());</pre>
	<pre class="python">self.SetPortfolioConstruction(SectorWeightingPortfolioConstructionModel())</pre>
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

<p>This model supports other data types for the rebalancing frequency argument. For more information about the supported types, see <a href='/docs/v2/writing-algorithms/algorithm-framework/portfolio-construction/key-concepts#06-Rebalance-Frequency'>Rebalance Frequency</a>.</p>

<p>To view the implementation of this model, see the <span class="csharp"><a target="_blank" rel="nofollow" href="https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Portfolio/SectorWeightingPortfolioConstructionModel.cs">LEAN GitHub repository</a></span><span class="python"><a target="_blank" rel="nofollow" href="https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Portfolio/SectorWeightingPortfolioConstructionModel.py">LEAN GitHub repository</a></span>.</p>
