<p>The following example demonstrates how to read and plot chart data from a live algorithm through the cloud API.</p>

<div class="section-example-container">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>
from time import sleep
import matplotlib.pyplot as plt
import matplotlib.dates as mdates
from datetime import datetime, timedelta

# The project ID of the live algorithm
project_id = 23456789

### Read Live Algorithm Chart
# Define the chart name to retrieve
chart_name = "Strategy Equity"
# Prepare data payload to fetch chart data
payload = {
    "projectId": project_id,  # ID of the project that's live trading
    "name": chart_name,  # Name of the chart to retrieve
    "count": 500,  # Number of data points to fetch
    "start": 1717801200,  # Start time in Unix timestamp
    "end": 1743462000  # End time in Unix timestamp
}
# Retry up to 10 times if the chart data is still loading
for attempt in range(10):
    # Send a POST request to the /live/chart/read endpoint
    response = post(f'{BASE_URL}/live/chart/read', headers=get_headers(), json=payload)
    # Parse the JSON response
    result = response.json()
    # Check if the data is still loading
    if result.get('status') == 'loading':
        print(f"Chart data is loading... Progress: {result['progress']}% (attempt {attempt + 1}/10)")
        sleep(10)
        continue
    break

# If the request was successful, extract and plot the chart data
if result['success']:
    chart = result['chart']
    series_items = list(chart['series'].items())
    # Create a subplot for each series
    fig, axes = plt.subplots(len(series_items), 1, figsize=(12, 4 * len(series_items)), sharex=True)
    if len(series_items) == 1:
        axes = [axes]
    for ax, (series_name, series_data) in zip(axes, series_items):
        values = series_data['values']
        timestamps = [datetime.utcfromtimestamp(point[0]) for point in values]
        # Check if the data is OHLC (5 elements: timestamp, open, high, low, close)
        is_ohlc = len(values[0]) == 5
        if is_ohlc:
            # Plot candlestick chart using matplotlib bar and vlines
            dates = mdates.date2num(timestamps)
            # Calculate bar width as 60% of the average interval between points
            bar_width = (dates[-1] - dates[0]) / len(dates) * 0.6
            for i, point in enumerate(values):
                _, o, h, l, c = point
                color = 'green' if c >= o else 'red'
                # Draw the high-low wick
                ax.vlines(dates[i], l, h, color=color, linewidth=0.8)
                # Draw the open-close body
                ax.bar(dates[i], abs(c - o) or 0.01, bottom=min(o, c),
                       width=bar_width, color=color, edgecolor=color)
            ax.xaxis_date()
            ax.set_ylabel("Price")
        else:
            # Plot line chart for simple [timestamp, value] data
            y_values = [point[1] for point in values]
            ax.plot(timestamps, y_values)
            ax.set_ylabel("Value")
        ax.set_title(series_name)
        ax.tick_params(axis='x', rotation=45)
    fig.suptitle(chart_name, fontsize=14)
    plt.tight_layout()
    plt.savefig("live_chart.png")
    plt.show()</pre>
</div>