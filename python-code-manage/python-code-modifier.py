from bs4 import BeautifulSoup
from pathlib import Path
from helper import *
from exceptions import SUFFIXES

file_count = 0
code_snippet_count = 0

for path in [p for suffix in SUFFIXES for p in Path().resolve().rglob(suffix)]:
    if 'single-page' in str(path): continue
    with open(path, "r", encoding="utf-8") as file:
        content = file.read()
        soup = BeautifulSoup(content, 'html.parser')

        for code_section in soup.find_all("div", class_="section-example-container"):
            for code_snippet in code_section.find_all('pre', {'class' : 'python'}):
                clean_snippet = get_code_snippet(code_snippet)
                converted_snippet = conversion(clean_snippet)
                content = content.replace(clean_snippet, converted_snippet)
                
                code_snippet_count += 1
                
        for code_section in soup.find_all("div", class_="python section-example-container"):
            for code_snippet in code_section.find_all('pre'):
                clean_snippet = get_code_snippet(code_snippet)
                converted_snippet = conversion(clean_snippet)
                content = content.replace(clean_snippet, converted_snippet)
                
                code_snippet_count += 1

        for code_section in soup.find_all("code", class_="python"):
            clean_snippet = get_code_snippet(code_section)
            converted_snippet = conversion(clean_snippet)
            content = content.replace(clean_snippet, converted_snippet)
            
            code_snippet_count += 1
                
    with open(path, "w", encoding="utf-8") as file:
        file.write(content)
            
    file_count += 1
            
print(f"Found {code_snippet_count} pieces of code snippets from {file_count} files.")