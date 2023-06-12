from bs4 import BeautifulSoup
from datetime import datetime
import json
import os
from pathlib import Path
import pdfkit
from urllib.request import urlopen, urlretrieve
import sys
import time
from typing import Union, Tuple, List

SOURCE_URL = "https://s3.amazonaws.com/cdn.quantconnect.com/web/cache"
DESTINATION_PATH = "single-page"
OUTPUT_FILENAME = "Quantconnect-%s"
DEFAULT_VERSION = "2023.01.17"
LAST_SECTION = 6
TITLE_PAGE = f"""<h1>QuantConnect Documentation - %s</h1>
<h4>Created on {datetime.utcnow().strftime("%m/%d/%Y")}</h4>
Copyright QuantConnect 2023
"""
PAGE_BREAKER = '<p style="page-break-after: always;">&nbsp;</p>'
EXCLUSIONS = [
    "3.7.6.3.",
    "3.7.7.3.1.",
    "3.7.7.3.2.",
    "3.7.7.3.3.",
    "3.7.7.3.4.",
    "3.7.7.3.5.",
    "3.7.7.3.8.",
    "3.7.7.3.8.",
    "3.7.7.3.10.",
    "3.7.9.3.",
    "3.7.10.3.",
    "3.7.11.3.",
    "13.1."
]   # these are unique in Writing Algorithm
IMAGE_DIR = "../single-page/images"     # relative path from file's perspective
CSS_DIR = "single-page/css"
sections = {}

def GetContent(date: str) -> dict:
    filename = f"documentation.v2.{date}.en.json"
    url = f"{SOURCE_URL}/{filename}"
    try:
        response = urlopen(url)
    except:
        raise Exception(f"GetContent(): {url} is not a valid URL.")
    
    code = response.code
    if code == 200:
        print(f"GetContent(): Fetch documentation file - {filename} successfully.")
        return json.load(response)
    else:
        raise Exception(f"GetContent(): {url} does not return valid content - error code: {code}.")
    
def SectionNumber(indent: int, section: str) -> str:
    numberings = [int(x) for x in section.split(".")]
    
    if indent > len(numberings):
        return f"{'.'.join(str(x) for x in numberings)}.1"
    else:
        numberings = numberings[:indent]
        numberings[-1] += 1
        return '.'.join(str(x) for x in numberings)

def Generate(branch: Union[dict, list], this_section: str) -> Tuple[str, str]:
    global sections
    html = ""
    
    if isinstance(branch, list):
        for element in branch:
            content, this_section = Generate(element, this_section)
            html += content
        
    elif isinstance(branch, dict):
        # unwanted files
        if 'name' in branch and branch['name'].strip() and ".json" not in branch['name']:
            # indent depth is identified by file depth, subtract base level
            indent = len([x for x in branch["filePath"].split("/") if x]) - 1
            this_section = SectionNumber(indent, this_section)
            # exclusions
            if all(x not in this_section for x in EXCLUSIONS):
                sections[this_section] = branch['name']
            html += f"""<section id="{this_section}"><h3>{this_section} {branch['name']}</h3></section>

"""
        if branch["hasContent"] and ".json" not in branch['name']:
            contents = branch["contents"]
            contents = contents if isinstance(contents, list) else list(contents.values())
            for content in contents:
                if 'name' in content and content['name'].strip() and ".json" not in content['name']:
                    html += f"""<h3>{content['name']}</h3>
"""
                # Completion of links
                c = f"""{content['content'].strip().replace("a href='/", "a href='https://www.quantconnect.com/docs/v2/").replace('a href="/', 'a href="/https://www.quantconnect.com/docs/v2/')}"""
                # fix any html unclosed tags
                soup = BeautifulSoup(c, features="lxml")
                html += f"""{soup.prettify()}
"""

            # 2nd level add a page break
            html += f"""{PAGE_BREAKER}
"""
        # Recursively generate content in order
        if "branches" in branch and branch["branches"] and ".json" not in branch['name']:
            subbranch = branch["branches"]
            subbranch = subbranch if isinstance(subbranch, list) else list(subbranch.values())
            content, this_section = Generate(subbranch, this_section)
            html += content

    return html, this_section

def Knit(content: list, name: str) -> str:
    html = ""
    this_section = "0"
    
    try:
        content, this_section = Generate(content, this_section)
        
        images = ExtractImage(content)
        for img_url, img_path in images.items():
            content = content.replace(img_url, img_path)
        
        html += content
        
        print(f"Knit(): Knitted documentation content for {name} successfully.")
        return html

    except Exception as e:
        raise Exception(f"Knit(): Unable to knit documentation content - {e}")
    
def TitlePageAndTableOfContentGeneration(topic: str) -> str:
    global sections
    linebreaker = "\n"
    
    return f"""{TITLE_PAGE % topic}
{PAGE_BREAKER}
<h3>Table of Content</h3>
<nav>
<ul>
{linebreaker.join([f'<li><a href="#{id}" class="toc-h{len(id.split("."))}" target="_parent">{id} {title}</a></li>' for id, title in sections.items()])}
</ul>
</nav>
{PAGE_BREAKER}
"""

def WriteToHtmlFile(content: str, name: str) -> Path:
    output_dir = Path(DESTINATION_PATH)
    output_dir.mkdir(exist_ok=True, parents=True)
    filepath = output_dir / f'{OUTPUT_FILENAME % name}.html'
    
    try:
        with open(filepath, "w", encoding="utf-8") as html_file:
            html_file.write(content)
        print(f"WriteToFile(): Successfully written content to {filepath}")
        return filepath
    
    except Exception as e:
        raise Exception(f"WriteToFile(): Unable to write content to {filepath} - {e}")

def PdfConversion(html_path: Union[Path, str], language: str, css: Union[str, List[str]] = None) -> None:
    try:
        pdf_name = f'{str(html_path)[:-5]}-{"CSharp" if language == "csharp" else "Python"}.pdf'
        pdfkit.from_file(str(html_path), pdf_name, css=f'{css}/pdf-styles-{language}.css')
        print(f"PdfConversion(): Successfully converting {html_path} to {pdf_name}")
    except Exception as e:
        # Do not break with raising exceptions in case due to warnings
        print(f"PdfConversion(): Unable to converting {html_path} - {e}")
        
def ExtractImage(content: str):
    conversions = {}
    soup = BeautifulSoup(content, features="lxml")
    images = soup.findAll('img')
    
    for image in images:
        url = image["src"]
        name = f'{IMAGE_DIR}/{url.split("/")[-1].split("?")[0]}'
        if os.path.exists(name):
            urlretrieve(url, name)
        conversions[url] = name
    
    return conversions

def ConvertTime(sec: float) -> str:
    mins = sec // 60
    sec = sec % 60
    hours = mins // 60
    mins = mins % 60
    return f"{int(hours):02}:{int(mins):02}:{sec}"

def Run(date: datetime) -> None:
    global sections
    start_time = time.time()
    print(f"Run(): Start processing")
    date_str = date.strftime('%Y.%m.%d') if date else DEFAULT_VERSION

    try:
        content = GetContent(date_str)
    except:
        raise Exception(f"Run(): unable to fetch content from target URL.")
    
    i = 1
    for branch in content["branches"].values():
        branch_content = list(branch["branches"].values()) if isinstance(branch["branches"], dict) else branch["branches"]
        html_content = Knit(branch_content, branch["name"])
        table_of_content = TitlePageAndTableOfContentGeneration(branch["name"])
        html_path = WriteToHtmlFile(table_of_content + html_content, branch["name"].title().replace(' ', '-'))
        for language in ["csharp", "py"]:
            PdfConversion(html_path, language=language, css=CSS_DIR)
        if i == LAST_SECTION:
            break
        i += 1
        sections = {}
        
    end_time = time.time()
    time_lapsed = end_time - start_time
    print(f"Run(): Finish processing in {ConvertTime(time_lapsed)}")
    
if __name__ == "__main__":
    if len(sys.argv) > 2:
        raise ValueError("Main(): takes at most 1 argument.")
    
    date = None
    if len(sys.argv) == 2:
        try:
            date = datetime.strptime(sys.argv[-1], "%Y%m%d")
        except:
            raise Exception(f"Main(): {sys.argv[-1]} is not in valid format, expected date input in format: yyyyMMdd")
    
    Run(date)