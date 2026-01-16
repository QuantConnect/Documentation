"""Main orchestration for regression testing."""
import os
import time
import multiprocessing as mp
from datetime import datetime

from config import Config
from utils import Language, log_with_time
from api_client import APIClient
from file_processor import FileProcessor
from backtest_runner import BacktestRunner
from compilers import PythonCompiler, CSharpCompiler


# Module-level worker runner (one per worker process)
_worker_runner = None

def _init_worker(api_client, compiler_by_language, timestamp):
    """Initialize each worker with its own projects and runner."""
    global _worker_runner
    worker_id = mp.current_process()._identity[0]
    _worker_runner = BacktestRunner(
        api_client,
        compiler_by_language,
        # Create projects for this worker
        {
            Language.PYTHON: api_client.create_project(
                f'Example Tests/{timestamp}_Worker{worker_id}_Py', 'Py'
            ),
            Language.CSHARP: api_client.create_project(
                f'Example Tests/{timestamp}_Worker{worker_id}_C', 'C#'
            )
        }
    )

def _backtest_code_block(code_block):
    """Process a single code block (called by each worker)."""
    global _worker_runner
    result = _worker_runner.run_backtest(code_block)
    if result.success:
        code_block.statistics = result.statistics
    else:
        print(
            f'{code_block}\n',
            f'-> Backtest failed. Error: {result.error_message}\n',
        )


class RegressionTestManager:
    """Orchestrates the entire regression testing process."""

    def run(self):
        """Run the complete regression test suite."""
        start_time = time.time()
        log_with_time(start_time, "Start regression testing.")

        # Create some objects we'll share accross all the workers.
        api_client = APIClient()
        compiler_by_language = {
            Language.CSHARP: CSharpCompiler(),
            Language.PYTHON: PythonCompiler()
        }

        # Authenticate
        if not api_client.authenticate():
            print("Failed to connect to QuantConnect API. Quitting...")
            return

        # Parse the docs for all algorithms that we need to backtest.
        algorithms = FileProcessor(compiler_by_language).process_code_blocks(Config.ROOT_DIR)[:10]
        log_with_time(start_time, f"Found {len(algorithms)} algorithms to test")

        # Set up with multiprocessing.
        workers = Config.WORKERS
        log_with_time(start_time, f"Start testing with {workers} workers")
        # Process code blocks in parallel with n workers.
        timestamp = datetime.now().strftime('%Y%m%d_%H%M%S_%f')
        worker_args = (api_client, compiler_by_language, timestamp)
        with mp.Pool(workers, _init_worker, worker_args) as pool:
            pool.map(_backtest_code_block, algorithms)

        log_with_time(start_time, "Finished all testing")
        log_with_time(start_time, "Done!")
