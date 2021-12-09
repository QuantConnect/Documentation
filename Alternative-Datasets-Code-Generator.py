import json
import pathlib

documentations = {"Alternative Datasets": "alternative-data-dump-v2021-12-06.json"}

for section, source in documentations.items():
    json_file = open(source, encoding="utf-8")
    doc = json.load(json_file)
    count = 4

    for dataset in doc:
        i = 1
        
        # Create path if not exist
        main_dir = f'02 Writing Algorithms/02 User Guides/04 Alternative Datasets/{count:02} {dataset["name"].strip()}'
        destination_folder = pathlib.Path(main_dir)
        destination_folder.mkdir(parents=True, exist_ok=True)
        
        for item in dataset["about"]:
            content = item["content"] \
                        .replace('<div class="qc-embed-frame"><div class="qc-embed-dummy"></div><div class="qc-embed-element"><iframe class="qc-embed-backtest"',
                                              '<div class="qc-embed-frame python" style="display: inline-block; position: relative; width: 100%; min-height: 100px; min-width: 300px;"><div class="qc-embed-dummy" style="padding-top: 56.25%;"></div><div class="qc-embed-element" style="position: absolute; top: 0; bottom: 0; left: 0; right: 0;"><iframe class="qc-embed-backtest"') \
                        .replace('<code class="language-python">', 
                                 '<code class="python">') \
                        .replace('<code class="language-cs">',
                                 '<code class="csharp">')
            
            if item["title"] == "Example Applications":
                with open(destination_folder / f'99 {item["title"].strip()}.html', "w", encoding="utf-8") as html_file:
                    html_file.write(content)
            else: 
                with open(destination_folder / f'{i:02} {item["title"].strip()}.html', "w", encoding="utf-8") as html_file:
                    html_file.write(content)
                    i += 1
                    
        for item in dataset["documentation"]:
            content = item["content"] \
                        .replace('<div class="qc-embed-frame"><div class="qc-embed-dummy"></div><div class="qc-embed-element"><iframe class="qc-embed-backtest"',
                                              '<div class="qc-embed-frame python" style="display: inline-block; position: relative; width: 100%; min-height: 100px; min-width: 300px;"><div class="qc-embed-dummy" style="padding-top: 56.25%;"></div><div class="qc-embed-element" style="position: absolute; top: 0; bottom: 0; left: 0; right: 0;"><iframe class="qc-embed-backtest"') \
                        .replace('<code class="language-python">', 
                                 '<code class="python">') \
                        .replace('<code class="language-cs">',
                                 '<code class="csharp">')
            
            with open(destination_folder / f'{i:02} {item["title"].strip()}.html', "w", encoding="utf-8") as html_file:
                html_file.write(content)
                i += 1
                
        print(f'Documentation of {dataset["name"]} is generated and inplace!')
        count += 1