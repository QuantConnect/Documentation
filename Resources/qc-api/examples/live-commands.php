<p>The following example demonstates creating, and broadcasting live commands through the cloud API.</p>

<div class="python section-example-container testable">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

# Create Compilation Job
data = {
  "projectId": project_id,
  "command": {
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
response = post(f'{BASE_URL}/live/commands/create', headers = get_headers(), data = data)
response.json()

# Read Compilation Result
org_id = "org_id..."
data = {
    "organizationId": org_id,
    "excludeProjectId": None,
    "command": {
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
response = post(f'{BASE_URL}/live/commands/broadcast', headers = get_headers(), data = data)
response.json()</pre>
</div>