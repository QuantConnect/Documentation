<p>The following example demonstrates listing, creating, reading, and updating live algorithms of a project through the cloud API.</p>

<div class="section-example-container">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

### List Live Algorithms
# Define placeholder IDs for project, compilation and node (replace with actual values)
project_id = 12345678
compile_id = "compile_id..."
node_id = "node_id..."
# Prepare data payload to list the past and current deployments of the project
payload = {
    "projectId": project_id  # ID of the project
}
# Send a POST request to the /live/list endpoint to list algorithms
response = post(f'{BASE_URL}/live/list', headers=get_headers(), json=payload)
# Parse the JSON response into python managable dict
result = response.json()
deployments = result['live'] if result['success'] else []
# The most recent deployment of the project, if any
latest = max(deployments, key=lambda x: x['launched'], default=None)
print(f"Live deployments: {len(deployments)}, latest: {latest['deployId'] if latest else None}")

### Create Live Algorithm
# Deploy only if the project has no running deployment
if latest is None or latest['status'] != 'Running':
    # Prepare the data payload for creating a live algorithm with necessary details
    payload = {
        "versionId": -1,  # Use the latest version of LEAN
        "projectId": project_id,  # ID of the project to deploy as a live algorithm
        "compileId": compile_id,  # Compilation ID for the algorithm code
        "nodeId": node_id,  # Node ID where the algorithm will run
        "brokerage": {  # Brokerage configuration for live trading
            "id": "QuantConnectBrokerage"  # Paper trading brokerage identifier
        },
        "dataProviders": {  # Data provider configuration
            "QuantConnectBrokerage": {
                "id": "QuantConnectBrokerage"  # Data provider identifier
            }
        },
        "parameters": {}  # Optional algorithm parameters (empty in this example)
    }
    # Send a POST request to the /live/create endpoint to deploy the algorithm
    response = post(f'{BASE_URL}/live/create', headers=get_headers(), json=payload)
    # Parse the JSON response into python managable dict from the API
    result = response.json()
    # Check if the request was successful and print the result
    if result['success']:
        print("Live Algorithm Created Successfully:")
        print(result)

### Read Live Algorithm Statistics
# Prepare data payload with the project ID to fetch the statistics of its latest deployment
payload = {
    "projectId": project_id  # ID of the project
}
# Send a POST request to the /live/read endpoint to get algorithm statistics
response = post(f'{BASE_URL}/live/read', headers=get_headers(), json=payload)
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the statistics
if result['success']:
    print("Live Algorithm Statistics:")
    print(result)

### Liquidate Live Algorithm
# Prepare data payload with project ID to liquidate the algorithm
payload = {
    "projectId": project_id  # ID of the project to liquidate
}
# Send a POST request to the /live/update/liquidate endpoint to liquidate
response = post(f'{BASE_URL}/live/update/liquidate', headers=get_headers(), json=payload)
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the result
if result['success']:
    print("Live Algorithm Liquidated Successfully:")
    print(result)

### Stop Live Algorithm
# Prepare data payload with project ID to stop the algorithm
payload = {
    "projectId": project_id  # ID of the project to stop
}
# Send a POST request to the /live/update/stop endpoint to stop the algorithm
response = post(f'{BASE_URL}/live/update/stop', headers=get_headers(), json=payload)
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the result
if result['success']:
    print("Live Algorithm Stopped Successfully:")
    print(result)

### Export Orders
from decimal import Decimal
from time import sleep

# Download the orders of the last 750 days as a CSV file
payload = {
    "projectId": project_id  # ID of the project
}
response = post(f'{BASE_URL}/live/export/orders', headers=get_headers(), json=payload)
rows = response.text.splitlines()
header, recent = rows[0], rows[1:]
# The export starts at its first row. Older orders come from the /live/orders/read endpoint.
cutoff = recent[0].split(',')[1][:19].replace('T', ' ') if recent else '9999'

# Map the order type and status enums to the names in the CSV file
order_types = ["Market", "Limit", "Stop Market", "Stop Limit", "Market On Open", "Market On Close",
    "Option Exercise", "Limit If Touched", "Combo Market", "Combo Limit", "Combo Leg Limit", "Trailing Stop"]
order_statuses = ["New", "Submitted", "Partially Filled", "Filled", "", "Canceled", "None", "Invalid",
    "Cancel Pending", "Update Submitted"]

def number(value):
    text = format(Decimal(str(value)), 'f')
    return text.rstrip('0').rstrip('.') if '.' in text else text

# Read the older orders of each deployment that started before the cutoff
older = []
response = post(f'{BASE_URL}/live/list', headers=get_headers(), json={"projectId": project_id})
deployments = sorted(response.json()['live'], key=lambda x: x['launched'])
for deployment in deployments:
    if deployment['launched'] >= cutoff:
        continue
    start = 0
    while True:
        payload = {
            "projectId": project_id,  # ID of the project
            "deployId": deployment['deployId'],  # ID of the deployment
            "start": start,  # Starting index for orders
            "end": start + 1000  # Ending index for orders
        }
        result = post(f'{BASE_URL}/live/orders/read', headers=get_headers(), json=payload).json()
        # The first request can return a loading status while the orders are prepared
        if result.get('status') == 'loading':
            sleep(1)
            continue
        for order in result['orders']:
            if order['time'][:19].replace('T', ' ') >= cutoff:
                continue
            older.append(','.join([
                deployment['deployId'], order['time'], order['symbol']['value'],
                number(order['price']), number(order['quantity']),
                f'"{order_types[order["type"]]}"', f'"{order_statuses[order["status"]]}"',
                number(order['value']), '"' + order['tag'].replace('"', '""') + '" '
            ]))
        start += 1000
        if start >= result['length']:
            break

# Save all the orders in one CSV file
with open(f'orders-{project_id}.csv', 'w', newline='') as file:
    file.write('\n'.join([header] + older + recent) + '\n')
print(f"Saved {len(older) + len(recent)} orders")

### Export Trades
# Download the closed trades that opened since the start time as a CSV file
payload = {
    "projectId": project_id,  # ID of the project
    "start": 1717801200,  # Start time (Unix timestamp) for the trade entry time
    "end": 1743462000  # End time (Unix timestamp) for the trade exit time
}
response = post(f'{BASE_URL}/live/export/trades', headers=get_headers(), json=payload)
with open(f'trades-{project_id}.csv', 'w', newline='') as file:
    file.write(response.text)

### Export Insights
# Download the insights of all the live deployments of the project as a JSON file
payload = {
    "deployId": latest['deployId']  # ID of a deployment of the project
}
response = post(f'{BASE_URL}/live/export/insights', headers=get_headers(), json=payload)
with open(f'insights-{project_id}.json', 'w', newline='') as file:
    file.write(response.text)

### Export Logs
# Download the logs of all the live deployments of the project as a text file
payload = {
    "projectId": project_id,  # ID of the project
    "deployId": latest['deployId']  # ID of a deployment of the project, used to name the file
}
response = post(f'{BASE_URL}/live/export/logs', headers=get_headers(), json=payload)
with open(f'logs-{project_id}.txt', 'w', newline='') as file:
    file.write(response.text)</pre>
</div>