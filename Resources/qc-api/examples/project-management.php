<p>The following example demonstates creating, reading, updating, and deleting a project through the cloud API.</p>

<div class="python section-example-container testable">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

# Create Project
response = post(f'{BASE_URL}/projects/create', headers = get_headers(), data = { "name": f"Project_{int(time())}", "language": "Py" })
result = response.json()
project_id = result['projects'][0]['projectId']

# Read Project
response = post(f'{BASE_URL}/projects/read', headers = get_headers(), data = { "id": project_id })
result = response.json()

# Update Project
response = post(f'{BASE_URL}/projects/update', headers = get_headers(),
               data = {
                  "projectId": project_id,
                  "name": f"Project_{project_id}",
                  "description": "The new project name is awesome!"
            })
result = response.json()

# Delete Project
response = post(f'{BASE_URL}/projects/delete', headers = get_headers(), data = { "projectId": project_id })
result = response.json()</pre>
</div>