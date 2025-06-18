<p>The following example demonstates reading backtest statistics, chart, orders, insights, and report through the cloud API.</p>

<div class="python section-example-container testable">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

# The project and backtest ID of the backtest we wish to read its results
project_id = 12345678
backtest_id = "..."

### Read Backtest Statistics
# Prepare data payload to read backtest statistics
payload = {
    "projectId": project_id,  # ID of the project containing the backtest
    "backtestId": backtest_id  # ID of the backtest
}
# Send a POST request to the /backtests/read endpoint to get statistics
response = post(f'{BASE_URL}/backtests/read', headers=get_headers(), json=payload)
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the statistics
if result['success']:
    print("Backtest Statistics:")
    print(result)

### Read Backtest Charts
# Define the chart name to retrieve (e.g., Strategy Equity)
chart_name = "Strategy Equity"
# Prepare data payload to fetch chart data
payload = {
    "projectId": project_id,  # ID of the project
    "backtestId": backtest_id,  # ID of the backtest
    "name": chart_name,  # Name of the chart to retrieve
    "count": 100,  # Number of data points to fetch
    "start": 0,  # Starting index for chart data
    "end": 100  # Ending index for chart data
}
# Send a POST request to the /backtests/charts/read endpoint to get chart data
response = post(f'{BASE_URL}/backtests/charts/read', headers=get_headers(), json=payload)
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the chart data
if result['success']:
    print("Backtest Chart Data:")
    print(result)

### Read Backtest Orders
# Prepare data payload to fetch orders
payload = {
    "projectId": project_id,  # ID of the project
    "backtestId": backtest_id,  # ID of the backtest
    "start": 0,  # Starting index for orders
    "end": 100  # Ending index for orders
}
# Send a POST request to the /backtests/orders/read endpoint to get orders
response = post(f'{BASE_URL}/backtests/orders/read', headers=get_headers(), json=payload)
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the orders
if result['success']:
    print("Backtest Orders:")
    print(result)

### Read Backtest Insights
# Prepare data payload to fetch insights
payload = {
    "projectId": project_id,  # ID of the project
    "backtestId": backtest_id,  # ID of the backtest
    "start": 0,  # Starting index for insights
    "end": 100  # Ending index for insights
}
# Send a POST request to the /backtests/read/insights endpoint to get insights
response = post(f'{BASE_URL}/backtests/read/insights', headers=get_headers(), json=payload)
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the insights
if result['success']:
    print("Backtest Insights:")
    print(result)

### Read Backtest Report
# Prepare data payload to fetch the backtest report
payload = {
    "projectId": project_id,  # ID of the project
    "backtestId": backtest_id  # ID of the backtest
}
# Send a POST request to the /backtests/read/report endpoint to get the report
response = post(f'{BASE_URL}/backtests/read/report', headers=get_headers(), json=payload)
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the report
if result['success']:
    print("Backtest Report:")
    print(result)</pre>
</div>