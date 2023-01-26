import os
import pathlib
import shutil
from urllib.request import urlopen

for clean_up in os.listdir('03 Writing Algorithms/14 Datasets'):
    if not '01 Overview' in clean_up and not "readme" in clean_up:
        destination = '03 Writing Algorithms/14 Datasets/' + clean_up
        temp = 'tmp/03 Writing Algorithms/14 Datasets/' + clean_up
        if os.path.isdir(destination):
            shutil.copytree(destination, temp, dirs_exist_ok=True,
                            ignore=lambda dir, files: [f for f in files if os.path.isfile(os.path.join(dir, f)) and str(f) != "metadata.json"])
            shutil.rmtree(destination)
            shutil.copytree(temp, destination, dirs_exist_ok=True)
            shutil.rmtree(temp)

url = urlopen("https://s3.amazonaws.com/cdn.quantconnect.com/web/docs/alternative-data-dump-v2021-12-06.json")
response = url.read().decode("utf-8") \
    .replace("true", "True") \
    .replace("false", "False") \
    .replace("null", "None")
doc = eval(response)

vendor_count = 2
vendors = {}
product_count = {}
attr = False

json_00 = {}

priority = ["QuantConnect", "AlgoSeek", "Morningstar", "TickData", "TrueData", "CoinAPI", "OANDA"]
vendor_names = priority + sorted([x for x in [dataset["vendorName"].strip() for dataset in doc] if x not in priority])
for vendor in vendor_names:
    if vendor not in vendors:
        vendors[vendor] = vendor_count
        product_count[vendor] = {dataset["name"].strip(): m+1 for m, dataset in enumerate(sorted([x for x in doc if x["vendorName"].strip() == vendor], key=lambda x: x['name']))}
        vendor_count += 1
        
        json_00[vendor] = {
  "type" : "landing",
  "heading" : vendor,
  "subHeading" : "",
  "content" : "",
  "alsoLinks" : [],
  "featureShortDescription": {}
}
        
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
    
    all_sections = {**{item["title"]: item for item in dataset["about"] if item["title"]}, **{item["title"]: item for item in dataset["documentation"] if item["title"]}}
    
    for _, item in all_sections.items():
        content = item["content"].replace("\/", "/") \
                    .replace("https://www.quantconnect.com/docs/v2/", "/docs/v2/") \
                    .replace("https://www.quantconnect.com/datasets/", "/datasets/") \
                    .replace('<div class="qc-embed-frame"><div class="qc-embed-dummy"></div><div class="qc-embed-element"><iframe class="qc-embed-backtest"',
                                            '<div class="qc-embed-frame python" style="display: inline-block; position: relative; width: 100%; min-height: 100px; min-width: 300px;"><div class="qc-embed-dummy" style="padding-top: 56.25%;"></div><div class="qc-embed-element" style="position: absolute; top: 0; bottom: 0; left: 0; right: 0;"><iframe class="qc-embed-backtest"') \
                    .replace('<pre><code class="language-python">', 
                                '<pre class="python">') \
                    .replace('<pre><code class="language-cs">',
                                '<pre class="csharp">') \
                    .replace('</code>',
                                '')
                    
        if item["title"].lower() == "about the provider":
            json_00[vendorName]["content"] = content
            json_00[vendorName]["featureShortDescription"][f"{product_count[vendorName][datasetName]:02}"] = ""
            
            with open(f'03 Writing Algorithms/14 Datasets/{vendors[vendorName]:02} {vendorName}/00.json', 'w', encoding='utf-8') as html_file:
                html_file.write(str(json_00[vendorName]).replace('"', '\\"').replace("'", '"'))
        
        if item["title"].lower() == "example applications":
            with open(destination_folder / f'99 {item["title"].strip()}.html', "w", encoding="utf-8") as html_file:
                html_file.write(content)
                
            continue
        
        else: 
            if item["title"].lower() == "introduction":
                backslash = '\\'
                content += f"""
                
<p>For more information about the {datasetName} dataset, including CLI commands and pricing, see the <a href=\"{dataset['url'].lower().replace(backslash, '')}\">dataset listing</a>.<p>"""
                
        if item["title"].strip() == "Universe Selection" and vendorName not in priority:
            universe_html += f"""    <li><a href="/docs/v2/writing-algorithms/datasets/{vendorName.lower().replace(' ', '-')}/{datasetName.lower().replace(' ', '-')}#{i:02}-Universe-Selection">{datasetName}</a></li>
"""
        
        with open(destination_folder / f'{i:02} {item["title"].strip()}.html', "w", encoding="utf-8") as html_file:
            html_file.write(content)
            i += 1
            
    print(f'Documentation of {dataset["name"]} is generated and inplace!')
    
universe_html += "</ul>"
with open('Resources/datasets/supported-alternative-dataset-universe.html', "w", encoding="utf-8") as html_file:
    html_file.write(universe_html)