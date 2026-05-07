"""Strict mypy syntax check for project-templates/python/**/main.py.

Adapted from LEAN's run_syntax_check.py
(https://github.com/QuantConnect/Lean/blob/master/run_syntax_check.py).
Differences vs. LEAN's original:
  * Walks `project-templates/python/**/main.py` only.
  * Passes `--strict` to mypy on top of LEAN's flag set.
  * Requires a 100% pass rate (LEAN tolerates up to 0.9% failures).
  * No MYPYPATH (templates are self-contained).
  * Writes its log next to itself, not at the repo root.

Usage:
    python project-templates/python/run_syntax_check.py
    python project-templates/python/run_syntax_check.py equity-rsi   # path substring filter
"""

import os
import re
import sys
import time
import tempfile
from pathlib import Path
from subprocess import run
from multiprocessing import Pool, Lock, freeze_support

SCRIPT_DIR = Path(__file__).resolve().parent
LOG_PATH = SCRIPT_DIR / "syntax-check.log"

target_files: list[str] = []
lock = None
start_time = time.time()


def init_pool(l):
    global lock
    lock = l


def log(message: str):
    print(message)
    with open(LOG_PATH, "a") as file:
        file.write(message)


def sync_log(message: str):
    with lock:
        log(message)


for main_py in sorted(SCRIPT_DIR.rglob("main.py")):
    target_files.append(str(main_py))


def adjust_file_contents(target_file: str):
    try:
        file = Path(target_file)
        file_content = file.read_text(encoding="utf-8")
        adjusted_import = (
            "from AlgorithmImports import *;"
            "from datetime import date, time, datetime, timedelta;"
            "import pandas as pd;import numpy as np;"
            "import math;import json;import os;"
        )

        tmp_file = tempfile.NamedTemporaryFile(prefix=f"{file.name}_", delete=False)
        Path(tmp_file.name).write_text(
            "# mypy: disable-error-code=\"no-redef\"\n"
            + file_content.replace("from AlgorithmImports import *", adjusted_import),
            encoding="utf-8",
        )
        return tmp_file
    except:
        import traceback
        sync_log(f"{target_file} failed An exception occurred: {traceback.format_exc()}")
        return None


specific_order_attributes = ['limit_price', 'trigger_price', 'trigger_touched', 'stop_price', 'stop_triggered', 'trailing_amount', 'trailing_as_percentage']

specific_ibase_data_attributes = ['is_fill_forward', 'volume', 'open', 'high', 'low', 'close', 'bid', 'bid_size', 'ask', 'ask_size', 'last_bid_size', 'last_ask_size', 'bid_price', 'ask_price', 'last_price', 'period', 'tick_type', 'quantity', 'exchange_code', 'exchange', 'sale_condition', 'parsed_sale_condition', 'suspicious', 'update']

specific_indicator_attributes = ['is_ready', 'samples', 'name', 'current', 'update', 'reset', 'updated']


def should_ignore(line: str, prev_line_ignored: bool) -> bool:
    result = any(to_ignore in line for to_ignore in (
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
        'No overload variant of "register_indicator" of "QCAlgorithm" matches argument types',
        'No overload variant of "warm_up_indicator" of "QCAlgorithm" matches argument types',
    ))

    if result or ('note: ' in line and prev_line_ignored) or \
        ('None' in line and '[func-returns-value]' not in line):
        return True

    order_attributes_match = re.search(r'error: "Order" has no attribute "([^"]+)"', line)
    if order_attributes_match and order_attributes_match.group(1) in specific_order_attributes:
        return True

    base_data_attributes_match = re.search(r'error: "IBaseData" has no attribute "([^"]+)"', line)
    if base_data_attributes_match and base_data_attributes_match.group(1) in specific_ibase_data_attributes:
        return True

    indicator_attributes_match = re.search(r'error: "IIndicatorWarmUpPeriodProvider" has no attribute "([^"]+)"', line) or re.search(r'error: "Iterable\[IndicatorDataPoint\]" has no attribute "([^"]+)"', line)
    if indicator_attributes_match and indicator_attributes_match.group(1) in specific_indicator_attributes:
        return True

    if re.search('error: "(IBuyingPowerModel)|(IBenchmark)|(IMarginInterestRateModel)" has no attribute "([^"]+)"', line):
        return True

    if re.search(r'error: Incompatible types in assignment \(expression has type "([^"]+)", variable has type "([^"]+)"\)', line):
        return True

    return False


def run_syntax_check(target_file: str):
    tmp_file = adjust_file_contents(target_file)
    if not tmp_file:
        return False

    try:
        algorithm_result = run(
            [
                sys.executable, "-m", "mypy",
                "--strict",
                "--skip-cache-mtime-checks", "--skip-version-check",
                "--show-error-codes", "--no-error-summary", "--no-color-output",
                "--ignore-missing-imports", "--check-untyped-defs",
                tmp_file.name,
            ],
            capture_output=True, text=True,
        )

        output = ""
        if algorithm_result.stderr:
            output += algorithm_result.stderr
        if algorithm_result.stdout:
            output += algorithm_result.stdout

        filtered_output = ""
        prev_line_ignored = False
        for line in output.splitlines():
            ignored = not line.startswith(tmp_file.name) or should_ignore(line, prev_line_ignored)
            if not ignored:
                filtered_output += f"{line}\n"
            prev_line_ignored = ignored

        if filtered_output:
            sync_log(f"{target_file}\n{filtered_output}")
            return False
        return True
    except:
        import traceback
        sync_log(f"{target_file} failed An exception occurred: {traceback.format_exc()}")
    finally:
        tmp_file.close()
        os.unlink(tmp_file.name)
    return False


if __name__ == "__main__":
    freeze_support()

    pool_size = min(os.cpu_count() or 4, 8)
    with Pool(pool_size, initializer=init_pool, initargs=(Lock(),)) as pool:
        if len(sys.argv) > 1:
            target_files = [t for t in target_files if sys.argv[1] in t]
        result = pool.map(run_syntax_check, target_files)
        log(f"TEMPLATES: {target_files}")
        log(str(result))
        success_rate = round((sum(result) / len(result)) * 100, 1) if result else 0.0
        log(f"SUCCESS RATE {success_rate}% took {time.time() - start_time}s")
        exit(0 if result and all(result) else 1)
