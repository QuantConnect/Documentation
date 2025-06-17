<p>The following example demonstates creating, reading, updating, deleting, and listing backtests of a project through the cloud API.</p>

<div class="python section-example-container testable">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

### Create Backtest
# Define placeholder compilation ID (replace with actual value)
compile_id = "compile_id..."
# Send a POST request to the /backtests/create endpoint to create a backtest
response = post(f'{BASE_URL}/backtests/create', headers=get_headers(), data={
    "projectId": project_id,  # ID of the project to backtest
    "compileId": compile_id,  # Compilation ID for the backtest
    "backtestName": f"Backtest {int(time())}"  # Unique name for the backtest using current timestamp
})
# Parse the JSON response into python managable dict
result = response.json()
# Extract the backtest ID from the response
backtest_id = result['backtest']['backtestId']
# Check if the request was successful and print the result
if result['success']:
    print("Backtest Created Successfully:")
    print(result)

### Read Backtest Statistics
# Prepare data payload to read backtest statistics
data = {
    "projectId": project_id,  # ID of the project
    "backtestId": backtest_id  # ID of the backtest to read
}
# Send a POST request to the /backtests/read endpoint to get statistics
response = post(f'{BASE_URL}/backtests/read', headers=get_headers(), data=data)
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the statistics
if result['success']:
    print("Backtest Statistics:")
    print(result)

### Update Backtest
# Send a POST request to the /backtests/update endpoint to update backtest details
response = post(f'{BASE_URL}/backtests/update', headers=get_headers(), data={
    "projectId": project_id,  # ID of the project
    "backtestId": backtest_id,  # ID of the backtest to update
    "name": f"Backtest_{backtest_id}",  # New name for the backtest
    "note": "The new backtest name is awesome!"  # Additional note
})
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the result
if result['success']:
    print("Backtest Updated Successfully:")
    print(result)

### Delete Backtest
# Prepare data payload to delete the backtest
data = {
    "projectId": project_id,  # ID of the project
    "backtestId": backtest_id  # ID of the backtest to delete
}
# Send a POST request to the /backtests/delete endpoint to delete the backtest
response = post(f'{BASE_URL}/backtests/delete', headers=get_headers(), data=data)
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the result
if result['success']:
    print("Backtest Deleted Successfully:")
    print(result)

### List Backtests
# Prepare data payload to list backtests with statistics
data = {
    "projectId": project_id,  # ID of the project
    "includeStatistics": True  # Include statistics in the response
}
# Send a POST request to the /backtests/list endpoint to list backtests
response = post(f'{BASE_URL}/backtests/list', headers=get_headers(), data=data)
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the list
if result['success']:
    print("List of Backtests:")
    print(result)</pre>
</div>