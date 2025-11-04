"""Backtest execution and validation."""
import time

from config import Config
from utils import Language


class BacktestResult:
    """Result of a backtest execution."""

    def __init__(self, success, error_message, statistics={}):
        self.success = success
        self.error_message = error_message
        self.statistics = statistics


class BacktestRunner:
    """Handles backtest execution and validation."""

    _file_name = {
        Language.CSHARP: "Main.cs",
        Language.PYTHON: "main.py"
    }

    def __init__(
            self, api_client, compiler_by_langugage, project_id_by_language):
        """Initialize backtest runner."""
        self._api_client = api_client
        self._compiler_by_langugage = compiler_by_langugage
        self._project_id_by_language = project_id_by_language

    def run_backtest(self, code_block):
        """Execute a backtest and return results."""
        # Get the project Id.
        project_id = self._project_id_by_language[code_block.language]
        # Clean and prepare code.
        compiler = self._compiler_by_langugage[code_block.language]
        code = compiler.prepare_for_backtest(
            compiler.clean_code(code_block.code)
        )

        #print(f'{datetime.now()} -- Starting test for {code_block}')

        # Update the project code.
        success = self._api_client.update_file(
            project_id, self._file_name[code_block.language], code
        )
        if not success:
            return BacktestResult(False, "Update project content failed")

        # Compile the project.
        compile_id = self._api_client.compile_project(project_id)
        if not compile_id:
            return BacktestResult(False, "Compile project failed")

        ## Check the syntax.
        #if language == Language.PYTHON:
        #    syntax_error = self._api_client.check_python_syntax(code)
        #    if syntax_error:
        #        return BacktestResult(False, f"Syntax error: {syntax_error}")

        # Create a backtest.
        response = self._api_client.create_backtest(
            project_id, compile_id, code_block.get_backtest_name()
        )
        if not response.get("success"):
            return BacktestResult(
                False, f"Create backtest failed. Reponse={response}"
            )
        backtest_id = response["backtest"]["backtestId"]
        
        # Read the backtest results.
        backtest = self._api_client.read_backtest(project_id, backtest_id)
        if not backtest:
            return BacktestResult(False, "Read backtest failed")

        # Ensure the backtest finished without error.
        is_valid, error_msg = self._validate_backtest(
            backtest, project_id, backtest_id
        )
        if not is_valid:
            return BacktestResult(False, error_msg)

        # Get the backtest statistics.
        statistics = self._get_statistics(backtest, project_id, backtest_id)
        if not statistics:
            return BacktestResult(False, "No statistics returned")

        return BacktestResult(True, "", statistics)

    def _validate_backtest(self, backtest, project_id, backtest_id):
        """Validate backtest for errors and warnings."""
        # Check for errors
        if backtest.get('error'):
            return False, f'Backtest error: {backtest["error"]}'

        # Check for stacktrace
        if backtest.get('stacktrace'):
            return False, f'Backtest stacktrace: {backtest["stacktrace"]}'

        # Check logs
        if not self._logs_are_available(project_id, backtest_id):
            return False, "Logs didn't load in time"
        failed_queries = self._scan_logs(project_id, backtest_id)
        if failed_queries:
            return False, f'Failed queries: {failed_queries}'

        return True, ""

    def _logs_are_available(self, project_id, backtest_id):
        """Check if logs are available."""
        for _ in range(Config.MAX_LOG_ATTEMPTS):
            response = self._api_client.read_backtest_logs(
                project_id, backtest_id, start=0, end=1
            )
            if response.get("success") and response.get('length'):
                return True
            time.sleep(5)
        return False

    def _scan_logs(self, project_id, backtest_id):
        """Scan logs for error patterns."""
        failed_queries = []
        for query in Config.ERROR_LOG_QUERIES:
            log_lines = self._api_client.read_backtest_logs(
                project_id, backtest_id, query
            ).get('length', 0)
            # Check if query matched or if too many log lines
            if (query and log_lines or 
                not query and log_lines > Config.MAX_LOG_LINES):
                failed_queries.append(query)
        return failed_queries

    def _get_statistics(self, backtest, project_id, backtest_id):
        """Get backtest statistics."""
        for _ in range(Config.MAX_RETRY_ATTEMPTS):
            statistics = backtest.get("statistics")
            if isinstance(statistics, list) and statistics:
                statistics = statistics[0]
            if statistics:
                return statistics
            time.sleep(5)
            backtest = self._api_client.read_backtest(project_id, backtest_id)
            if not backtest:
                break
        return None
