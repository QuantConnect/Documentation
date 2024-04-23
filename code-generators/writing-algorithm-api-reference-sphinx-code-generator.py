import json
from pathlib import Path
import re
from urllib.request import urlopen

LEAN = "https://github.com/QuantConnect/Lean/blob/master"
RAW_LEAN = "https://raw.githubusercontent.com/QuantConnect/Lean/master"
TYPE_JSON = "https://www.quantconnect.com/services/inspector?type=T:QuantConnect.Algorithm.QCAlgorithm&language=python"
SOURCE_FILE = "Algorithm/QCAlgorithm.cs"

WRITE_PATH = Path("08 Drafts/98 API Reference")   #"03 Writing Algorithms/98 API Reference/"
WRITE_PATH.mkdir(parents=True, exist_ok=True)
WRITE_FILE = WRITE_PATH / "01 QCAlgorithm API.html"

SOURCE_CODES = {}
XML_REGEX_PATTERNS = [r'<see cref="(.*?)"(?:\s*/)?>', r'typeparam name="(.*?)"(?:\s*/)?']

def render_qcalgorithm_api():
    content = json.loads(urlopen(TYPE_JSON).read())
    
    type_heading_html = _render_type_heading(f'{content["type-name"]} API', content["description"], content["full-type-name"])
    methods = _render_methods(content["methods"])
    properties = _render_properties(content["properties"])
    
    return f'''{type_heading_html}
<div class=\"subsection-content\">
{methods}
</div>
<div class=\"subsection-content\">
{properties}
</div>'''

def _render_type_heading(type_name, type_description, type_full_name, type_extend_description=""):
    return f'''<h3 class=\"type-name\">{type_name}</h3>
<p>{extract_xml_content(type_description)}</p>
<div class=\"code-snippet\"><pre><span class=\"object-type\">class</span> {type_full_name}()<a class=\"code-source\" href=\"{LEAN}/{SOURCE_FILE}\">[source]</a>
</pre></div>
{type_extend_description}'''

def _render_methods(method_list):
    methods = []
    for method in sorted(method_list, key=lambda x: x["method-name"]):
        method_html = _render_method(method)
        methods.append(method_html)
    return '\n'.join(methods)

def _render_method(method_dict):
    line_arguments = ""
    if "method-arguments" in method_dict:
        line_arguments = ", ".join(f'{arg["argument-name"]}={arg["argument-default"]}' if arg["argument-optional"] else arg["argument-name"]
                               for arg in method_dict["method-arguments"])

    # Do not render the ones without documentation attributes
    if len(method_dict["documentation-attributes"]) == 0:
        return ""

    doc_attr = method_dict["documentation-attributes"][0]
    source_url = f'{doc_attr["fileName"]}#L{doc_attr["line"]}'
    method_params = _get_params(method_dict["method-arguments"], source_url, doc_attr["line"])
    method_return = _get_return(method_dict["method-return-type-short-name"], source_url, doc_attr["line"])
    method_description = extract_xml_content(method_dict["method-description"])

    return f'''<div class=\"code-snippet\"><pre>{method_dict["method-name"]}(<span class=\"object-type\">{line_arguments}</span>)<a class=\"code-source\" href=\"{LEAN}/{source_url}\">[source]</a></pre></div>
<div class=\"subsection-content\">
<p>{method_description}</p>
{method_params}
{method_return}
</div>
'''

def _get_params(args, source_url, line_num):
    if len(args) == 0:
        return ""

    arg_htmls = []
    for i, arg in enumerate(args):
        arg_type = _type_conversion(arg["argument-type-short-name"])
        argument_description = _get_param_description(source_url, line_num, i)
        arg_html = f'''    <li>
    <span class=\"arg-name\">{arg["argument-name"]}</span> (<span class=\"object-type\">{arg_type}{", optional" if arg["argument-optional"] else ""}</span>){" &mdash; " + argument_description if argument_description else ""}
    </li>'''
        arg_htmls.append(arg_html)
        
    args_html = '\n'.join(arg_htmls)
    
    return f'''<div class=\"subsection-header\">Parameters:</div>
<ul class=\"subsection-content\">
{args_html}
</ul>'''

def _get_param_description(source_url, line_num, arg_num):
    key = source_url.split('#')[0]
    if key not in SOURCE_CODES:
        raw = urlopen(f"{RAW_LEAN}/{source_url}").readlines()
        SOURCE_CODES[key] = raw
    else:
        raw = SOURCE_CODES[key]
        
    substr = '\n'.join(x.decode('utf-8') for x in raw[:line_num]).split("<summary>")[-1]
    description_char = substr.find("<param")
    if description_char == -1:
        return None
    
    while arg_num > 0:
        description_char = substr[description_char+6:].find("<param")
        substr = substr[description_char:]
        arg_num -= 1
    
    description = ' '.join(x.replace('/', '').strip() for x in substr[description_char+6:].split("</param>")[0].split("\">")[-1].split('\n'))
    return extract_xml_content(description)

def _get_return(return_type_name, source_url, line_num):
    if return_type_name == "void":
        return ""
    
    return_type = _type_conversion(return_type_name)
    description_html = ""
    return_description = _get_return_description(source_url, line_num)
    if return_description:
        description_html = f'''<div class=\"subsection-header\">Returns:</div>
<p class=\"subsection-content\">{return_description}</p>'''

    return f'''{description_html}
<div class=\"subsection-header\">Return type:</div>
<p class=\"subsection-content\">{return_type}</p>'''

def _get_return_description(source_url, line_num):
    key = source_url.split('#')[0]
    if key not in SOURCE_CODES:
        raw = urlopen(f"{RAW_LEAN}/{source_url}").readlines()
        SOURCE_CODES[key] = raw
    else:
        raw = SOURCE_CODES[key]
    
    substr = '\n'.join(x.decode('utf-8') for x in raw[:line_num]).split("<summary>")[-1]
    description_char = substr.find("<returns>")
    if description_char == -1:
        return None
    
    description = ' '.join(x.replace('/', '').strip() for x in substr[description_char+9:].split("</returns>")[0].split('\n'))
    return extract_xml_content(description)

def _type_conversion(type):
    type_replacement = {
        "IEnumerable<KeyValuePair": "Dict",
        "IEnumerable": "List",
        "Nullable": "Optional",
        "Func": "Callable",
        "Array": "List",
        "KeyValuePair": "Dict",
        "DataDictionary": "Dict",
        "Dictionary": "Dict",
        "<": "[",
        ">": "]",
        "String": "str",
        "Decimal": "float",
        "Double": "float",
        "Single": "float",
        "Int8": "int",
        "Int16": "int",
        "Int32": "int",
        "Int64": "int",
        "Uint": "int",
        "Long": "int",
        "Short": "int",
        "Boolean": "bool",
        "DateTime": "datetime",
        "TimeSpan": "timedelta",
        "Void": "None",
    }

    for i, (t, py_t) in enumerate(type_replacement.items()):
        if t in type or t.lower() in type:
            type = type.replace(t, py_t).replace(t.lower(), py_t)
            if i == 0:
                type = type[:-1]
    
    return type
                
def _render_properties(property_list):
    properties = []
    for property in sorted(property_list, key=lambda x: x["property-name"]):
        property_html = _render_property(property)
        properties.append(property_html)
    return '\n'.join(properties)

def _render_property(property_dict):
    # Do not render the ones without documentation attributes
    if len(property_dict["documentation-attributes"]) == 0:
        return ""

    doc_attr = property_dict["documentation-attributes"][0]
    source_url = f'{doc_attr["fileName"]}#L{doc_attr["line"]}'
    property_return = _get_property_return(property_dict["property-short-type-name"], source_url, doc_attr["line"])
    property_description = extract_xml_content(property_dict["property-description"])

    return f'''<div class=\"code-snippet\"><pre><span class=\"object-type\">property</span> {property_dict["property-name"]}<a class=\"code-source\" href=\"{LEAN}/{source_url}\">[source]</a></pre></div>
<p>{property_description}</p>
{property_return}
'''

def _get_property_return(return_type_name, source_url, line_num):
    return_type = _type_conversion(return_type_name)
    return_description = _get_property_return_description(source_url, line_num)
    
    return f'''<div class=\"subsection-header\">Returns:</div>
<p class=\"subsection-content\">{return_description}</p>
<div class=\"subsection-header\">Return type:</div>
<p class=\"subsection-content\">{return_type}</p>'''

def _get_property_return_description(source_url, line_num):
    key = source_url.split('#')[0]
    if key not in SOURCE_CODES:
        raw = urlopen(f"{RAW_LEAN}/{source_url}").readlines()
        SOURCE_CODES[key] = raw
    else:
        raw = SOURCE_CODES[key]

    substr = '\n'.join(x.decode('utf-8') for x in raw[:line_num]).split("<summary>")[-1]
    description = ' '.join(x.replace('/', '').strip() for x in substr.split("</summary>")[0].split('\n'))
    return extract_xml_content(description)

def extract_xml_content(xml_string):
    output = xml_string
    
    for pattern in XML_REGEX_PATTERNS:
        content = re.search(pattern, xml_string)
        if content:
            output = output.replace(content.group(0), content.group(1))
    
    return output.replace('<', '&lt;').replace('>', '&gt;')

    
if __name__ == '__main__':
    qcalgorithm_api_html = render_qcalgorithm_api()

    style = '''
<style>
.code-source        { color: grey; float: right }
.object-type        { font-style: italic; }
.arg-name           { font-weight: bold; }
.subsection-header  { font-weight: bold; background-color: lightgray; }
.subsection-content { margin-left: 10px; }
</style>
'''
    
    with open(WRITE_FILE, 'w', encoding="utf-8") as file:
        file.write(qcalgorithm_api_html)
        file.write(style)