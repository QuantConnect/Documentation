<?
$imgLink = "https://cdn.quantconnect.com/i/tu/universe-dataframe-us-equity-alternative-data.png";
?>

<p class='csharp'>
  To get historical data for an <a href='/docs/v2/writing-algorithms/universes/equity/alternative-data-universes'>alternative data universe</a>, call the <code>History</code> method with the <code>Universe</code> object.
</p>

<p class='python'>
  To get historical data for an <a href='/docs/v2/writing-algorithms/universes/equity/alternative-data-universes'>alternative data universe</a>, call the <code>history</code> method with the <code>Universe</code> object.
  Set the <code>flatten</code> argument to <code>True</code> to get a DataFrame that has columns for the data point attributes.
</p>

<div class="section-example-container">
    <pre class="csharp">public class AltDataUniverseHistoryAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2024, 12, 23);
        // Add a universe of US Equities based on an alternative dataset.
        var universe = AddUniverse&lt;BrainStockRankingUniverse&gt;();
        // Get 5 days of history for the universe.
        var history = History(universe, TimeSpan.FromDays(5));
        // Iterate through each day of the universe history.
        foreach (var altCoarse in history)
        {
            // Iterate through each asset in the universe on this day and access its data point attributes.
            foreach (BrainStockRankingUniverse stockRanking in altCoarse)
            {
                var symbol = stockRanking.Symbol;
                var t = stockRanking.EndTime;
                var rank2Days = stockRanking.Rank2Days;
            }
        }
    }
}</pre>
    <pre class="python">class AltDataUniverseHistoryAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 12, 23)    
        # Add a universe of US Equities based on an alternative dataset.
        universe = self.add_universe(BrainStockRankingUniverse)
        # Get 5 days of history for the universe.
        history = self.history(universe, timedelta(5), flatten=True)</pre>
</div>

<img class='python docs-image' src='<?=$imgLink?>' alt='DataFrame of the last 5 days of a US Equity alternative data universe.'>

<div class="python section-example-container">
    <pre class="python"># Select the asset with the greatest value each day.
daily_winner = history.groupby('time').apply(lambda x: x.nlargest(1, 'value')).reset_index(level=1, drop=True).value</pre>
</div>

<div class="python section-example-container">
    <pre>time        symbol          
2024-12-18  FIC R735QTJ8XC9X    0.054204
2024-12-19  FIC R735QTJ8XC9X    0.073250
2024-12-20  FIC R735QTJ8XC9X    0.065142
2024-12-21  FIC R735QTJ8XC9X    0.065142
2024-12-22  FIC R735QTJ8XC9X    0.065142
2024-12-23  FIC R735QTJ8XC9X    0.065142
Name: value, dtype: float64</pre>
</div>

<p class='python'>To get the data in the format of the objects that you receive in your universe filter function instead of a DataFrame, use <code>flatten=False</code>.</p>

<div class="python section-example-container">
    <pre class="python"># Get the historical universe data over the last 5 days in a Series where
# the values in the series are lists of the universe selection objects.
history = self.history(universe, timedelta(5), flatten=False)
# Select the asset with the greatest value each day.
for (universe_symbol, time), data in history.items():
    leader = sorted(data, key=lambda x: x.value)[-1]</pre>
</div>
