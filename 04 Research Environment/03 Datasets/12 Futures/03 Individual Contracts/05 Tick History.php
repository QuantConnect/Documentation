<? include(DOCS_RESOURCES."/securities/data-definitions/ticks.html"); ?>

<p>To get tick data, call the <span class='python'><code>history</code> or <code>history[Tick]</code></span><span class='csharp'>History&lt;Tick&gt;</span> method with the contract Symbol object(s) and <code class='python'>Resolution.TICK</code><code class='csharp'>Resolution.Tick</code>.</p>

<div class="section-example-container">
    <pre class="csharp">var history = qb.History&lt;Tick&gt;(contractSymbol, TimeSpan.FromDays(3), Resolution.Tick);
foreach (var tick in history)
{
    Console.WriteLine(tick);
}</pre>
    <pre class="python"># DataFrame format
history_df = qb.history(contract_symbol, timedelta(3), Resolution.TICK)
display(history_df)

# Tick objects
history = qb.history[Tick](contract_symbol, timedelta(3), Resolution.TICK)
for tick in history:
    print(trade_bar)</pre>
</div>

<p><code>Tick</code> objects have the following properties:</p>
<div data-tree='QuantConnect.Data.Market.Tick'></div>

<p>
  Tick data is grouped into one millisecond buckets.
  Ticks are a sparse dataset, so request ticks over a trailing period of time or between start and end times.
</p>
