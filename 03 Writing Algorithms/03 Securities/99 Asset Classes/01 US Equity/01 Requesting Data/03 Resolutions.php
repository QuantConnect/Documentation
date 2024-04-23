<? include(DOCS_RESOURCES."/securities/resolutions/equity.html"); ?>

<p>The default resolution for Equity subscriptions is <code class="csharp">Resolution.Minute</code><code class="python">Resolution.MINUTE</code>. To change the resolution, pass a <code>resolution</code> argument to the <code class="csharp">AddEquity</code><code class="python">add_equity</code> method.</p>

<div class="section-example-container">
    <pre class="csharp">_symbol = AddEquity("SPY", Resolution.Daily).Symbol;</pre>
    <pre class="python">self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol</pre>
</div>

<p>To create custom resolution periods, see <a href="/docs/v2/writing-algorithms/consolidating-data/getting-started">Consolidating Data</a>.</p>
