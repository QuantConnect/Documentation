<p>
  To get historical <a href='/docs/v2/research-environment/datasets/futures/individual-contracts#03-Trade-History'>trade</a>, <a href='/docs/v2/research-environment/datasets/futures/individual-contracts#04-Quote-History'>quote</a>, or <a href='/docs/v2/research-environment/datasets/futures/individual-contracts#05-Tick-History'>tick</a> data for the <a href='/docs/v2/writing-algorithms/securities/asset-classes/futures/requesting-data/universes#03-Continous-Contracts'>continuous contract</a>, use the <code class='python'>symbol</code><code class='csharp'>Symbol</code> property of the <code>Future</code> object when you make the history request.
</p>

<div class="section-example-container">
    <pre class="csharp">// Get the daily TradeBar objects of the continuous contract. 
var history = qb.History&lt;TradeBar&gt;(future.Symbol, new DateTime(2025, 1, 1), new DateTime(2025, 4, 1), Resolution.Daily);</pre>
    <pre class="python"># Get the daily TradeBar objects of the continuous contract. 
history = qb.history(TradeBar, future.symbol, datetime(2025, 1, 1), datetime(2025, 4, 1), Resolution.DAILY)</pre>
</div>

<div class='dataframe-wrapper'>
<table class="dataframe python">
  <thead>
    <tr style="text-align: right;">
      <th></th>
      <th></th>
      <th></th>
      <th>close</th>
      <th>high</th>
      <th>low</th>
      <th>open</th>
      <th>volume</th>
    </tr>
    <tr>
      <th>expiry</th>
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
      <th rowspan="11" valign="top">1899-12-30</th>
      <th rowspan="11" valign="top">/ES</th>
      <th>2025-01-02 17:00:00</th>
      <td>5972.236830</td>
      <td>6037.810377</td>
      <td>5926.587554</td>
      <td>6020.408166</td>
      <td>1496415.0</td>
    </tr>
    <tr>
      <th>2025-01-03 17:00:00</th>
      <td>6038.062583</td>
      <td>6049.664056</td>
      <td>5986.360364</td>
      <td>5997.457425</td>
      <td>1009229.0</td>
    </tr>
    <tr>
      <th>2025-01-06 17:00:00</th>
      <td>6081.442006</td>
      <td>6121.794957</td>
      <td>6056.978029</td>
      <td>6085.981713</td>
      <td>1207465.0</td>
    </tr>
    <tr>
      <th>2025-01-07 17:00:00</th>
      <td>6007.293457</td>
      <td>6098.844216</td>
      <td>5987.369187</td>
      <td>6095.313333</td>
      <td>1543464.0</td>
    </tr>
    <tr>
      <th>2025-01-08 17:00:00</th>
      <td>6007.293457</td>
      <td>6024.443462</td>
      <td>5969.210359</td>
      <td>6006.536839</td>
      <td>1436052.0</td>
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
      <th>2025-03-25 17:00:00</th>
      <td>5832.000000</td>
      <td>5837.250000</td>
      <td>5809.500000</td>
      <td>5826.000000</td>
      <td>842430.0</td>
    </tr>
    <tr>
      <th>2025-03-26 17:00:00</th>
      <td>5754.750000</td>
      <td>5834.500000</td>
      <td>5743.000000</td>
      <td>5824.000000</td>
      <td>1269497.0</td>
    </tr>
    <tr>
      <th>2025-03-27 17:00:00</th>
      <td>5741.250000</td>
      <td>5779.750000</td>
      <td>5720.000000</td>
      <td>5742.750000</td>
      <td>1287285.0</td>
    </tr>
    <tr>
      <th>2025-03-28 17:00:00</th>
      <td>5602.500000</td>
      <td>5731.250000</td>
      <td>5602.250000</td>
      <td>5723.500000</td>
      <td>1595221.0</td>
    </tr>
    <tr>
      <th>2025-03-31 17:00:00</th>
      <td>5644.500000</td>
      <td>5672.750000</td>
      <td>5533.750000</td>
      <td>5563.500000</td>
      <td>1766518.0</td>
    </tr>
  </tbody>
</table>
</div>
