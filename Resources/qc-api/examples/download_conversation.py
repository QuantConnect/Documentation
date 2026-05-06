"""Download a single agent conversation by deployment id and save it to JSON.

Usage:
    python download_conversation.py <deployment_id> [path/to/prompt.md]

The JSON is saved next to the prompt file as ``<deployment_id>.json``.
If no prompt file is given, ``prompt.md`` in the current directory is used.

Credentials resolve in this order:
    1. ``USER_ID`` / ``API_TOKEN`` set below.
    2. Any value left blank/zero falls back to ``~/.lean/credentials``.
"""

import json
import sys
from base64 import b64encode
from hashlib import sha256
from pathlib import Path
from time import time

import requests

URL = "https://beta.quantconnect.com/api/v2/agents/deployments/conversation/read"

DEPLOYMENT_ID = "A-99bd84b60376ecda56c1145b2074ca32"
PROMPTS_FILE = Path("prompt.md")

USER_ID = 0
API_TOKEN = ""


def load_credentials() -> dict:
    global USER_ID, API_TOKEN
    if not USER_ID or not API_TOKEN:
        with open(Path.home() / ".lean" / "credentials") as f:
            file_creds = json.load(f)
        if not USER_ID:
            USER_ID = file_creds["user-id"]
        if not API_TOKEN:
            API_TOKEN = file_creds["api-token"]
    return {"user-id": USER_ID, "api-token": API_TOKEN}


def main():
    args = sys.argv[1:]
    deployment_id = args[0] if args else DEPLOYMENT_ID
    prompts_file = Path(args[1]) if len(args) > 1 else PROMPTS_FILE

    creds = load_credentials()
    timestamp = str(int(time()))
    hashed = sha256(f"{creds['api-token']}:{timestamp}".encode()).hexdigest()
    auth = b64encode(f"{creds['user-id']}:{hashed}".encode()).decode()

    resp = requests.post(
        URL,
        json={"deploymentId": deployment_id},
        headers={"Authorization": f"Basic {auth}", "Timestamp": timestamp},
    )
    resp.raise_for_status()

    out = prompts_file.resolve().parent / f"{deployment_id}.json"
    out.write_text(json.dumps(resp.json(), indent=2))
    print(f"Saved to {out}")


if __name__ == "__main__":
    main()
