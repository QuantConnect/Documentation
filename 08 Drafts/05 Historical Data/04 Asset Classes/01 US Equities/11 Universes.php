<?
$imgLink = "https://cdn.quantconnect.com/i/tu/universe-dataframe-us-etf-constituents.png";
$dataTypeLink = "/docs/v2/writing-algorithms/universes/equity";
?>

<p class='csharp'>
  To get historical <a href='<?=$dataTypeLink?>'>universe data</a>, call the <code>History</code> method with the <code>Universe</code> object.
</p>

<p class='python'>
  To get historical <a href='<?=$dataTypeLink?>'>universe data</a>, call the <code>history</code> method with the <code>Universe</code> object.
  This method returns a DataFrame with columns for the data point attributes.
</p>

<div class="section-example-container">
    <pre class="csharp">public class USEquityUniverseHistoryAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2024, 12, 23);
        // Add a universe of US Equities based on the constituents of an ETF.
        var universe = AddUniverse(Universe.ETF("SPY"));
        // Get 5 days of history for the universe.
        var history = History(universe, TimeSpan.FromDays(5));
        // Iterate through each day of the universe history.
        foreach (var constituents in history)
        {
            // Select the 2 assets with the smallest weights in the ETF on this day.
            var dailyLargest = constituents.Select(c => c as ETFConstituentData).OrderByDescending(c => c.Weight).Take(2);
        }
    }
}</pre>
    <pre class="python">class USEquityUniverseHistoryAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 12, 23)
        # Add a universe of US Equities based on the constituents of an ETF.
        universe = self.add_universe(self.universe.etf('SPY'))
        # Get 5 days of history for the universe.
        history = self.history(universe, timedelta(5), flatten=True)</pre>
</div>

<img class='python docs-image' src='<?=$imgLink?>' alt='DataFrame of universe data for an asset.'>

<div class="python section-example-container">
    <pre class="python"># Select the 2 assets with the smallest weights in the ETF each day.
daily_smallest = history.groupby('time').apply(lambda x: x.nsmallest(2, 'weight')).reset_index(level=1, drop=True).weight</pre>
</div>

<div class="python section-example-container">
    <pre>time        symbol            
2024-12-19  AMTMW YM37RIGZUD0L    0.000053
            NWSVV VHJF6S7EZRL1    0.000068
2024-12-20  AMTMW YM37RIGZUD0L    0.000051
            NWSVV VHJF6S7EZRL1    0.000069
2024-12-21  AMTMW YM37RIGZUD0L    0.000048
            NWSVV VHJF6S7EZRL1    0.000069
Name: weight, dtype: float64</pre>
</div>

<p class='python'>To get the data in the format of the objects that you receive in your universe filter function instead of a DataFrame, use <code>flatten=False</code>.</p>

<div class="python section-example-container">
    <pre class="python"># Get the historical universe data over the last 30 days in a Series where
# the values in the series are lists of the universe selection objects.
history = self.history(universe, timedelta(30), flatten=False)
# Iterate through each day of universe selection.
for (universe_symbol, end_time), constituents in history.items():
    # Select the 10 largest assets in the ETF on this day.
    largest = sorted(constituents, key=lambda c: c.weight)[-10:]</pre>
</div>
