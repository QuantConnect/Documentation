<?php echo file_get_contents(DOCS_RESOURCES."/securities/resolutions/forex.html"); ?>

<p>The default resolution for Forex subscriptions is <code class="csharp">Resolution.Minute</code><code class="python">Resolution.MINUTE</code>. To change the resolution, pass a <code>resolution</code> argument to the <code class="csharp">AddForex</code><code class="python">add_forex</code> method.</p>

<div class="section-example-container">
    <pre class="csharp">_symbol = AddForex("EURUSD", Resolution.Daily).Symbol;</pre>
    <pre class="python">self._symbol = self.add_forex("EURUSD", Resolution.DAILY).symbol</pre>
</div>

<p>To create custom resolution periods, see <a href="/docs/v2/writing-algorithms/consolidating-data/getting-started">Consolidating Data</a>.</p>