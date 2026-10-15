<p>The following example demonstates creating, reading, updating, and listing live algorithms of a project through the cloud API.</p>

<div class="section-example-container">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

### Create Live Algorithm
# Define placeholder IDs for compilation and node (replace with actual values)
project_id = 12345678
compile_id = "compile_id..."
node_id = "node_id..."
# Prepare the data payload for creating a live algorithm with necessary details
payload = {
    "versionId": "-1",  # Use the latest version of the algorithm
    "projectId": project_id,  # ID of the project to deploy as a live algorithm
    "compileId": compile_id,  # Compilation ID for the algorithm code
    "nodeId": node_id,  # Node ID where the algorithm will run
    "brokerage": {  # Brokerage configuration for live trading
        "id": "QuantConnectBrokerage",  # Brokerage identifier
        "user": "",  # Brokerage username (replace with actual value)
        "password": "",  # Brokerage password (replace with actual value)
        "environment": "live-paper",  # Trading environment (live or paper)
        "account": ""  # Brokerage account ID (replace with actual value)
    },
    "dataProviders": {  # Data provider configuration
        "QuantConnectBrokerage": {
            "id": "QuantConnectBrokerage"  # Data provider identifier
        }
    },
    "parameters": {},  # Optional algorithm parameters (empty in this example)
    "notification": {}  # Optional notification settings (empty in this example)
}
# Send a POST request to the /live/create endpoint to deploy the algorithm
response = post(f'{BASE_URL}/live/create', headers=get_headers(), json=data)
# Parse the JSON response into python managable dict from the API
result = response.json()
# Extract the deploy ID from the response for future operations
deploy_id = result['deployId']
# Check if the request was successful and print the result
if result['success']:
    print("Live Algorithm Created Successfully:")
    print(result)

### Read Live Algorithm Statistics
# Prepare data payload with project and deploy IDs to fetch statistics
payload = {
    "projectId": project_id,  # ID of the project
    "deployId": deploy_id  # ID of the deployed live algorithm
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

### List Live Algorithms
# Prepare data payload with filters for listing live algorithms
payload = {
    "status": "Running",  # Filter to show only running algorithms
    "start": 1717801200,  # Start time (Unix timestamp) for the list range
    "end": 1743462000  # End time (Unix timestamp) for the list range
}
# Send a POST request to the /live/list endpoint to list algorithms
response = post(f'{BASE_URL}/live/list', headers=get_headers(), json=payload)
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the list
if result['success']:
    print("List of Live Algorithms:")
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
    "start": 1717801200  # Start time (Unix timestamp) for the trade entry time
}
response = post(f'{BASE_URL}/live/export/trades', headers=get_headers(), json=payload)
with open(f'trades-{project_id}.csv', 'w', newline='') as file:
    file.write(response.text)</pre>
</div>