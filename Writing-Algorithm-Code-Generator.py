import json
import pathlib
from urllib.request import urlopen

source = {"CSharp": "http://cdn.quantconnect.com.s3.us-east-1.amazonaws.com/terminal/cache/api/csharp_tree.json",
          "Python": "http://cdn.quantconnect.com.s3.us-east-1.amazonaws.com/terminal/cache/api/python_tree.json"}

base = "02 Writing Algorithms/04 API Reference/"
dir = {"Adding Data": "01 Adding Data/",
       "Algorithm Framework": "02 Algorithm Framework/",
       "Charting": "03 Charting/",
       "Consolidating Data": "04 Consolidating Data/",
       "Handling Data": "05 Handling Data/",
       "Historical Data": "06 Historical Data/",
       "Indicators": "07 Indicators/",
       "Live Trading": "08 Live Trading/",
       "Logging": "09 Logging/",
       "MachineLearning": "10 Machine Learning/",
       "Modeling": "11 Modeling/",
       "Parameter and Optimization": "12 Parameter and Optimization/",
       "Scheduled Events": "13 Scheduled Events/",
       "Securities and Portfolio": "14 Securities and Portfolio/",
       "Trading and Orders": "15 Trading and Orders/",
       "Universes": "16 Universes/"}

for json_file in source.values():
    doc = json.load(urlopen(source))

    algo_methods = json_file["tree"]["core"]["data"][0]["children"]
    i = 1
    
    for method in algo_methods:
        if "DocumentAttribute" in method:
            j = 1
            
            for child in method["children"]:
                code = f'/{method["text"]}/{child["text"]}'
                k = 1
                
                if "concentrate" in child:
                    # Create path if not exist
                    destination_folder = pathlib.Path(base + dir[method["DocumentAttribute"]] + f'{i:02} {method["text"]}/' + f'{j:02} {child["text"]}')
                    destination_folder.mkdir(parents=True, exist_ok=True)

                    if "Description" in child:
                        with open(destination_folder / "{k:02} Description.html", "w", encoding="utf-8") as html_file:
                            html_file.write(f'<p>{child["Description"]}</p>')
                        
                        k += 1
                        
                    with open(destination_folder / "{k:02} Description.html", "w", encoding="utf-8") as html_file:
                        if "Parameters" in child:
                            ## Table
                            html_file.write(f'<p>{child["Description"]}</p>')
                        
                        else:
                            html_file.write(f'{child["text"]} request takes no argument.')
                        
                        k += 1
                        
                    with open(destination_folder / "{k:02} Description.html", "w", encoding="utf-8") as html_file:
                        ## Table "ReturnValue"
                        html_file.write(f'<p>{child["Description"]}</p>')
                        
                        k += 1
                
                    j += 1
                
            i += 1
    
    keys = json_file["keys"]
    
    