"""Utility functions and enums."""
import time
from datetime import datetime
from enum import Enum


class Language(Enum):
    """Supported programming languages."""
    CSHARP = "csharp"
    PYTHON = "python"


class CodeBlock:
    """Represents a code block in the docs."""

    def __init__(self, path, div_idx, pre_idx, language, code):
        self.path = path
        self.div_idx = div_idx
        self.pre_idx = pre_idx
        self.language = language
        self.code = code
        self.statistics = {}
        self.url = self._get_url(path)

    def __repr__(self):
        return f"CodeBlock(path='{self.path}', div={self.div_idx}, pre={self.pre_idx}, language={self.language.value}, url='{self.url}')"

    def _get_url(self, path):
        """
        Generate the documentation URL from the file path.

        Example:
            ./03 Writing Algorithms/12 Universes/03 Equity/01 Liquidity Universes/10 Selection Frequency.html
            -> quantconnect.com/docs/v2/writing-algorithms/universes/equity/liquidity-universes#10-Selection-Frequency
        """
        # Remove leading ./ if present.
        path = self.path.lstrip('./')
        # Extract the number and filename from the last part.
        parts = path.split('/')
        filename = parts[-1]  # e.g., "10 Selection Frequency.html"
        # Remove .html extension
        filename = filename.replace('.html', '').replace('.php', '')
        # Extract the section number and title.
        # e.g., "10 Selection Frequency" -> section_id = "10-Selection-Frequency"
        section_id = filename.replace(' ', '-')
        # Build the URL path from all directory parts.
        # Remove leading numbers and spaces from each directory part.
        url_parts = []
        for part in parts[:-1]:  # Exclude the filename.
            # Remove leading number and space.
            # (e.g., "03 Writing Algorithms" -> "Writing Algorithms")
            clean_part = part.split(' ', 1)[-1] if ' ' in part else part
            # Convert to lowercase and replace spaces with hyphens.
            url_parts.append(clean_part.lower().replace(' ', '-'))
        # Construct the full URL.
        url_path = '/'.join(url_parts)
        return f"quantconnect.com/docs/v2/{url_path}#{section_id}"

    def get_backtest_name(self):
        """Generate a backtest name from the file path and and snippet location."""
        return f"{self.path.replace('/', ' - ')} div={self.div_idx} pre={self.pre_idx}"


def log_with_time(start_time, message):
    """
    Print a log message with timestamp and elapsed time.

    Args:
        start_time: Start time from time.time()
        message: Message to log
    """
    elapsed = time.time() - start_time
    print(f"{datetime.now()}::{elapsed:.4f}::{message}")
