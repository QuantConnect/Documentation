<p>Performance chart displays series of metrics to help you analyze the computational performance of your algorithm for code optimization purposes. The following table describes the series displayed on the chart:</p>

<table class="qc-table table">
  <thead>
    <tr>
      <th style="width: 25%">Series<br></th>
      <th style="width: 75%">Description</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>CPU</td>
      <td>Total CPU usage as a percentage.</td>
    </tr>
    <tr>
      <td>ManagedRAM</td>
      <td>RAM used on the machine.</td>
    </tr>
    <tr>
      <td>TotalRAM</td>
      <td>Amount of private memory allocated for the current process (includes both managed and unmanaged memory).</td>
    </tr>
    <tr>
      <td>ActiveSecurities</td>
      <td>The number of <a href="/docs/v2/writing-algorithms/universes/key-concepts#10-Active-Securities">active securities</a>. An active security is a security that is currently selected by the universe or has holdings or open orders.</td>
    </tr>
    <tr>
      <td>DataPoints</td>
      <td>The number of data points processed per second.</td>
    </tr>
    <tr>
      <td>HistoryDataPoints</td>
      <td>The number of data points of algorithm history provider.</td>
    </tr>
    <tr>
      <td>Subscriptions</td>
      <td>The total execution time reading data subscriptions, measured in seconds, recorded since the last sampling event.</td>
    </tr>
    <tr>
      <td>Selection</td>
      <td>The total execution time adding and removing securities of <a href="/docs/v2/writing-algorithms/universes/key-concepts">universe selection</a>, measured in seconds, recorded since the last sampling event. It includes the time spent in universe selection functions.</td>
    </tr>
    <tr>
      <td>Slice</td>
      <td>The total creation time of a <a href="/docs/v2/writing-algorithms/key-concepts/time-modeling/timeslices">time slice</a>, measured in seconds, recorded since the last sampling event. The <code>Slice</code> object contains the data used to update the algorithm state.</td>
    </tr>
    <tr>
      <td>Schedule</td>
      <td>The total execution time of <a href="/docs/v2/writing-algorithms/scheduled-events">scheduled events</a>, measured in seconds, recorded since the last sampling event.</td>
    </tr>
    <tr>
      <td>Consolidators</td>
      <td>The total execution time of <a href="/docs/v2/writing-algorithms/consolidating-data/getting-started">data consolidation events</a>, measured in seconds, recorded since the last sampling event. It includes the time spent updating indicators and executing consolidator handlers.</td>
    </tr>
    <tr>
      <td>Securities</td>
      <td>The total execution time of <a href="/docs/v2/writing-algorithms/securities/key-concepts">security updates</a>, measured in seconds, recorded since the last sampling event. It includes the time spent in <a href="/docs/v2/writing-algorithms/universes/key-concepts#06-Security-Changed-Events">security change events</a> and <a href="/docs/v2/writing-algorithms/securities/asset-classes/us-equity/corporate-actions#04-Symbol-Changes">symbol change events.</td>
    </tr>
    <tr>
      <td>Transactions</td>
      <td>The total execution time of <a href="/docs/v2/writing-algorithms/trading-and-orders/key-concepts">order events</a>, measured in seconds, recorded since the last sampling event. For example, processing order fills, cancellations, and updates such as moving trailing stops. It includes the time spent in <a href="https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/order-events">order events handlers</a>.</td>
    </tr>
    <tr>
      <td>SplitsDividendsDelisting</td>
      <td>The total execution time of <a href="/docs/v2/writing-algorithms/securities/asset-classes/us-equity/corporate-actions">corporate action events</a>, measured in seconds, recorded since the last sampling event.</td>
    </tr>
    <tr>
      <td>OnData</td>
      <td>The total execution time of <code class="csharp">OnData</code><code class="python">on_data</code> method call and <code class="csharp">Alpha.Update</code><code class="python">alpha.update</code>, measured in seconds, recorded since the last sampling event.</td>
    </tr>
  </tbody>
</table>

<p>The Performance chart is disabled by default. To enable this chart, set the <span class="csharp"><code>Settings.PerformanceSamplePeriod</code> property</span><span class="python"><code>self.settings.performance_sample_period</code> attribute</span> to the desired sampling period:</p>

<div class="section-example-container">
    <pre class="csharp">Settings.PerformanceSamplePeriod = TimeSpan.FromDays(7);</pre>
    <pre class="python">self.settings.performance_sample_period = timedelta(7)</pre>
</div>