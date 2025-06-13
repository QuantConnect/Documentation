<p>The following example demonstates uploading, getting, deleting, and listing Object Store objects through the cloud API.</p>

<div class="python section-example-container testable">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

# Upload Object Store File
response = post(f'{BASE_URL}/object/set', headers = get_headers(), 
                data = { "organizationId": ORGANIZATION_ID, "key": key },
                files = { "objectData": b"Hello, world!" })
response.json()

# Get Object Store Metadata
response = post(f'{BASE_URL}/object/properties', headers = get_headers(), data = { "organizationId": ORGANIZATION_ID, "key": key })
response.json()

# Get Object Store File
response = post(f'{BASE_URL}/object/get', headers = get_headers(), data = { "organizationId": ORGANIZATION_ID, "keys": [key] })
response.json()

# Delete Object Store File
response = post(f'{BASE_URL}/object/delete', headers = get_headers(), data = { "organizationId": ORGANIZATION_ID, "key": key })
response.json()

# List Object Store Files
path = ""
response = post(f'{BASE_URL}/object/delete', headers = get_headers(), data = { "organizationId": ORGANIZATION_ID, "path": path })
response.json()</pre>
</div>