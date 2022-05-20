<p>The <code>InsightWeightingPortfolioConstructionModel</code> generates target portfolio weights based on the <code>Insight.Weight </code>for the last Insight of each Symbol. If the Insight has a direction of <code>InsightDirection.Up</code>, the model generates long targets. If the Insight has a direction of <code>InsightDirection.Down</code>, the model generates short targets. If the sum of all the last active Insight per Symbol is greater than 1, the model factors down each target percent holdings proportionally so the sum is 1. The model ignores <code>Insight</code> objects that have no <code>Weight </code>value.</p>


<div class="section-example-container">
	<pre class="csharp">SetPortfolioConstruction(new InsightWeightingPortfolioConstructionModel());</pre>
	<pre class="python">self.SetPortfolioConstruction(InsightWeightingPortfolioConstructionModel())</pre>
</div>

<?php 
include(DOCS_RESOURCES."/algorithm-framework/equal-weighting-pcm-arguments.php"); 
$supportedPortfolioBias = true;
$getPCMArgumentText($supportedPortfolioBias);
?>

<p>To view the implementation of this model, see the <span class="csharp"><a target="_blank" rel="nofollow" href="https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Portfolio/InsightWeightingPortfolioConstructionModel.cs">LEAN GitHub repository</a></span><span class="python"><a target="_blank" rel="nofollow" href="https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Portfolio/InsightWeightingPortfolioConstructionModel.py">LEAN GitHub repository</a></span>.</p>

