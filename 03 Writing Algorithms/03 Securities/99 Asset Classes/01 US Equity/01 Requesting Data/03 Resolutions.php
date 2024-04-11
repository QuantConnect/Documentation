<? include(DOCS_RESOURCES."/securities/resolutions/equity.html"); ?>

<p>The default resolution for Equity subscriptions is <code>Resolution.Minute</code>. To change the resolution, pass a <code>resolution</code> argument to the <code>AddEquity</code> method.</p>

<div class="section-example-container">
    <pre class="csharp">_symbol = AddEquity("SPY", Resolution.Daily).Symbol;</pre>
    <pre class="python">self.symbol = self.add_equity("SPY", Resolution.daily).symbol</pre>
</div>

<p>To create custom resolution periods, see <a href="/docs/v2/writing-algorithms/consolidating-data/getting-started">Consolidating Data</a>.</p>
