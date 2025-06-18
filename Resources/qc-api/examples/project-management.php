<p>The following example demonstates creating, reading, updating, and deleting a project through the cloud API.</p>

<div class="python section-example-container testable">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

# The project ID of the project to manage
project_id = 12345678

### Create Project
# Send a POST request to the /projects/create endpoint to create a new project
response = post(f'{BASE_URL}/projects/create', headers=get_headers(), json={
    "name": f"Project_{int(time())}",  # Unique project name using current timestamp
    "language": "Py"  # Programming language for the project (Python)
})
# Parse the JSON response into python managable dict
result = response.json()
# Extract the project ID from the response
project_id = result['projects'][0]['projectId']
# Check if the request was successful and print the result
if result['success']:
    print("Project Created Successfully:")
    print(result)

### Read Project
# Prepare data payload to read project details
payload = {
    "id": project_id  # ID of the project to read
}
# Send a POST request to the /projects/read endpoint to get project details
response = post(f'{BASE_URL}/projects/read', headers=get_headers(), json=payload)
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the project details
if result['success']:
    print("Project Details:")
    print(result)

### Update Project
# Send a POST request to the /projects/update endpoint to update project details
response = post(f'{BASE_URL}/projects/update', headers=get_headers(), json={
    "projectId": project_id,  # ID of the project to update
    "name": f"Project_{project_id}",  # New name for the project
    "description": "The new project name is awesome!"  # New description
})
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the result
if result['success']:
    print("Project Updated Successfully:")
    print(result)

### Delete Project
# Prepare data payload to delete the project
payload = {
    "projectId": project_id  # ID of the project to delete
}
# Send a POST request to the /projects/delete endpoint to delete the project
response = post(f'{BASE_URL}/projects/delete', headers=get_headers(), json=payload)
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the result
if result['success']:
    print("Project Deleted Successfully:")
    print(result)</pre>
</div>