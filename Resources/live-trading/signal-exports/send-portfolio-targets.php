<p>The signal export manager is responsible for sending signals based on your portfolio holdings to <?=$providerName?>. By default, it waits five seconds after an order is filled. Set the <code class="csharp">AutomaticExportTimeSpan</code><code class="python">automatic_export_time_span</code> property to change the waiting time.</p>

<div class="section-example-container">
<pre class="csharp">SignalExport.AutomaticExportTimeSpan = TimeSpan.FromSeconds(1);</pre>
<pre class="python">self.signal_export.automatic_export_time_span = timedelta(seconds=1)</pre>
</div>

<p>To send targets that aren't based on your portfolio holdings, see <a href="/docs/v2/writing-algorithms/live-trading/signal-exports/key-concepts#05-Manual-Exports">Manual Exports</a>.</p>

<p>To disable automatic exports, set the <code class="csharp">AutomaticExportTimeSpan</code><code class="python">automatic_export_time_span</code> property to <code class="csharp">null</code><code class="python">None</code>.</p>

<div class="section-example-container">
<pre class="csharp">SignalExport.AutomaticExportTimeSpan = null;</pre>
<pre class="python">self.signal_export.automatic_export_time_span = None</pre>
</div>