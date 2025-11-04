"""File processing for HTML/PHP documentation files."""
import os
import subprocess
from bs4 import BeautifulSoup

from config import Config
from utils import Language, CodeBlock


class FileProcessor:
    """Handles file discovery and code extraction from documentation."""

    def __init__(self, compilers):
        """
        Initialize file processor with compiler instances.

        Args:
            compilers: Dictionary mapping Language to compiler instances
        """
        self._compilers = compilers
    
    def process_code_blocks(self, directory):
        """
        Process all code blocks in documentation files and extract 
        testable algorithms.

        Walks through all HTML/PHP files in the directory, extracts 
        code snippets from section-example-container divs, 
        validates them (syntax, QCAlgorithm class, date ranges),
        and returns a list of CodeBlocks objects ready for backtesting.

        Args:
            directory: Root directory to search for documentation files

        Returns:
            List of CodeBlocks objects containing validated, testable 
            algorithm code.
        """
        self._remove_temp_php_file()
        print('Gathering code blocks...')
        algorithms = []
        for root, _, filenames in sorted(os.walk(directory)):
            file_paths = sorted([
                os.path.join(root, f)
                for f in filenames
                if f.lower().endswith(('.html', '.php'))
            ])
            
            for file_path in file_paths:
                # Skip directories in the skip list.
                if any(p in file_path for p in Config.SKIP_DIRECTORIES):
                    continue
                #print(f'Processing file {file_path}')

                indicator_ref_page = '/01 Supported Indicators' in file_path

                # Drop the extension and the file number.
                h3_title = file_path.split('/')[-1].split('.')[0][3:].lower()
                should_backtest_h3 = h3_title in Config.BACKTEST_H3_TITLES

                # Convert PHP to HTML if needed
                original_file_path = file_path
                if file_path.endswith(".php"):
                    self._run_php_script(file_path)
                    file_path = Config.TEMP_PHP_FILE

                # Read the HTML file.
                with open(file_path, 'r', encoding='utf-8') as file:
                    soup = BeautifulSoup(file, 'html.parser')

                    # Get all div elements with the `section-example-container`
                    # class.
                    divs = soup.find_all(
                        lambda tag: (
                            tag.name == 'div' and
                            'class' in tag.attrs and
                            'section-example-container' in tag['class']
                        )
                    )
                    # Iterate through each div.
                    for div_idx, div in enumerate(divs):
                        classes = div.attrs.get('class', [])
                        # Skip <div> blocks with the skip-test class.
                        if 'skip-test' in classes:
                            continue
                        # Check for the `testable` class.
                        testable_div = 'testable' in classes
                        # Iterate through each <pre> snippet.
                        for pre_idx, pre in enumerate(div.find_all('pre')):
                            code = pre.get_text()
                            classes = pre.get('class', [])

                            # Determine the language.
                            if 'csharp' in classes:
                                language = Language.CSHARP
                            elif 'python' in classes:
                                language = Language.PYTHON
                            else:
                                continue

                            compiler = self._compilers[language]
                            # If this code block doesn't subclass 
                            # QCAlgorithm, just continue.
                            if not compiler.is_algorithm_class(code):
                                continue
                            # Create a CodeBlock object for this snippet
                            # to make logging and backtesting easier.
                            code_block = CodeBlock(
                                original_file_path, div_idx, pre_idx, language, 
                                code
                            )
                            # If this code block doesn't have `testable`...
                            if not testable_div:
                                # If the algorithm has >=50 lines or we're in a
                                # "should_backtest_h3", we should add `testable`
                                # to it.
                                if (len(code.split('\n')) >= Config.MIN_LINES_FOR_BACKTEST or
                                    should_backtest_h3):
                                    print(
                                        f'{code_block}\n',
                                        '-> Missing `testable` class.\n'
                                    )

                                # If this code block is not in Examples h3...
                                elif not should_backtest_h3:
                                    # Test if we can build it without error.
                                    error = compiler.compile_fragment(code)
                                    if error:
                                        print(
                                            f'{code_block}\n',
                                            f'-> Compile failed. Errors:\n{error}\n'
                                        )

                                continue
                            # Check if the algorithm has a date range.
                            if (not indicator_ref_page and 
                                not compiler.has_date_range(code)):
                                print(
                                    f'{code_block}\n',
                                    f'-> Missing date range.\n',
                                )
                                continue

                            #print(
                            #    f'{code_block}\n', 
                            #    '-> Selected for backtesting.\n'
                            #)
                            algorithms.append(code_block)
        self._remove_temp_php_file()
        return algorithms
    
    def _run_php_script(self, php_path):
        """Convert PHP to HTML by executing it."""
        # Read the PHP script
        with open(php_path, 'r', encoding="utf-8") as f:
            content = f.read()

        # Replace DOCS_RESOURCES with actual path
        content = (
            content
            .replace('DOCS_RESOURCES."', '"./Resources')
            .replace("DOCS_RESOURCES.'", "'./Resources")
        )

        # Write temporary PHP file
        with open(Config.TEMP_PHP_FILE, 'w', encoding='utf-8') as f:
            f.write(content)

        # Execute PHP script
        result = subprocess.run(
            ['php', '-d', 'short_open_tag=1', Config.TEMP_PHP_FILE],
            capture_output=True,
            text=True,
            encoding='utf-8'
        )

        # Update output to mark testable containers
        output = result.stdout.strip().replace(
            '<div class="section-example-container to-be-tested">',
            '<div class="section-example-container testable">'
        )

        # Write processed output
        with open(Config.TEMP_PHP_FILE, 'w', encoding='utf-8') as f:
            f.write(output)

    def _remove_temp_php_file(self):
        """Remove the temporary PHP file, if it exists."""
        try:
            os.remove(Config.TEMP_PHP_FILE)
        except FileNotFoundError:
            pass
