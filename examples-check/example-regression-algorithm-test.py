import base64
import hashlib
import json
import os
import requests
import subprocess
import time
from AlgorithmImports import *
from bs4 import BeautifulSoup
from datetime import datetime
from itertools import zip_longest
import multiprocessing as mp

ROOT_DIR = "."
VALIDATE_MODE = False
MAX_COWORKER = 6        # Limited by number of backtest nodes

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
        
    def init_api(self):
        """Connect to QuantConnect API for backtesting."""
        headers = self.get_json_header()
        response = requests.post(f"{BASE_API}/authenticate", headers = headers).json()
        
        if response["success"]:
            print("API Authentication Successfully.")
            return True
        print("API Authentication Failed.")
        return False
    
    def run_php_script(self, php_path):
        # Read the php script.
        with open(php_path, 'r', encoding="utf-8") as f:
            input = f.read()
        input = input.replace("DOCS_RESOURCES.\"", "\"Resources")
        
        # Create a temporary PHP file
        with open('temp_script.php', 'w') as f:
            f.write(input)
        
        # Run the PHP script and capture the output
        result = subprocess.run(['php', 'temp_script.php'], capture_output=True, text=True)
        return result.stdout.strip().replace("<div class=\"section-example-container\">", "<div class=\"section-example-container testable\">")
        
    def get_testing_files(self, directory):
        """Recursively read all HTML/PHP files."""
        target_text = ['section-example-container', 'testable', 'div']
        files = []
        
        for root, _, filenames in os.walk(directory):
            for filename in filenames:
                if filename.endswith(('.html', '.php')):
                    file_path = os.path.join(root, filename)
                    # Check if the file contains the target text
                    with open(file_path, 'r', encoding='utf-8') as file:
                        if all([x in file.read() for x in target_text]):
                            files.append(file_path)
                            
        return files

    def extract_pre_content(self, file_path):
        """Extract code snippets from testable section example container."""
        # Convert php codes
        if file_path.endswith(".php"):
            self.run_php_script(file_path)
            file_path = "temp_script.php"
            
        with open(file_path, 'r', encoding='utf-8') as file:
            soup = BeautifulSoup(file, 'html.parser')
            
            snippets = []
            # Get all code snippets from testable container
            for div in soup.find_all('div', class_=['section-example-container', 'testable']):
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
                # Rechek every 2 seconds
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
        
    def create_backtest(self, file_path, example_num, project_id, compile_id):
        """Create a regression backtest and return the result json"""
        headers = self.get_json_header()
        data = {
            "projectId": project_id,
            "compileId": compile_id,
            "backtestName": f"{file_path} Example {example_num}"
        }
        response = requests.post(
            f"{BASE_API}/backtests/create",
            headers = headers,
            data = data
        ).json()
        
        return response
        
    def read_backtest(self, response, project_id):
        headers = self.get_json_header()
        
        if response["success"]:
            backtest = response["backtest"]
            if isinstance(backtest, list):
                backtest = backtest[-1]
            backtest_id = backtest["backtestId"]
            
            errors = 0
            while not backtest["completed"]:
                # Recheck every 10 seconds
                time.sleep(10)
                
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
                    errors += 1
                    if errors >= 5:
                        return None, response
                    continue
                backtest = response["backtest"]
                if isinstance(backtest, list):
                    backtest = backtest[-1]
                    
            # Add order hash
            backtest["statistics"]["OrderListHash"] = Extensions.get_hash(backtest["orders"])
                
            return str(backtest["statistics"]), response
        
        return None, response

    def backtest(self, file_path, example_num, language, content, total_example_num, manager_list):
        self.backtest_started.value = False
        project_id = CS_PROJECT_ID if language == "csharp" else PY_PROJECT_ID
        file_name = "Main.cs" if language == "csharp" else "main.py"
        content = CS_AUTOCOMPLETE + "\n" + content if language == "csharp" else PY_AUTOCOMPLETE + "\n" + content
        
        # Update the test project to the example code snippets
        update_success = self.update_algorithm_content(project_id, file_name, content)
        if not update_success:
            msg = "Update project content failed"
            self.log_error(file_path, example_num, language, "", msg)
            if example_num == total_example_num:
                self.backtest_started.value = True
            return
        
        # Compile the project
        compile_id = self.compile_project(project_id)
        if not compile_id:
            msg = "Compile project failed"
            self.log_error(file_path, example_num, language, "", msg)
            if example_num == total_example_num:
                self.backtest_started.value = True
            return
        
        # Backtest the project
        response = self.create_backtest(file_path, example_num, project_id, compile_id)
        if example_num == total_example_num:
            self.backtest_started.value = True
        
        # Read the backtest results
        result_json, response = self.read_backtest(response, project_id)
        if not result_json:
            msg = f"Backtest failed, no results json returned {'- ' + str(response['error']) if response.get('error', None) else ''}"
            self.log_error(file_path, example_num, language, "", msg)
        manager_list.append(result_json)

    def perform_backtests(self, file_path, contents):
        """Perform backtests on the provided contents."""
        manager = mp.Manager()
        manager_list_cs = manager.list()
        manager_list_py = manager.list()
        cs_size = len(contents[0])
        py_size = len(contents[1])
        
        for i, (cs_content, py_content) in enumerate(zip_longest(contents[0], contents[1])):
            if cs_content:
                process_cs = mp.Process(target=self.backtest, args=(file_path, i+1, "csharp", self.clean_code(cs_content), cs_size, manager_list_cs))
                process_cs.start()
            if py_content:
                process_py = mp.Process(target=self.backtest, args=(file_path, i+1, "python", self.clean_code(py_content), py_size, manager_list_py))
                process_py.start()
            if cs_content:
                process_cs.join()
            if py_content:
                process_py.join()
            
        return (
            list(manager_list_cs),  # C# results
            list(manager_list_py)   # Python results
        )
        
    def validation(self, file_path, example_num, language, existing_script, new_json):
        for j, (existing, new) in enumerate(zip(existing_script.split('\n'), new_json.split('\n'))):
            if existing.strip() != new.strip():
                print(self.log_error(file_path, example_num, language, existing, new, j))
                
    def log_error(self, file_path, example_num, language, expect, actual, line=None):
        return f"""
    Regression Test Failed:
    In ::       {file_path} Example {example_num}, {language}{", line " + line+1 if line is not None else ""}
    Expect ::   {expect}
    But was ::  {actual}
    """
    
    def insert_validate_example_container(self, file_path, soup, results):
        for i, (div, (csharp_results, python_results)) in enumerate(zip(soup.find_all('div', class_=['section-example-container', 'testable']), results)):
            # C# results insertion
            for pre, new_result in zip(div.find_all('pre', class_='csharp'), csharp_results):
                if not new_result:
                    print(f"No result json returned for {file_path} CSharp Example {i+1}, Skipping...")
                    continue
                
                existing_script = div.find_all('script', class_='csharp-result')
                new_json = json.dumps(json.loads(new_result.replace('\'', '\"')), indent=4)

                if existing_script:
                    # Compare existing result with new result in validate mode
                    if VALIDATE_MODE:
                        self.validation(file_path, i+1, "CSharp", existing_script[0].text.strip(), new_json)
                    # Overwrite the existing result if not validate mode
                    else:
                        existing_script[0].string = new_json
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
                
                existing_script = div.find_all('script', class_='python-result')
                new_json = json.dumps(json.loads(new_result.replace('\'', '\"')), indent=4)

                if existing_script:
                    # Compare existing result with new result in validate mode
                    if VALIDATE_MODE:
                        self.validation(file_path, i+1, "Python", existing_script[0].text.strip(), new_json)
                    # Overwrite the existing result if not validate mode
                    else:
                        existing_script[0].string = new_json
                else:
                    # Insert new script if none exists
                    if VALIDATE_MODE:
                        print(f"{file_path} Example {i+1}, Python: No existing regression test result exists, writing new results...")
                    new_script = soup.new_tag('script', type='text')
                    new_script['class'] = 'python-result'
                    new_script.string = new_json
                    pre.insert_after(new_script)
                    
        return soup
    
    def insert_validate_php(self, file_path, soup, results):
        for i, (div, (csharp_results, python_results)) in enumerate(zip(soup.find_all('div', class_='regression-test-results'), results)):
            # C# results insertion
            for existing_script, new_result in zip(div.find_all('script', class_='csharp-result'), csharp_results):
                if not new_result:
                    print(f"No result json returned for {file_path} CSharp Example {i+1}, Skipping...")
                    continue
                new_json = json.dumps(json.loads(new_result.replace('\'', '\"')), indent=4)

                # Compare existing result with new result in validate mode
                if VALIDATE_MODE:
                    self.validation(file_path, i+1, "CSharp", existing_script[0].text.strip(), new_json)
                # Overwrite the existing result if not validate mode
                else:
                    existing_script[0].string = new_json

            # Python results insertion
            for existing_script, new_result in zip(div.find_all('script', class_='python-result'), python_results):
                if not new_result:
                    print(f"No result json returned for {file_path} Python Example {i+1}, Skipping...")
                    continue
                new_json = json.dumps(json.loads(new_result.replace('\'', '\"')), indent=4)

                # Compare existing result with new result in validate mode
                if VALIDATE_MODE:
                    self.validation(file_path, i+1, "Python", existing_script[0].text.strip(), new_json)
                # Overwrite the existing result if not validate mode
                else:
                    existing_script[0].string = new_json
                    
        return soup

    def insert_validate_results(self, file_path, results):
        """Insert/Validate backtest results into the original file."""
        with open(file_path, 'r+', encoding='utf-8') as file:
            soup = BeautifulSoup(file, 'html.parser')
            divs = soup.find_all('div', class_=['section-example-container', 'testable'])

            if divs:
                soup = self.insert_validate_example_container(file_path, soup, results)
            else:
                soup = self.insert_validate_php(file_path, soup, results)

            file.seek(0)
            file.write(str(soup.prettify(formatter="html5")))
            file.truncate()

    def process_file(self, file_path):
        """Complete processing for a single file."""
        # Automatically acquire and release the semaphore
        with self.semaphore:
            snippets = self.extract_pre_content(file_path)
            if snippets:
                results = [self.perform_backtests(file_path, content) for content in snippets]
                self.insert_validate_results(file_path, results)

    def run(self):
        start_time = time.time()
        print(f"{datetime.now()}::{time.time()-start_time:.4f}::Start regression testing.")
        
        files = self.get_testing_files(ROOT_DIR)
        total_file_num = len(files)
        print(f"{datetime.now()}::{time.time()-start_time:.4f}::Get all testable algorithms from {total_file_num} files, now start testing...")
        
        # Speed up with multiprocessing.
        process_data_list = []
        self.backtest_started = mp.Value('b', True)     # 'b' for boolean
        self.semaphore = mp.Semaphore(MAX_COWORKER)
        
        for times_index, file_path in enumerate(files):
            # Start a new process if the previous backtest has been started to avoid wrong file content update and compilation
            while not self.backtest_started.value:
                # Check every 3 seconds
                time.sleep(3)
            self.backtest_started.value = False

            # Start the new process
            process = mp.Process(target=self.process_file, args=(file_path,))
            process.start()
            process_data_list.append(process)
        
            task_num = times_index+1
            if task_num % 5 == 0:
                print(f"{datetime.now()}::{time.time()-start_time:.4f}::Sent regression test task for {task_num}/{total_file_num} files")

        # Wait for all processes to finish
        for process in process_data_list:
            process.join()
        
        print(f"{datetime.now()}::{time.time()-start_time:.4f}::Finish all testing. Removing temp files...")
        
        # Remove temp php script if any
        try:
            os.remove("temp_script.php")
        except:
            pass       # ignore if not found
        
        print(f"{datetime.now()}::{time.time()-start_time:.4f}::Done!")


if __name__ == "__main__":
    tester = RegressionTests()
    api_connect_success = tester.init_api()
    if api_connect_success:
        tester.run()
    else:
        print("Failed to connect to QuantConnect API. Quitting...")