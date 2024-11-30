import os
from pathlib import Path
from itertools import groupby
from shutil import copy, copy2, move, rmtree
from bs4 import BeautifulSoup
from _code_generation_helpers import get_json_content

DATASET = "03 Writing Algorithms/14 Datasets"

def _directory_content(path):
    def append_path(v):
        return [Path(f'{path}/{x}') for x in v]
    key = lambda x: x[3:]
    grouped = groupby(sorted(os.listdir(path), key=key), key)
    return {k: append_path(v) for k,v in grouped}

def _move(src, dst):
    [os.remove(x) for x in dst.iterdir() if x.suffix == '.html'] 
    for path in [p for p in src if p != dst]:
        for subpath in path.iterdir():
            final_dst = Path(os.path.join(dst, subpath.name))
            if not subpath.is_dir():
                move(subpath, final_dst)
                continue
            final_dst.mkdir(parents=True, exist_ok=True)
            [move(x, os.path.join(final_dst, x.name)) for x in subpath.iterdir()]
        rmtree(path)

def _write_metadata_file(folder, vendor, dataset):
    vendor_ = vendor.lower().replace(" ", "-")
    dataset_ = dataset.lower().replace(" ", "-")
    with open(folder / f'metadata.json', "w", encoding="utf-8") as json_file:
        json_file.write(f'''{{
    "type": "metadata",
    "values": {{
        "description": "{dataset} dataset from {vendor}.",
        "keywords": "data, financial data, alternative dataset",
        "og:description": "{dataset} dataset from {vendor}.",
        "og:title": "{dataset} - Documentation QuantConnect.com",
        "og:type": "website",
        "og:site_name": "{dataset} - QuantConnect.com",
        "og:image": "https://cdn.quantconnect.com/docs/i/writing-algorithms/datasets/{vendor_}/{dataset_}.png"
    }}
}}''')

if __name__ == '__main__':

    url = "https://s3.amazonaws.com/cdn.quantconnect.com/web/docs/alternative-data-dump-v2024-01-02.json"
    docs = sorted(get_json_content(url), key=lambda x: x["vendorName"].strip())

    docs_by_vendor = {k : sorted(v, key=lambda x: x['name'].strip())
        for k,v in groupby(docs, lambda x: x["vendorName"].strip())}

    priority = ["QuantConnect", "AlgoSeek", "Morningstar", "TickData", "CoinAPI", "OANDA"]
    vendors = [x for x in priority if x in docs_by_vendor]
    vendors += [k for k in docs_by_vendor if k not in vendors]

    current = _directory_content(DATASET)
    current.pop('Overview')

    languages = {"language-python": "python", "language-cs": "csharp"}

    universe_html = """<p>The following alternative datasets support universe selection:</p>
<ul>
"""
    
    for i, vendor in enumerate(vendors):
        vendor_folder = Path(f'{DATASET}/{i+2:02} {vendor}')
        vendor_folder.mkdir(parents=True, exist_ok=True)
        _move(current.pop(vendor, []), vendor_folder)
        datasets = _directory_content(vendor_folder)
        for f, dataset in enumerate(docs_by_vendor.pop(vendor)):
            name = dataset['name'].strip()
            folder = Path(f'{vendor_folder}/{f+1:02} {name}')
            folder.mkdir(parents=True, exist_ok=True)
            _move(datasets.pop(name, []), folder)

            [os.remove(x) for x in folder.iterdir() if x.suffix == '.html'] 
            
            _write_metadata_file(folder, vendor, name)

            all_sections = {**{item["title"]: item["content"] for item in dataset["about"] if item["title"]},
                            **{item["title"]: item["content"] for item in dataset["documentation"] if item["title"]}}

            for k, (title, content) in enumerate(all_sections.items()):        
                content = content.replace("\/", "/") \
                            .replace("->", "-&gt;") \
                            .replace("=>", "=&gt;") \
                            .replace("=<", "=&lt;") \
                            .replace("https://www.quantconnect.com/docs/v2/", "/docs/v2/") \
                            .replace("https://www.quantconnect.com/datasets/", "/datasets/") \
                            .replace('<div class="qc-embed-frame"><div class="qc-embed-dummy"></div><div class="qc-embed-element"><iframe class="qc-embed-backtest"',
                                     '<div class="qc-embed-frame python" style="display: inline-block; position: relative; width: 100%; min-height: 100px; min-width: 300px;"><div class="qc-embed-dummy" style="padding-top: 56.25%;"></div><div class="qc-embed-element" style="position: absolute; top: 0; bottom: 0; left: 0; right: 0;"><iframe class="qc-embed-backtest"')
                soup = BeautifulSoup(content, 'html.parser')
                for code_section in soup.find_all("div", class_="section-example-container"):
                    for pre_code_section in soup.find_all("pre"):
                        for old, new in languages.items():
                            for code_snippet in pre_code_section.find_all('code', {'class' : old}):
                                converted = f'{code_snippet}'.replace('code', 'pre').replace(old, new)
                                content = content.replace(f'{pre_code_section}', converted)

                if title.lower() == "example applications":
                    start = content.find('<div class="dataset-embeds">')
                    if start > 0:
                        text = ''
                        for old, new in languages.items():
                            for code_section in soup.find_all("div", class_=f"qc-embed-frame {old}"):
                                text += f"\n<div class='{new}'><div class='qc-embed-frame' style='display: inline-block; position: relative; width: 100%; min-height: 100px; min-width: 300px;'><div class='qc-embed-dummy' style='padding-top: 56.25%;'></div><div class='qc-embed-element' style='position: absolute; top: 0; bottom: 0; left: 0; right: 0;'><iframe class='qc-embed-backtest' src='https://www.quantconnect.com/terminal/processCache?request=embedded_backtest{str(code_section)[-61:-28]}.html' style='max-width: calc(100vw - 30px); max-height: 100vw; overflow: hidden;' scrolling='no' width='100%' height='100%'></iframe></div></div></div>"
                        end = start + len(str(soup.find_all("div", class_="dataset-embeds")))
                        content = content.replace(content[start:end], text)

                    with open(folder / f'99 {title.strip()}.html', "w", encoding="utf-8") as html_file:
                        html_file.write(content)
                else:
                    for old, new in languages.items():
                        content = content.replace(old, new)
                    if title.lower() == "introduction":
                        backslash = '\\'
                        content += f"""

<p>For more information about the {name} dataset, including CLI commands and pricing, see the <a href=\"{dataset['url'].lower().replace(backslash, '')}\">dataset listing</a>.<p>"""

                if title.strip() == "Universe Selection" and vendor not in priority:
                    universe_html += f"""    <li><a href="/docs/v2/writing-algorithms/datasets/{vendor.lower().replace(' ', '-')}/{name.lower().replace(' ', '-')}#{k+1:02}-Universe-Selection">{name}</a></li>
"""

                with open(folder / f'{1+k:02} {title.strip()}.html', "w", encoding="utf-8") as html_file:
                    html_file.write(content)

            print(f'Documentation of {dataset["name"]} is generated and inplace!')

        [rmtree(path) for paths in datasets.values() for path in paths if path.is_dir()]
    [rmtree(path) for paths in current.values() for path in paths if path.is_dir()]
    
    with open('Resources/datasets/supported-alternative-dataset-universe.html', "w", encoding="utf-8") as html_file:
        html_file.write(universe_html + "</ul>")

    for root, dirs, files in os.walk(DATASET):
        for path in [os.path.join(root, p) for p in dirs]:      
            if not os.listdir(path):
                rmtree(path)