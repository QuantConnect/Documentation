import os
import pathlib
import shutil
from urllib.request import urlopen

for clean_up in os.listdir('02 Writing Algorithms/14 Datasets'):
    if not '01 Overview' in clean_up and not "readme" in clean_up:
        shutil.rmtree('02 Writing Algorithms/14 Datasets/' + clean_up)

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

priority = ["QuantConnect", "AlgoSeek", "Morningstar", "TickData", "TrueData", "CoinAPI", "OANDA"]
vendor_names = priority + sorted([x for x in [dataset["vendorName"].strip() for dataset in doc] if x not in priority])
for vendor in vendor_names:
    if vendor not in vendors:
        vendors[vendor] = vendor_count
        product_count[vendor] = 1
        vendor_count += 1
        
universe_html = """<p>The following alternative datasets support universe selection:</p>
<ul>
"""

for dataset in doc:
    i = 1
    vendorName = dataset["vendorName"].strip()
    datasetName = dataset["name"].strip()
        
    # Create path if not exist
    main_dir = f'02 Writing Algorithms/14 Datasets/{vendors[vendorName]:02} {vendorName}/{product_count[vendorName]:02} {datasetName}'
    destination_folder = pathlib.Path(main_dir)
    destination_folder.mkdir(parents=True, exist_ok=True)
    
    for item in dataset["about"]:
        if not item["title"]: continue
        
        content = item["content"].replace("\/", "/") \
                    .replace('<div class="qc-embed-frame"><div class="qc-embed-dummy"></div><div class="qc-embed-element"><iframe class="qc-embed-backtest"',
                                            '<div class="qc-embed-frame python" style="display: inline-block; position: relative; width: 100%; min-height: 100px; min-width: 300px;"><div class="qc-embed-dummy" style="padding-top: 56.25%;"></div><div class="qc-embed-element" style="position: absolute; top: 0; bottom: 0; left: 0; right: 0;"><iframe class="qc-embed-backtest"') \
                    .replace('<pre><code class="language-python">', 
                                '<pre class="python">') \
                    .replace('<pre><code class="language-cs">',
                                '<pre class="csharp">') \
                    .replace('</code>',
                                '')
        
        if item["title"] == "Example Applications":
            with open(destination_folder / f'99 {item["title"].strip()}.html', "w", encoding="utf-8") as html_file:
                html_file.write(content)
        else: 
            if item["title"] == "Data Point Attributes":
                attr = True
                
            with open(destination_folder / f'{i:02} {item["title"].strip()}.html', "w", encoding="utf-8") as html_file:
                html_file.write(content)
                i += 1
                
    for item in dataset["documentation"]:
        content = item["content"].replace("\/", "/") \
                    .replace('<div class="qc-embed-frame"><div class="qc-embed-dummy"></div><div class="qc-embed-element"><iframe class="qc-embed-backtest"',
                                            '<div class="qc-embed-frame python" style="display: inline-block; position: relative; width: 100%; min-height: 100px; min-width: 300px;"><div class="qc-embed-dummy" style="padding-top: 56.25%;"></div><div class="qc-embed-element" style="position: absolute; top: 0; bottom: 0; left: 0; right: 0;"><iframe class="qc-embed-backtest"') \
                    .replace('<pre><code class="language-python">', 
                                '<pre class="python">') \
                    .replace('<pre><code class="language-cs">',
                                '<pre class="csharp">') \
                    .replace('</code>',
                                '')
                
        if item["title"].strip() == "Universe Selection" and vendorName not in priority:
            universe_html += f"""    <li><a href="https://www.quantconnect.com/docs/v2/writing-algorithms/datasets/{vendorName.lower().replace(' ', '-')}/{datasetName.lower().replace(' ', '-')}#{i:02}-Universe-Selection">{datasetName}</a></li>
"""
        
        if item["title"] == "Data Point Attributes" and attr:
            continue
        else:
            with open(destination_folder / f'{i:02} {item["title"].strip()}.html', "w", encoding="utf-8") as html_file:
                html_file.write(content)
                i += 1
            
    print(f'Documentation of {dataset["name"]} is generated and inplace!')
    product_count[vendorName] = product_count[vendorName] + 1
    
universe_html += "</ul>"
with open('02 Writing Algorithms/12 Universes/11 Alternative Data Universes/02 Supported Datasets.html', "w", encoding="utf-8") as html_file:
    html_file.write(universe_html)