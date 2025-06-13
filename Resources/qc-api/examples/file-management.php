<p>The following example demonstates creating, reading, updating, and deleting a file through the cloud API.</p>

<div class="python section-example-container testable">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

# Create File
response = post(f'{BASE_URL}/files/create', headers = get_headers(),
                data = {
                    "projectId": project_id,
                    "name": "utils.py",
                    "content": '''
# region imports
from AlgorithmImports import *
# endregion

class Project(QCAlgorithm):

    def Initialize(self):
        self.AddEquity("SPY", Resolution.Minute)
''' })
response.json()

# Read File
response = post(f'{BASE_URL}/files/read', headers = get_headers(), data = { "projectId": project_id, "includeLibraries": True })
result = response.json()
fileName = result['files'][0]['name']
content = result['files'][0]['content']

# Update File Contents
content = content.replace("SPY", "TSLA")
response = post(f'{BASE_URL}/files/update', headers = get_headers(),
                data = {
                    "projectId": project_id,
                    "name": "utils.py",
                    "content": content
                })
response.json()

# Rename File
response = post(f'{BASE_URL}/files/update', headers = get_headers(),
                data = {
                    "projectId": project_id,
                    "name": "utils.py",
                    "newName": "utils2.py"
                })
response.json()

# Delete File
response = post(f'{BASE_URL}/files/delete', headers = get_headers(), data = { "projectId": project_id, "name": "utils2.py" })
response.json()</pre>
</div>