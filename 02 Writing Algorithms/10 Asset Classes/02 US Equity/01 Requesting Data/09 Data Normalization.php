What is data normalization:
<br>- A technique of how the data is adjusted for corporate actions.
<br>-By default equity data in QuantConnect is Split and Dividend adjusted backwards in time to give smooth continuous prices. This allows easy use for indicators. Some algorithms need raw or partially adjusted price data. You can control this with the { SetDataNormalizationMode()} method. The { DataNormalizationMode} enum has the values Adjusted (default), Raw, SplitAdjusted, and TotalReturn. When data is set to Raw mode the dividends are paid as cash into your algorithm, and the splits are directly applied to your holding quantity. 

<br><br>
What data normalization options are available:
<?php include(DOCS_RESOURCES."/datasets/data-normalization.html"); ?>


<br><br>
How to set the data normalization mode:
<br>-Either with &lt;equity&gt;.SetDataNormalizationMode, UniverseSettings.DataNormalizationMode, or in a security initialize
<br>-Example snippets

<br><br>
Ramifications:
<br>-The normalization of the data returned from a History request matches the normalization of the data subscription.