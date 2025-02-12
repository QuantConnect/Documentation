import base64
import hashlib
import json
import multiprocessing as mp
import os
import re
import requests
import subprocess
import threading
import time
from bs4 import BeautifulSoup
from datetime import datetime
from itertools import zip_longest
from ratelimit import limits, sleep_and_retry

ROOT_DIR = "."
VALIDATE_MODE = bool(os.environ["DOCS_REGRESSION_TEST_VALIDATION_MODE"])
MAX_COWORKER = 3        # Limited by number of backtest nodes divided by 2 (C# & Py run simutaneously)

BASE_API = "https://www.quantconnect.com/api/v2"
USER_ID = os.environ["DOCS_REGRESSION_TEST_USER_ID"]
USER_TOKEN = os.environ["DOCS_REGRESSION_TEST_USER_TOKEN"]
CS_PROJECT_ID = os.environ["DOCS_REGRESSION_TEST_CS_PROJECT"]
PY_PROJECT_ID = os.environ["DOCS_REGRESSION_TEST_PY_PROJECT"]

CALLS = 10
RATE_LIMIT = 60

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
    @sleep_and_retry
    @limits(calls=CALLS, period=RATE_LIMIT)
    def check_limit(self):
        ''' Empty function just to check for calls to API '''
        return
    
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
        input = input.replace("DOCS_RESOURCES.\"", "\"Resources").replace("DOCS_RESOURCES.'", "'Resources")
        
        # Create a temporary PHP file
        with open('temp_script.php', 'w', encoding='utf-8') as f:
            f.write(input)
        
        # Run the PHP script and capture the output
        result = subprocess.run(['php', 'temp_script.php'], capture_output=True, text=True, encoding='utf-8')
        output = result.stdout.strip().replace("<div class=\"section-example-container to-be-tested\">", "<div class=\"section-example-container testable\">")
        
        # Create a temporary PHP file
        with open('temp_script.php', 'w', encoding='utf-8') as f:
            f.write(output)
        
    def get_testing_files(self, directory):
        """Recursively read all HTML/PHP files."""
        target_text = ['section-example-container', 'testable', 'div']
        files = []
        
        for root, _, filenames in os.walk(directory):
            for filename in filenames:
                if filename.lower().endswith(('.html', '.php')):
                    file_path = os.path.join(root, filename)
                    # Check if the file contains the target text
                    with open(file_path, 'r', encoding='utf-8') as file:
                        text = file.read()
                        if all([x in text for x in target_text]):
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
            for div in soup.find_all(lambda tag: tag.name == 'div' and 'class' in tag.attrs and 'section-example-container' in tag['class'] and 'testable' in tag['class']):
                csharp_contents = []
                python_contents = []
                
                # Get C# and Python pre separately
                for pre in div.find_all('pre'):
                    # Set all data normalization mode as raw, and add start/end date if there is not any
                    # To ensure time-universality of the test results
                    if 'csharp' in pre.get('class', []):
                        pattern = r'^( *)(public override void Initialize\(\)\s*{)'
                        replacement = (
                            r'\1\2\n'
                            r'\1    SetStartDate(2024, 9, 1);\n'
                            r'\1    SetEndDate(StartDate.AddDays(90));\n'
                            r'\1    UniverseSettings.DataNormalizationMode = DataNormalizationMode.Raw;\n'
                        )
                        content = re.sub(pattern, replacement, pre.get_text(), flags=re.MULTILINE)
                        
                        csharp_contents.append(content)
                        
                    elif 'python' in pre.get('class', []):
                        pattern = r'^( *)(def (initialize|Initialize)\(self\):)'
                        replacement = (
                            r'\1\2\n'
                            r'\1    self.set_start_date(2024, 9, 1)\n'
                            r'\1    self.set_end_date(self.start_date + timedelta(90))\n'
                            r'\1    self.universe_settings.data_normalization_mode = DataNormalizationMode.RAW'
                        )
                        content = re.sub(pattern, replacement, pre.get_text(), flags=re.MULTILINE)
                        
                        python_contents.append(content)
                        
                snippets.append((csharp_contents, python_contents))

            return snippets

    def clean_code(self, code):
        return code.replace('&amp;&amp;', '&&').replace('&lt;', '<').replace('&gt;', '>')
    
    def update_algorithm_content(self, project_id, file_name, snippets):
        """Update the Main on the project for backtesting."""
        errors = 0
        while errors < 5:
            headers = self.get_json_header()
            data = {
                "projectId": project_id,
                "name": file_name,
                "content": snippets
            }
            self.check_limit()
            response = requests.post(
                f"{BASE_API}/files/update",
                headers = headers,
                data = data
            ).json()
            
            if response["success"]:
                return True
            
            errors += 1
            # Retry after 3 second
            time.sleep(3)
            
        return False
    
    def compile_project(self, project_id):
        """Compile the project for backtesting."""
        headers = self.get_json_header()
        data = {
            "projectId": project_id,
        }
        self.check_limit()
        response = requests.post(
            f"{BASE_API}/compile/create",
            headers = headers,
            data = data
        ).json()
        
        errors = 0
        while errors < 5:
            if response["state"] == "BuildError":
                return None
                
            data = {
                "projectId": project_id,
                "compileId": response["compileId"]
            }
            self.check_limit()
            response = requests.post(
                f"{BASE_API}/compile/read",
                headers = headers,
                data = data
            ).json()
            
            if response["success"]:
                return response["compileId"]
            
            errors += 1
            # Recheck every 1 seconds
            time.sleep(1)
        
        return None
        
    def create_backtest(self, file_path, example_num, project_id, compile_id):
        """Create a regression backtest and return the result json"""
        ### We cannot select backtest node in api. Right now, we filter manually in the cloud.
        ### Otherwise, we can filter the nodes without gpu by:
        ### [x for x in result.json()['nodes']['backtest'] if "gpu" not in x['sku'].lower()]
        
        errors = 0
        while errors < 5:
            headers = self.get_json_header()
            data = {
                "projectId": project_id,
                "compileId": compile_id,
                "backtestName": f"{file_path} Example {example_num}"
            }
            self.check_limit()
            response = requests.post(
                f"{BASE_API}/backtests/create",
                headers = headers,
                data = data
            ).json()
            
            if response["success"]:
                return response["backtest"]["backtestId"]
            
            errors += 1
            # Recheck every 3 seconds
            time.sleep(3)
        
        return None
        
    def read_backtest(self, project_id, backtest_id):
        errors = 0
        while True:
            headers = self.get_json_header()
            data = {
                "projectId": project_id,
                "backtestId": backtest_id
            }
            self.check_limit()
            response = requests.post(
                f"{BASE_API}/backtests/read",
                headers = headers,
                data = data
            ).json()
            
            if not response["success"]:
                errors += 1
                if errors >= 5:
                    return None
                continue
            
            backtest = response["backtest"]
            if isinstance(backtest, list):
                backtest = backtest[0]
                
            if backtest.get("completed", None):
                break
            
            # Recheck every 10 seconds
            time.sleep(10)
        
        statistics = backtest["statistics"]
        if isinstance(statistics, list):
            if statistics:
                statistics = statistics[0]
            else:
                statistics = None
        
        return statistics
    
    def get_order_list_hash(self, project_id, backtest_id):
        """Create a regression backtest and return the result json"""
        headers = self.get_json_header()
        data = {
            "start": 0,
            "end": 99,
            "projectId": project_id,
            "backtestId": backtest_id
        }
        self.check_limit()
        response = requests.post(
            f"{BASE_API}/backtests/orders/read",
            headers = headers,
            data = data
        ).json()
        
        if response.get("orders", None):
            data = response["orders"]
            # Remove the "events" property, since it is changing by each backtest.
            for item in data:
                item.pop('events', None)
            result = data
            return hashlib.md5(json.dumps(result, indent=2).encode()).hexdigest()
        return ""

    def backtest(self, file_path, example_num, language, content, results):
        project_id = CS_PROJECT_ID if language == "csharp" else PY_PROJECT_ID
        file_name = "Main.cs" if language == "csharp" else "main.py"
        content = CS_AUTOCOMPLETE + "\n" + content if language == "csharp" else PY_AUTOCOMPLETE + "\n" + content
        
        # Avoid messing up with other updates and compilation in the same project
        with self.semaphore:
            # Update the test project to the example code snippets
            update_success = self.update_algorithm_content(project_id, file_name, content)
            if not update_success:
                msg = "Update project content failed"
                self.log_error(file_path, example_num, language, "", msg)
                return
            
            # Compile the project
            compile_id = self.compile_project(project_id)
            if not compile_id:
                msg = "Compile project failed"
                self.log_error(file_path, example_num, language, "", msg)
                return
            
            # Backtest the project
            backtest_id = self.create_backtest(file_path, example_num, project_id, compile_id)
            if not backtest_id:
                msg = "Create backtest failed"
                self.log_error(file_path, example_num, language, "", msg)
                return
        
        # Read the backtest results
        result_json = self.read_backtest(project_id, backtest_id)
        if not result_json:
            msg = "Backtest failed, no results json returned"
            self.log_error(file_path, example_num, language, "", msg)
            return
        
        # Add order hash and append the result in a thread-safe way
        result_json["OrderListHash"] = self.get_order_list_hash(project_id, backtest_id)
        
        results.append(json.dumps(result_json))

    def perform_backtests(self, file_path, contents):
        """Perform backtests on the provided contents."""
        cs_results = []
        py_results = []

        for i, (cs_content, py_content) in enumerate(zip_longest(contents[0], contents[1])):
            threads = []
            if cs_content:
                thread_cs = threading.Thread(target=self.backtest, args=(file_path, i + 1, "csharp", self.clean_code(cs_content), cs_results))
                threads.append(thread_cs)
                thread_cs.start()
            if py_content:
                thread_py = threading.Thread(target=self.backtest, args=(file_path, i + 1, "python", self.clean_code(py_content), py_results))
                threads.append(thread_py)
                thread_py.start()

            for thread in threads:
                thread.join()

        return (cs_results, py_results)
        
    def validation(self, file_path, example_num, language, existing_script, new_json):
        for j, (existing, new) in enumerate(zip(existing_script.split('\n'), new_json.split('\n'))):
            if existing.strip() != new.strip():
                self.log_error(file_path, example_num, language, existing, new, j+1)
                
    def log_error(self, file_path, example_num, language, expect, actual, line=None):
        print(f"""
    Regression Test Failed:
    In ::       {file_path} Example {example_num}, {language}{", line " + line+1 if line is not None else ""}
    Expect ::   {expect}
    But was ::  {actual}
    """)
    
    def insert_validate_example_container(self, file_path, soup, results):
        for i, (div, (csharp_results, python_results)) in enumerate(zip(soup.find_all(lambda tag: tag.name == 'div' and 'class' in tag.attrs and 'section-example-container' in tag['class'] and 'testable' in tag['class']), results)):
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
            for existing_script, new_result in zip_longest(div.find_all('script', class_='csharp-result'), csharp_results):
                if not new_result:
                    print(f"No result json returned for {file_path} CSharp Example {i+1}, Skipping...")
                    continue
                new_json = json.dumps(json.loads(new_result.replace('\'', '\"')), indent=4)

                # Compare existing result with new result in validate mode
                if VALIDATE_MODE:
                    self.validation(file_path, i+1, "CSharp", existing_script.text.strip(), new_json)
                # Overwrite the existing result if not validate mode
                else:
                    existing_script.string = new_json

            # Python results insertion
            for existing_script, new_result in zip_longest(div.find_all('script', class_='python-result'), python_results):
                if not new_result:
                    print(f"No result json returned for {file_path} Python Example {i+1}, Skipping...")
                    continue
                new_json = json.dumps(json.loads(new_result.replace('\'', '\"')), indent=4)

                # Compare existing result with new result in validate mode
                if VALIDATE_MODE:
                    self.validation(file_path, i+1, "Python", existing_script.text.strip(), new_json)
                # Overwrite the existing result if not validate mode
                else:
                    existing_script.string = new_json
                    
        return soup

    def insert_validate_results(self, file_path, results):
        """Insert/Validate backtest results into the original file."""
        with open(file_path, 'r+', encoding='utf-8') as file:
            soup = BeautifulSoup(file, 'html.parser')
            divs = soup.find_all(lambda tag: tag.name == 'div' and 'class' in tag.attrs and 'section-example-container' in tag['class'] and 'testable' in tag['class'])

            if divs:
                soup = self.insert_validate_example_container(file_path, soup, results)
            else:
                soup = self.insert_validate_php(file_path, soup, results)

            file.seek(0)
            file.write(str(soup.prettify(formatter="html5")))
            file.truncate()

    def process_file(self, file_path, start_time):
        """Complete processing for a single file."""
        snippets = self.extract_pre_content(file_path)
        if snippets:
            results = [self.perform_backtests(file_path, content) for content in snippets]
            self.insert_validate_results(file_path, results)
            
        self.tasks_completed.value += 1
        if self.tasks_completed.value % 5 == 0:
            print(f"{datetime.now()}::{time.time()-start_time:.4f}::Sent regression test task for {self.tasks_completed.value} files")

    def run(self):
        start_time = time.time()
        print(f"{datetime.now()}::{time.time()-start_time:.4f}::Start regression testing.")
        
        files = self.get_testing_files(ROOT_DIR)
        total_file_num = len(files)
        print(f"{datetime.now()}::{time.time()-start_time:.4f}::Get all testable algorithms from {total_file_num} files, now start testing...")
        
        # Speed up with multiprocessing.
        max_workers = min(MAX_COWORKER, mp.cpu_count())
        manager = mp.Manager()
        self.semaphore = manager.Semaphore(1)       # Critical process only allows 1 worker at a time
        
        self.tasks_completed = manager.Value('i', 0)
        with mp.Pool(processes=max_workers) as pool:
            pool.starmap(self.process_file, [(file_path, start_time) for file_path in files])
        
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