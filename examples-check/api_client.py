"""API client for QuantConnect API interactions."""
import base64
import hashlib
import time
import requests
from ratelimit import limits, sleep_and_retry

from config import Config


class APIClient:
    """Handles authentication and API calls to QuantConnect."""

    def __init__(self):
        self._user_id = Config.USER_ID
        self._user_token = Config.USER_TOKEN
        self._base_api = Config.BASE_API

    def _get_headers(self):
        """Generate authentication headers for API requests."""
        # Get the timestamp.
        timestamp = str(int(time.time()))
        time_stamped_token = f"{self._user_token}:{timestamp}"
        # Get the hashed API token.
        hashed_token = hashlib.sha256(
            time_stamped_token.encode('utf-8')
        ).hexdigest()
        authentication = f"{self._user_id}:{hashed_token}"
        api_token = base64.b64encode(
            authentication.encode('utf-8')
        ).decode('ascii')
        # Create the headers dictionary.
        return {
            'Authorization': f'Basic {api_token}',
            'Timestamp': timestamp
        }

    @sleep_and_retry
    @limits(calls=Config.CALLS, period=Config.RATE_LIMIT)
    def _post(self, endpoint, payload={}):
        """Make a rate-limited POST request to the API."""
        return requests.post(
            f"{self._base_api}/{endpoint}",
            headers=self._get_headers(),
            json=payload
        ).json()

    def authenticate(self):
        """Authenticate with QuantConnect API."""
        response = self._post("authenticate")
        if response.get("success"):
            print("API Authentication Successful.")
            return True
        print("API Authentication Failed.")
        return False

    def create_project(self, name, language):
        """Create a new project and return the project Id."""
        return self._post(
            'projects/create', {'name': name, 'language': language}
        )['projects'][0]['projectId']

    def update_file(self, project_id, file_name, content):
        """Update a file in the project."""
        for _ in range(Config.MAX_RETRY_ATTEMPTS):
            response = self._post(
                "files/update", 
                {"projectId": project_id, "name": file_name, "content": content}
            )
            if response.get("success"):
                return True
            time.sleep(3)
        return False

    def compile_project(self, project_id):
        """Compile a project and return the compile ID."""
        response = self._post("compile/create", {"projectId": project_id})
        for _ in range(Config.MAX_RETRY_ATTEMPTS):
            if response.get("state") == "BuildError":
                return None
            response = self._post(
                "compile/read", 
                {"projectId": project_id, "compileId": response["compileId"]}
            )
            if response.get("success") and response.get("state") == "BuildSuccess":
                return response["compileId"]
            time.sleep(1)
        return None

    def create_backtest(self, project_id, compile_id, backtest_name):
        """Create a backtest and return the backtest Id."""
        for _ in range(Config.MAX_RETRY_ATTEMPTS):
            response = self._post(
                "backtests/create", 
                {
                    "projectId": project_id,
                    "compileId": compile_id,
                    "backtestName": backtest_name
                }
            )
            if response.get("success"):
                break
            time.sleep(3)
        return response

    def read_backtest(self, project_id, backtest_id):
        """Read backtest results, waiting for completion."""
        errors = 0
        while True:
            response = self._post(
                "backtests/read", 
                {"projectId": project_id, "backtestId": backtest_id}
            )
            if not response.get("success"):
                errors += 1
                if errors >= Config.MAX_RETRY_ATTEMPTS:
                    return None
                continue
            backtest = response["backtest"]
            if isinstance(backtest, list):
                backtest = backtest[0]
            if backtest.get("completed"):
                break
            time.sleep(10)
        return backtest

    def read_backtest_logs(
            self, project_id, backtest_id, query='', start=0, end=1):
        """Read backtest logs with optional query filter."""
        return self._post(
            "backtests/read/log", 
            {
                "projectId": project_id,
                "backtestId": backtest_id,
                'start': start,
                'end': end,
                'query': query
            }
        )

    def read_backtest_orders(self, project_id, backtest_id, start=0, end=99):
        """Read backtest orders."""
        return self._post(
            "backtests/orders/read", 
            {
                "start": start,
                "end": end,
                "projectId": project_id,
                "backtestId": backtest_id
            }
        )
    
    def check_python_syntax(self, content):
        """Run the Syntax Check AI tool."""
        return self._post(
            "ai/tools/syntax-check", 
            {'files': [{'name': 'file.py', 'content': content}]}
        ).get('payload', [])
