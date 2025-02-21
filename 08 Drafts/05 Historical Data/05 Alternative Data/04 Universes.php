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

<table border="1" class="dataframe python">
  <thead>
    <tr style="text-align: right;">
      <th></th>
      <th></th>
      <th>rank10days</th>
      <th>rank21days</th>
      <th>rank2days</th>
      <th>rank3days</th>
      <th>rank5days</th>
      <th>value</th>
    </tr>
    <tr>
      <th>time</th>
      <th>symbol</th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th rowspan="5" valign="top">2024-12-18</th>
      <th>A RPTMYV3VC57P</th>
      <td>-0.001895</td>
      <td>0.005938</td>
      <td>-0.007858</td>
      <td>-0.006320</td>
      <td>0.001771</td>
      <td>-0.007858</td>
    </tr>
    <tr>
      <th>AAL VM9RIYHM8ACL</th>
      <td>-0.003977</td>
      <td>0.006520</td>
      <td>-0.005671</td>
      <td>-0.003738</td>
      <td>-0.004715</td>
      <td>-0.005671</td>
    </tr>
    <tr>
      <th>AAPL R735QTJ8XC9X</th>
      <td>0.027450</td>
      <td>0.037339</td>
      <td>0.006018</td>
      <td>0.001489</td>
      <td>0.010102</td>
      <td>0.006018</td>
    </tr>
    <tr>
      <th>ABBV VCY032R250MD</th>
      <td>-0.002814</td>
      <td>0.012297</td>
      <td>-0.001717</td>
      <td>-0.000679</td>
      <td>-0.007454</td>
      <td>-0.001717</td>
    </tr>
    <tr>
      <th>ABNB XK8H247DY6W5</th>
      <td>0.020533</td>
      <td>0.046173</td>
      <td>-0.002303</td>
      <td>0.007350</td>
      <td>0.011252</td>
      <td>-0.002303</td>
    </tr>
    <tr>
      <th>...</th>
      <th>...</th>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
    </tr>
    <tr>
      <th rowspan="5" valign="top">2024-12-23</th>
      <th>ZI XF2DKG2HHLK5</th>
      <td>-0.038118</td>
      <td>-0.037527</td>
      <td>-0.035904</td>
      <td>-0.041195</td>
      <td>-0.041388</td>
      <td>-0.035904</td>
    </tr>
    <tr>
      <th>ZM X3RPXTZRW09X</th>
      <td>0.006784</td>
      <td>0.020690</td>
      <td>-0.005674</td>
      <td>-0.008556</td>
      <td>-0.002120</td>
      <td>-0.005674</td>
    </tr>
    <tr>
      <th>ZS WSVU0MELFQED</th>
      <td>0.010422</td>
      <td>0.019619</td>
      <td>-0.003743</td>
      <td>-0.002079</td>
      <td>0.002778</td>
      <td>-0.003743</td>
    </tr>
    <tr>
      <th>ZTO WF2L9EOCSQCL</th>
      <td>-0.021455</td>
      <td>-0.015939</td>
      <td>-0.018053</td>
      <td>-0.020263</td>
      <td>-0.024979</td>
      <td>-0.018053</td>
    </tr>
    <tr>
      <th>ZTS VDRJHVQ4FNFP</th>
      <td>0.030589</td>
      <td>0.041400</td>
      <td>0.024744</td>
      <td>0.019891</td>
      <td>0.031048</td>
      <td>0.024744</td>
    </tr>
  </tbody>
</table>


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
