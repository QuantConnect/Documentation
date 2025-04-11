<p>
    <span class='csharp'>To get daily data on all the tradable contracts for a given date, call the <code class='csharp'>History&lt;FutureUniverse&gt;</code><code> method with the continuous Futures contract <code>Symbol</code>.</code>
    <span class='python'>
        To get daily data on all the tradable contracts for a given date, call the <code>history</code> method with the <code>FutureUniverse</code> type and the continuous contract <code>Symbol</code>. 
        If you pass <code>flatten=True</code>, this method returns a DataFrame with columns for the data point attributes.
    </span>

    The result contains the entire Futures chain for each trading day, not the subset of contracts that pass your universe filter. 
    The daily Futures chains contain the prices, volume, and open interest.
</p>

<div class="section-example-container">
    <pre class="csharp">// Get the daily contract objects during Q1 2025.
var history = History&lt;FutureUniverse&gt;(future.Symbol, new DateTime(2025, 1, 1), new DateTime(2025, 4, 1)); 
// Iterate through each day of the history.
foreach (var futureUniverse in history)
{
    var t = futureUniverse.EndTime;
    // Select the contract with the greatest open interest.
    var mostOi = futureUniverse.Select(c => c as FutureUniverse).OrderByDescending(c => c.OpenInterest).First();
}</pre>
    <pre class="python"># Get the daily contract objects during Q1 2025. 
history = self.history(FutureUniverse, future.symbol, datetime(2025, 1, 1), datetime(2025, 4, 1), flatten=True)</pre>
</div>

<div class='dataframe-wrapper'>
<table class="dataframe python">
  <thead>
    <tr style="text-align: right;">
      <th></th>
      <th></th>
      <th>close</th>
      <th>high</th>
      <th>low</th>
      <th>open</th>
      <th>openinterest</th>
      <th>value</th>
      <th>volume</th>
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
      <th rowspan="5" valign="top">2025-01-03</th>
      <th>ES YQYHC5L1GPA9</th>
      <td>5920.000</td>
      <td>5985.000</td>
      <td>5874.750</td>
      <td>5967.750</td>
      <td>2068557.0</td>
      <td>5920.000</td>
      <td>1496415.0</td>
    </tr>
    <tr>
      <th>ES YTG30NVEFCW1</th>
      <td>5975.750</td>
      <td>6037.500</td>
      <td>5930.000</td>
      <td>6018.750</td>
      <td>7388.0</td>
      <td>5975.750</td>
      <td>1305.0</td>
    </tr>
    <tr>
      <th>ES YVXOP65RE0HT</th>
      <td>6030.250</td>
      <td>6030.250</td>
      <td>6000.000</td>
      <td>6017.750</td>
      <td>393.0</td>
      <td>6030.250</td>
      <td>13.0</td>
    </tr>
    <tr>
      <th>ES YYFADOG4CO3L</th>
      <td>6086.625</td>
      <td>6185.750</td>
      <td>5938.875</td>
      <td>6133.375</td>
      <td>1152.0</td>
      <td>6086.625</td>
      <td>0.0</td>
    </tr>
    <tr>
      <th>ES Z0WW26QHBBPD</th>
      <td>6139.000</td>
      <td>6187.375</td>
      <td>6113.375</td>
      <td>6187.375</td>
      <td>0.0</td>
      <td>6139.000</td>
      <td>0.0</td>
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
      <th rowspan="2" valign="top">2025-03-29</th>
      <th>ES Z0WW26QHBBPD</th>
      <td>5750.000</td>
      <td>5750.000</td>
      <td>5750.000</td>
      <td>5750.000</td>
      <td>6.0</td>
      <td>5750.000</td>
      <td>1.0</td>
    </tr>
    <tr>
      <th>ES Z3EHQP0U9ZB5</th>
      <td>0.000</td>
      <td>0.000</td>
      <td>0.000</td>
      <td>0.000</td>
      <td>0.0</td>
      <td>0.000</td>
      <td>0.0</td>
    </tr>
    <tr>
      <th rowspan="3" valign="top">2025-04-01</th>
      <th>ES YTG30NVEFCW1</th>
      <td>5644.500</td>
      <td>5672.750</td>
      <td>5533.750</td>
      <td>5563.500</td>
      <td>2082053.0</td>
      <td>5644.500</td>
      <td>1766518.0</td>
    </tr>
    <tr>
      <th>ES YVXOP65RE0HT</th>
      <td>5691.750</td>
      <td>5719.750</td>
      <td>5580.000</td>
      <td>5612.000</td>
      <td>7042.0</td>
      <td>5691.750</td>
      <td>632.0</td>
    </tr>
    <tr>
      <th>ES YYFADOG4CO3L</th>
      <td>5735.000</td>
      <td>5821.125</td>
      <td>5617.750</td>
      <td>5654.250</td>
      <td>2540.0</td>
      <td>5735.000</td>
      <td>0.0</td>
    </tr>
  </tbody>
</table>
</div>

<div class="python section-example-container">
    <pre class="python"># Select the contract with the largest open interest each day.
most_oi = history.groupby('time').apply(lambda x: x.nlargest(1, 'openinterest')).reset_index(level=1, drop=True).openinterest</pre>
</div>

<div class="python section-example-container">
    <pre>time        symbol         
2025-01-03  ES YQYHC5L1GPA9    2068557.0
2025-01-04  ES YQYHC5L1GPA9    2068557.0
2025-01-07  ES YQYHC5L1GPA9    2048207.0
2025-01-08  ES YQYHC5L1GPA9    2058863.0
2025-01-09  ES YQYHC5L1GPA9    2047807.0
                                 ...    
2025-03-26  ES YTG30NVEFCW1    2086227.0
2025-03-27  ES YTG30NVEFCW1    2064932.0
2025-03-28  ES YTG30NVEFCW1    2070698.0
2025-03-29  ES YTG30NVEFCW1    2064619.0
2025-04-01  ES YTG30NVEFCW1    2082053.0
Name: openinterest, Length: 62, dtype: float64</pre>
</div>

<p class='python'>
  If you intend to use the data in the DataFrame to create <code>FutureUniverse</code> objects, request that the history request returns the data type you need. 
  Otherwise, LEAN consumes unnecessary computational resources populating the DataFrame.  
  To get a list of <code>FutureUniverse</code> objects instead of a DataFrame, call the <code>history[FutureUniverse]</code> method.
</p>

<div class="python section-example-container">
    <pre class="python"># Get the 5 trailing daily FutureUniverse objects in FutureUniverse format. 
history = self.history[FutureUniverse](future.symbol, 5, Resolution.DAILY)
# Iterate through the FutureUniverse objects and access their volumes.
for future_universe in history:
    t = future_universe.end_time
    most_oi = sorted(future_universe, key=lambda contract: contract.open_interest)[-1]</pre>
</div>

<p>The method represents each contract with an <code>FutureUniverse</code> object, which have the following properties:</p>

<div data-tree='QuantConnect.Data.UniverseSelection.FutureUniverse'></div>
