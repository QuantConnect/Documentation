"""Create QuantConnect Cloud projects from documentation testable code blocks.

Usage:
    python examples-check/run_single.py <file1> [file2] ...

Creates a project for each testable code block and prints the project ID
so a human can run the backtest manually.

Credentials (checked in order):
    1. Environment variables: DOCS_REGRESSION_TEST_USER_ID, DOCS_REGRESSION_TEST_USER_TOKEN
    2. LEAN CLI credentials file: ~/.lean/credentials

Dependencies: requests, beautifulsoup4
    pip install requests beautifulsoup4
"""
import sys
import os
import re
import json
import time
import base64
import hashlib
import subprocess
from pathlib import Path

try:
    import requests
except ImportError:
    sys.exit("Missing dependency: pip install requests")

try:
    from bs4 import BeautifulSoup
except ImportError:
    sys.exit("Missing dependency: pip install beautifulsoup4")


def _load_credentials():
    """Load API credentials from environment variables or ~/.lean/credentials."""
    user_id = os.environ.get("DOCS_REGRESSION_TEST_USER_ID", "")
    user_token = os.environ.get("DOCS_REGRESSION_TEST_USER_TOKEN", "")
    if user_id and user_token:
        return user_id, user_token

    lean_creds = Path.home() / ".lean" / "credentials"
    if lean_creds.exists():
        try:
            creds = json.loads(lean_creds.read_text())
            user_id = str(creds.get("user-id", ""))
            user_token = creds.get("api-token", "")
            if user_id and user_token:
                return user_id, user_token
        except (json.JSONDecodeError, KeyError):
            pass

    return "", ""


BASE_API = "https://www.quantconnect.com/api/v2"
USER_ID, USER_TOKEN = _load_credentials()
MAX_RETRIES = 5

# Patterns to detect QCAlgorithm subclasses
ALGO_PATTERNS = {
    "csharp": ": QCAlgorithm",
    "python": "(QCAlgorithm)"
}

# Import prefixes added before backtesting
IMPORTS = {
    "csharp": """using System;
using System.Collections;
using System.Collections.Generic;
using System.Linq;
using System.Globalization;
using System.Drawing;
using QuantConnect;
using QuantConnect.Algorithm.Framework;
using QuantConnect.Algorithm.Framework.Selection;
using QuantConnect.Algorithm.Framework.Alphas;
using QuantConnect.Algorithm.Framework.Portfolio;
using QuantConnect.Algorithm.Framework.Portfolio.SignalExports;
using QuantConnect.Algorithm.Framework.Execution;
using QuantConnect.Algorithm.Framework.Risk;
using QuantConnect.Algorithm.Selection;
using QuantConnect.Api;
using QuantConnect.Parameters;
using QuantConnect.Benchmarks;
using QuantConnect.Brokerages;
using QuantConnect.Commands;
using QuantConnect.Configuration;
using QuantConnect.Util;
using QuantConnect.Interfaces;
using QuantConnect.Algorithm;
using QuantConnect.Indicators;
using QuantConnect.Data;
using QuantConnect.Data.Auxiliary;
using QuantConnect.Data.Consolidators;
using QuantConnect.Data.Custom;
using QuantConnect.Data.Custom.IconicTypes;
using QuantConnect.DataSource;
using QuantConnect.Data.Fundamental;
using QuantConnect.Data.Market;
using QuantConnect.Data.Shortable;
using QuantConnect.Data.UniverseSelection;
using QuantConnect.Notifications;
using QuantConnect.Orders;
using QuantConnect.Orders.Fees;
using QuantConnect.Orders.Fills;
using QuantConnect.Orders.OptionExercise;
using QuantConnect.Orders.Slippage;
using QuantConnect.Orders.TimeInForces;
using QuantConnect.Python;
using QuantConnect.Scheduling;
using QuantConnect.Securities;
using QuantConnect.Securities.Equity;
using QuantConnect.Securities.Future;
using QuantConnect.Securities.Option;
using QuantConnect.Securities.Positions;
using QuantConnect.Securities.Forex;
using QuantConnect.Securities.Crypto;
using QuantConnect.Securities.CryptoFuture;
using QuantConnect.Securities.IndexOption;
using QuantConnect.Securities.Interfaces;
using QuantConnect.Securities.Volatility;
using QuantConnect.Storage;
using QuantConnect.Statistics;
using QuantConnect.Indicators.CandlestickPatterns;
using QCAlgorithmFramework = QuantConnect.Algorithm.QCAlgorithm;
using QCAlgorithmFrameworkBridge = QuantConnect.Algorithm.QCAlgorithm;
using Calendar = QuantConnect.Data.Consolidators.Calendar;
""",
    "python": "from AlgorithmImports import *\n"
}


def get_headers():
    """Generate authentication headers for the QC API."""
    timestamp = str(int(time.time()))
    hashed = hashlib.sha256(
        f"{USER_TOKEN}:{timestamp}".encode('utf-8')
    ).hexdigest()
    auth = base64.b64encode(
        f"{USER_ID}:{hashed}".encode('utf-8')
    ).decode('ascii')
    return {'Authorization': f'Basic {auth}', 'Timestamp': timestamp}


def api_post(endpoint, payload=None):
    """Make a POST request to the QC API."""
    return requests.post(
        f"{BASE_API}/{endpoint}",
        headers=get_headers(),
        json=payload or {}
    ).json()


def clean_code(code):
    """Clean HTML entities from code."""
    return code.replace('&amp;&amp;', '&&').replace('&lt;', '<').replace('&gt;', '>')


def read_file_as_html(file_path):
    """Read a file and return its HTML content. Handles PHP files."""
    if file_path.endswith('.php'):
        # Try to convert PHP to HTML
        with open(file_path, 'r', encoding='utf-8') as f:
            content = f.read()
        content = (
            content
            .replace('DOCS_RESOURCES."', '"./Resources')
            .replace("DOCS_RESOURCES.'", "'./Resources")
        )
        try:
            result = subprocess.run(
                ['php', '-d', 'short_open_tag=1', '-r', content],
                capture_output=True, text=True, encoding='utf-8', timeout=30
            )
            if result.returncode == 0 and result.stdout.strip():
                return result.stdout.strip().replace(
                    '<div class="section-example-container to-be-tested">',
                    '<div class="section-example-container testable">'
                )
        except (FileNotFoundError, subprocess.TimeoutExpired):
            pass
        # If PHP is unavailable, fall back to raw content
        return content
    else:
        with open(file_path, 'r', encoding='utf-8') as f:
            return f.read()


def extract_testable_blocks(file_path):
    """Extract testable code blocks from a documentation file.

    Returns list of (language, code, description) tuples.
    """
    html = read_file_as_html(file_path)
    soup = BeautifulSoup(html, 'html.parser')
    blocks = []

    divs = soup.find_all(
        lambda tag: (
            tag.name == 'div' and
            'class' in tag.attrs and
            'section-example-container' in tag['class']
        )
    )

    for div_idx, div in enumerate(divs):
        classes = div.attrs.get('class', [])
        if 'skip-test' in classes:
            continue

        testable = 'testable' in classes

        # Also check if the file name suggests examples
        filename = os.path.basename(file_path).split('.')[0]
        h3_title = re.sub(r'^\d+\s*', '', filename).lower()
        is_example_page = h3_title in [
            'example', 'examples',
            'demonstration algorithm', 'demonstration algorithms',
            'example application', 'example applications'
        ]

        for pre in div.find_all('pre'):
            code = pre.get_text()
            pre_classes = pre.get('class', [])

            if 'csharp' in pre_classes:
                language = 'csharp'
            elif 'python' in pre_classes:
                language = 'python'
            else:
                continue

            # Must be a QCAlgorithm subclass
            if ALGO_PATTERNS[language] not in code:
                continue

            # Must be testable or an example page with substantial code
            if not testable and not (is_example_page and len(code.split('\n')) >= 50):
                continue

            desc = f"{os.path.basename(file_path)} div={div_idx} [{language}]"
            blocks.append((language, code, desc))

    return blocks


def create_project(language, code, source_desc):
    """Create a QuantConnect Cloud project with the code uploaded.

    Returns (project_id: int | None, message: str).
    """
    lang_code = 'Py' if language == 'python' else 'C#'
    file_name = 'main.py' if language == 'python' else 'Main.cs'

    # Prepare code
    code = clean_code(code)
    full_code = IMPORTS[language] + code

    # Create project
    timestamp = int(time.time())
    project_name = f'DocTest/{timestamp}_{language}'
    response = api_post('projects/create', {
        'name': project_name,
        'language': lang_code
    })
    if not response.get('success') and not response.get('projects'):
        return None, f"Failed to create project: {response}"
    project_id = response['projects'][0]['projectId']

    # Upload code
    for _ in range(MAX_RETRIES):
        resp = api_post('files/update', {
            'projectId': project_id,
            'name': file_name,
            'content': full_code
        })
        if resp.get('success'):
            break
        time.sleep(3)
    else:
        return None, "Failed to upload code"

    return project_id, project_name


def main():
    if len(sys.argv) < 2:
        print(__doc__)
        sys.exit(1)

    if not USER_ID or not USER_TOKEN:
        sys.exit(
            "Error: Set DOCS_REGRESSION_TEST_USER_ID and "
            "DOCS_REGRESSION_TEST_USER_TOKEN environment variables."
        )

    # Authenticate
    resp = api_post('authenticate')
    if not resp.get('success'):
        sys.exit("API authentication failed. Check your credentials.")
    print("API authentication successful.\n")

    file_paths = sys.argv[1:]
    created = []

    for file_path in file_paths:
        if not os.path.exists(file_path):
            print(f"SKIP: {file_path} (file not found)")
            continue

        blocks = extract_testable_blocks(file_path)
        if not blocks:
            print(f"SKIP: {file_path} (no testable code blocks found)")
            continue

        print(f"Found {len(blocks)} testable block(s) in {file_path}")

        for language, code, desc in blocks:
            print(f"\n  Creating project for: {desc}")
            project_id, message = create_project(language, code, desc)
            if project_id:
                print(f"  OK: project {project_id} ({message})")
                created.append((desc, project_id, message))
            else:
                print(f"  FAIL: {message}")

    print()
    if created:
        print("Created projects:")
        for desc, project_id, name in created:
            print(f"  {project_id}  {name}  ({desc})")
    else:
        print("No projects created.")


if __name__ == '__main__':
    main()
