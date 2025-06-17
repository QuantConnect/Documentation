<p>The following example demonstates uploading, getting, deleting, and listing Object Store objects through the cloud API.</p>

<div class="python section-example-container testable">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

# The key of the object wishes to manipulate
key = "..."

### Upload Object Store File
# Send a POST request to the /object/set endpoint to upload a file
response = post(f'{BASE_URL}/object/set', headers=get_headers(), 
                data={"organizationId": ORGANIZATION_ID, "key": key},  # Organization ID and key for the object
                files={"objectData": b"Hello, world!"})  # File content as bytes
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the result
if result['success']:
    print("Object Store File Uploaded Successfully:")
    print(result)

### Get Object Store Metadata
# Prepare data payload to get object metadata
data = {
    "organizationId": ORGANIZATION_ID,  # ID of the organization
    "key": key  # Key of the object to get metadata for
}
# Send a POST request to the /object/properties endpoint to get metadata
response = post(f'{BASE_URL}/object/properties', headers=get_headers(), data=data)
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the metadata
if result['success']:
    print("Object Store Metadata:")
    print(result)

### Get Object Store File
# Prepare data payload to retrieve the object
data = {
    "organizationId": ORGANIZATION_ID,  # ID of the organization
    "keys": [key]  # List of keys to retrieve (single key in this case)
}
# Send a POST request to the /object/get endpoint to get the object
response = post(f'{BASE_URL}/object/get', headers=get_headers(), data=data)
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the file content
if result['success']:
    print("Object Store File Content:")
    print(result)

### Delete Object Store File
# Prepare data payload to delete the object
data = {
    "organizationId": ORGANIZATION_ID,  # ID of the organization
    "key": key  # Key of the object to delete
}
# Send a POST request to the /object/delete endpoint to delete the object
response = post(f'{BASE_URL}/object/delete', headers=get_headers(), data=data)
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the result
if result['success']:
    print("Object Store File Deleted Successfully:")
    print(result)

### List Object Store Files
# Define an empty path to list all objects (replace with specific path if needed)
path = ""
# Prepare data payload to list objects
data = {
    "organizationId": ORGANIZATION_ID,  # ID of the organization
    "path": path  # Path to list objects from
}
# Send a POST request to the /object/list endpoint to list objects
response = post(f'{BASE_URL}/object/list', headers=get_headers(), data=data)
# Parse the JSON response into python managable dict
result = response.json()
# Check if the request was successful and print the list
if result['success']:
    print("List of Object Store Files:")
    print(result)</pre>
</div>