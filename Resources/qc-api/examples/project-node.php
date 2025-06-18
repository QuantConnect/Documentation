<p>The following example demonstates reading, and updating a project nodes through the cloud API.</p>

<div class="python section-example-container testable">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

# The project ID of the project to manage
project_id = 12345678

### Read Project Nodes
# Prepare data payload to read project nodes
payload = {
    "projectId": project_id  # ID of the project
}
# Send a POST request to the /projects/nodes/read endpoint to get node details
response = post(f'{BASE_URL}/projects/nodes/read', headers=get_headers(), json=payload)
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the node details
if result['success']:
    print("Project Nodes:")
    print(result)

### Update Project Nodes
# Define a list of node names to update (replace with actual node names)
nodes = ["node_name_1", "node_name_2"]
# Prepare data payload to update project nodes
payload = {
    "projectId": project_id,  # ID of the project
    "nodes": nodes  # List of node names to associate with the project
}
# Send a POST request to the /projects/nodes/update endpoint to update nodes
response = post(f'{BASE_URL}/projects/nodes/update', headers=get_headers(), json=payload)
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the result
if result['success']:
    print("Project Nodes Updated Successfully:")
    print(result)</pre>
</div>