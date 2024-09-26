<div class='csharp'>
<p>
  To get historical chains for <?=$assetClass?>, call the <code class='csharp'>History&lt;OptionUniverse&gt;</code> method with the canonical Option <code>Symbol</code>.
  This method returns the entire Option chain for each trading day, not the subset of contracts that pass your universe filter.
</p>

<? 
include(DOCS_RESOURCES."/option-pricing/greek-history-code.php"); 
?>

<p>
    The Greeks and IV values that you get from a <a href='/docs/v2/writing-algorithms/historical-data/history-requests'>history request</a> of the Option universe are the daily, pre-calculated values based on the end of the previous trading day.
    To get the intraday values or to customize the Greeks and IV calculations, create some <a href='<?=$indicatorLink?>'>Option indicators</a>.
</p>

  <p>
    The <code class='csharp'>History&lt;OptionUniverse&gt;</code> method represents each contract with an <code>OptionUniverse</code> object, which have the following properties:
  </p>
  <div data-tree="QuantConnect.Data.UniverseSelection.OptionUniverse"></div>
  
</div>


<p class='python'><i>This feature is coming soon for Python.</i></p>
