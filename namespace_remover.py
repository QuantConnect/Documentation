import os
import re
from pathlib import Path

def find_namespace_end(content, start_index):
    """Find the index of the closing brace that matches the namespace's opening brace."""
    brace_count = 0
    i = start_index
    while i < len(content):
        if content[i] == '{':
            brace_count += 1
        elif content[i] == '}':
            brace_count -= 1
            if brace_count == 0:
                return i
        i += 1
    return -1  # No matching closing brace found

def process_file(file_path):
    try:
        # Read the file content
        with open(file_path, 'r', encoding='utf-8') as file:
            content = file.read()

        # Regex to match the start of namespace
        pattern = r'(?m)namespace\s+\w+\s*\{'
        new_content = content
        offset = 0  # Track position in original content

        # Find all namespace starts
        for match in re.finditer(pattern, content):
            namespace_start = match.start()
            namespace_end = match.end() - 1  # Position of opening {

            # Find the matching closing brace
            closing_brace_index = find_namespace_end(content, namespace_end)
            if closing_brace_index == -1:
                print(f"Skipping malformed namespace in {file_path} at position {namespace_start}")
                continue

            # Extract the content between { and }
            inner_content = content[namespace_end + 1:closing_brace_index].rstrip()

            # Split into lines
            lines = inner_content.split('\n')
            # Find the minimum indentation of non-empty lines
            indents = [len(line) - len(line.lstrip()) for line in lines if line.strip()]
            min_indent = min(indents) if indents else 0

            # De-indent by removing min_indent from each line
            cleaned_lines = []
            for line in lines:
                if line.strip():
                    # Remove min_indent, ensuring non-negative
                    new_indent = max(0, len(line) - len(line.lstrip()) - min_indent)
                    cleaned_lines.append(' ' * new_indent + line.lstrip())
                else:
                    cleaned_lines.append('')  # Preserve empty lines as empty
            cleaned_content = '\n'.join(cleaned_lines).strip()

            # Replace the entire namespace block with the cleaned content
            namespace_block = content[namespace_start:closing_brace_index + 1]
            new_content = new_content[:namespace_start + offset] + cleaned_content + new_content[closing_brace_index + 1 + offset:]
            offset += len(cleaned_content) - len(namespace_block)

        # Only write back if content changed
        if new_content != content:
            with open(file_path, 'w', encoding='utf-8') as file:
                file.write(new_content)
            print(f"Modified: {file_path}")

    except Exception as e:
        print(f"Error processing {file_path}: {e}")

def find_and_process_files(directory):
    # Convert directory to Path object
    dir_path = Path(directory)
    
    # Recursively find all .html and .php files
    for file_path in dir_path.rglob('*'):
        if file_path.suffix.lower() in ('.html', '.php'):
            process_file(file_path)

if __name__ == "__main__":
    find_and_process_files(Path.cwd())