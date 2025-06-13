<p>The following example demonstates creating, reading, updating, deleting, and listing backtests of a project through the cloud API.</p>

<div class="python section-example-container testable">
    <<pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

# Create Backtest
compile_id = "compile_id..."
response = post(f'{BASE_URL}/backtests/create', headers = get_headers(),
                data = {
                    "projectId": project_id,
                    "compileId": compile_id,
                    "backtestName": f"Backtest {int(time())}"
                })
result = response.json()
backtest_id = result['backtest']['backtestId']

# Read Backtest Statistics
response = post(f'{BASE_URL}/backtests/read', headers = get_headers(), data = { "projectId": project_id, "backtestId": backtest_id })
response.json()

# Update Backtest
response = post(f'{BASE_URL}/backtests/update', headers = get_headers(),
                data = {
                    "projectId": project_id,
                    "backtestId": backtest_id,
                    "name": f"Backtest_{backtestId}",
                    "note": "The new backtest name is awesome!"
                })
response.json()

# Delete Backtest
response = post(f'{BASE_URL}/backtests/delete', headers = get_headers(), data = { "projectId": project_id, "backtestId": backtest_id })
response.json()

# List Backtest
response = post(f'{BASE_URL}/backtests/list', headers = get_headers(), data = { "projectId": project_id, "includeStatistics": True })
response.json()</pre>
</div>