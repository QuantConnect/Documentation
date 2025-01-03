<p>The <code class="csharp">Market</code><code class="python">market</code> property distinguishes between tickers that have the same string value but represent different underlying assets. A prime example of this is the various market makers who have different prices for EURUSD. We store this data separately and as they have different fill prices, we treat the execution venues as different markets.</p>

<p>The following table shows the available markets for each security type:</p>
<? include(DOCS_RESOURCES."/datasets/supported-securities/markets.html"); ?>
