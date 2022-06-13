<p>The datasets integrated into the Dataset Market cover the following markets:</p>

<?php echo file_get_contents(DOCS_RESOURCES."/enumerations/market.html"); ?>

<p>LEAN can usually determine the correct market based on the ticker you provide when you create the security subscription. To manually set the market for a security, pass a <code>market</code> argument when you create the security subscription.</p>

<div class="section-example-container">
    <pre class="csharp">AddEquity("SPY", market: Market.USA);</pre>
    <pre class="python">self.AddEquity("SPY", market=Market.USA)</pre>
</div>