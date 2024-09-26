<p class='python'><i>This feature is coming soon.</i></p>

<div class='csharp'>
<p>The Greeks and IV values that you get from a <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/history-requests'>history request</a> of the Option universe are the daily, pre-calculated values based on the end of the previous trading day.</p>
  
<? include(DOCS_RESOURCES."/option-pricing/greek-history-code.php"); ?>
  
  <p>
    You can't customize the Greeks and IV values that you get from a history request.
    However, you can create <a href='<?=$indicatorLink?>'>indicators</a> to customize how the Greeks and IV are calculated for the contracts already in your universe.
  </p>

</div>
