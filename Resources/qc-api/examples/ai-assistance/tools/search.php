<p>The following example demonstates searching content in QuantConnect through the cloud API.</p>

<div class="python section-example-container testable">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

### Search Content
# Send a POST request to the /ai/tools/search endpoint to search content
response = post(f'{BASE_URL}/ai/tools/search', headers=get_headers(), json={
    "language": "Python",  # Programming language to filter search results
    "criteria": [  # Search criteria
        {
            "input": "option",  # Search term (e.g., "option")
            "type": "Docs",  # Type of content to search (e.g., documentation)
            "count": 1  # Number of results to return
        }
    ]
})
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the search results
if result['success']:
    print("Search Results:")
    print(result)</pre>
</div>