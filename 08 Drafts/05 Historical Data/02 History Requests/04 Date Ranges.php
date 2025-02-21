<p>
  To get historical data for a specific date range, call <code class='python'>history</code><code class='csharp'>History</code> method with start and end <code class='python'>datetime</code><code class='csharp'>DateTime</code> objects. 
  The <code class='csharp'>DateTime</code><code class='python'>datetime</code> objects you provide are based in the <a href='<?=$writingAlgorithms ? "/docs/v2/writing-algorithms/initialization#12-Set-Time-Zone" : "/docs/v2/research-environment/initialization#04-Set-Time-Zone" ?>'><?=$writingAlgorithms ? "algorithm" : "notebook" ?> time zone</a>.
</p>

<div class="section-example-container">
    <pre class="csharp">public class DateRangeHistoryAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2024, 12, 1);
        // Get the Symbol of an asset.
        var symbol = AddEquity("SPY").Symbol;
        // Get the daily-resolution TradeBar data of the asset during 2020.
        var history = History&lt;TradeBar&gt;(symbol, new DateTime(2020, 1, 1), new DateTime(2021, 1, 1), Resolution.Daily);
    }
}</pre>
    <pre class="python">class DateRangeHistoryAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 12, 1)
        # Get the Symbol of an asset.
        symbol = self.add_equity('SPY').symbol
        # Get the daily-resolution TradeBar data of the asset during 2020.
        history = self.history(TradeBar, symbol, datetime(2020, 1, 1), datetime(2021, 1, 1), Resolution.DAILY)</pre>
</div>

<table border="1" class="dataframe python">
  <thead>
    <tr style="text-align: right;">
      <th></th>
      <th></th>
      <th>close</th>
      <th>high</th>
      <th>low</th>
      <th>open</th>
      <th>volume</th>
    </tr>
    <tr>
      <th>symbol</th>
      <th>time</th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th rowspan="11" valign="top">SPY</th>
      <th>2020-01-02 16:00:00</th>
      <td>301.194352</td>
      <td>301.194352</td>
      <td>299.029520</td>
      <td>299.998363</td>
      <td>52757344.0</td>
    </tr>
    <tr>
      <th>2020-01-03 16:00:00</th>
      <td>298.913630</td>
      <td>300.053991</td>
      <td>297.708370</td>
      <td>297.782540</td>
      <td>65558742.0</td>
    </tr>
    <tr>
      <th>2020-01-06 16:00:00</th>
      <td>300.053991</td>
      <td>300.137432</td>
      <td>297.013028</td>
      <td>297.087198</td>
      <td>47199709.0</td>
    </tr>
    <tr>
      <th>2020-01-07 16:00:00</th>
      <td>299.210309</td>
      <td>299.956643</td>
      <td>298.756019</td>
      <td>299.479175</td>
      <td>37979987.0</td>
    </tr>
    <tr>
      <th>2020-01-08 16:00:00</th>
      <td>300.804960</td>
      <td>302.038033</td>
      <td>299.163953</td>
      <td>299.451361</td>
      <td>62265971.0</td>
    </tr>
    <tr>
      <th>...</th>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
    </tr>
    <tr>
      <th>2020-12-24 13:00:00</th>
      <td>348.496921</td>
      <td>348.496921</td>
      <td>347.033045</td>
      <td>347.609151</td>
      <td>22897100.0</td>
    </tr>
    <tr>
      <th>2020-12-28 16:00:00</th>
      <td>351.490783</td>
      <td>351.887446</td>
      <td>350.451904</td>
      <td>351.150786</td>
      <td>34765681.0</td>
    </tr>
    <tr>
      <th>2020-12-29 16:00:00</th>
      <td>350.820234</td>
      <td>353.219101</td>
      <td>350.225239</td>
      <td>353.077436</td>
      <td>48584327.0</td>
    </tr>
    <tr>
      <th>2020-12-30 16:00:00</th>
      <td>351.320785</td>
      <td>352.350220</td>
      <td>350.933566</td>
      <td>351.689115</td>
      <td>46030043.0</td>
    </tr>
    <tr>
      <th>2020-12-31 16:00:00</th>
      <td>353.105769</td>
      <td>353.825429</td>
      <td>350.612458</td>
      <td>351.188564</td>
      <td>58258603.0</td>
    </tr>
  </tbody>
</table>

<p>
  If there is no data for the date range you request, the result is empty.
  For more information about missing data points, see <a href='/docs/v2/writing-algorithms/historical-data/history-responses#05-Missing-Data-Points'>Missing Data Points</a>.
</p>
