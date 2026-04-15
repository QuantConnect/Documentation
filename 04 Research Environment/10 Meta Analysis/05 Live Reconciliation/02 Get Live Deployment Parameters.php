<p>Follow these steps to read the live deployment's start datetime, starting equity, and end datetime — the three parameters the OOS backtest must match. All three values come from the live "Strategy Equity" chart, so you only need the project Id.</p>

<ol>
    <li>Define the project Id.</li>
    <div class="section-example-container">
        <pre class="csharp">var projectId = 23034953;</pre>
        <pre class="python">project_id = 23034953</pre>
    </div>

    <p>The following table provides links to documentation that explains how to get the project Id, depending on the platform you use:</p>

    <table class="qc-table table">
        <thead>
            <tr>
                <th style='width: 50%'>Platform</th>
                <th>Project Id</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Cloud Platform</td>
                <td><a href='/docs/v2/cloud-platform/projects/getting-started#13-Get-Project-Id'>Get Project Id</a></td>
            </tr>
            <tr>
                <td>Local Platform</td>
                <td><a href='/docs/v2/local-platform/projects/getting-started#14-Get-Project-Id'>Get Project Id</a></td>
            </tr>
            <tr>
                <td>CLI</td>
                <td><a href='/docs/v2/lean-cli/projects/project-management#07-Get-Project-Id'>Get Project Id</a></td>
            </tr>
        </tbody>
    </table>

    <li>Read the live "Strategy Equity" chart with the <code class="csharp">ReadLiveChart</code><code class="python">read_live_chart</code> method. The first and last <code>Equity</code> points give you the start datetime, starting equity, and end datetime.</li>
    <div class="section-example-container">
        <pre class="csharp">var nowSec = (int)DateTimeOffset.UtcNow.ToUnixTimeSeconds();

Chart ReadLiveChartWithRetry(int projectId, string chartName)
{
    for (var attempt = 0; attempt &lt; 10; attempt++)
    {
        var result = api.ReadLiveChart(projectId, chartName, 0, nowSec, 500);
        if (result.Success) return result.Chart;
        Console.WriteLine($"Chart data is loading... (attempt {attempt + 1}/10)");
        Thread.Sleep(10000);
    }
    throw new Exception($"Failed to read {chartName} chart after 10 attempts");
}

var strategyEquity = ReadLiveChartWithRetry(projectId, "Strategy Equity");
// The first few points in the series can have a null close, so keep only
// the points with a valid close value before extracting start/end.
var validValues = strategyEquity.Series["Equity"].Values
    .OfType&lt;Candlestick&gt;()
    .Where(v =&gt; v.Close.HasValue)
    .ToList();

// Start datetime and starting equity: first valid point.
var startDatetime = validValues.First().Time;
var startingCash = validValues.First().Close.Value;
// End datetime: last valid timestamp of the live Strategy Equity series.
// Uncomment the next line instead to reconcile up to "now" and see what
// would have happened had you not stopped the live algorithm:
// var endDatetime = DateTime.UtcNow;
var endDatetime = validValues.Last().Time;

Console.WriteLine($"Start (UTC): {startDatetime}");
Console.WriteLine($"Starting equity: ${startingCash:N2}");
Console.WriteLine($"End (UTC): {endDatetime}");</pre>
        <pre class="python">from datetime import datetime
from time import sleep, time

def read_chart(project_id, chart_name, start=0, end=int(time()), count=500):
    # Retry up to 10 times until the chart data finishes loading.
    for attempt in range(10):
        result = api.read_live_chart(project_id, chart_name, start, end, count)
        if result.success:
            return result.chart
        print(f"Chart data is loading... (attempt {attempt + 1}/10)")
        sleep(10)
    raise RuntimeError(f"Failed to read {chart_name} chart after 10 attempts")

strategy_equity = read_chart(project_id, 'Strategy Equity')
# The first few points in the series can have a None close, so keep only
# the points with a valid close value before extracting start/end.
valid_values = [v for v in strategy_equity.series['Equity'].values if v.close is not None]

# Start datetime and starting equity: first valid point.
start_datetime = valid_values[0].time
starting_cash = valid_values[0].close
# End datetime: last valid timestamp of the live Strategy Equity series.
# Uncomment the next line instead to reconcile up to "now" and see what
# would have happened had you not stopped the live algorithm:
# end_datetime = datetime.utcnow()
end_datetime = valid_values[-1].time

print(f"Start (UTC): {start_datetime}")
print(f"Starting equity: ${starting_cash:,.2f}")
print(f"End (UTC): {end_datetime}")</pre>
    </div>
</ol>
