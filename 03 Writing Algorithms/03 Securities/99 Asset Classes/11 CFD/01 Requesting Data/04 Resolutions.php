<?php echo file_get_contents(DOCS_RESOURCES."/securities/resolutions/cfd.html"); ?>

<p>The default resolution for CFD subscriptions is <code class="csharp">Resolution.Minute</code><code class="python">Resolution.MINUTE</code>. To change the resolution, pass a <code>resolution</code> argument to the <code class="csharp">AddCfd</code><code class="python">add_cfd</code> method.</p>

<div class="section-example-container">
    <pre class="csharp">_symbol = AddCfd("XAUUSD", Resolution.Daily).Symbol;</pre>
    <pre class="python">self._symbol = self.add_cfd("XAUUSD", Resolution.DAILY).symbol</pre>
</div>

<p>To create custom resolution periods, see <a href="/docs/v2/writing-algorithms/consolidating-data/getting-started">Consolidating Data</a>.</p>