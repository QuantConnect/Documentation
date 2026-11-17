from __future__ import annotations

import os
import sys
import time
import tempfile
import re
from pathlib import Path
from subprocess import run

target_files: list[str] = []
start_time = time.time()

LOG_PATH = Path(__file__).resolve().parent / "syntax-check.log"


def log(message: str) -> None:
    print(message)
    with open(LOG_PATH, "a") as file:
        file.write(message)

for subdir, dirs, files_in_folder in os.walk("./project-templates/python"):
    for file_name in files_in_folder:
        file_path = subdir + os.sep + file_name
        if file_path.endswith(".py") and file_name != "run_syntax_check.py":
            target_files.append(file_path)
target_files.sort()

def adjust_file_contents(target_file: str, tmp_dir: str, index: int) -> str | None:
    try:
        file = Path(target_file)
        file_content = file.read_text(encoding='utf-8')
        adjusted_import = 'from AlgorithmImports import *;from datetime import date, time, datetime, timedelta;import pandas as pd;import numpy as np;import math;import json;import os;'

        # Every template is a main.py, so each copy needs its own module name in the shared mypy run.
        # The name must also change between runs, or --skip-cache-mtime-checks reuses the cached result and hides errors.
        tmp_file = os.path.join(tmp_dir, f"{Path(tmp_dir).name}_{index}.py")
        Path(tmp_file).write_text("# mypy: disable-error-code=\"no-redef\"\n" + file_content.replace("from AlgorithmImports import *", adjusted_import), encoding='utf-8')
        return tmp_file
    except:
        import traceback
        log(f"{target_file} failed An exception occurred: {traceback.format_exc()}")
        return None

specific_order_attributes = ['limit_price', 'trigger_price', 'trigger_touched', 'stop_price', 'stop_triggered', 'trailing_amount', 'trailing_as_percentage']

specific_ibase_data_attributes = ['is_fill_forward', 'volume', 'open', 'high', 'low', 'close', 'bid', 'bid_size', 'ask', 'ask_size', 'last_bid_size', 'last_ask_size', 'bid_price', 'ask_price', 'last_price', 'period', 'tick_type', 'quantity', 'exchange_code', 'exchange', 'sale_condition', 'parsed_sale_condition', 'suspicious', 'update']

specific_indicator_attributes = ['is_ready', 'samples', 'name', 'current', 'update', 'reset', 'updated']

def should_ignore(line: str, prev_line_ignored: bool) -> bool:
    result = any(to_ignore in line for to_ignore in (
        # this ('object') is just noise: mypy might not be able to resolve base class in some cases
        '"object"',
        'Name "datetime" is not defined',
        'Name "np" is not defined',
        'Name "pd" is not defined',
        'Name "math" is not defined',
        'Name "time" is not defined',
        'Name "json" is not defined',
        'Name "timedelta" is not defined',
        'be derived from BaseException',
        'Argument 1 of "update" is incompatible with supertype "IndicatorBase"; supertype defines the argument type as "IBaseData"',
        'Module has no attribute "JsonConvert"',
        'Too many arguments for "update" of "IndicatorBase"',
        'Signature of "update" incompatible with supertype "IndicatorBase"',
        'Signature of "update" incompatible with supertype "QuantConnect.Indicators.IndicatorBase"',
        'has incompatible type "Symbol"; expected "str"',
        # This methods take an indicator and consolidator which might be instances of custom
        # indicator/consolidator Python classes that don't inherit from PythonIndicator or IDataConsolidator
        'No overload variant of "register_indicator" of "QCAlgorithm" matches argument types',
        'No overload variant of "warm_up_indicator" of "QCAlgorithm" matches argument types'
    ))

    if result or ('note: ' in line and prev_line_ignored) or\
        ('None' in line and '[func-returns-value]' not in line):
        return True

    # Ignore accessing specific order types properties
    order_attributes_match = re.search(r'error: "Order" has no attribute "([^"]+)"', line)
    if order_attributes_match and order_attributes_match.group(1) in specific_order_attributes:
        return True

    # Ignore accessing specific properties of common data types derived from IBaseData, like Tick, TradeBar and QoteBar
    base_data_attributes_match = re.search(r'error: "IBaseData" has no attribute "([^"]+)"', line)
    if base_data_attributes_match and base_data_attributes_match.group(1) in specific_ibase_data_attributes:
        return True

    # Ignore accessing indicator properties. Useful for instance when adding indicators of different types
    # to a list and then iterating over them, the common type will be IIndicatorWarmUpPeriodProvider
    indicator_attributes_match = re.search(r'error: "IIndicatorWarmUpPeriodProvider" has no attribute "([^"]+)"', line) or re.search(r'error: "Iterable\[IndicatorDataPoint\]" has no attribute "([^"]+)"', line)
    if indicator_attributes_match and indicator_attributes_match.group(1) in specific_indicator_attributes:
        return True

    # Ignore accessing specific properties of some models, just to reduce noise in regression algorithms asserting internal stuff.
    # We don't expect users to be accessing properties of models like this in most cases
    if re.search('error: "(IBuyingPowerModel)|(IBenchmark)|(IMarginInterestRateModel)" has no attribute "([^"]+)"', line):
        return True

    # In some cases Python developers use the same variable and redefine it, this is not a problem in Python but mypy doesn't like it
    if re.search(r'error: Incompatible types in assignment \(expression has type "([^"]+)", variable has type "([^"]+)"\)', line):
        return True

    return False


def filter_output(output: str, tmp_file: str) -> str:
    filtered_output = ''
    prev_line_ignored = False
    for line in output.splitlines():
        ignored = not line.startswith(f"{tmp_file}:") or should_ignore(line, prev_line_ignored)
        if not ignored:
            filtered_output += f"{line}\n"
        prev_line_ignored = ignored
    return filtered_output


def run_syntax_check(target_files: list[str]) -> list[bool]:
    with tempfile.TemporaryDirectory() as tmp_dir:
        tmp_files = [adjust_file_contents(target_file, tmp_dir, i) for i, target_file in enumerate(target_files)]
        files_to_check = [tmp_file for tmp_file in tmp_files if tmp_file]
        if not files_to_check:
            return [False] * len(target_files)

        # One run for all templates: each template pulls in thousands of site-packages modules,
        # which a separate process per template re-checks every time
        algorithm_result = run([sys.executable, "-m", "mypy", "--strict", "--skip-cache-mtime-checks", "--skip-version-check", "--show-error-codes",
            "--no-error-summary", "--no-color-output", "--ignore-missing-imports", "--check-untyped-defs", *files_to_check], capture_output=True, text=True)

        output = algorithm_result.stderr + algorithm_result.stdout
        results = []
        for target_file, tmp_file in zip(target_files, tmp_files):
            filtered_output = filter_output(output, tmp_file) if tmp_file else ''
            if filtered_output:
                log(f"{target_file}\n{filtered_output}")
            results.append(tmp_file is not None and not filtered_output)

        # Exit code 2 (a crash, or a blocking error such as invalid syntax) means mypy didn't check every template
        if algorithm_result.returncode > 1:
            log(f"mypy stopped with exit code {algorithm_result.returncode}\n{output}")
            return [False] * len(target_files)
        return results

if __name__ == '__main__':
    if len(sys.argv) > 1:
        target_files = [target for target in target_files if sys.argv[1] in target]
    result = run_syntax_check(target_files)
    log(f"TEMPLATES: {target_files}")
    log(str(result))
    success_rate = round((sum(result) / len(result)) * 100, 1)
    log(f"SUCCESS RATE {success_rate}% took {time.time() - start_time}s")
    exit(0 if success_rate >= 100 else 1)
