<p>The following example demonstates creating, reading, updating, and listing live algorithms of a project through the cloud API.</p>

<div class="python section-example-container testable">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

# Create Live Algorithm
compile_id = "compile_id..."
node_id = "node_id..."
data = {
    "versionId": "-1",
    "projectId": project_id,
    "compileId": compile_id,
    "nodeId": node_id,
   
    "brokerage":{
        "id":"QuantConnectBrokerage",
        "user":"",
        "password":"",
        "environment":"live-paper",
        "account":""
    },

    "dataProviders":{
        "QuantConnectBrokerage":{
            "id":"QuantConnectBrokerage"}
            },
        "parameters":{},
        "notification":{}
    }
response = post(f'{BASE_URL}/live/create', headers = get_headers(), json = data)
result = response.json()
deploy_id = result['deployId']

# Read Live Algorithm Statistics
response = post(f'{BASE_URL}/live/read', headers = get_headers(), data = { "projectId": project_id, "deployId": deploy_id })
response.json()

# Liquidate Live Algorithm
response = post(f'{BASE_URL}/live/update/liquidate', headers = get_headers(), data = { "projectId": project_id })
response.json()

# Stop Live Algorithm
response = post(f'{BASE_URL}/live/update/stop', headers = get_headers(), data = { "projectId": project_id })
response.json()

# List Live Algorithm
response = post(f'{BASE_URL}/live/list', headers = get_headers(), data = { "status": "Running", "start": 1717801200, "end": 1743462000 })
response.json()</pre>
</div>