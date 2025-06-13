<p>The following example demonstates PEP8 conversion of python codes through the cloud API.</p>

<div class="python section-example-container testable">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

response = post(f'{BASE_URL}/ai/tools/pep8-convert', headers = get_headers(),
    json = {
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