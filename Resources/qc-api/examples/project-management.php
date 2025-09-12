<p>The following examples demonstrate project management through the QuantConnect API.</p>

<h4>Example 1: CRUD Operations</h4>
<p>This example demonstates creating, reading, updating, and deleting a project through the cloud API.</p>

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


<h4>Example 2: Delete All Projects in a Directory</h4>
<p>This example deletes all projects under a specific directory in the organization workspace.</p>

<div class="python section-example-container testable">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>
def get_projects_in_directory(key):
    """Get all projects in a specific directory in the organization 
    workspace.

    Keyword arguments:
    key -- path to a directory of projects in the organization workspace
           (ex: path/to/dir)
    """
    # Split the key into path segments.
    key_segments = [s for s in key.split('/') if s]
    if not key_segments:
        print('Invalid key:', key)
        return
    # Iterate through all projects
    response = post(f'{BASE_URL}/projects/read', headers=get_headers())
    response.raise_for_status()
    project_ids = []
    for i, project in enumerate(response.json()['projects']):
        if all(a == b for a, b in zip(key_segments, project['name'].split('/'))):
            project_ids.append(project['projectId'])
    return project_ids

def delete_projects(project_ids):
    """Delete a set of projects.

    Keyword arguments:
    project_ids -- list of project Ids.
    """
    for id_ in project_ids:
        response = post(
            f'{BASE_URL}/projects/delete', 
            headers=get_headers(), 
            json={'projectId': id_}
        )
        response.raise_for_status()

delete_projects(get_projects_in_directory('/path/to/projects/to/delete'))</pre>
</div>
