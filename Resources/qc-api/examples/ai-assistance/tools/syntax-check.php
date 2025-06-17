<p>The following example demonstates syntax checking of codes through the cloud API.</p>

<div class="python section-example-container testable">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

### Syntax Check
# Send a POST request to the /ai/tools/syntax-check endpoint to check code syntax if appropriate
response = post(f'{BASE_URL}/ai/tools/syntax-check', headers=get_headers(), json={
    "language": "Python",  # Programming language of the code
    "files": [  # List of files to check
        {
            "name": "utils.py",  # Name of the file
            "content": '''
# region imports
from AlgorithmImports import *
# endregion

class Project(QCAlgorithm):

    def Initialize(self):
        self.AddEquity("SPY", Resolution.Minute)
'''  # Content of the file (Python code)
        }
    ]
})
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the syntax check results
if result['success']:
    print("Syntax Check Results:")
    print(result)</pre>
</div>