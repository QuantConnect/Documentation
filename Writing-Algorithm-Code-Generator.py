import os
import pathlib
from urllib.request import urlopen

source = "http://cdn.quantconnect.com.s3.us-east-1.amazonaws.com/terminal/cache/api/csharp_tree.json"

base = "02 Writing Algorithms/04 API Reference/"
dir_ = {"Adding Data": "02 Adding Data/",
       "Algorithm Framework": "03 Algorithm Framework/",
       "Charting": "04 Charting/",
       "Consolidating Data": "05 Consolidating Data/",
       "Handling Data": "06 Handling Data/",
       "Historical Data": "07 Historical Data/",
       "Indicators": "08 Indicators/",
       "Live Trading": "09 Live Trading/",
       "Logging": "10 Logging/",
       "MachineLearning": "11 Machine Learning/",
       "Modeling": "12 Modeling/",
       "Parameter and Optimization": "13 Parameter and Optimization/",
       "Scheduled Events": "14 Scheduled Events/",
       "Securities and Portfolio": "15 Securities and Portfolio/",
       "Trading and Orders": "16 Trading and Orders/",
       "Universes": "17 Universes/"}

counter = {key: 0 for key in dir_.keys()}

path_ = pathlib.Path(base + "01 Overview/")
path_.mkdir(parents=True, exist_ok=True)
with open(path_ / "01 Indexes.html", "w", encoding="utf-8") as html_file:
    html_file.write(f'<p>Available API References:<br/></p>')

def Table(input_, previous_name, n, type_map):
    if "DocumentationAttributes" not in input_ or not "DocumentationAttributes":
        if "concentrate" in input_:
            for item in input_["concentrate"]:
                previous_name, n = Table(item, previous_name, n, type_map)
                
        elif "children" in input_:
            for item in input_["children"]:
                previous_name, n = Table(item, previous_name, n, type_map)
                
        return previous_name, n
    
    doc_attr = [x["tag"] for x in input_["DocumentationAttributes"]]
    name = input_["Name"] if "Name" in input_ else input_["ShortType"]
    i = n
    
    for tag in doc_attr:
        args = []
            
        if "Parameters" in input_:
            params = input_["Parameters"]
            for item in params:
                if "GenericParameters" not in item:
                    args.append(item["Name"])
                    
                else:
                    args.append("/".join([x["Name"] for x in item["GenericParameters"]]))
            
        call = name + "(" + ",".join(args).replace("/", "_") + ")"
        
        properties = []
        methods = []
        
        if "ReturnValue" in input_:
            if "Properties" in input_["ReturnValue"]:
                properties.extend(input_["ReturnValue"]["Properties"])
                
            if "Methods" in input_["ReturnValue"]:
                methods.extend(input_["ReturnValue"]["Methods"])
                
        if previous_name != name:
            counter[tag] = counter[tag] + 1
            
            path = pathlib.Path(base + dir_[tag] + f"{counter[tag]:02} " + name)
            path.mkdir(parents=True, exist_ok=True)
            
            i = 4
            
        else:
            path = pathlib.Path(base + dir_[tag] + f"{counter[tag]:02} " + name)
            
            i = n
            
        with open(path_ / "01 Indexes.html", "rb+") as html_file:
            html_file.seek(-4, os.SEEK_END)
            html_file.truncate()
            
        with open(path_ / "01 Indexes.html", "a", encoding="utf-8") as html_file:
            html_file.write(f'<ul><li><a href="#{call}"><i class="fa fa-link"></i>{call}</a></li></ul></p>')
            
        with open(path / f'{i:02} {call}.html', "w", encoding="utf-8") as html_file:
            html_file.write(Box(input_, type_map))
            
    i += 1
    
    return name, i


def Box(input_, type_map):
    args = {}
        
    if "Parameters" in input_:
        params = input_["Parameters"]
        for item in params:
            if "GenericParameters" in item:
                item = item["GenericParameters"][-1]
                
            args[item["Name"]] = {"Description": "/", "Type": "/"}
            
            if "Description" in item:
                args[item["Name"]]["Description"] = item["Description"]
                
                if args[item["Name"]]["Description"][-1] != ".":
                    args[item["Name"]]["Description"] = args[item["Name"]]["Description"] + "."
                
            if "EnumValues" in item:
                args[item["Name"]]["Description"] = args[item["Name"]]["Description"] + f'<br/><i>Options: {item["EnumValues"]}</i>'

            if "ShortType" in item:
                args[item["Name"]]["Type"] = item["ShortType"]
                
            elif "Type" in item:
                args[item["Name"]]["Type"] = item["Type"]
                
            else:
                args[item["Name"]]["Type"] = type_map[str(item["typeId"])]
        
    call = input_["Name"] + "(" + ",".join(list(args.keys())).replace("/", "_") + ")"
    
    params = ""
    if args:
        params += """<table class="table qc-table">
<thead>
<tr>
  <th width="80px">Type</th>
  <th width="100px">Name</th>
  <th>Description</th>
</tr>
</thead>
<tbody>"""

        for name, prop in args.items():
            params += f'<tr><td><code>{prop["Type"]}</code></td><td>{name}</td><td>{prop["Description"]}</td></tr>'
            
        params += "</tbody></table>"
        
    else:
        params += "<p>This method requires no argument input.</p>"
    
    ret = ""
    
    if "ReturnValue" in input_:
        if "Name" in input_["ReturnValue"]:
            ret += f'<code>{input_["ReturnValue"]["Name"]}</code> '
            
        elif "ShortType" in input_["ReturnValue"]:
            ret += f'<code>{input_["ReturnValue"]["ShortType"]}</code> '
            
        elif "Type" in input_["ReturnValue"]:
            ret += f'<code>{input_["ReturnValue"]["Type"].split(".")[-1]}</code> '
            
        else:
            ret += f'<code>{type_map[str(input_["ReturnValue"]["typeId"])]}</code>'
            
        if "Description" in input_["ReturnValue"]:
            ret += f'- {input_["ReturnValue"]["Description"]}'
            
    else:
        ret += "This method provides no return."
        
    slash = '\"'
    
    write_up = f"""<div style="padding: 10px; border: 1px solid #ccc; margin-bottom: 25px; border-radius: 3px">
<a id={call}><code>{call}</code></a>
<p>{input_["Description"].replace("{slash}", "") if "Description" in input_ else ""}</p>
<h4>Parameters</h4>
{params}

<h4>Returns</h4>
{ret}
</div>"""

    return write_up


json_file = urlopen(source).read().decode("utf-8")
json_file = json_file.replace("true", "True").replace("false", "False").replace("null", "None")
doc = eval(json_file)

keys = doc["keys"]

type_map = {}
for key in keys.items():
    if "GenericParameters" in key[1]:
        type_map[key[0]] = key[1]["Type"].split(".")[-1][:-1]
    else:
        type_map[key[0]] = key[1]["ShortType"]

algo_methods = [doc["tree"]["core"]["data"][0]["children"], keys.values()]

for branch in algo_methods:
    previous_name = ""
    i = 4
    
    for item in branch:
        previous_name, i = Table(item, previous_name, i, type_map)