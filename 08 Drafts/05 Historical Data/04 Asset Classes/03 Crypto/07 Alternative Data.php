<p class='csharp'>
  To get historical alternative data, call the <code>History&lt;<span class='placeholder-text'>alternativeDataClass</span>&gt;</code> method with the dataset <code>Symbol</code>.
</p>

<p class='python'>
  To get historical alternative data, call the <code>history</code> method with the dataset <code>Symbol</code>.
  This method returns a DataFrame that contains the data point attributes.
</p>

<div class="section-example-container">
    <pre class="csharp">public class CryptoAlternativeDataHistoryAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2024, 12, 19);
        // Get the Symbol of an asset.
        var symbol = AddEquity("BTCUSD").Symbol;
        // Add the alternative dataset and save a reference to its Symbol.
        var datasetSymbol = AddData&lt;BitcoinMetadata&gt;(symbol).Symbol;
        // Get the trailing 5 days of alternative data for the asset.
        var history = History&lt;BitcoinMetadata&gt;(datasetSymbol, 5, Resolution.Daily);
        // Iterate each data point and access its attributes.
        foreach (var dataPoint in history)
        {
            var t = dataPoint.EndTime;
            var marketCap = dataPoint.MarketCapitalization;
        }
    }
}</pre>
    <pre class="python">class CryptoAlternativeDataHistoryAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 12, 19)
        # Get the Symbol of an asset.
        symbol = self.add_crypto('BTCUSD').symbol
        # Add the alternative dataset and save a reference to its Symbol.
        dataset_symbol = self.add_data(BitcoinMetadata, symbol).symbol
        # Get the trailing 5 days of alternative data for the asset in DataFrame format.
        history = self.history(dataset_symbol, 5, Resolution.DAILY)</pre>
</div>

<div class="dataframe-wrapper">
<table class="dataframe python">
  <thead>
    <tr style="text-align: right;">
      <th></th>
      <th></th>
      <th>averageblocksize</th>
      <th>blockchainsize</th>
      <th>costpercentoftransactionvolume</th>
      <th>costpertransaction</th>
      <th>difficulty</th>
      <th>estimatedtransactionvolume</th>
      <th>estimatedtransactionvolumeusd</th>
      <th>hashrate</th>
      <th>marketcapitalization</th>
      <th>mediantransactionconfirmationtime</th>
      <th>...</th>
      <th>mywalletnumberofusers</th>
      <th>numberoftransactionperblock</th>
      <th>numberoftransactions</th>
      <th>numberoftransactionsexcludingpopularaddresses</th>
      <th>numberofuniquebitcoinaddressesused</th>
      <th>totalbitcoins</th>
      <th>totalnumberoftransactions</th>
      <th>totaloutputvolume</th>
      <th>totaltransactionfees</th>
      <th>totaltransactionfeesusd</th>
    </tr>
    <tr>
      <th>symbol</th>
      <th>time</th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
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
      <th rowspan="5" valign="top">BTCUSD.BitcoinMetadata</th>
      <th>2024-12-15</th>
      <td>1.5554</td>
      <td>622522.4629</td>
      <td>0.9645</td>
      <td>99.7845</td>
      <td>1.039196e+14</td>
      <td>52757.2240</td>
      <td>5.353586e+09</td>
      <td>8.213738e+08</td>
      <td>1.999814e+12</td>
      <td>7.5167</td>
      <td>...</td>
      <td>89780467.0</td>
      <td>3254.6038</td>
      <td>517482.0</td>
      <td>517269.0</td>
      <td>547370.0</td>
      <td>1.979622e+07</td>
      <td>1.131425e+09</td>
      <td>430661.4198</td>
      <td>11.9933</td>
      <td>1.216796e+06</td>
    </tr>
    <tr>
      <th>2024-12-16</th>
      <td>1.5014</td>
      <td>622769.7569</td>
      <td>0.6667</td>
      <td>118.2001</td>
      <td>1.039196e+14</td>
      <td>66749.2576</td>
      <td>6.822123e+09</td>
      <td>7.077246e+08</td>
      <td>2.038652e+12</td>
      <td>7.8167</td>
      <td>...</td>
      <td>89784782.0</td>
      <td>2762.9708</td>
      <td>378527.0</td>
      <td>378325.0</td>
      <td>540459.0</td>
      <td>1.979658e+07</td>
      <td>1.131942e+09</td>
      <td>435592.1612</td>
      <td>9.5660</td>
      <td>9.890319e+05</td>
    </tr>
    <tr>
      <th>2024-12-17</th>
      <td>1.5993</td>
      <td>622975.3876</td>
      <td>0.2560</td>
      <td>120.9025</td>
      <td>1.082427e+14</td>
      <td>185055.0677</td>
      <td>1.948991e+10</td>
      <td>7.963548e+08</td>
      <td>2.096909e+12</td>
      <td>7.6167</td>
      <td>...</td>
      <td>89788382.0</td>
      <td>2788.8311</td>
      <td>412747.0</td>
      <td>412506.0</td>
      <td>628257.0</td>
      <td>1.979710e+07</td>
      <td>1.132320e+09</td>
      <td>944149.5981</td>
      <td>12.3276</td>
      <td>1.295375e+06</td>
    </tr>
    <tr>
      <th>2024-12-18</th>
      <td>1.6135</td>
      <td>623212.2483</td>
      <td>0.2910</td>
      <td>111.2104</td>
      <td>1.085226e+14</td>
      <td>150308.1866</td>
      <td>1.603829e+10</td>
      <td>7.336779e+08</td>
      <td>2.099723e+12</td>
      <td>8.2167</td>
      <td>...</td>
      <td>89792154.0</td>
      <td>3086.0809</td>
      <td>419707.0</td>
      <td>419484.0</td>
      <td>587955.0</td>
      <td>1.979750e+07</td>
      <td>1.132733e+09</td>
      <td>899983.4775</td>
      <td>12.7376</td>
      <td>1.359282e+06</td>
    </tr>
    <tr>
      <th>2024-12-19</th>
      <td>1.5915</td>
      <td>623431.5971</td>
      <td>0.3013</td>
      <td>116.3402</td>
      <td>1.085226e+14</td>
      <td>154047.1023</td>
      <td>1.600095e+10</td>
      <td>7.768354e+08</td>
      <td>2.034833e+12</td>
      <td>8.3833</td>
      <td>...</td>
      <td>89797436.0</td>
      <td>2877.5417</td>
      <td>414366.0</td>
      <td>414128.0</td>
      <td>583495.0</td>
      <td>1.979795e+07</td>
      <td>1.133152e+09</td>
      <td>863124.9378</td>
      <td>13.1134</td>
      <td>1.363842e+06</td>
    </tr>
  </tbody>
</table>
</div>


<div class="python section-example-container">
    <pre class="python"># Calculate the growth in market cap.
growth = history.marketcapitalization.pct_change().iloc[1:]</pre>
</div>

<div class="python section-example-container">
    <pre>symbol                  time      
BTCUSD.BitcoinMetadata  2024-12-16    0.019420
                        2024-12-17    0.028577
                        2024-12-18    0.001342
                        2024-12-19   -0.030904
Name: marketcapitalization, dtype: float64</pre>
</div>

<p>For information on historical data for other alternative datasets, see the documentation in the <a href='/datasets/'>Dataset Market</a>.</p>
