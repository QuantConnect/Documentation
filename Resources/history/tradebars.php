<p class='csharp'>
  To get historical <a href='<?=$dataTypeLink?>'>trade data</a>, call the <code>History&lt;<?=$dataType?>&gt;</code> method with a security's <code>Symbol</code>.
</p>

<p class='python'>
  To get historical <a href='<?=$dataTypeLink?>'>trade data</a>, call the <code>history</code> method with the <code><?=$dataType?></code> type and a security's <code>Symbol</code>.
  This method returns a DataFrame with columns for the open, high, low, close, and volume.
</p>

<div class="section-example-container">
    <pre class="csharp">public class <?=$assetClass?><?=$dataType?>HistoryAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2024, 12, 19);
        // Get the Symbol of a security.
        var symbol = <?=$symbolC?>;
        // Get the 5 trailing daily <?=$dataType?> objects of the security. 
        var history = History&lt;<?=$dataType?>&gt;(symbol, 5, Resolution.Daily);
        // Iterate through each TradeBar and calculate its dollar volume.
        foreach (var bar in history)
        {
            var t = bar.EndTime;
            var dollarVolume = bar.Close * bar.Volume;
        }
    }
}</pre>
    <pre class="python">class <?=$assetClass?><?=$dataType?>HistoryAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 12, 19)
        # Get the Symbol of a security.
        symbol = <?=$symbolPy?>

        # Get the 5 trailing daily <?=$dataType?> objects of the security in DataFrame format. 
        history = self.history(<?=$dataType?>, symbol, 5, Resolution.DAILY)</pre>
</div>

<?=$dataFrame?>

<div class="python section-example-container">
    <pre class="python"># Calculate the daily returns.
daily_returns = history.close.pct_change().iloc[1:]</pre>
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
    <pre class="python"># Get the 5 trailing daily <?=$dataType?> objects of the security in <?=$dataType?> format. 
history = self.history[<?=$dataType?>](symbol, 5, Resolution.DAILY)
# Iterate through the TradeBar objects and access their volumes.
for trade_bar in history:
    volume = trade_bar.volume</pre>
</div>
