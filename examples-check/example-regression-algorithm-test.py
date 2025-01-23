import base64
import hashlib
import time
import os
import requests
import json
from bs4 import BeautifulSoup
from multiprocessing import Pool

ROOT_DIR = "."
VALIDATE_MODE = True

BASE_API = "https://www.quantconnect.com/api/v2"
USER_ID = os.environ["DOCS_REGRESSION_TEST_USER_ID"]
USER_TOKEN = os.environ["DOCS_REGRESSION_TEST_USER_TOKEN"]
CS_PROJECT_ID = os.environ["DOCS_REGRESSION_TEST_CS_PROJECT"]
PY_PROJECT_ID = os.environ["DOCS_REGRESSION_TEST_PY_PROJECT"]

CS_AUTOCOMPLETE = """using System;
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
using QCAlgorithmFramework = QuantConnect.Algorithm.QCAlgorithm;
using QCAlgorithmFrameworkBridge = QuantConnect.Algorithm.QCAlgorithm;
using Calendar = QuantConnect.Data.Consolidators.Calendar;
"""
PY_AUTOCOMPLETE = "from AlgorithmImports import *"


class RegressionTests:
    def get_json_header(self):
        # Get timestamp
        timestamp = str(int(time.time()))
        time_stamped_token = USER_TOKEN + ':' + timestamp

        # Get hased API token
        hashed_token = hashlib.sha256(time_stamped_token.encode('utf-8')).hexdigest()
        authentication = f"{USER_ID}:{hashed_token}"
        api_token = base64.b64encode(authentication.encode('utf-8')).decode('ascii')
        
        return {
            'Authorization': 'Basic %s' % api_token,
            'Timestamp': timestamp
        }
        
    def init_api(self, id, token):
        """Connect to QuantConnect API for backtesting."""
        headers = self.get_json_header()
        response = requests.post(f"{BASE_API}/authenticate", headers = headers).json()
        
        if response["success"]:
            print("API Authentication Successfully.")
            return True
        print("API Authentication Failed.")
        return False
        
    def read_html_files(self, directory):
        """Recursively read all HTML/PHP files."""
        files = []
        for root, _, filenames in os.walk(directory):
            for filename in filenames:
                if filename.endswith(('.html', '.php')):
                    files.append(os.path.join(root, filename))
        return files

    def extract_pre_content(self, file_path):
        """Extract code snippets from testable section example container."""
        with open(file_path, 'r', encoding='utf-8') as file:
            soup = BeautifulSoup(file, 'html.parser')
            
            snippets = []
            # Get all code snippets from testable container
            for div in soup.find_all('div', class_='section-example-container', id='testable'):
                csharp_contents = []
                python_contents = []
                
                # Get C# and Python pre separately
                for pre in div.find_all('pre'):
                    if 'csharp' in pre.get('class', []):
                        csharp_contents.append(pre.get_text())
                    elif 'python' in pre.get('class', []):
                        python_contents.append(pre.get_text())
                        
                snippets.append((csharp_contents, python_contents))

            return snippets

    def clean_code(self, code):
        return code.replace('&amp;&amp;', '&&').replace('&lt;', '<').replace('&gt;', '>')
    
    def update_algorithm_content(self, project_id, file_name, snippets):
        """Update the Main on the project for backtesting."""
        headers = self.get_json_header()
        data = {
            "projectId": project_id,
            "name": file_name,
            "content": snippets
        }
        response = requests.post(
            f"{BASE_API}/files/update",
            headers = headers,
            data = data
        ).json()
        
        return response["success"]
    
    def compile_project(self, project_id):
        """Compile the project for backtesting."""
        headers = self.get_json_header()
        data = {
            "projectId": project_id,
        }
        response = requests.post(
            f"{BASE_API}/compile/create",
            headers = headers,
            data = data
        ).json()
        
        if response["success"]:
            while response["state"] != "BuildSuccess":
                if response["state"] == "BuildError":
                    return None
                time.sleep(2)
                
                data = {
                    "projectId": project_id,
                    "compileId": response["compileId"]
                }
                response = requests.post(
                    f"{BASE_API}/compile/read",
                    headers = headers,
                    data = data
                ).json()
                
                if not response["success"]:
                    return None
                
                return response["compileId"]
        
        return None
        
    def create_backtest(self, project_id, compile_id):
        """Create a regression backtest and return the result json"""
        headers = self.get_json_header()
        data = {
            "projectId": project_id,
            "compileId": compile_id,
            "backtestName": "regression_test"
        }
        response = requests.post(
            f"{BASE_API}/backtests/create",
            headers = headers,
            data = data
        ).json()
        
        if response["success"]:
            backtest = response["backtest"]
            if isinstance(backtest, list):
                backtest = backtest[-1]
            backtest_id = backtest["backtestId"]
            
            while backtest["status"] != "Completed.":
                time.sleep(15)
                
                data = {
                    "projectId": project_id,
                    "backtestId": backtest_id
                }
                response = requests.post(
                    f"{BASE_API}/backtests/read",
                    headers = headers,
                    data = data
                ).json()
                
                if not response["success"]:
                    return None
                backtest = response["backtest"]
                if isinstance(backtest, list):
                    backtest = backtest[-1]
                
            return str(backtest["statistics"])
        
        return None

    def backtest(self, file_path, example_num, language, content):
        project_id = CS_PROJECT_ID if language == "csharp" else PY_PROJECT_ID
        file_name = "Main.cs" if language == "csharp" else "main.py"
        content = CS_AUTOCOMPLETE + "\n" + content if language == "csharp" else PY_AUTOCOMPLETE + "\n" + content
        
        # Update the test project to the example code snippets
        update_success = self.update_algorithm_content(project_id, file_name, content)
        if not update_success:
            print(f"Update project content failed: {file_path} - {language} - Example {example_num}")
            return None
        
        # Compile the project
        compile_id = self.compile_project(project_id)
        if not compile_id:
            print(f"Compile project failed: {file_path} - {language} - Example {example_num}")
            return None
        
        # Backtest the project
        result_json = self.create_backtest(project_id, compile_id)
        return result_json

    def perform_backtests(self, file_path, contents):
        """Perform backtests on the provided contents."""
        return (
            [self.backtest(file_path, i, "csharp", self.clean_code(content)) for i, content in enumerate(contents[0])],  # C# results
            [self.backtest(file_path, i, "python", self.clean_code(content)) for i, content in enumerate(contents[1])]   # Python results
        )
        
    def validation(self, file_path, example_num, language, existing_script, new_json):
        for j, (existing, new) in enumerate(zip(existing_script.split('\n'), new_json.split('\n'))):
            if existing.strip() != new.strip():
                print(f"""
    Regression Test Failed:
    In ::       {file_path} Example {example_num}, {language}, line {j+1}
    Expect ::   {existing}
    But was ::  {new}
    """)

    def insert_validate_results(self, file_path, results):
        """Insert/Validate backtest results into the original file."""
        with open(file_path, 'r+', encoding='utf-8') as file:
            soup = BeautifulSoup(file, 'html.parser')
            divs = soup.find_all('div', class_='section-example-container', id='testable')

            for i, (div, (csharp_results, python_results)) in enumerate(zip(divs, results)):
                # C# results insertion
                for pre, new_result in zip(div.find_all('pre', class_='csharp'), csharp_results):
                    if not new_result:
                        print(f"No result json returned for {file_path} CSharp Example {i+1}, Skipping...")
                        continue
                    
                    existing_script = div.find_all('script', class_='csharp-result')[0]
                    new_json = json.dumps(json.loads(new_result.replace('\'', '\"')), indent=4)

                    if existing_script:
                        # Compare existing result with new result in validate mode
                        if VALIDATE_MODE:
                            self.validation(file_path, i, "CSharp", existing_script.text.strip(), new_json)
                        # Overwrite the existing result if not validate mode
                        else:
                            existing_script.string = new_json
                    else:
                        # Insert new script if none exists
                        if VALIDATE_MODE:
                            print(f"{file_path} Example {i+1}, CSharp: No existing regression test result exists, writing new results...")
                        new_script = soup.new_tag('script', type='text')
                        new_script['class'] = 'csharp-result'
                        new_script.string = new_json
                        pre.insert_after(new_script)

                # Python results insertion
                for pre, new_result in zip(div.find_all('pre', class_='python'), python_results):
                    if not new_result:
                        print(f"No result json returned for {file_path} Python Example {i+1}, Skipping...")
                        continue
                    
                    existing_script = div.find_all('script', class_='python-result')[0]
                    new_json = json.dumps(json.loads(new_result.replace('\'', '\"')), indent=4)

                    if existing_script:
                        # Compare existing result with new result in validate mode
                        if VALIDATE_MODE:
                            self.validation(file_path, i, "Python", existing_script.text.strip(), new_json)
                        # Overwrite the existing result if not validate mode
                        else:
                            existing_script.string = new_json
                    else:
                        # Insert new script if none exists
                        if VALIDATE_MODE:
                            print(f"{file_path} Example {i+1}, Python: No existing regression test result exists, writing new results...")
                        new_script = soup.new_tag('script', type='text')
                        new_script['class'] = 'python-result'
                        new_script.string = new_json
                        pre.insert_after(new_script)

            file.seek(0)
            file.write(str(soup.prettify(formatter="html5")))
            file.truncate()

    def process_file(self, file_path):
        """Complete processing for a single file."""
        snippets = self.extract_pre_content(file_path)
        if snippets:
            results = [self.perform_backtests(file_path, content) for content in snippets]
            self.insert_validate_results(file_path, results)

    def run(self):
        files = self.read_html_files(ROOT_DIR)
        
        # Speed up with multiprocessing.
        with Pool() as pool:
            pool.map(self.process_file, files)


if __name__ == "__main__":
    tester = RegressionTests()
    api_connect_success = tester.init_api(USER_ID, USER_TOKEN)
    if api_connect_success:
        tester.run()
    else:
        print("Failed to connect to QuantConnect API. Quitting...")