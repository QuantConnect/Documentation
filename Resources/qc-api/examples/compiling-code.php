<p>The following example demonstates creating, and reading a compilation job through the cloud API.</p>

<div class="python section-example-container testable">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

# Create Compilation Job
response = post(f'{BASE_URL}/compile/create', headers = get_headers(), data = { "projectId": project_id })
result = response.json()
compile_id = result['compileId']

# Read Compilation Result
response = post(f'{BASE_URL}/compile/read', headers = get_headers(), data = { "projectId": project_id, "compileId": compile_id })
response.json()</pre>
</div>