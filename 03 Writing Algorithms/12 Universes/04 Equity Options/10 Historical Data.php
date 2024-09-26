<div class='csharp'>
<p>
  To get historical chains for an Equity Option, call the <code class='csharp'>History&lt;OptionUniverse&gt;</code> method with the canonical Option <code>Symbol</code>.
  This method returns the entire Option chain for each trading day, not the subset of contracts that pass your universe filter.
</p>

<? include(DOCS_RESOURCES."/option-pricing/greek-history-code.php"); ?>

<p>
    The Greeks and IV values that you get from a history request of the Option universe are the daily, pre-calculated values based on the end of the previous trading day.
    To get the intraday values, create some <a href='/docs/v2/writing-algorithms/securities/asset-classes/equity-options/greeks-and-implied-volatility/indicators'>Option indicators</a>.
</p>
</div>


<p class='python'><i>This feature is coming soon for Python.</i></p>
