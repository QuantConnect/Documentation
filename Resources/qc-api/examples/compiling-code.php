<p>The following example demonstates creating, and reading a compilation job through the cloud API.</p>

<div class="python section-example-container testable">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

# The project ID of the project to compile
project_id = 12345678

### Create Compilation Job
# Prepare data payload to create a compilation job
data = {
    "projectId": project_id  # ID of the project to compile
}
# Send a POST request to the /compile/create endpoint to start compilation
response = post(f'{BASE_URL}/compile/create', headers=get_headers(), data=data)
# Parse the JSON response into python managable dict
result = response.json()
# Extract the compile ID from the response
compile_id = result['compileId']
# Check if the request was successful and print the result
if result['success']:
    print("Compilation Job Created Successfully:")
    print(result)

### Read Compilation Result
# Prepare data payload to read compilation result
data = {
    "projectId": project_id,  # ID of the project
    "compileId": compile_id  # ID of the compilation job
}
# Send a POST request to the /compile/read endpoint to get compilation result
response = post(f'{BASE_URL}/compile/read', headers=get_headers(), data=data)
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the result
if result['success']:
    print("Compilation Result:")
    print(result)</pre>
</div>