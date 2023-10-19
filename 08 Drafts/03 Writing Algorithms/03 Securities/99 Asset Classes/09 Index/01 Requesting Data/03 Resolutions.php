<?php echo file_get_contents(DOCS_RESOURCES."/securities/resolutions/index.html"); ?>

<p>The default resolution for Index subscriptions is <code>Resolution.Minute</code>. To change the resolution, pass a <code>resolution</code> argument to the <code>AddIndex</code> method.</p>

<div class="section-example-container">
    <pre class="csharp">_symbol = AddIndex("VIX", Resolution.Daily).Symbol;</pre>
    <pre class="python">self.symbol = self.AddIndex("VIX", Resolution.Daily).Symbol</pre>
</div>

<p>To create custom resolution periods, see <a href="/docs/v2/writing-algorithms/consolidating-data/getting-started">Consolidating Data</a>.</p>