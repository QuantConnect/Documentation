<p class='csharp'>
  To get historical <a href='<?=$dataTypeLink?>'>tick data</a>, call the <code>History&lt;Tick&gt;</code> method with a trading pair's <code>Symbol</code> and <code>Resolution.Tick</code>.
</p>

<p class='python'>
  To get historical <a href='<?=$dataTypeLink?>'>tick data</a>, call the <code>history</code> method with a trading pair's <code>Symbol</code> and <code>Resolution.TICK</code>.
  This method returns a DataFrame that contains data on bids, asks, and last trade prices.
</p>

<div class="section-example-container">
    <pre class="csharp">public class <?=$assetClass?><?=$dataType?>HistoryAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2024, 12, 19);
        // Get the Symbol of a trading pair.
        var symbol = <?=$symbolC?>;
        // Get the trailing 2 days of ticks for the pair.
        var history = History&lt;Tick&gt;(symbol, TimeSpan.FromDays(2), Resolution.Tick);
        // Calculate the spread.
        var spread = history.Select(tick => tick.AskPrice - tick.BidPrice);
    }
}</pre>
    <pre class="python">class <?=$assetClass?><?=$dataType?>HistoryAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 12, 19)
        # Get the Symbol of a trading pair.
        symbol = <?=$symbolPy?>

        # Get the trailing 2 days of ticks for the pair in DataFrame format.
        history = self.history(symbol, timedelta(2), Resolution.TICK)</pre>
</div>

<?=$dataFrame?>

<div class="python section-example-container">
    <pre class="python"># Calculate the spread.
spread = history.askprice - history.bidprice</pre>
</div>

<div class="python section-example-container">
    <pre><?=$series?></pre>
</div>

<p class='python'>
  If you intend to use the data in the DataFrame to create <code><?=$dataType?></code> objects, request that the history request returns the data type you need. 
  Otherwise, LEAN consumes unnecessary computational resources populating the DataFrame.  
  To get a list of <code><?=$dataType?></code> objects instead of a DataFrame, call the <code>history[<?=$dataType?>]</code> method.
</p>


<div class="python section-example-container">
    <pre class="python"># Get the trailing 2 days of ticks for a pair in Tick format. 
history = self.history[Tick](symbol, timedelta(2), Resolution.TICK)
# Iterate through each quote tick and calculate the spread.
for tick in history:
    spread = tick.bid_price - tick.ask_price</pre>
</div>

<p>Ticks are a sparse dataset, so request ticks over a trailing period of time or between start and end times.</p>
