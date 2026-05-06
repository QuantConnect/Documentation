<p>The following example demonstrates creating, reading, updating, deleting, and listing agent tasks; deploying a task; and reading, prompting, stopping, and deleting a deployment through the cloud API.</p>

<div class="section-example-container">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

### Create Task
# Send a POST request to the /agents/tasks/create endpoint to create a task
response = post(f'{BASE_URL}/agents/tasks/create', headers=get_headers(), json={
    "organizationId": ORGANIZATION_ID,                  # Organization that owns the task
    "agentId": 47,                                      # ID of the agent the task uses
    "name": f"Task_{int(time())}",                      # Unique task name
    "prompt": "Build a buy and hold strategy for SPY",  # Prompt the agent will run
    "projectPreference": "project-each-task-deployment" # New project per deployment
})
# Parse the JSON response into python managable dict
result = response.json()
# Extract the task ID from the response
task_id = result['task']['id']
# Check if the request was successful and print the result
if result['success']:
    print("Task Created Successfully:")
    print(result)

### Read Task
# Send a POST request to the /agents/tasks/read endpoint to read task details
response = post(f'{BASE_URL}/agents/tasks/read', headers=get_headers(), json={
    "taskId": task_id  # ID of the task to read
})
# Parse the JSON response into python managable dict
result = response.json()
# Extract the task name from the response
task_name = result['task']['name']
# Check if the request was successful and print the details
if result['success']:
    print("Task Details:")
    print(result)

### Update Task
# Send a POST request to the /agents/tasks/update endpoint to rename the task
response = post(f'{BASE_URL}/agents/tasks/update', headers=get_headers(), json={
    "taskId": task_id,           # ID of the task to update
    "name": f"Test - {task_name}" # Prepend "Test - " to the existing name
})
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the result
if result['success']:
    print("Task Updated Successfully:")
    print(result)

### List Tasks
# Send a POST request to the /agents/tasks/list endpoint to list tasks in the org
response = post(f'{BASE_URL}/agents/tasks/list', headers=get_headers(), json={
    "organizationId": ORGANIZATION_ID  # Organization whose tasks to list
})
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the list
if result['success']:
    print("List of Tasks:")
    print(result)

### Create Deployment
# Send a POST request to the /agents/deployments/create endpoint to deploy the task
response = post(f'{BASE_URL}/agents/deployments/create', headers=get_headers(), json={
    "taskId": task_id  # ID of the task to deploy
})
# Parse the JSON response into python managable dict
result = response.json()
# Extract the deployment ID from the response
deployment_id = result['deployment']['deploymentId']
# Check if the request was successful and print the result
if result['success']:
    print("Deployment Created Successfully:")
    print(result)

### Read Deployment
# Poll the /agents/deployments/read endpoint until the deployment reaches a terminal status
from time import sleep
while True:
    response = post(f'{BASE_URL}/agents/deployments/read', headers=get_headers(), json={
        "deploymentId": deployment_id  # ID of the deployment to read
    })
    # Parse the JSON response into python managable dict
    result = response.json()
    status = result['deployment']['status']
    print(f"Deployment status: {status}")
    # Stop polling once the deployment is no longer running
    if status in ('Completed', 'Stopped', 'Failed'):
        break
    # Wait before polling again
    sleep(10)
# Print the final deployment details
print("Deployment Details:")
print(result)

### List Deployments
# Send a POST request to the /agents/deployments/list endpoint to list deployments of the task
response = post(f'{BASE_URL}/agents/deployments/list', headers=get_headers(), json={
    "taskId": task_id  # ID of the task whose deployments to list
})
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the list
if result['success']:
    print("List of Deployments:")
    print(result)

### Update Deployment (send a follow-up prompt)
# Send a POST request to the /agents/deployments/prompt endpoint with a new prompt
response = post(f'{BASE_URL}/agents/deployments/prompt', headers=get_headers(), json={
    "deploymentId": deployment_id,      # ID of the running deployment
    "prompt": "Replace SPY for QQQ"     # Follow-up prompt for the deployment
})
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the result
if result['success']:
    print("Prompt Sent Successfully:")
    print(result)

### Read Conversation
# Send a POST request to the /agents/deployments/conversation/read endpoint to read the transcript
response = post(f'{BASE_URL}/agents/deployments/conversation/read', headers=get_headers(), json={
    "deploymentId": deployment_id  # ID of the deployment whose conversation to read
})
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the conversation
if result['success']:
    print("Conversation Transcript:")
    print(result)

### Stop Deployment
# Send a POST request to the /agents/deployments/stop endpoint to stop the running deployment
response = post(f'{BASE_URL}/agents/deployments/stop', headers=get_headers(), json={
    "deploymentId": deployment_id  # ID of the deployment to stop
})
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the result
if result['success']:
    print("Deployment Stopped Successfully:")
    print(result)

### Delete Deployment
# Send a POST request to the /agents/deployments/delete endpoint to delete the deployment
response = post(f'{BASE_URL}/agents/deployments/delete', headers=get_headers(), json={
    "deploymentId": deployment_id  # ID of the deployment to delete
})
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the result
if result['success']:
    print("Deployment Deleted Successfully:")
    print(result)

### Delete Task
# Send a POST request to the /agents/tasks/delete endpoint to delete the task
response = post(f'{BASE_URL}/agents/tasks/delete', headers=get_headers(), json={
    "taskId": task_id  # ID of the task to delete
})
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the result
if result['success']:
    print("Task Deleted Successfully:")
    print(result)</pre>
</div>
