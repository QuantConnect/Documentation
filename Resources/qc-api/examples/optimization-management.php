<p>The following example demonstates creating, reading, updating, deleting, aborting and listing backtests of a project through the cloud API.</p>

<div class="python section-example-container testable">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

# Estimate Optimization Cost
headers = get_headers()
response = post(f'{BASE_URL}/optimizations/estimate', headers = get_headers(),
               data = {
                  "projectId": project_id,
                  "name": f"Optimization_{compileId[:5]}",
                  "target": "TotalPerformance.PortfolioStatistics.SharpeRatio",
                  "targetTo": "max",
                  "targetValue": None,
                  "strategy": "QuantConnect.Optimizer.Strategies.GridSearchOptimizationStrategy",
                  "compileId": compile_id,
                  "parameters[0][key]": "ema_fast",
                  "parameters[0][min]": 100,
                  "parameters[0][max]": 200,
                  "parameters[0][step]": 50,
                  "parameters[1][key]": "ema_slow",
                  "parameters[1][min]": 200,
                  "parameters[1][max]": 300,
                  "parameters[1][step]": 50,
                  "constraints": [ {
                      "target": "TotalPerformance.PortfolioStatistics.SharpeRatio",
                      "operator": "greater",
                      "target-value": 1
                    }
                  ]
            })
response.json()

# Create Optimization
response = post(f'{BASE_URL}/optimizations/create', headers = get_headers(),
                data = {
                  "projectId": project_id,
                  "name": f"Optimization_{compileId[:5]}",
                  "target": "TotalPerformance.PortfolioStatistics.SharpeRatio",
                  "targetTo": "max",
                  "targetValue": None,
                  "strategy": "QuantConnect.Optimizer.Strategies.GridSearchOptimizationStrategy",
                  "compileId": compile_id,
                  "parameters[0][key]": "ema_fast",
                  "parameters[0][min]": 100,
                  "parameters[0][max]": 200,
                  "parameters[0][step]": 50,
                  "parameters[1][key]": "ema_slow",
                  "parameters[1][min]": 200,
                  "parameters[1][max]": 300,
                  "parameters[1][step]": 50,
                  "constraints": [ {
                      "target": "TotalPerformance.PortfolioStatistics.SharpeRatio",
                      "operator": "greater",
                      "target-value": 1
                    }
                  ],
                  "estimatedCost": 10,
                  "nodeType": "O2-8",
                  "parallelNodes": 4
            })
response.json()
optimization_id = response.json()['optimizations'][0]['optimizationId']

# Update Optimization
response = post(f'{BASE_URL}/optimizations/update', headers = get_headers(),
                data = {
                    "optimizationId": optimization_id,
                    "name": f"Optimization_{optimizationId[:5]}"
                })
response.json()

# Read Optimization
response = post(f'{BASE_URL}/optimizations/read', headers = get_headers(), data = { "optimizationId": optimization_id })
response.json()

# Abort Optimization
response = post(f'{BASE_URL}/optimizations/abort', headers = get_headers(), data = { "optimizationId": optimization_id })
response.json()

# Delete Optimization
response = post(f'{BASE_URL}/optimizations/delete', headers = get_headers(), data = { "optimizationId": optimization_id })
response.json()

# List Optimization
response = post(f'{BASE_URL}/optimizations/list', headers = get_headers(), data = { "projectId": project_id })
response.json()</pre>
</div>