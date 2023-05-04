<? if ($cloudPlatform) { ?>
<p>The latest master branch on the LEAN GitHub repository is the default engine branch that runs the backtesting, research, and live trading nodes in QuantConnect Cloud. The latest version of LEAN is generally the safest as it includes all bug fixes.</p>
  
<p>Trading Firm or Institution tier users concerned for stability can elect to use older or custom versions of LEAN in the IDE. These are powered by the <a rel="nofollow" target="_blank" href='https://github.com/QuantConnect/Lean/branches'>QuantConnect/LEAN Github Branches</a>. We use a continuous deployment process to ship custom branches to production for trading. To create a custom version of LEAN, make a pull request to LEAN which will be reviewed by our team.</p>
<? } if ($localPlatform) { ?>
<p>The latest master branch on the LEAN GitHub repository is the default engine branch that runs backtests, research notebooks, and live trading algorithms. The latest version of LEAN is generally the safest as it includes all bug fixes. Trading Firm or Institution tier users concerned for stability can elect to use older or custom versions of LEAN. </p>
<? } ?>
