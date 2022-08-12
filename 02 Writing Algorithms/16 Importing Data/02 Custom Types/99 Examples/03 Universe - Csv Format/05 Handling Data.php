<p>As your filtered assets from your custom universe selection piped, LEAN adds their data points in the <code>Slice</code> that passes to your algorithm's <code>OnData</code> method.</p>

<div class="section-example-container">
<pre class="csharp">public class MyAlgorithm : QCAlgorithm
{
    public override void OnData(Slice slice)
    {
        if (slice.Bars.Count == 0) return;

        var percentage = 1m / slice.Bars.Count;
        foreach (var tradeBar in slice.Bars.Values)
        {
            Log($"{Time} :: {tradeBar.Symbol} :: {tradeBar.ToString}");
            SetHoldings(tradeBar.Symbol, percentage);
        }
    }
}</pre>
<pre class="python">class MyAlgorithm(QCAlgorithm):
    def OnData(self, slice: Slice) -&gt; None:
        if slice.Bars.Count == 0: return

        percentage = 1 / slice.Bars.Count
        for trade_bar in slice.Bars.Values:
            self.Log(f"{self.Time} :: {trade_bar.Symbol} :: {trade_bar.ToString}")
            self.SetHoldings(trade_bar.Symbol, percentage)</pre>
</div>