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
<script>
function openTab(evt, category) {
  var i, tabcontent, tablinks;

  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {{
    tabcontent[i].style.display = "none";
  }

  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {{
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }

  document.getElementById(category).style.display = "block";
  evt.currentTarget.className += " active";
}
</script>
<div class="tab">
<button class="tablinks" onclick="openTab(event, 'All')">All</button>''')
    
    for topic in dir_:
        html_file.write(f'''<button class="tablinks" onclick="openTab(event, '{topic}')">{topic}</button>\n''')
    
    html_file.write('''</div>
<div id="All" class="tabcontent">
<table cellspacing="0" cellpadding="0">
<tbody>
</tbody></table>
</div>''')

    for topic in dir_:    
        html_file.write(f'''</div>
<div id="{topic}" class="tabcontent">
<table cellspacing="0" cellpadding="0">
<tbody>
</tbody></table>
</div>''')
        
with open(path_ / "02 Public Members.html", "w", encoding="utf-8") as html_file:
    html_file.write("""<script>
function ShowHide(className) {
    if (this.value == "Show Details ▼") {
        this.value = "Hide Details ▲";
    }
    else {
        this.value = "Show Details ▼";
    }
    
    var x = document.getElementById(className);
    for (i = 0; i < x.length; i++)
    if (x[i].style.display == "none") {
        x[i].style.display = "block";
    }
    else {
        x[i].style.display = "none";
    }
};

function openCategory(category) {
    openTab(event, category);
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}
</script>
<p>Below shows all available method members:</p><br/>\n""")
    
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
            lines = []
            
            with open(path_ / f'02 Public Members.html', "r", encoding="utf-8") as fin:
                for line in fin.readlines():
                    lines.append(line)
            
            with open(path_ / f'02 Public Members.html', "w", encoding="utf-8") as html_file:
                k = 1
                
                for line in lines:
                    if '<font color="#cdcdcd" size="2px">0/0</font>' in line:
                        line = line.replace(">0/0<", f">{k}/{j}<")
                    
                        k += 1
                        
                    html_file.write(line)
                
                write_up, description = Box(input_, doc_attr, type_map, j)
                html_file.write(f'''<div class="container" style="border: 1px solid #ccc; margin-bottom: 25px">
{write_up}
</div>''')
        
            j = 1
            
        else:
            with open(path_ / f'02 Public Members.html', "rb+") as html_file:
                html_file.seek(-6, os.SEEK_END)
                html_file.truncate()
            
            with open(path_ / f'02 Public Members.html', "a", encoding="utf-8") as html_file:
                write_up, description = Box(input_, doc_attr, type_map, j)
                html_file.write(f'''{write_up}
</div>''')
                
            j += 1
        
        lines = []
        
        with open(path_ / f'01 All Available Methods.html', "r", encoding="utf-8") as fin:
            for line in fin.readlines():
                lines.append(line)
                
        with open(path_ / f'01 All Available Methods.html', "w", encoding="utf-8") as html_file:
            active = False
            
            for line in lines:
                if '<div id="All" class="tabcontent">' in line or f'<div id="{tag}" class="tabcontent">' in line:
                    active = True
                
                if active and '</tbody></table>' in line:
                    html_file.write(f'''<tr>
<td width="20%"><a href="#{call.replace(" ", "-")}"><i class="fa fa-link"></i>{call}</a></td>
<td>{description}</td>
</tr>
</tbody></table>''')
                    
                    active = False
                    
                else:
                    html_file.write(line)

    return name, j


def Box(input_, doc_attr, type_map, j):
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
  <th width="80px" align="left"><h5>Type</h5></th>
  <th width="100px" align="left"><h5>Name</h5></th>
  <th align="left"><h5>Description</h5></th>
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
            
            description = description.replace("</value>", "").replace("``1", "&lt;T&gt;")
            params += f'<tr><td><code>{prop["Type"]}</code></td><td>{name}</td><td>{description}</td></tr>'
            
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
            
        if ret_ == "Void":
            ret = f"<code>{ret_}</code> - This method provides no return."
        
        else:
            ret += f'<code>{ret_}</code>'
                
            if "Description" in input_["ReturnValue"]:
                ret += f' - {input_["ReturnValue"]["Description"]}'
            
    else:
        ret = "This method provides no return."
        
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
        
    buttons = [f'<button class="tablinks" onclick="openCategory("{attr_}")">{topic}</button>' for attr_ in doc_attr]
    
    write_up = f"""<div class="{call.replace(" ", "-")}-head" style="background-color: #f0ffff; border: 1px solid #ccc">
{''.join(buttons)}
<h3>
<a id={call.replace(" ", "-")}><b>{input_["Name"] + "()"}</b></a>    
<font color="#cdcdcd" size="2px">0/0</font>
</h3>
<pre>
{call_}
</pre>
</div>
<div class="{call.replace(" ", "-")}-content" style="background-color: #ffffff; border: 1px solid #ccc">
<p>{description}</p>
<button class="details" onclick="ShowHide(visible-{input_["Name"]}-{j})" style="border: none; background-color: transparent; outline: none; color: blue">Show Details ▼</button>
<div class="visible-{input_["Name"]}-{j}" style="display:none;">
<h4><b>Parameters</b></h4>
{params}
<br/>
<h4><b>Returns</b></h4>
<p>
{ret}
</p>
</div>
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