<p>The following example demonstates how to get additional context and suggestions for error messages through the cloud API.</p>

<div class="python section-example-container testable">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

### Error Enhancement
# Send a POST request to the /ai/tools/error-enhance endpoint to enhance error message
response = post(f'{BASE_URL}/ai/tools/error-enhance/', headers=get_headers(), json={
    "language": "Python",  # Programming language of the code
    "error": {  # Error details
        "message": '''  at initialize
        self._option = self.add_index_option("SPX", Resolution.MINUTE, "SPXW").symbol
                    ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    in c1afe80a-e056-4841-b0c1-d9c562cf2bd8.py: line 15
    The specified market wasn't found in the markets lookup. Requested: spxw. You can add markets by calling QuantConnect.Market.Add(string,int) (Parameter 'market')''',  # Error message
        "stacktrace": ""  # Stacktrace (empty in this case)
    }
})
# Parse the JSON response
result = response.json()
# Check if the request was successful and print the enhanced error details
if result['success']:
    print("Enhanced Error Details:")
    print(result)</pre>
</div>