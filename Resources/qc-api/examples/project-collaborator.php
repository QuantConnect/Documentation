<p>The following example demonstates creating, reading, and deleting a project collaborator through the cloud API.</p>

<div class="python section-example-container testable">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

# Create Project Collaborator
collaborator_id = 'johnny_walker'   # You can find your user name in you profile URL, e.g., https://www.quantconnect.com/u/johnny_walker
response = post(f'{BASE_URL}/projects/collaboration/create', headers = get_headers(), data = { "projectId": project_id, "collaboratorUserId": collaborator_id, "collaborationLiveControl": True, "collaborationWrite": True })
result = response.json()

# Read Project Collaborator
response = post(f'{BASE_URL}/projects/collaboration/read', headers = get_headers(), data = { "projectId": project_id })
result = response.json()

# Delete Project Collaborator
response = post(f'{BASE_URL}/projects/collaboration/delete', headers = get_headers(), data = { "projectId": project_id, "collaboratorUserId": collaborator_id })
result = response.json()</pre>
</div>