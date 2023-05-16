from datetime import datetime
import json
from pathlib import Path
from urllib.request import urlopen
import sys
import time
from typing import Union, Tuple

SOURCE_URL = "https://s3.amazonaws.com/cdn.quantconnect.com/web/cache"
DESTINATION_PATH = "single-page"
OUTPUT_FILENAME = "quantconnect-documentation.html"
DEFAULT_VERSION = "2023.01.17"
LAST_SECTION = "6"
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
            # indent depth is identified by file depth
            indent = len([x for x in branch["filePath"].split("/") if x])
            this_section = SectionNumber(indent, this_section)
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
                html += f"""{content['content'].strip().replace("a href='/", "a href='https://www.quantconnect.com/docs/v2/").replace('a href="/', 'a href="/https://www.quantconnect.com/docs/v2/')}
"""
        # Recursively generate content in order
        if "branches" in branch and branch["branches"] and ".json" not in branch['name']:
            subbranch = branch["branches"]
            subbranch = subbranch if isinstance(subbranch, list) else list(subbranch.values())
            content, this_section = Generate(subbranch, this_section)
            html += content
            
        # 2nd level add a page break
        if indent == 2:
            html += """<p style="page-break-after: always;">&nbsp;</p>
"""
    return html, this_section

def Knit(content: dict) -> str:
    html = ""
    this_section = "0"
    
    try:
        for branch in content["branches"].values():
            content, this_section = Generate(branch, this_section)
            html += content
            if this_section[0] == LAST_SECTION:
                break
        
        print(f"Knit(): Knitted documentation content successfully.")
        return html

    except Exception as e:
        raise Exception(f"Knit(): Unable to knit documentation content - {e}")
    
def TableOfContentGeneration():
    global sections
    linebreaker = "\n"
    
    return f"""<nav>
<ul>
{linebreaker.join([f'<li><a href="#{id}" target="_parent">{id} {title}</a></li>' for id, title in sections.items()])}
</ul>
</nav>
"""

def WriteToFile(content: str) -> None:
    output_dir = Path(DESTINATION_PATH)
    output_dir.mkdir(exist_ok=True, parents=True)
    
    try:
        with open(output_dir / OUTPUT_FILENAME, "w", encoding="utf-8") as html_file:
            html_file.write(content)
        print(f"WriteToFile(): Successfully written content to {output_dir / OUTPUT_FILENAME}")
    
    except Exception as e:
        raise Exception(f"WriteToFile(): Unable to write content to {output_dir / OUTPUT_FILENAME} - {e}")
        
def ConvertTime(sec: float) -> str:
    mins = sec // 60
    sec = sec % 60
    hours = mins // 60
    mins = mins % 60
    return f"{int(hours):02}:{int(mins):02}:{sec}"

def Run(date: datetime) -> None:
    start_time = time.time()
    print(f"Run(): Start processing")
    date_str = date.strftime('%Y.%m.%d') if date else DEFAULT_VERSION

    try:
        content = GetContent(date_str)
    except:
        raise Exception(f"Run(): unable to fetch content from target URL.")
    
    html_content = Knit(content)
    table_of_content = TableOfContentGeneration()
    WriteToFile(table_of_content + html_content)
        
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