<p>The following example demonstates creating, and broadcasting live commands through the cloud API.</p>

<div class="python section-example-container testable">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

# The project ID of the project to handle the live command
project_id = 12345678

### Create Live Command
# Prepare data payload to create a command to order for a live algorithm
data = {
    "projectId": project_id,  # ID of the project to send the command to
    "command": {  # Command details
        "$type": "OrderCommand",  # Type of command (OrderCommand for placing orders)
        "symbol": {  # Symbol details for the order
            "id": "BTCUSD 2XR",  # Unique identifier for the symbol
            "value": "BTCUSD"  # Symbol name
        },
        "order_type": "market",  # Type of order (market order in this case)
        "quantity": "0.1",  # Quantity to trade
        "limit_price": 0,  # Limit price (0 for market order)
        "stop_price": 0,  # Stop price (0 for market order)
        "tag": ""  # Optional tag for the order
    }
}
# Send a POST request to the /live/commands/create endpoint to create the command
response = post(f'{BASE_URL}/live/commands/create', headers=get_headers(), data=data)
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the result
if result['success']:
    print("Command Created Successfully:")
    print(result)

### Broadcast Live Command
# Define organization ID placeholder (replace with actual value)
org_id = "org_id..."
# Prepare data payload to broadcast a command to order for all listed live algorithms
data = {
    "organizationId": org_id,  # ID of the organization to broadcast to
    "excludeProjectId": None,  # Optional project ID to exclude (None means no exclusion)
    "command": {  # Command details (same as above)
        "$type": "OrderCommand",
        "symbol": {
            "id": "BTCUSD 2XR",
            "value": "BTCUSD"
        },
        "order_type": "market",
        "quantity": "0.1",
        "limit_price": 0,
        "stop_price": 0,
        "tag": ""
    }
}
# Send a POST request to the /live/commands/broadcast endpoint to broadcast the command
response = post(f'{BASE_URL}/live/commands/broadcast', headers=get_headers(), data=data)
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the result
if result['success']:
    print("Command Broadcasted Successfully:")
    print(result)</pre>
</div>