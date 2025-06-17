<p>The following example demonstates reading the organization account status through the cloud API.</p>

<div class="python section-example-container testable">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

### Read Account Status
# Send a POST request to the /account/read endpoint to read account status
response = post(f'{BASE_URL}/account/read', headers=get_headers())
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the account status
if result['success']:
    print("Account Status:")
    print(result)</pre>
</div>