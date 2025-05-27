from base64 import b64encode
from hashlib import sha256
from time import time
from requests import get, post
BASE_URL = 'https://www.quantconnect.com/api/v2/'

# You need to replace these with your actual credentials.
# You can request your credentials at https://www.quantconnect.com/settings/
# You can find our organization ID at https://www.quantconnect.com/organization/ 
USER_ID = 0
API_TOKEN = '____'
ORGANIZATION_ID = '____'
# Define a key to filter projects by name.
# This key will be used to search for projects that contain this string in their name.
PROJECT_NAME_SEARCH_KEY = 'unsorted'

def get_headers():
    # Get timestamp
    timestamp = f'{int(time())}'
    time_stamped_token = f'{API_TOKEN}:{timestamp}'.encode('utf-8')

    # Get hased API token
    hashed_token = sha256(time_stamped_token).hexdigest()
    authentication = f'{USER_ID}:{hashed_token}'.encode('utf-8')
    authentication = b64encode(authentication).decode('ascii')

    # Create headers dictionary.
    return {
        'Authorization': f'Basic {authentication}',
        'Timestamp': timestamp
    }

# Authenticate
response = post(f'{BASE_URL}/authenticate', headers = get_headers())
print(response.json())

def delete_project(project_id):
    """
    Deletes a project by its ID.
    :param project_id: The ID of the project to delete.
    :return: Response from the API.
    """
    response = post(f'{BASE_URL}/projects/delete', headers=get_headers(), data={'projectId': project_id})
    return response.json()

def list_projects(organization_id):
    """
    Lists all projects for the authenticated user.
    :return: Response from the API containing the list of projects.
    """
    response = get(f'{BASE_URL}/projects/read', headers=get_headers())
    projects = response.json().get('projects', [])
    # Filter projects by organization ID to ensure we only get projects belonging to the specified organization.
    return [p for p in projects if p['organizationId'] == organization_id]

if __name__ == "__main__":
    # List all projects of the organization
    projects = list_projects(ORGANIZATION_ID)
    print("All Projects:", len(projects))
    
    # Filter projects by a specific key in the name
    projects = [p for p in projects if PROJECT_NAME_SEARCH_KEY in p['name']]
    print("Filtered Projects:", len(projects))

    for project in projects:
        # Delete a specific project by ID
        project_id_to_delete = project['projectId']
        delete_response = delete_project(project_id_to_delete)
        print(f"Project ID: {project_id_to_delete}, Name: {project['name']}, Delete Response: {delete_response}")