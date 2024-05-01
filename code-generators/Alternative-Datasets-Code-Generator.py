import os
import pathlib
import shutil
from urllib.request import urlopen
from bs4 import BeautifulSoup
        
def metadata_content(vendor, dataset):
    vendor_ = vendor.lower().replace(" ", "-")
    dataset_ = dataset.lower().replace(" ", "-")
    return f'''{{
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
}}'''

for clean_up in os.listdir('03 Writing Algorithms/14 Datasets'):
    if not '01 Overview' in clean_up and not "readme" in clean_up:
        destination = '03 Writing Algorithms/14 Datasets/' + clean_up
        temp = 'tmp/03 Writing Algorithms/14 Datasets/' + clean_up
        if os.path.isdir(destination):
            shutil.copytree(destination, temp, dirs_exist_ok=True,
                            ignore=lambda dir, files: [f for f in files if os.path.isfile(os.path.join(dir, f)) and str(f) != "metadata.json" and str(f) != "00.json"])
            shutil.rmtree(destination)
            shutil.copytree(temp, destination, dirs_exist_ok=True)
            shutil.rmtree(temp)

url = urlopen("https://s3.amazonaws.com/cdn.quantconnect.com/web/docs/alternative-data-dump-v2024-01-02.json")
response = url.read().decode("utf-8") \
    .replace("true", "True") \
    .replace("false", "False") \
    .replace("null", "None")
doc = eval(response)

languages = {"language-python": "python", "language-cs": "csharp"}
vendor_count = 2
vendors = {}
product_count = {}
attr = False

priority = ["QuantConnect", "AlgoSeek", "Morningstar", "TickData", "CoinAPI", "OANDA"]
vendor_names = priority + sorted([x for x in [dataset["vendorName"].strip() for dataset in doc] if x not in priority])
for vendor in vendor_names:
    if vendor not in vendors:
        vendors[vendor] = vendor_count
        product_count[vendor] = {dataset["name"].strip(): m+1 for m, dataset in enumerate(sorted([x for x in doc if x["vendorName"].strip() == vendor], key=lambda x: x['name']))}
        vendor_count += 1

universe_html = """<p>The following alternative datasets support universe selection:</p>
<ul>
"""

for dataset in doc:
    i = 1
    vendorName = dataset["vendorName"].strip()
    datasetName = dataset["name"].strip()

    # Create path if not exist
    main_dir = f'03 Writing Algorithms/14 Datasets/{vendors[vendorName]:02} {vendorName}/{product_count[vendorName][datasetName]:02} {datasetName}'
    destination_folder = pathlib.Path(main_dir)
    destination_folder.mkdir(parents=True, exist_ok=True)

    with open(destination_folder / f'metadata.json', "w", encoding="utf-8") as json_file:
        metadata = metadata_content(vendorName, datasetName)
        json_file.write(metadata)

    all_sections = {**{item["title"]: item["content"] for item in dataset["about"] if item["title"]},
                    **{item["title"]: item["content"] for item in dataset["documentation"] if item["title"]}}

    for title, content in all_sections.items():        
        content = content.replace("\/", "/") \
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

            with open(destination_folder / f'99 {title.strip()}.html', "w", encoding="utf-8") as html_file:
                html_file.write(content)
        else:
            for old, new in languages.items():
                content = content.replace(old, new)
            if title.lower() == "introduction":
                backslash = '\\'
                content += f"""
                
<p>For more information about the {datasetName} dataset, including CLI commands and pricing, see the <a href=\"{dataset['url'].lower().replace(backslash, '')}\">dataset listing</a>.<p>"""

        if title.strip() == "Universe Selection" and vendorName not in priority:
            universe_html += f"""    <li><a href="/docs/v2/writing-algorithms/datasets/{vendorName.lower().replace(' ', '-')}/{datasetName.lower().replace(' ', '-')}#{i:02}-Universe-Selection">{datasetName}</a></li>
"""

        with open(destination_folder / f'{i:02} {title.strip()}.html', "w", encoding="utf-8") as html_file:
            html_file.write(content)
            i += 1

    print(f'Documentation of {dataset["name"]} is generated and inplace!')

universe_html += "</ul>"
with open('Resources/datasets/supported-alternative-dataset-universe.html', "w", encoding="utf-8") as html_file:
    html_file.write(universe_html)
