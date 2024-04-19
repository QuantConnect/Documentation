<p>LEAN groups all of the India Equity exchanges under <code>Market.India</code>. To set the market for a security, pass a <code>market</code> argument to the <code class="csharp">AddEquity</code><code class="python">add_equity</code> method.</p>

<div class="section-example-container">
    <pre class="csharp">_symbol = AddEquity("YESBANK", market: Market.India).Symbol;</pre>
    <pre class="python">self._symbol = self.add_equity("YESBANK", market=Market.INDIA).symbol</pre>
</div>


<?php echo file_get_contents(DOCS_RESOURCES."/securities/supported-markets.html"); ?>
