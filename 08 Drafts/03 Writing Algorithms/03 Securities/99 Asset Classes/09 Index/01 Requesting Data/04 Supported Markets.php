<p>The only market available for Indices is <code>Market.USA</code>, so you don't need to pass a <code>market</code> argument to the <code>AddIndex</code> method.</p>

<div class="section-example-container">
    <pre class="csharp">_symbol = AddIndex("VIX", market: Market.USA).Symbol;</pre>
    <pre class="python">self.symbol = self.AddIndex("VIX", market=Market.USA).Symbol</pre>
</div>

<?php echo file_get_contents(DOCS_RESOURCES."/securities/supported-markets.html"); ?>