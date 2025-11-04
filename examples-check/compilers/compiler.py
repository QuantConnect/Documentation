"""Base compiler class with shared functionality."""
from abc import ABC, abstractmethod

from config import Config


class Compiler(ABC):
    """Abstract base class for code compilers."""

    # Configuration
    _ramdisk_path = Config.RAMDISK

    # Subclasses should define these class attributes
    IMPORTS = ""  # Import/using statements to prepend to code
    DATE_RANGE_PATTERNS = []  # Patterns to check for date range
    ALGORITHM_CLASS_PATTERN = ""  # Pattern to identify algorithm class

    @abstractmethod
    def compile_fragment(self, code):
        """
        Compile a code fragment.

        Returns:
            Error message if compilation fails, None if successful.
        """
        pass

    def has_date_range(self, code):
        """Check if code has start and end dates."""
        return all(pattern in code for pattern in self.DATE_RANGE_PATTERNS)

    def is_algorithm_class(self, code):
        """Check if code defines a QCAlgorithm subclass."""
        return self.ALGORITHM_CLASS_PATTERN in code

    def prepare_for_backtest(self, code):
        """Add necessary imports/usings to code."""
        return self.IMPORTS + "\n" + code

    def clean_code(self, code):
        """Clean HTML entities from code."""
        return (
            code
            .replace('&amp;&amp;', '&&')
            .replace('&lt;', '<')
            .replace('&gt;', '>')
        )
