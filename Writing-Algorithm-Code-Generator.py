import os
import pathlib
from urllib.request import urlopen

source = {"01 CSharp/": "http://cdn.quantconnect.com.s3.us-east-1.amazonaws.com/terminal/cache/api/csharp_tree.json",
          "02 Python/": "http://cdn.quantconnect.com.s3.us-east-1.amazonaws.com/terminal/cache/api/python_tree.json"}

base = "02 Writing Algorithms/04 API Reference/"
dir_ = {"Adding Data": "01 Adding Data/",
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

counter = {key: 0 for key in dir_.keys()}

def Table(lang, input_, previous_name, n):
    if "DocumentationAttributes" not in input_ or not "DocumentationAttributes":
        if "concentrate" in input_:
            for item in input_["concentrate"]:
                previous_name, n = Table(lang, item, previous_name, n)
                
        elif "children" in input_:
            for item in input_["children"]:
                previous_name, n = Table(lang, item, previous_name, n)
                
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
            
            path = pathlib.Path(base + lang + dir_[tag] + f"{counter[tag]:02} " + name)
            path.mkdir(parents=True, exist_ok=True)
            
            i = 4
            
            with open(path / '01 Available Overloads.html', "w", encoding="utf-8") as html_file:
                html_file.write(f'<p>The <code>{name}</code> method provides the following overload options:<br/>')
                html_file.write(f'<ul><li><a href="#{name}">{call}</a></li></ul></p>')
            
            with open(path / '02 Available Properties.html', "w", encoding="utf-8") as html_file:
                if properties:
                    html_file.write(f'<p>The <code>{name}</code> method has the following properties:<br/>')
                    
                    for property_ in properties:
                        prop = f'{property_["Name"]}'
                        
                        if "ShortType" in property_:
                            prop += f'<i>({property_["ShortType"]})</i> '
                            
                        elif "Type" in property_:
                            prop += f'<i>({property_["Type"]})</i> '
                            
                        if "Description" in property_:
                            prop += f'<br/>{property_["Description"]}'
                        
                        html_file.write(f'<ul><li>{prop}</li></ul></p>')
                
                else:
                    html_file.write(f'<p>The <code>{name}</code> method has no property.</p>')
            
            with open(path / '03 Available Methods.html', "w", encoding="utf-8") as html_file:
                if methods:
                    write_up = "".join([Box(method, name) for method in methods])
                    
                else:
                    write_up = '</p>No sub-method is available for this method.</p>'
                
                html_file.write(write_up)
            
        else:
            path = pathlib.Path(base + lang + dir_[tag] + f"{counter[tag]:02} " + name)
            
            i = n
            
            with open(path / '01 Available Overloads.html', "rb+") as html_file:
                html_file.seek(-9, os.SEEK_END)
                html_file.truncate()
                
            with open(path / '01 Available Overloads.html', "a", encoding="utf-8") as html_file:
                html_file.write(f'<li><a href="#{name}">{call}</a></li></ul></p>')
                
        with open(path / f'{i:02} {call}.html', "w", encoding="utf-8") as html_file:
            html_file.write(Box(input_, name))
            
    i += 1
    
    return name, i


def Box(input_, name):
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
                args[item["Name"]]["Description"] = args[item["Name"]]["Description"] + f' Options: {item["EnumValues"]}'

            if "ShortType" in item:
                args[item["Name"]]["Type"] = item["ShortType"]
                
            elif "Type" in item:
                args[item["Name"]]["Type"] = item["Type"]
        
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
            params += f'<tr><td>{prop["Type"]}</td><td><code>{name}</code></td><td>{prop["Description"]}</td></tr>'
            
        params += "</tbody></table>"
        
    else:
        params += "<p>This method requires no argument input.</p>"
    
    ret = ""
    
    if "ReturnValue" in input_:
        if "Name" in input_["ReturnValue"]:
            ret += f'<code>{input_["ReturnValue"]["Name"]}</code> '
            
        if "ShortType" in input_["ReturnValue"]:
            ret += f'<i>({input_["ReturnValue"]["ShortType"]})</i> '
            
        elif "Type" in input_["ReturnValue"]:
            ret += f'<i>({input_["ReturnValue"]["Type"]})</i> '
            
        if "Description" in input_["ReturnValue"]:
            ret += f'- {input_["ReturnValue"]["Description"]}'
            
        else:
            ret += "This method provides no return."
            
    else:
        ret += "This method provides no return."
    
    write_up = f"""<div style="padding: 10px; border: 1px solid #ccc; margin-bottom: 25px; border-radius: 3px">
<a id="{name}"><i class="fa fa-link"></i> <code>{call}</code></a>
<p>{input_["Description"] if "Description" in input_ else ""}</p>
<h4>Parameters</h4>
{params}

<h4>Returns</h4>
{ret}
</div>"""

    return write_up


for lang, source in source.items():
    json_file = urlopen(source).read().decode("utf-8")
    json_file = json_file.replace("true", "True").replace("false", "False").replace("null", "None")
    doc = eval(json_file)

    algo_methods = [doc["tree"]["core"]["data"][0]["children"], doc["keys"].values()]
    
    for branch in algo_methods:
        previous_name = ""
        i = 4
        
        for item in branch:
            previous_name, i = Table(lang, item, previous_name, i)