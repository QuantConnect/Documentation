<p>The following example demonstates creating, reading, and deleting a project collaborator through the cloud API.</p>

<div class="python section-example-container testable">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

# The project ID of the project to manage its collaborator
project_id = 12345678

### Create Project Collaborator
# Define collaborator ID (replace with actual user ID)
collaborator_id = 'johnny_walker'  # User ID from profile URL (e.g., https://www.quantconnect.com/u/johnny_walker)
# Send a POST request to the /projects/collaboration/create endpoint to add a collaborator
response = post(f'{BASE_URL}/projects/collaboration/create', headers=get_headers(), json={
    "projectId": project_id,  # ID of the project
    "collaboratorUserId": collaborator_id,  # ID of the user to add as collaborator
    "collaborationLiveControl": True,  # Grant live control permission
    "collaborationWrite": True  # Grant write permission
})
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the result
if result['success']:
    print("Project Collaborator Created Successfully:")
    print(result)

### Read Project Collaborator
# Prepare data payload to read collaborators
payload = {
    "projectId": project_id  # ID of the project
}
# Send a POST request to the /projects/collaboration/read endpoint to get collaborators
response = post(f'{BASE_URL}/projects/collaboration/read', headers=get_headers(), json=payload)
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the collaborators
if result['success']:
    print("Project Collaborators:")
    print(result)

### Delete Project Collaborator
# Prepare data payload to delete a collaborator
payload = {
    "projectId": project_id,  # ID of the project
    "collaboratorUserId": collaborator_id  # ID of the collaborator to remove
}
# Send a POST request to the /projects/collaboration/delete endpoint to remove collaborator
response = post(f'{BASE_URL}/projects/collaboration/delete', headers=get_headers(), json=payload)
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the result
if result['success']:
    print("Project Collaborator Deleted Successfully:")
    print(result)</pre>
</div>