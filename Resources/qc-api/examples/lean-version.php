<p>The following example demonstates getting a list of Lean versions through the cloud API.</p>

<div class="python section-example-container testable">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

### Get Lean Versions
# Send a POST request to the /lean/versions/read endpoint to get Lean versions
response = post(f'{BASE_URL}/lean/versions/read', headers=get_headers())
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the versions
if result['success']:
    print("Lean Versions:")
    print(result)</pre>
</div>