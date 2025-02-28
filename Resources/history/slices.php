<p>
  To get historical <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/timeslices'>Slice</a> data, call the <code class='csharp'>History</code><code class='python'>history</code> method without passing any <code>Symbol</code> objects.
  This method returns <code>Slice</code> objects, which contain data points from all the datasets in your algorithm.
  If you omit the <code>resolution</code> argument, it uses the resolution that you set for each security and dataset when you created the subscriptions.
</p>

<div class="section-example-container">
    <pre class="csharp">public class SliceHistoryAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2024, 12, 1);
        // Add some assets and datasets.
        <?=$symbolC?>;
<? if ($dataType == "TradeBar") { ?>
        // Get the historical Slice objects over the last 5 days for all the subcriptions in your algorithm.
        var history = History(5, Resolution.Daily);
        // Iterate through each historical Slice.
        foreach (var slice in history)
        {
            // Iterate through each TradeBar in this Slice.
            foreach (var kvp in slice.Bars)
            {
                var symbol = kvp.Key;
                var bar = kvp.Value;
            }
        }
<? } else { ?>
        // Get the historical Slice objects over the last 5 minutes for all the subcriptions in your algorithm.
        var history = History(5, Resolution.Minute);
        // Iterate through each historical Slice.
        foreach (var slice in history)
        {
            // Iterate through each QuoteBar in this Slice.
            foreach (var kvp in slice.QuoteBars)
            {
                var symbol = kvp.Key;
                var bar = kvp.Value;
            }
        }
<? } ?>
    }
}</pre>
    <pre class="python">class SliceHistoryAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 12, 1)
        # Add some assets and datasets.
        <?=$symbolPy?>

<? if ($dataType == "TradeBar") { ?>
        # Get the historical Slice objects over the last 5 days for all the subcriptions in your algorithm.
        history = self.history(5, Resolution.DAILY)
        # Iterate through each historical Slice.
        for slice_ in history:
            # Iterate through each TradeBar in this Slice.
            for symbol, trade_bar in slice_.bars.items():
                close = trade_bar.close
<? } else { ?>
        # Get the historical Slice objects over the last 5 minutes for all the subcriptions in your algorithm.
        history = self.history(5, Resolution.MINUTE)
        # Iterate through each historical Slice.
        for slice_ in history:
            # Iterate through each QuoteBar in this Slice.
            for symbol, quote_bar in slice_.bars.items():
                midprice = quote_bar.close
<? } ?></pre>
</div>
