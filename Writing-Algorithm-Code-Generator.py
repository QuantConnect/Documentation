import json
from urllib.request import urlopen

source = {"CSharp": "http://cdn.quantconnect.com.s3.us-east-1.amazonaws.com/terminal/cache/api/csharp_tree.json",
          "Python": "http://cdn.quantconnect.com.s3.us-east-1.amazonaws.com/terminal/cache/api/python_tree.json"}

dir = {"Adding Data": "01 Adding Data",
       "Algorithm Framework": "02 Algorithm Framework",
       "Charting": "03 Charting",
       "Consolidating Data": "04 Consolidating Data",
       "Handling Data": "05 Handling Data",
       "Historical Data": "06 Historical Data",
       "Indicators": "07 Indicators",
       "Live Trading": "08 Live Trading",
       "Logging": "09 Logging",
       "MachineLearning": "10 Machine Learning",
       "Modeling": "11 Modeling",
       "Parameter and Optimization": "12 Parameter and Optimization",
       "Scheduled Events": "13 Scheduled Events",
       "Securities and Portfolio": "14 Securities and Portfolio",
       "Trading and Orders": "15 Trading and Orders",
       "Universes": "16 Universes"}

for json_file in source.values():
    doc = json.load(urlopen(source))

    algo_methods = json_file["tree"]["core"]["data"][0]
    
    
    
    keys = json_file["keys"]
    
    