"""Configuration settings for regression testing."""
import os


class Config:
    """Configuration constants and environment variables."""

    # Paths
    RAMDISK = "/mnt/ramdisk"
    ROOT_DIR = "."
    MYPY_CONFIG = "/app/Documentation/examples-check/mypy.ini"
    TEMP_PHP_FILE = "temp_script.php"

    # Test settings
    WORKERS = 10  # Limited by number of backtest nodes in the organization.

    # API settings
    BASE_API = "https://www.quantconnect.com/api/v2"
    USER_ID = os.environ["DOCS_REGRESSION_TEST_USER_ID"]
    USER_TOKEN = os.environ["DOCS_REGRESSION_TEST_USER_TOKEN"]

    # Rate limiting
    CALLS = 100
    RATE_LIMIT = 60  # seconds

    # Thresholds
    MIN_LINES_FOR_BACKTEST = 50
    MAX_LOG_LINES = 500
    MAX_LOG_ATTEMPTS = 12
    MAX_RETRY_ATTEMPTS = 5

    # Directories to skip
    SKIP_DIRECTORIES = [
        './single-page/',
        './Resources/',
        './07 Meta/',
        './08 Drafts/',
        './URL Test/',
        '/04 Market Hours/',
        '/05 Market Hours/'
    ]

    # H3 titles that should trigger backtesting
    BACKTEST_H3_TITLES = [
        'example', 'examples',
        'demonstration algorithm', 'demonstration algorithms',
        'example application', 'example applications'
    ]

    # Log queries to check for errors
    ERROR_LOG_QUERIES = [
        'Portfolio value is less than or equal to zero, stopping algorithm',
        'Executed MarginCallOrder',
        'Insufficient buying power to complete orders',
        'The security does not have an accurate price as it has not yet received a bar of data.',
        'QCAlgorithm.EmitInsights(): Warning: cannot emit insights for delisted securities, these will be discarded',
        'This operation is not allowed in ',  # ... Initialize or during warm up
        'Unable to submit order with id',  # ... that has zero quantity
        #'Warning: To meet brokerage precision requirements, order',  # ... LimitPrice was rounded to 3761.75 from 3761.721875
        #'Warning: Due to brokerage limitations, orders will be rounded to the nearest lot size of',
        #'Exceeded maximum data points per series for organization tier, chart update skipped.',
        'is marked as non-tradable.',
        'There is no data for this symbol yet', # .. please check the security.HasData flag to ensure there is at least one data point.
        'orders not supported for', # Ex: MarketOnOpen orders not supported for Future.
        ''   # To check if the total number of log lines is reasonable
    ]
