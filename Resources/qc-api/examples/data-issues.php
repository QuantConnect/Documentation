<p>The following example demonstrates listing the open data issues for a ticker and reporting a new issue with an attached project and backtest when none exists through the cloud API.</p>

<div class="section-example-container">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

### List Data Issues
# Send a POST request to the /community/data-library/issues/list endpoint to find open issues for SPY
response = post(f'{BASE_URL}/community/data-library/issues/list', headers=get_headers(), json={
    "status": "open",  # open or closed
    "start": 0,        # Index of the first issue to return
    "end": 20,         # Index after the last issue to return
    "search": "SPY"    # Exact ticker match
})
# Parse the JSON response into python managable dict
result = response.json()
# Keep the open issues that already cover SPY minute data
existing = [i for i in result.get('results', []) if i['resolution'] == 'Minute']
# Check if the request was successful and print the issues
if result['success']:
    print("Open Data Issues:")
    print(result)

### List Backtests
# Send a POST request to the /backtests/list endpoint to find a backtest that shows the issue
project_id = 12345678  # Replace with the Id of your project
response = post(f'{BASE_URL}/backtests/list', headers=get_headers(), json={
    "projectId": project_id,
    "includeStatistics": False
})
# Parse the JSON response into python managable dict
result = response.json()
# Pick the first backtest of the project to attach to the issue
backtest_id = result['backtests'][0]['backtestId'] if result.get('backtests') else None

### Create Data Issue (only when no open issue covers it)
if not existing:
    # Send a POST request to the /community/data-library/issues/create endpoint to report the issue
    response = post(f'{BASE_URL}/community/data-library/issues/create', headers=get_headers(), json={
        "ticker": "SPY",
        "security": "Equity",
        "market": "USA",
        "resolution": "Minute",
        "from-date": "2024-08-01",
        "to-date": "2024-08-01",
        "from-time": "09:30",                 # Minute data over less than 7 days needs times
        "to-time": "16:00",
        "missing-points": "Missing Points",  # At least one issue type
        "content": "SPY minute bars are missing on 2024-08-01 between 09:30 and 16:00.",
        "select-project": project_id,         # Optional. Project that shows the issue
        "select-backtest": backtest_id        # Optional. Backtest of that project
    })
    # Parse the JSON response into python managable dict
    result = response.json()
    # Check if the request was successful and print the link to the issue
    if result['success']:
        print(f"https://www.quantconnect.com/datasets/issue/{result['discussionId']}")</pre>
</div>
