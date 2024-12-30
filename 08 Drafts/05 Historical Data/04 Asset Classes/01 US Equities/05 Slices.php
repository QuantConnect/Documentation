<p>
  To get historical <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/timeslices'>Slice</a> data, call the <code class='csharp'>History</code><code class='python'>history</code> method without passing any <code>Symbol</code> objects.
  This method returns <code>Slice</code> objects, which contain data points from all the datasets in your algorithm.
  If you omit the <code>resolution</code> argument, it uses the resolution that you set for each security and dataset when you created the subscriptions.
</p>

<div class="section-example-container">
    <pre class="csharp">// Get the historical Slice objects over the last 5 days for all the subcriptions in your algorithm.
var history = History(5, Resolution.Daily);</pre>
    <pre class="python"># Get the historical Slice objects over the last 5 days for all the subcriptions in your algorithm.
history = self.history(5, Resolution.DAILY)
# Iterate through each Slice.
for slice_ in history:
    # Iterate through each TradeBar in this Slice.
    for symbol, trade_bar in slice_.bars.items():
        close = trade_bar.close</pre>
</div>
