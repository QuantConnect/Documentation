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
    <pre class="csharp">// Add a universe of US Equities based on an alternative dataset.
var universe = AddUniverse&lt;BrainStockRankingUniverse&gt;();
// Get 5 days of history for the universe.
var history = History(universe, TimeSpan.FromDays(5));</pre>
    <pre class="python"># Add a universe of US Equities based on an alternative dataset.
universe = self.add_universe(BrainStockRankingUniverse)
# Get 5 days of history for the universe.
history = self.history(universe, timedelta(5), flatten=True)</pre>
</div>

<img class='python docs-image' src='<?=$imgLink?>' alt='DataFrame of the last 5 days of a US Equity alternative data universe.'>

<p class='python'>To get the data in the format of the objects that you receive in your universe filter function instead of a DataFrame, use <code>flatten=False</code>.</p>

<div class="python section-example-container">
    <pre class="python"># Get the historical universe data over the last 30 days in a Series where
# the values in the series are lists of the universe selection objects.
history = self.history(universe, timedelta(30), flatten=False)</pre>
</div>
