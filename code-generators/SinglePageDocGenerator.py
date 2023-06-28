import base64
from bs4 import BeautifulSoup
from datetime import datetime
import json
import os
from pathlib import Path
import pdfkit
from PIL import Image
import re
import sys
import time
from typing import Union, Tuple, List
from urllib.request import urlopen, urlretrieve
from wand.image import Image as WandImage

SOURCE_URL = "https://s3.amazonaws.com/cdn.quantconnect.com/web/cache"
DESTINATION_PATH = "single-page"
OUTPUT_FILENAME = "Quantconnect-%s"
DEFAULT_VERSION = "2023.01.17"
LAST_SECTION = 6
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
COVER_PAGE_DIR = "single-page/cover-page"
IMAGE_DIR = "single-page/images"
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

def BreadCrumb(all: dict, section: str, name: str) -> str:
    breadcrumb = [name]
    
    while '.' in section:
        section = '.'.join(section.split('.')[:-1])
        if section in all:
            breadcrumb.append(all[section])
        
    return ' > '.join(breadcrumb[::-1])

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
            breadcrumb = BreadCrumb(sections, this_section, branch['name'])
            subtopic = len(breadcrumb.split(" > ")) >= 2
            html += f"""<p class='page-breadcrumb'>{breadcrumb}</p>
<div class='page-heading'>
    <section id="{this_section}">
        <h1>{breadcrumb.split(" > ")[-2] if subtopic else branch['name']}</h1>
        {"<h2>" + branch['name'] + "</h2>" if subtopic else ""}
    </section>
</div>
"""
        if branch["hasContent"] and ".json" not in branch['name']:
            contents = branch["contents"]
            contents = contents if isinstance(contents, list) else list(contents.values())
            for content in contents:
                if 'name' in content and content['name'].strip() and ".json" not in content['name']:
                    html += f"""<h3>{content['name']}</h3>
"""
                # Completion of links
                c = f"""{content['content'].strip().replace("a href='/docs/v2", "a href='https://www.quantconnect.com/docs/v2/").replace('a href="/docs/v2', 'a href="https://www.quantconnect.com/docs/v2/').replace('a href=/docs/v2', 'a href=https://www.quantconnect.com/docs/v2/').replace('a href="/', 'a href="https://www.quantconnect.com/').replace("a href='/", "a href='https://www.quantconnect.com/").replace("a href=/", "a href=https://www.quantconnect.com/")}"""
                # fix any html unclosed tags and hided details
                soup = BeautifulSoup(c, features="lxml")
                for x in soup.find_all("div", {"class": "method-details"}):
                    x["style"] = x["style"].replace("display: none", "display: block")
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
    global sections
    html = ""
    this_section = "0"
    
    try:
        content, this_section = Generate(content, this_section)
        
        images = ExtractImage(content)
        for img_url, img_path in images.items():
            base64_img = base64.b64encode(open(img_path, 'rb').read()).decode()
            content = content.replace(img_url, f'data:image/{img_path.split(".")[-1]};base64,{base64_img}')
            
        content = ModifySectionPointer(content, name)
        html += content
        
        print(f"Knit(): Knitted documentation content for {name} successfully.")
        return html

    except Exception as e:
        raise Exception(f"Knit(): Unable to knit documentation content - {e}")
    
def ModifySectionPointer(content: str, name: str) -> str:
    global sections
    # Point to section in document if in the same section
    section_num = list(sections.keys())
    names = list([x.lower() for x in sections.values()])
    replacement = {}
    soup = BeautifulSoup(content, features="lxml")
    
    for x in soup.findAll('a'):
        try:
            if f"https://www.quantconnect.com/docs/v2/{name.lower().replace(' ', '-')}" in x["href"] or f"https://www.quantconnect.com/docs/v2//{name.lower().replace(' ', '-')}" in x["href"]:
                section = [y.replace('-', ' ').lower().strip() for y in re.split('/|#', x["href"].split(name.lower().replace(' ', '-'))[-1])]
                # rundown the section number list by subsection in url
                subnames = names; c = 0
                for subsection in section:
                    if subsection in subnames:
                        ind = subnames.index(subsection)
                        replacement[x["href"]] = f'#{section_num[c+ind]}'
                        c = ind+1
                        subnames = names[c:]
        except:
            pass       # no "href" in tag "a"
        
    for x in soup.find_all("div", {"class": "content clickable"}):
        try:
            if f"window.location.href = '/docs/v2/{name.lower().replace(' ', '-')}" in x["onclick"]:
                section = [y.replace('-', ' ').lower().strip() for y in re.split('/|#', x["onclick"].split(name.lower().replace(' ', '-'))[-1][:-1])]
                # rundown the section number list by subsection in url
                subnames = names; c = 0
                for subsection in section:
                    if subsection in subnames:
                        ind = subnames.index(subsection)
                        replacement[x["onclick"]] = f"window.location.href = '#{section_num[c+ind]}'"
                        c = ind+1
                        subnames = names[c:]
        except:
            pass       # no "onclick" in tag
        
    for link, num in replacement.items():
        content = content.replace(link, num)
        
    return content
    
def CoverPageAndTableOfContentGeneration(topic: str) -> str:
    global sections
    linebreaker = "\n"
    main_cover = open(f'{COVER_PAGE_DIR}/main-cover.html', 'r', encoding='utf-8').read()
    cover_page = open(f'{COVER_PAGE_DIR}/{topic.lower().replace(" ", "-")}.html', 'r', encoding='utf-8').read()
    
    # convert image to base64
    images = ExtractImage(cover_page)
    for img_url, img_path in images.items():
        base64_img = base64.b64encode(open(img_path, 'rb').read()).decode()
        cover_page = cover_page.replace(img_url, f'data:;base64,{base64_img}')
    
    return f"""{main_cover}{PAGE_BREAKER}
{cover_page}
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
        
def ExtractImage(content: str) -> dict:
    conversions = {}
    soup = BeautifulSoup(content, features="lxml")
    images = soup.findAll('img')
    
    for image in images:
        url = image["src"]
        path = f'{IMAGE_DIR}/{url.split("/")[-1].split("?")[0]}'
        try:
            if url and not os.path.exists(path):
                urlretrieve(url, path)
                
                if url.endswith('.webp'):
                    # Convert the webp image to PNG
                    png_path = path.replace("webp", "png")
                    image = Image.open(path).convert("RGBA")
                    image.save(png_path, "png")
                    
                elif url.endswith('.svg'):
                    with WandImage(filename=path) as img:
                        with img.convert('png') as output_img:
                            png_path = path.lower().replace("svg", "png")
                            output_img.save(filename=png_path)
                
                # to avoid libpng warning: iCCP: known incorrect sRGB profile
                if path.lower().endswith('.png'):
                    img = Image.open(path)
                    if 'icc_profile' in img.info:
                        del img.info['icc_profile']
                        img.save(path)
                        
            if path.lower().endswith('.svg') or path.lower().endswith('.webp'):
                # Update the image source to the relative PNG path
                path = f'{".".join(path.split(".")[:-1])}.png'
            
            conversions[url] = path
            
        except Exception as e:
            print(f"Unable to fetch image from {url} - {e}")
    
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
        table_of_content = CoverPageAndTableOfContentGeneration(branch["name"])
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