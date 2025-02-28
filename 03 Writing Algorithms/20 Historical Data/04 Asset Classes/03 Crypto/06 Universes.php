<p class='csharp'>
  To get historical <a href="/docs/v2/writing-algorithms/universes/crypto">universe data</a>, call the <code>History</code> method with the <code>Universe</code> object.
  This method doesn't apply your <a href='/docs/v2/writing-algorithms/universes/key-concepts#05-Selection-Functions'>selection function</a>.
  It returns all of the universe data. 
</p>

<p class='python'>
  To get historical <a href="/docs/v2/writing-algorithms/universes/crypto">universe data</a>, call the <code>history</code> method with the <code>Universe</code> object.
  This method doesn't apply your <a href='/docs/v2/writing-algorithms/universes/key-concepts#05-Selection-Functions'>selection function</a>.
  It returns all of the universe data in a DataFrame with columns for the data point attributes.
</p>

<div class="section-example-container">
    <pre class="csharp">public class CryptoUniverseHistoryAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2024, 12, 23);
        // Add a universe of Cryptocurrencies on Coinbase.
        var universe = AddUniverse(CryptoUniverse.Coinbase());
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
    <pre class="python">class CryptoUniverseHistoryAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 12, 23)
        # Add a universe of Cryptocurrencies on Coinbase.
        universe = self.add_universe(CryptoUniverse.coinbase())
        # Get 5 days of history for the universe.
        history = self.history(universe, timedelta(5), flatten=True)</pre>
</div>

<div class="dataframe-wrapper">
  <table class="dataframe python">
    <thead>
      <tr style="text-align: right;">
        <th></th>
        <th></th>
        <th>close</th>
        <th>high</th>
        <th>low</th>
        <th>open</th>
        <th>price</th>
        <th>volume</th>
        <th>volumeinusd</th>
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
        <th></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th rowspan="5" valign="top">2024-12-19</th>
        <th>00USD 2XR</th>
        <td>0.050300</td>
        <td>0.051500</td>
        <td>0.049200</td>
        <td>0.051500</td>
        <td>0.050300</td>
        <td>3.173495e+06</td>
        <td>1.596268e+05</td>
      </tr>
      <tr>
        <th>00USDC 2XR</th>
        <td>0.050300</td>
        <td>0.051500</td>
        <td>0.049200</td>
        <td>0.051500</td>
        <td>0.050300</td>
        <td>3.173495e+06</td>
        <td>1.596268e+05</td>
      </tr>
      <tr>
        <th>1INCHBTC 2XR</th>
        <td>0.000004</td>
        <td>0.000005</td>
        <td>0.000004</td>
        <td>0.000005</td>
        <td>0.000004</td>
        <td>9.815380e+03</td>
        <td>4.584371e+03</td>
      </tr>
      <tr>
        <th>1INCHEUR 2XR</th>
        <td>0.446000</td>
        <td>0.467000</td>
        <td>0.442000</td>
        <td>0.465000</td>
        <td>0.446000</td>
        <td>9.007325e+04</td>
        <td>4.215390e+04</td>
      </tr>
      <tr>
        <th>1INCHGBP 2XR</th>
        <td>0.369000</td>
        <td>0.385000</td>
        <td>0.366000</td>
        <td>0.381000</td>
        <td>0.369000</td>
        <td>3.932220e+04</td>
        <td>1.845807e+04</td>
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
        <td>...</td>
      </tr>
      <tr>
        <th rowspan="5" valign="top">2024-12-23</th>
        <th>ZETAUSDC 2XR</th>
        <td>0.574500</td>
        <td>0.638600</td>
        <td>0.562600</td>
        <td>0.598400</td>
        <td>0.574500</td>
        <td>1.522481e+06</td>
        <td>8.746654e+05</td>
      </tr>
      <tr>
        <th>ZROUSD 2XR</th>
        <td>5.487000</td>
        <td>6.363000</td>
        <td>5.393000</td>
        <td>5.635000</td>
        <td>5.487000</td>
        <td>1.312069e+05</td>
        <td>7.199321e+05</td>
      </tr>
      <tr>
        <th>ZROUSDC 2XR</th>
        <td>5.487000</td>
        <td>6.363000</td>
        <td>5.393000</td>
        <td>5.635000</td>
        <td>5.487000</td>
        <td>1.312069e+05</td>
        <td>7.199321e+05</td>
      </tr>
      <tr>
        <th>ZRXUSD 2XR</th>
        <td>0.449944</td>
        <td>0.509425</td>
        <td>0.441590</td>
        <td>0.476001</td>
        <td>0.449944</td>
        <td>3.371118e+06</td>
        <td>1.516814e+06</td>
      </tr>
      <tr>
        <th>ZRXUSDC 2XR</th>
        <td>0.449944</td>
        <td>0.509425</td>
        <td>0.441590</td>
        <td>0.476001</td>
        <td>0.449944</td>
        <td>3.371118e+06</td>
        <td>1.516814e+06</td>
      </tr>
    </tbody>
  </table>
</div>

<div class="python section-example-container">
    <pre class="python"># Select the 2 assets with the largest dollar volume each day.
most_traded = history.groupby('time').apply(lambda x: x.nlargest(2, 'volumeinusd')).reset_index(level=1, drop=True).volumeinusd</pre>
</div>

<div class="python section-example-container">
    <pre>time        symbol     
2024-12-19  BTCUSD         1.245065e+09
            BTCUSDC 2XR    1.245065e+09
2024-12-20  BTCUSD         2.169212e+09
            BTCUSDC 2XR    2.169212e+09
2024-12-21  BTCUSD         2.231721e+09
            BTCUSDC 2XR    2.231721e+09
2024-12-22  BTCUSD         2.077999e+09
            BTCUSDC 2XR    2.077999e+09
2024-12-23  BTCUSD         6.169765e+08
            BTCUSDC 2XR    6.169765e+08
Name: volumeinusd, dtype: float64</pre>
</div>

<p class='python'>
  To get the data in the format of the objects that you receive in your universe filter function instead of a DataFrame, use <code>flatten=False</code>.
  This call returns a Series where the values are lists of the universe data objects.
</p>

<div class="python section-example-container">
    <pre class="python"># Get the historical universe data over the last 30 days in a Series where
# the values in the series are lists of the universe selection objects.
history = self.history(universe, timedelta(30), flatten=False)
# Iterate through each day of universe selection.
for (universe_symbol, end_time), constituents in history.items():
    # Select the 10 assets with the largest dollar volume this day.
    most_liquid = sorted(constituents, key=lambda c: c.volume_in_usd)[-10:]</pre>
</div>
