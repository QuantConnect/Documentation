<p>The following example demonstrates how to read and plot chart data from a backtest through the cloud API.</p>

<div class="section-example-container">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>
import matplotlib.pyplot as plt
from datetime import datetime

# The project and backtest ID of the backtest
project_id = 12345678
backtest_id = "..."

### Read Backtest Chart
# Define the chart name to retrieve
chart_name = "Strategy Equity"
# Prepare data payload to fetch chart data
payload = {
    "projectId": project_id,  # ID of the project
    "backtestId": backtest_id,  # ID of the backtest
    "name": chart_name,  # Name of the chart to retrieve
    "count": 500,  # Number of data points to fetch
    "start": 1717801200,  # Start time in Unix timestamp
    "end": 1743462000  # End time in Unix timestamp
}
# Send a POST request to the /backtests/chart/read endpoint
response = post(f'{BASE_URL}/backtests/chart/read', headers=get_headers(), json=payload)
# Parse the JSON response
result = response.json()

# Check if the data is still loading
if result.get('status') == 'loading':
    print(f"Chart data is loading... Progress: {result['progress']}%")
# If the request was successful, extract and plot the chart data
elif result['success']:
    chart = result['chart']
    # Iterate through each series in the chart
    for series_name, series_data in chart['series'].items():
        # Extract the timestamps and values from the series
        # Each data point is a list: [timestamp, value] or [timestamp, open, high, low, close]
        values = series_data['values']
        timestamps = [datetime.utcfromtimestamp(point[0]) for point in values]
        y_values = [point[1] for point in values]
        # Plot the series
        plt.plot(timestamps, y_values, label=series_name)
    # Configure the plot
    plt.title(chart_name)
    plt.xlabel("Date")
    plt.ylabel("Value")
    plt.legend()
    plt.xticks(rotation=45)
    plt.tight_layout()
    plt.savefig("backtest_chart.png")
    plt.show()</pre>
</div>