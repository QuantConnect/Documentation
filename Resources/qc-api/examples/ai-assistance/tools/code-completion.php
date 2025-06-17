<p>The following example demonstates code completion for a specific algorithm attribute through the cloud API.</p>

<div class="python section-example-container testable">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

### Code Completion
# Send a POST request to the /ai/tools/complete endpoint to get code completion
response = post(f'{BASE_URL}/ai/tools/complete', headers=get_headers(), json={
    "language": "Python",  # Programming language for code completion
    "sentence": "self.add_equity('AAPL",  # Partial code to complete
    "responseSizeLimit": 30  # Maximum size of the completion response
})
# Parse the JSON response
result = response.json()
# Check if the request was successful and print the code completion results
if result['success']:
    print("Code Completion Results:")
    print(result)</pre>
</div>