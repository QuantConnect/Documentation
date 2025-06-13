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

# Authenticate to verify credentials
response = post(f'{BASE_URL}/authenticate', headers = get_headers())
print(response.json())

# --------------------

