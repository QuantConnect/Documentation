<p>The following example demonstates reading a live algorithm's statistics, portfolio state, logs, chart, orders, and insights through the cloud API.</p>

<div class="python section-example-container testable">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

# Read Live Algorithm Statistics
response = post(f'{BASE_URL}/live/read', headers = get_headers(), data = { "projectId": project_id, "deployId": deploy_id })
response.json()

# Read Live Algorithm Charts
chart_name = "Strategy Equity"
response = post(f'{BASE_URL}/live/chart/read', headers = get_headers()
                data = {
                    "projectId": project_id,
                    "name": chart_name,
                    "count": 100
                    "start": 1717801200,
                    "end": 1743462000
                })
response.json()

# Read Live Algorithm Portfolio State
response = post(f'{BASE_URL}/live/portfolio/read', headers = get_headers(), data = { "projectId": project_id })
response.json()

# Read Live Algorithm Orders
response = post(f'{BASE_URL}/live/orders/read', headers = get_headers(),
                data = {
                    "projectId": project_id,
                    "start": 0,
                    "end": 100
                })
response.json()

# Read Live Algorithm Insights
response = post(f'{BASE_URL}/live/insights/read', headers = get_headers(),
                data = {
                    "projectId": project_id,
                    "start": 0,
                    "end": 100
                })
response.json()

# Read Live Algorithm Logs
response = post(f'{BASE_URL}/live/logs/read', headers = get_headers(),
                data = {
                    "projectId": project_id,
                    "algorithmId": deploy_id,
                    "format": "json",
                    "startLine": 0,
                    "endLine": 100
                })
response.json()</pre>
</div>