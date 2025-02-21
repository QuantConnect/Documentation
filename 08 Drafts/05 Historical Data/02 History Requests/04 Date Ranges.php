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
      <td>324.87</td>
      <td>324.870</td>
      <td>322.535</td>
      <td>323.58</td>
      <td>48912531.0</td>
    </tr>
    <tr>
      <th>2020-01-03 16:00:00</th>
      <td>322.41</td>
      <td>323.640</td>
      <td>321.110</td>
      <td>321.19</td>
      <td>60780998.0</td>
    </tr>
    <tr>
      <th>2020-01-06 16:00:00</th>
      <td>323.64</td>
      <td>323.730</td>
      <td>320.360</td>
      <td>320.44</td>
      <td>43759922.0</td>
    </tr>
    <tr>
      <th>2020-01-07 16:00:00</th>
      <td>322.73</td>
      <td>323.535</td>
      <td>322.240</td>
      <td>323.02</td>
      <td>35212108.0</td>
    </tr>
    <tr>
      <th>2020-01-08 16:00:00</th>
      <td>324.45</td>
      <td>325.780</td>
      <td>322.680</td>
      <td>322.99</td>
      <td>57728195.0</td>
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
      <td>369.00</td>
      <td>369.000</td>
      <td>367.450</td>
      <td>368.06</td>
      <td>21624848.0</td>
    </tr>
    <tr>
      <th>2020-12-28 16:00:00</th>
      <td>372.17</td>
      <td>372.590</td>
      <td>371.070</td>
      <td>371.81</td>
      <td>32833964.0</td>
    </tr>
    <tr>
      <th>2020-12-29 16:00:00</th>
      <td>371.46</td>
      <td>374.000</td>
      <td>370.830</td>
      <td>373.85</td>
      <td>45884792.0</td>
    </tr>
    <tr>
      <th>2020-12-30 16:00:00</th>
      <td>371.99</td>
      <td>373.080</td>
      <td>371.580</td>
      <td>372.38</td>
      <td>43472434.0</td>
    </tr>
    <tr>
      <th>2020-12-31 16:00:00</th>
      <td>373.88</td>
      <td>374.642</td>
      <td>371.240</td>
      <td>371.85</td>
      <td>55021528.0</td>
    </tr>
  </tbody>
</table>

<p>
  If there is no data for the date range you request, the result is empty.
  For more information about missing data points, see <a href='/docs/v2/writing-algorithms/historical-data/history-responses#05-Missing-Data-Points'>Missing Data Points</a>.
</p>
