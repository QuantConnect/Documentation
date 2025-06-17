<p>The following example demonstates initializing a backtest for a specific algorithm through the cloud API.</p>

<div class="python section-example-container testable">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

### Initialize Backtest
# Send a POST request to the /ai/tools/backtest-init endpoint to initialize a backtest
response = post(f'{BASE_URL}/ai/tools/backtest-init', headers=get_headers(), json={
    "language": "Python",  # Programming language of the algorithm
    "files": [  # List of files for the backtest
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
# Check if the request was successful and print the backtest initialization result
if result['success']:
    print("Backtest Initialized Successfully:")
    print(result)</pre>
</div>