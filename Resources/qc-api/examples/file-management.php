<p>The following example demonstates creating, reading, updating, and deleting a file through the cloud API.</p>

<div class="python section-example-container testable">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

# The project ID of the project to manage its files
project_id = 12345678

### Create File
# Send a POST request to the /files/create endpoint to create a new file
response = post(f'{BASE_URL}/files/create', headers=get_headers(), json={
    "projectId": project_id,  # ID of the project
    "name": "utils.py",  # Name of the new file
    "content": '''
# region imports
from AlgorithmImports import *
# endregion

class Project(QCAlgorithm):

    def Initialize(self):
        self.AddEquity("SPY", Resolution.Minute)
'''  # Content of the file (Python code)
})
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the result
if result['success']:
    print("File Created Successfully:")
    print(result)

### Read File
# Prepare data payload to read files in the project
payload = {
    "projectId": project_id,  # ID of the project
    "includeLibraries": True  # Include library files in the response
}
# Send a POST request to the /files/read endpoint to read files
response = post(f'{BASE_URL}/files/read', headers=get_headers(), json=payload)
# Parse the JSON response into python managable dict
result = response.json()
# Extract filename and content from the first file in the response
fileName = result['files'][0]['name']
content = result['files'][0]['content']
# Check if the request was successful and print the content
if result['success']:
    print("File Content:")
    print(content)

### Update File Contents
# Modify the content by replacing "SPY" with "TSLA"
content = content.replace("SPY", "TSLA")
# Send a POST request to the /files/update endpoint to update the file content
response = post(f'{BASE_URL}/files/update', headers=get_headers(), json={
    "projectId": project_id,  # ID of the project
    "name": "utils.py",  # Name of the file to update
    "content": content  # Updated content
})
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the result
if result['success']:
    print("File Updated Successfully:")
    print(result)

### Rename File
# Send a POST request to the /files/update endpoint to rename the file
response = post(f'{BASE_URL}/files/update', headers=get_headers(), json={
    "projectId": project_id,  # ID of the project
    "name": "utils.py",  # Current name of the file
    "newName": "utils2.py"  # New name for the file
})
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the result
if result['success']:
    print("File Renamed Successfully:")
    print(result)

### Delete File
# Prepare data payload to delete the file
payload = {
    "projectId": project_id,  # ID of the project
    "name": "utils2.py"  # Name of the file to delete
}
# Send a POST request to the /files/delete endpoint to delete the file
response = post(f'{BASE_URL}/files/delete', headers=get_headers(), json=payload)
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the result
if result['success']:
    print("File Deleted Successfully:")
    print(result)</pre>
</div>