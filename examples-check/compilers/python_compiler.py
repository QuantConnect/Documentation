"""Python code validation using mypy."""
import subprocess

from compilers.compiler import Compiler
from config import Config


class PythonCompiler(Compiler):
    """Handles Python code validation and type checking."""

    # Configuration
    _mypy_config_path = Config.MYPY_CONFIG

    # Class attributes for base class pattern matching
    IMPORTS = "from AlgorithmImports import *"
    DATE_RANGE_PATTERNS = ['self.set_start_date(', 'self.set_end_date(']
    ALGORITHM_CLASS_PATTERN = '(QCAlgorithm)'
    IGNORED_PATTERNS = [
        'Incompatible return value type (got "UnchangedUniverse", expected "list[Symbol]")',
        'Cannot find implementation or library stub for module named "Newtonsoft.Json"'
    ]

    # Python-specific imports for fragment compilation
    FRAGMENT_IMPORTS = [
        "from AlgorithmImports import *",
        'from datetime import date, time, datetime, timedelta',
        'import math',
        'import json',
        'import numpy as np',
        'import pandas as pd',
        'import matplotlib.pyplot as plt'
    ]

    def __init__(self):
        """Initialize the compiler and start dmypy daemon."""
        # Start the dmypy daemon.
        subprocess.run(
            ['dmypy', 'start', '--', '--config-file', self._mypy_config_path],
            capture_output=True
        )
        # Define the path.
        self._path = f"{self._ramdisk_path}/test.py"  

    def compile_fragment(self, code):
        """
        Compile a Python code fragment with mypy.

        Returns:
            Error message if type checking fails, None if successful.
        """
        # Write the algorithm file.
        with open(self._path, "w") as f:
            f.write('\n'.join(self.FRAGMENT_IMPORTS) + '\n' + code)
        # Compile it.
        result = subprocess.run(
            ['dmypy', 'check', self._path],
            capture_output=True,
            text=True
        )
        # Return the errors if there are any.
        if result.returncode:
            # Filter out specific known false positive errors.
            return self._filter_errors(result.stdout)
        return None

    def _filter_errors(self, output):
        """Filter out known false positive errors from mypy output."""
        # Remove lines containing ignored patterns.
        lines = output.split('\n')
        filtered_lines = [
            line for line in lines
            if line and not any(pattern in line for pattern in self.IGNORED_PATTERNS)
        ]
        if not len([line for line in filtered_lines if ': error: ' in line]):
            return None
        return '\n'.join(filtered_lines).strip()

