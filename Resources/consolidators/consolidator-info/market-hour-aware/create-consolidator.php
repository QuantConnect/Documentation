<p>
    To create a market hour aware consolidator, pass the following arguments to the <code>MarketHourAwareConsolidator</code> constructor:
</p>

<table class='qc-table table'>
    <thead>
        <tr>
            <th>Argument</th>
            <th>Data Type</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code class='csharp'>dailyStrictEndTimeEnabled</code><code class='python'>daily_strict_end_time_enabled</code></td>
            <td><code>bool</code></td>
            <td>Whether daily bars should have an <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/periods#03-Start-and-End-Time'><span class='csharp'>EndTime</span><span class='python'>end_time</span></a> that matches the market close time (<code class='python'>True</code><code class='csharp'>true</code>) instead of the following midnight (<code class='python'>False</code><code class='csharp'>false</code>). This argument usually matches your <a href='/docs/v2/writing-algorithms/initialization#10-Set-Algorithm-Settings'><code class='csharp'>DailyPreciseEndTime</code><code class='python'>daily_precise_end_time</code></a> setting.</td>
        </tr>
        <tr>
            <td><code>period</code></td>
            <td><code class='csharp'>TimeSpan</code><code class='python'>timedelta</code></td>
            <td>The consolidation period. The consolidator anchors intraday periods to the market open, so a bar never spans across closed market hours.</td>
        </tr>
        <tr>
            <td><code class='csharp'>dataType</code><code class='python'>data_type</code></td>
            <td><code class='csharp'>Type</code><code class='python'>type</code></td>
            <td>The type of the input data. In this case, use <code><?=$this->dataTypeArgPy?></code>.</td>
        </tr>
        <tr>
            <td><code class='csharp'>tickType</code><code class='python'>tick_type</code></td>
            <td><code>TickType</code></td>
            <td>The type of data to consolidate. In this case, use <code class='csharp'><?=$this->dataTypeArgC?></code><code class='python'><?=$this->dataTypeArgPy?></code>.</td>
        </tr>
        <tr>
            <td><code class='csharp'>extendedMarketHours</code><code class='python'>extended_market_hours</code></td>
            <td><code>bool</code></td>
            <td>Whether to consolidate data from extended market hours. If <code class='python'>False</code><code class='csharp'>false</code>, the consolidator ignores data that occurs while the market is closed and anchors bars to the regular market open. If <code class='python'>True</code><code class='csharp'>true</code>, it anchors bars to the start of the extended market hours.</td>
        </tr>
    </tbody>
</table>


<div class='section-example-container'>
    <pre class='csharp'>_consolidator = new MarketHourAwareConsolidator(true, TimeSpan.FromMinutes(7), <?=$this->dataTypeArgC?>, <?=$this->tickTypeArgC?>, false);</pre>
    <pre class='python'>self._consolidator = MarketHourAwareConsolidator(True, timedelta(minutes=7), <?=$this->dataTypeArgPy?>, <?=$this->tickTypeArgPy?>, False)</pre>
</div>
