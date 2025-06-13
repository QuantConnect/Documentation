<p>The following example demonstates initializing a backtest for a specific algorithm through the cloud API.</p>

<div class="python section-example-container testable">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

response = post(f'{BASE_URL}/ai/tools/backtest-init', headers = get_headers(),
    json = {
        "language": "Python",
        "files": [
        {
            "name": "utils.py",
            "content": '''
# region imports
from AlgorithmImports import *
# endregion

class Project(QCAlgorithm):

    def Initialize(self):
        self.AddEquity("SPY", Resolution.Minute)
'''
        }
    ]
    })
response.json()</pre>
</div>