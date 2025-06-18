<p>The following example demonstates reading a live algorithm's statistics, portfolio state, logs, chart, orders, and insights through the cloud API.</p>

<div class="python section-example-container testable">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

# The project and deployment ID of the live algorithm we wish to read its results
project_id = 12345678
deploy_id = "..."

### Read Live Algorithm Statistics
# Prepare data payload with project and deploy IDs to fetch statistics
payload = {
    "projectId": project_id,  # ID of the project containing the live algorithm
    "deployId": deploy_id  # ID of the deployed live algorithm
}
# Send a POST request to the /live/read endpoint to get algorithm statistics
response = post(f'{BASE_URL}/live/read', headers=get_headers(), json=payload)
# Parse the JSON response
result = response.json()
# Check if the request was successful and print the statistics
if result['success']:
    print("Live Algorithm Statistics:")
    print(result)

### Read Live Algorithm Charts
# Define the chart name to retrieve (e.g., Strategy Equity)
chart_name = "Strategy Equity"
# Prepare data payload to fetch chart data
payload = {
    "projectId": project_id,  # ID of the project
    "name": chart_name,  # Name of the chart to retrieve
    "count": 100,  # Number of data points to fetch
    "start": 1717801200,  # Start time (Unix timestamp) for the chart data
    "end": 1743462000  # End time (Unix timestamp) for the chart data
}
# Send a POST request to the /live/chart/read endpoint to get chart data
response = post(f'{BASE_URL}/live/chart/read', headers=get_headers(), json=payload)
# Parse the JSON response
result = response.json()
# Check if the request was successful and print the chart data
if result['success']:
    print("Live Algorithm Chart Data:")
    print(result)

### Read Live Algorithm Portfolio State
# Prepare data payload to fetch portfolio state
payload = {
    "projectId": project_id  # ID of the project
}
# Send a POST request to the /live/portfolio/read endpoint to get portfolio state
response = post(f'{BASE_URL}/live/portfolio/read', headers=get_headers(), json=payload)
# Parse the JSON response
result = response.json()
# Check if the request was successful and print the portfolio state
if result['success']:
    print("Live Algorithm Portfolio State:")
    print(result)

### Read Live Algorithm Orders
# Prepare data payload to fetch orders
payload = {
    "projectId": project_id,  # ID of the project
    "start": 0,  # Starting index for orders
    "end": 100  # Ending index for orders
}
# Send a POST request to the /live/orders/read endpoint to get orders
response = post(f'{BASE_URL}/live/orders/read', headers=get_headers(), json=payload)
# Parse the JSON response
result = response.json()
# Check if the request was successful and print the orders
if result['success']:
    print("Live Algorithm Orders:")
    print(result)

### Read Live Algorithm Insights
# Prepare data payload to fetch insights
payload = {
    "projectId": project_id,  # ID of the project
    "start": 0,  # Starting index for insights
    "end": 100  # Ending index for insights
}
# Send a POST request to the /live/insights/read endpoint to get insights
response = post(f'{BASE_URL}/live/insights/read', headers=get_headers(), json=payload)
# Parse the JSON response
result = response.json()
# Check if the request was successful and print the insights
if result['success']:
    print("Live Algorithm Insights:")
    print(result)

### Read Live Algorithm Logs
# Prepare data payload to fetch logs
payload = {
    "projectId": project_id,  # ID of the project
    "algorithmId": deploy_id,  # ID of the deployed algorithm (same as deploy_id)
    "format": "json",  # Format of the logs (JSON in this case)
    "startLine": 0,  # Starting line for logs
    "endLine": 100  # Ending line for logs
}
# Send a POST request to the /live/logs/read endpoint to get logs
response = post(f'{BASE_URL}/live/logs/read', headers=get_headers(), json=payload)
# Parse the JSON response
result = response.json()
# Check if the request was successful and print the logs
if result['success']:
    print("Live Algorithm Logs:")
    print(result)</pre>
</div>