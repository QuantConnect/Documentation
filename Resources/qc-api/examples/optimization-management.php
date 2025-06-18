<p>The following example demonstates creating, reading, updating, deleting, aborting and listing backtests of a project through the cloud API.</p>

<div class="python section-example-container testable">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

# The project ID of the project to manage an optimization job
project_id = 12345678

### Estimate Optimization Cost
# Send a POST request to the /optimizations/estimate endpoint to estimate cost
response = post(f'{BASE_URL}/optimizations/estimate', headers=get_headers(), json={
    "projectId": project_id,  # ID of the project
    "name": f"Optimization_{compileId[:5]}",  # Name of the optimization (using compile ID prefix)
    "target": "TotalPerformance.PortfolioStatistics.SharpeRatio",  # Optimization target metric
    "targetTo": "max",  # Direction to optimize (maximize)
    "targetValue": None,  # Specific target value (None for max/min)
    "strategy": "QuantConnect.Optimizer.Strategies.GridSearchOptimizationStrategy",  # Optimization strategy
    "compileId": compile_id,  # Compilation ID for the optimization
    "parameters[0][key]": "ema_fast",  # First parameter key
    "parameters[0][min]": 100,  # Minimum value for first parameter
    "parameters[0][max]": 200,  # Maximum value for first parameter
    "parameters[0][step]": 50,  # Step size for first parameter
    "parameters[1][key]": "ema_slow",  # Second parameter key
    "parameters[1][min]": 200,  # Minimum value for second parameter
    "parameters[1][max]": 300,  # Maximum value for second parameter
    "parameters[1][step]": 50,  # Step size for second parameter
    "constraints": [{  # Constraints for the optimization
        "target": "TotalPerformance.PortfolioStatistics.SharpeRatio",
        "operator": "greater",
        "target-value": 1
    }]
})
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the estimated cost
if result['success']:
    print("Optimization Cost Estimated Successfully:")
    print(result)

### Create Optimization
# Send a POST request to the /optimizations/create endpoint to create an optimization
response = post(f'{BASE_URL}/optimizations/create', headers=get_headers(), json={
    "projectId": project_id,  # ID of the project
    "name": f"Optimization_{compileId[:5]}",  # Name of the optimization
    "target": "TotalPerformance.PortfolioStatistics.SharpeRatio",  # Optimization target
    "targetTo": "max",  # Direction to optimize
    "targetValue": None,  # Specific target value
    "strategy": "QuantConnect.Optimizer.Strategies.GridSearchOptimizationStrategy",  # Strategy
    "compileId": compile_id,  # Compilation ID
    "parameters[0][key]": "ema_fast",  # First parameter key
    "parameters[0][min]": 100,  # Minimum value
    "parameters[0][max]": 200,  # Maximum value
    "parameters[0][step]": 50,  # Step size
    "parameters[1][key]": "ema_slow",  # Second parameter key
    "parameters[1][min]": 200,  # Minimum value
    "parameters[1][max]": 300,  # Maximum value
    "parameters[1][step]": 50,  # Step size
    "constraints": [{  # Constraints
        "target": "TotalPerformance.PortfolioStatistics.SharpeRatio",
        "operator": "greater",
        "target-value": 1
    }],
    "estimatedCost": 10,  # Estimated cost of optimization
    "nodeType": "O2-8",  # Node type for optimization
    "parallelNodes": 4  # Number of parallel nodes
})
# Parse the JSON response into python managable dict
result = response.json()
# Extract the optimization ID from the response
optimization_id = result['optimizations'][0]['optimizationId']
# Check if the request was successful and print the result
if result['success']:
    print("Optimization Created Successfully:")
    print(result)

### Update Optimization
# Send a POST request to the /optimizations/update endpoint to update the optimization
response = post(f'{BASE_URL}/optimizations/update', headers=get_headers(), json={
    "optimizationId": optimization_id,  # ID of the optimization to update
    "name": f"Optimization_{optimizationId[:5]}"  # New name for the optimization
})
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the result
if result['success']:
    print("Optimization Updated Successfully:")
    print(result)

### Read Optimization
# Prepare data payload to read optimization details
payload = {
    "optimizationId": optimization_id  # ID of the optimization to read
}
# Send a POST request to the /optimizations/read endpoint to get details
response = post(f'{BASE_URL}/optimizations/read', headers=get_headers(), json=payload)
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the details
if result['success']:
    print("Optimization Details:")
    print(result)

### Abort Optimization
# Prepare data payload to abort the optimization
payload = {
    "optimizationId": optimization_id  # ID of the optimization to abort
}
# Send a POST request to the /optimizations/abort endpoint to abort
response = post(f'{BASE_URL}/optimizations/abort', headers=get_headers(), json=payload)
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the result
if result['success']:
    print("Optimization Aborted Successfully:")
    print(result)

### Delete Optimization
# Prepare data payload to delete the optimization
payload = {
    "optimizationId": optimization_id  # ID of the optimization to delete
}
# Send a POST request to the /optimizations/delete endpoint to delete
response = post(f'{BASE_URL}/optimizations/delete', headers=get_headers(), json=payload)
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the result
if result['success']:
    print("Optimization Deleted Successfully:")
    print(result)

### List Optimizations
# Prepare data payload to list optimizations
payload = {
    "projectId": project_id  # ID of the project
}
# Send a POST request to the /optimizations/list endpoint to list optimizations
response = post(f'{BASE_URL}/optimizations/list', headers=get_headers(), json=payload)
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the list
if result['success']:
    print("List of Optimizations:")
    print(result)</pre>
</div>