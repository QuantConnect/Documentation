<p class='csharp'>
  To get historical <a href='<?=$dataTypeLink?>'>daily Option chain data</a>, call the <code>History</code> method with the Option <code>Symbol</code> object.
  The data this method returns contains information on all the currently tradable contracts, not just the contracts that pass your filter.
</p>

<p class='python'>
  To get historical <a href='<?=$dataTypeLink?>'>daily Option chain data</a>, call the <code>history</code> method with the Option <code>Symbol</code> object.
  The data this method returns contains information on all the currently tradable contracts, not just the contracts that pass your filter.
  If you pass <code>flatten=True</code>, this method returns a DataFrame with columns for the data point attributes.
</p>

<div class="section-example-container">
    <pre class="csharp">public class <?=$underlyingAssetClass?>OptionDailyOptionChainHistoryAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2024, 12, 23);
        // Add an <?=$underlyingAssetClass?> Option universe.
        var option = <?=$optionC?>;
        // Get the trailing 5 daily Option chains.
        var history = History&lt;OptionUniverse&gt;(option.Symbol, 5);
        // Iterate through each day of the history.
        foreach (var optionUniverse in history)
        {
            var t = optionUniverse.EndTime;
            // Select the 2 contracts with the most volume.
            var mostTraded = optionUniverse.Select(c => c as OptionUniverse).OrderByDescending(c => c.Volume).Take(2);
        }
    }
}</pre>
    <pre class="python">class <?=$underlyingAssetClass?>OptionDailyOptionChainHistoryAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 12, 23)
        # Add an <?=$underlyingAssetClass?> Option universe.
        option = <?=$optionPy?>

        # Get the trailing 5 daily Option chains in DataFrame format.
        history = self.history(option.symbol, 5, flatten=True)</pre>
</div>

<?=$dataFrame?>

<div class="python section-example-container">
    <pre class="python"># Select the 2 contracts with the greatest volume each day.
most_traded = history.groupby('time').apply(lambda x: x.nlargest(2, 'volume')).reset_index(level=1, drop=True).volume</pre>
</div>

<div class="python section-example-container">
    <pre><?=$series?></pre>
</div>

<p class='python'>
  To get the data in the format of <code>OptionUniverse</code> objects instead of a DataFrame, call the <code>history[OptionUniverse]</code> method.
</p>

<div class="python section-example-container">
    <pre class="python"># Get the historical OptionUniverse data over the last 30 days.
history = self.history[OptionUniverse](option.symbol, timedelta(30))
# Iterate through each daily Option chain.
for option_universe in history:
    t = option_universe.end_time
    # Select the contract with the most volume.
    most_traded = sorted(option_universe, key=lambda contract: contract.volume)[-1]</pre>
</div>
