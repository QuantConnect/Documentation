<p>The following example demonstates reading backtest statistics, chart, orders, insights, and report through the cloud API.</p>

<div class="python section-example-container testable">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

# Read Backtest Statistics
response = post(f'{BASE_URL}/backtests/read', headers = get_headers(), data = { "projectId": project_id, "backtestId": backtest_id })
response.json()

# Read Backtest Charts
chart_name = "Strategy Equity"
response = post(f'{BASE_URL}/backtests/charts/read', headers = get_headers()
                data = {
                    "projectId": project_id,
                    "backtestId": backtest_id,
                    "name": chart_name,
                    "count": 100
                    "start": 0,
                    "end": 100
                })
response.json()

# Read Backtest Orders
response = post(f'{BASE_URL}/backtests/orders/read', headers = get_headers(),
                data = {
                    "projectId": project_id,
                    "backtestId": backtest_id,
                    "start": 0,
                    "end": 100
                })
response.json()

# Read Backtest Insights
response = post(f'{BASE_URL}/backtests/read/insights', headers = get_headers(),
                data = {
                    "projectId": project_id,
                    "backtestId": backtest_id,
                    "start": 0,
                    "end": 100
                })
response.json()

# Read Backtest Report
response = post(f'{BASE_URL}/backtests/read/report', headers = get_headers(),
                data = {
                    "projectId": project_id,
                    "backtestId": backtest_id
                })
response.json()</pre>
</div>