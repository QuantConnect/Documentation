<p>The following example demonstates PEP8 conversion of python codes through the cloud API.</p>

<div class="python section-example-container testable">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

### PEP8 Conversion
# Send a POST request to the /ai/tools/pep8-convert endpoint to convert code to PEP8 syntax
response = post(f'{BASE_URL}/ai/tools/pep8-convert', headers=get_headers(), json={
    "files": [  # List of files to convert
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
# Check if the request was successful and print the PEP8 conversion results
if result['success']:
    print("PEP8 Conversion Results:")
    print(result)</pre>
</div>