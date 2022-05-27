from urllib.request import urlopen

destination = "Resources/enumerations"
enum_objects = [
    "https://raw.githubusercontent.com/QuantConnect/Lean/master/Common/Global.cs"
]

quotation = '\"'

def TableCreation(raw, namespace=""):
    object_ = ""
    active = False
    first_description = ""
    description = ""
    code = ""
    enum = ""

    current_object = {}     # dict is already ordered dict

    for i, line in enumerate(raw):
        if "namespace" in line:
            namespace = line.split("namespace ")[-1].strip()
            continue
        
        if "public enum" in line:
            object_ = line.split("public enum")[-1].strip()
            active = True
            continue
            
        if line.strip() != "" \
        and "{" not in line \
        and "}" not in line \
        and "///" not in line \
        and "[" not in line \
        and active:
            if "=" in line:
                item = line.split(" = ")
                enum = item[0].strip()
                code = int(item[-1].split(",")[0])
                current_object[enum] = {"code": code, "description": description}
            
            else:
                enum = line.split(",")[0].strip()
                code = None
                current_object[enum] = {"code": code, "description": description}
            
        if "///" in line:
            description += line.split("///")[-1]\
                .replace("<remarks>", "").replace("</remarks>", "")\
                .replace("<summary>", "").replace("</summary>", "").strip()
                
            if "remarks" not in line and "summary" not in line:
                description += " "
            
            if not active:
                first_description = description
            continue
                
        else:
            description = ""
                
        if "}" in line and active:
            active = False
            TableCreation(raw[i:], namespace)
            break
            
    if not current_object: return

    html = f'''<p>The following table describes the <code>{object_}</code> class selector methods:</p>

<table class="qc-table table">
<thead>
    <tr>
        <th colspan="3">{namespace}.{object_} - {first_description.replace("<see cref=" + quotation, "<code>").replace(quotation + "/>", "</code>")}</th>
    </tr>
    <tr>
        <th style="width: 5%;">Enum</th>
        <th style="width: 25%;">Value</th>
        <th style="width: 70%;">Description</th>
    </tr>
</thead>
<tbody>
'''

    n = 0
    exist_n = [x["code"] for x in current_object.values()]
    
    for enum, content in current_object.items():
        if content["code"] is None:
            while n in exist_n:
                n += 1
            
            current_object[enum]["code"] = n
            n += 1

    for enum, content in sorted(current_object.items(), key=lambda x: int(x[1]["code"])):
        html += f'''    <tr>
        <td>{content["code"]}</td>
        <td>{enum}</td>
        <td>{content["description"].replace("<see cref=" + quotation, "<code>").replace(quotation + "/>", "</code>")}</td>
    </tr>
'''

    html += """</tbody>
</table>"""

    with open(f"{destination}/{object_.lower()}.html", "w", encoding="utf-8") as file:
        file.write(html)

for url in enum_objects:
    raw = urlopen(url).read().decode("utf-8").split('\n')
    html = TableCreation(raw)