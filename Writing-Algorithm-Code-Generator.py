import os
import pathlib
from urllib.request import urlopen

source = "http://cdn.quantconnect.com.s3.us-east-1.amazonaws.com/terminal/cache/api/csharp_tree.json"

base = "02 Writing Algorithms/04 API Reference/"
dir_ = ["Adding Data", "Algorithm Framework", "Charting", "Consolidating Data",
       "Handling Data", "Historical Data", "Indicators", "Live Trading", "Logging",
       "MachineLearning", "Modeling", "Parameter and Optimization", "Scheduled Events",
       "Securities and Portfolio", "Trading and Orders", "Universes"]

path_ = pathlib.Path(base)
path_.mkdir(parents=True, exist_ok=True)

with open(path_ / "01 All Available Methods.html", "w", encoding="utf-8") as html_file:
    html_file.write('''<h3><code>QCAlgorithm</code> class subclasses/methods</h3><hr class="solid">
<div class="tab">
<button class="tablinks" onclick="openTab(event, 'All')">All</button>''')
    
    for topic in dir_:
        html_file.write(f'''<button class="tablinks" onclick="openTab(event, '{topic}')">{topic}</button>''')
    
    html_file.write('''</div>
<div id="All" class="tabcontent">
<table cellspacing="0" cellpadding="0">
<tbody>
</tbody></table>
</div>''')

    for topic in dir_:    
        html_file.write('''</div>
<div id="{topic}" class="tabcontent">
<table cellspacing="0" cellpadding="0">
<tbody>
</tbody></table>
</div>''')
        
with open(path_ / "02 Public Members.html", "w", encoding="utf-8") as html_file:
    html_file.write("<p>Below shows all availble method members:</p><br/>")
    
def Table(input_, previous_name, type_map, j):
    if "DocumentationAttributes" not in input_ or not "DocumentationAttributes":
        if "concentrate" in input_:
            for item in input_["concentrate"]:
                previous_name, j = Table(item, previous_name, type_map, j)
                
        elif "children" in input_:
            for item in input_["children"]:
                previous_name, j = Table(item, previous_name, type_map, j)
                
        return previous_name, j
    
    doc_attr = [x["tag"] for x in input_["DocumentationAttributes"]]
    name = input_["Name"] if "Name" in input_ else input_["ShortType"]
    
    for tag in doc_attr:
        args = {}
            
        if "Parameters" in input_:
            params = input_["Parameters"]
            for item in params:
                if "GenericParameters" not in item:
                    args[item["Name"]] = type_map[str(item["typeId"])]
                    
                else:
                    args[item["GenericParameters"][-1]["Name"]] = type_map[str(item["GenericParameters"][-1]["typeId"])]
            
        call = name + "(" + ", ".join([str(value) + " " + str(key) for key, value in args.items()]).replace("/", "_") + ")"
        
        if previous_name != name:
            with open(path_ / f'02 Public Members.html', "r", encoding="utf-8") as fin, open(path_ / f'02 Public Members_.html', "w", encoding="utf-8") as fout:
                for line in fin.readlines():
                    fout.write(line)
            
            k = 1
                
            with open(path_ / f'02 Public Members_.html', "r", encoding="utf-8") as fin, open(path_ / f'02 Public Members.html', "w", encoding="utf-8") as html_file:
                for line in fin.readlines():
                    if line == '<font color="#cdcdcd" size="1px">1/1</font>':
                        line = line.replace(">1/1<", f">{k}/{j}<")
                        
                    html_file.write(line)
                    
                    k += 1
                
                write_up, description = Box(input_, type_map)
                html_file.write(f'''<div class="container" style="border: 1px solid #ccc; margin-bottom: 25px">
{write_up}
</div>''')
        
            j = 1
            
        else:
            with open(path_ / f'02 Public Members.html', "rb+") as html_file:
                html_file.seek(-6, os.SEEK_END)
                html_file.truncate()
            
            with open(path_ / f'02 Public Members.html', "a", encoding="utf-8") as html_file:
                write_up, description = Box(input_, type_map)
                html_file.write(f'''{write_up}
</div>''')
                
        j += 1
        
        with open(path_ / f'01 All Available Methods.html', "r", encoding="utf-8") as fin, open(path_ / f'01 All Available Methods_.html', "w", encoding="utf-8") as fout:
            for line in fin.readlines():
                fout.write(line)
        
        with open(path_ / f'01 All Available Methods_.html', "r", encoding="utf-8") as fin, open(path_ / f'01 All Available Methods.html', "w", encoding="utf-8") as html_file:
            active = False
            
            for line in fin.readlines():
                if line == '<div id="All" class="tabcontent">' or line == f'<div id="{tag}" class="tabcontent">':
                    active = True
                
                if active and line == '</tbody></table>':
                    html_file.write(f'''<tr>
<td width="20%"><a href="#{call.replace(" ", "-")}"><i class="fa fa-link"></i>{call}</a></td>
<td>{description}</td>
</tr>
</tbody></table>''')
                    active = False
                    
                else:
                    html_file.write(line)

    return name, j


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
        
    call = input_["Name"] + "(" + ", ".join([str(value["Type"]) + " " + str(key) for key, value in args.items()]).replace("/", "_") + ")"
    
    params = ""
    if args:
        params += """<table cellspacing="0" cellpadding="0" style="border: none">
<thead>
<tr>
  <th width="80px" align="left">Type</th>
  <th width="100px" align="left">Name</th>
  <th align="left">Description</th>
</tr>
</thead>
<tbody>"""

        for name, prop in args.items():
            description = prop["Description"]
            
            start = description.find("<")
            while start != -1:
                end = description.find(">", start) + 1
                substring = description[start:end]
                new_substring = ""
                start2 = substring.find('"')
                
                if start2 != -1:
                    new_substring = substring[start2:substring.find('"', start2 + 1)]
                    new_substring = '<code>' + new_substring.split('(')[0].split(".")[-1].split('"')[0] + '</code>'
                
                if "seealso" in substring:
                    new_substring = "\nSee also: " + new_substring + ".\n"
                
                description = description.replace(substring, new_substring)
                start = description.find("<", end)
            
            params += f'<tr><td><code>{prop["Type"]}</code></td><td>{name}</td><td>{description.replace("</value>", "")}</td></tr>'
            
        params += "</tbody></table>"
        
    else:
        params += "<p>This method requires no argument input.</p>"
    
    ret = ""
    
    if "ReturnValue" in input_:
        if "Name" in input_["ReturnValue"]:
            ret_ = input_["ReturnValue"]["Name"]
            
        elif "ShortType" in input_["ReturnValue"]:
            ret_ = input_["ReturnValue"]["ShortType"]\
            
        elif "Type" in input_["ReturnValue"]:
            ret_ = input_["ReturnValue"]["Type"].split(".")[-1]
            
        else:
            ret_ = type_map[str(input_["ReturnValue"]["typeId"])]
            
        ret += f'<code>{ret_}</code>'
            
        if "Description" in input_["ReturnValue"]:
            ret += f' - {input_["ReturnValue"]["Description"]}'
            
    else:
        ret += "This method provides no return."
        
    if "Description" in input_:
        slash = r"\'"
        description = input_["Description"].replace(f"{slash}", "")
        
        start = description.find("<")
        while start != -1:
            end = description.find(">", start) + 1
            substring = description[start:end]
            new_substring = ""
            start2 = substring.find('"')
            
            if start2 != -1:
                new_substring = substring[start2:substring.find('"', start2 + 1)]
                new_substring = '<code>' + new_substring.split('(')[0].split(".")[-1].split('"')[0] + '</code>'
            
            description = description.replace(substring, new_substring)
            start = description.find("<", end)
    
        description = description.replace("</value>", "")
        
    else: 
        description = ""
    
    this_ = "&emsp;(" + "&emsp;"
    head_ = '<font color="#cdcdcd">' + ret_ + "</font> QuantConnect.Algorithm.QCAlgorithm." + input_["Name"] + this_
    count_ = ret_ + " QuantConnect.Algorithm.QCAlgorithm." + input_["Name"]
    next_ = ",\n" + " " * (len(count_) + 2) + "&emsp;"
    
    max_ = 0
    for value in args.values():
        type_ = str(value["Type"])
        
        if len(type_) > max_:
            max_ = len(type_)
        
    call_ = head_ + \
        next_.join(["<code>" + str(value["Type"]) + "</code>" + " " * (max_ + 2 - len(str(value["Type"]))) + str(key) for key, value in args.items()]) + \
        "\n" + " " * len(count_) + "&emsp;" + ")"
    
    write_up = f"""<div class="{call.replace(" ", "-")}-head" style="background-color: #f0fff; padding: 10px; border: 1px solid #ccc; border-radius: 3px">
<h3><a id={call.replace(" ", "-")}><b>{input_["Name"] + "()"}</b></a>    
<font color="#cdcdcd" size="2px">1/1</font>
</h3>
<pre>
{call_}
</pre>
</div>
<div class="{call.replace(" ", "-")}-content" style="background-color: #fffff; padding: 10px; border: 1px solid #ccc; border-radius: 3px">
<p>{description}</p>
<h4>Parameters</h4>
{params}

<h4>Returns</h4>
<p>
{ret}
</p>
</div>"""

    return write_up, description


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
    j = 1
    
    for item in branch:
        previous_name, j = Table(item, previous_name, type_map, j)

os.remove(path_ / f'01 All Available Methods_.html')
os.remove(path_ / f'02 Public Members_.html')