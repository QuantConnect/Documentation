<p>The following example demonstates creating, reading, updating, and listing live algorithms of a project through the cloud API.</p>

<div class="python section-example-container testable">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

### Create Live Algorithm
# Define placeholder IDs for compilation and node (replace with actual values)
project_id = 12345678
compile_id = "compile_id..."
node_id = "node_id..."
# Prepare the data payload for creating a live algorithm with necessary details
data = {
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
data = {
    "projectId": project_id,  # ID of the project
    "deployId": deploy_id  # ID of the deployed live algorithm
}
# Send a POST request to the /live/read endpoint to get algorithm statistics
response = post(f'{BASE_URL}/live/read', headers=get_headers(), data=data)
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the statistics
if result['success']:
    print("Live Algorithm Statistics:")
    print(result)

### Liquidate Live Algorithm
# Prepare data payload with project ID to liquidate the algorithm
data = {
    "projectId": project_id  # ID of the project to liquidate
}
# Send a POST request to the /live/update/liquidate endpoint to liquidate
response = post(f'{BASE_URL}/live/update/liquidate', headers=get_headers(), data=data)
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the result
if result['success']:
    print("Live Algorithm Liquidated Successfully:")
    print(result)

### Stop Live Algorithm
# Prepare data payload with project ID to stop the algorithm
data = {
    "projectId": project_id  # ID of the project to stop
}
# Send a POST request to the /live/update/stop endpoint to stop the algorithm
response = post(f'{BASE_URL}/live/update/stop', headers=get_headers(), data=data)
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the result
if result['success']:
    print("Live Algorithm Stopped Successfully:")
    print(result)

### List Live Algorithms
# Prepare data payload with filters for listing live algorithms
data = {
    "status": "Running",  # Filter to show only running algorithms
    "start": 1717801200,  # Start time (Unix timestamp) for the list range
    "end": 1743462000  # End time (Unix timestamp) for the list range
}
# Send a POST request to the /live/list endpoint to list algorithms
response = post(f'{BASE_URL}/live/list', headers=get_headers(), data=data)
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the list
if result['success']:
    print("List of Live Algorithms:")
    print(result)</pre>
</div>