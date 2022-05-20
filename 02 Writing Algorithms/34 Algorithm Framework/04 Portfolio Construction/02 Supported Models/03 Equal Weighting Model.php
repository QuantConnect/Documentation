<p>The <code>EqualWeightingPortfolioConstructionModel</code> assigns an equal share of the portfolio to the securities with active insights. This weighting scheme is useful for universe rotation based on simple portfolio strategies.</p>

<div class="section-example-container">
	<pre class="csharp">SetPortfolioConstruction(new EqualWeightingPortfolioConstructionModel());</pre>
	<pre class="python">self.SetPortfolioConstruction(EqualWeightingPortfolioConstructionModel())</pre>
</div>

<?php 
include(DOCS_RESOURCES."/algorithm-framework/equal-weighting-pcm-arguments.php"); 
$supportedPortfolioBias = true;
$getPCMArgumentText($supportedPortfolioBias);
?>

<p>To view the implementation of this model, see the <span class="csharp"><a target="_blank" rel="nofollow" href="https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Portfolio/EqualWeightingPortfolioConstructionModel.cs">LEAN GitHub repository</a></span><span class="python"><a target="_blank" rel="nofollow" href="https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Portfolio/EqualWeightingPortfolioConstructionModel.py">LEAN GitHub repository</a></span>.</p>
