<p>The following example demonstates reading, and updating a project nodes through the cloud API.</p>

<div class="python section-example-container testable">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

# Read Project Nodes
response = post(f'{BASE_URL}/projects/nodes/read', headers = get_headers(), data = { "projectId": project_id })
result = response.json()
response.json()

# Delete Project Collaborator
nodes = ["node_name_1", "node_name_2"]
response = post(f'{BASE_URL}/projects/nodes/update', headers = get_headers(), data = { "projectId": project_id, "nodes": nodes })
result = response.json()</pre>
</div>