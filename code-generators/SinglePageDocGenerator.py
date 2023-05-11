from datetime import datetime
import json
from pathlib import Path
from urllib.request import urlopen
import sys
import time
from typing import Union

SOURCE_URL = "https://s3.amazonaws.com/cdn.quantconnect.com/web/cache"
DESTINATION_PATH = "single-page-html"
OUTPUT_FILENAME = "qc-documentation"
DEFAULT_VERSION = "2023.01.17"

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

def Generate(branch: Union[dict, list]) -> str:
    html = ""
    
    if isinstance(branch, list):
        for element in branch:
            html += Generate(element)
        
    elif isinstance(branch, dict):
        html += f"""<h3>{branch['name']}</h3>

"""
        # Recursively generate content in order
        if "branches" in branch and branch["branches"]:
            subbranch = branch["branches"]
            subbranch = subbranch if isinstance(subbranch, list) else list(subbranch.values())
            html += Generate(subbranch)
        
        if branch["hasContent"]:
            contents = branch["contents"]
            contents = contents if isinstance(contents, list) else list(contents.values())
            for content in contents:
                html += f"""<h3>{content['name']}</h3>
{content['content'].strip()}

"""
    return html

def Knit(content: dict) -> str:
    html = ""
    
    try:
        for branch in content["branches"].values():
            html += Generate(branch)
        
        print(f"Knit(): Knitted documentation content successfully.")
        return html
    
    except Exception as e:
        raise Exception(f"Knit(): Unable to knit documentation content - {e}")

def WriteToFile(date: str, content: str) -> None:
    output_dir = Path(DESTINATION_PATH)
    output_dir.mkdir(exist_ok=True, parents=True)
    filename = f"{OUTPUT_FILENAME}-v{date}.html"
    
    try:
        with open(output_dir / filename, "w", encoding="utf-8") as html_file:
            html_file.write(content)
        print(f"WriteToFile(): Successfully written content to {output_dir / filename}")
    
    except Exception as e:
        raise Exception(f"WriteToFile(): Unable to write content to {output_dir / filename} - {e}")
        
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
    WriteToFile(date_str, html_content)
        
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