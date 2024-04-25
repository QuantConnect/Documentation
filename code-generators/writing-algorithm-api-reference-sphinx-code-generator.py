import copy
import json
from pathlib import Path
import re
import shutil
from urllib.request import urlopen

LEAN = "https://github.com/QuantConnect/Lean/blob/master"
RAW_LEAN = "https://raw.githubusercontent.com/QuantConnect/Lean/master"
LEAN_SERVICE = "https://www.quantconnect.com/services/inspector?language=python&type=T:%s"

WRITE_PATH = Path("08 Drafts/98 API Reference")   #"03 Writing Algorithms/98 API Reference/"
DOCS_SECTION = {
    "QCAlgorithm API": "QuantConnect.Algorithm.QCAlgorithm"
}
MAX_RECURSION = 1
XML_REGEX_PATTERNS = [r'<see cref="(.*?)"(?:\s*/)?>', r'typeparam name="(.*?)"(?:\s*/)?']
STYLE = '''
<style>
.code-source        { color: grey; float: right }
.object-type        { font-style: italic; }
.arg-name           { font-weight: bold; }
.subsection-header  { font-weight: bold; background-color: lightgray; }
.subsection-content { margin-left: 10px; }
</style>
'''

DOCS_ATTR = {}
EXTRAS = {}
DONE = []
SOURCE_CODES = {}

def render_docs():
    if WRITE_PATH.exists():
        shutil.rmtree(WRITE_PATH)
    WRITE_PATH.mkdir(parents=True, exist_ok=True)

    for i, (h3, type_json_url) in enumerate(DOCS_SECTION.items()):
        _render_section_docs(i, type_json_url, write=False)
        DONE.append(h3.strip().lower())
    i += 1
    
    for j, (tag, html_list) in enumerate(DOCS_ATTR.items()):
        with open(WRITE_PATH/ f"{i+j+1:02} {tag}.html", "w+", encoding="utf-8") as file:
            file.write(f"<h4 id=\"{tag}\">{tag}</h4>")
            file.write('\n'.join(html_list))
            file.write(STYLE)
    i += j + 1
        
    for x in list(EXTRAS.keys()):
        if x.strip().lower() in DONE:
            del EXTRAS[x]
    
    iters = 0
    while len(EXTRAS) > 0 and iters < MAX_RECURSION:
        extra_copy = copy.deepcopy(EXTRAS)
        EXTRAS.clear()
        
        for h3, type_json_url in sorted(extra_copy.items(), key=lambda x: x[0]):
            _render_section_docs(i, type_json_url, write=True)
            DONE.append(h3.strip().lower())
        
        for x in list(EXTRAS.keys()):
            if x.strip().lower() in DONE:
                del EXTRAS[x]
                
        iters += 1

def _render_section_docs(i, type_json_url, write=False):
    content = json.loads(urlopen(LEAN_SERVICE % type_json_url).read())
    
    # Not a type, do not render
    if "type-name" not in content:
        return
    
    type_heading_html = _render_type_heading(content["type-name"], content["description"], content["full-type-name"])
    methods = _render_methods(content["methods"])
    properties = _render_properties(content["properties"])
    fields = _render_fields(content["fields"])
    
    if write:
        with open(WRITE_PATH / f"{i+1:02} Type.html", 'a', encoding="utf-8") as file:
            html = f'''{type_heading_html}
<div class=\"subsection-content\">
{methods}
</div>
<div class=\"subsection-content\">
{properties}
</div>
<div class=\"subsection-content\">
{fields}
</div>'''
            file.write(html)
            file.write(STYLE)
    else:
        with open(WRITE_PATH / f"{i+1:02} Introduction.html", 'w+', encoding="utf-8") as file:
            file.write(type_heading_html)
            file.write(STYLE)

def _render_type_heading(type_name, type_description, type_full_name):
    repo_location = '/'.join(type_full_name.split(".")[1:])
    return f'''<h4 id=\"{type_name}\">{type_name}</h4>
<div class=\"code-snippet\"><pre><span class=\"object-type\">class</span> {type_full_name}()<a class=\"code-source\" href=\"{LEAN}/{repo_location}.cs\">[source]</a>
</pre></div>
<p>{extract_xml_content(type_description)}</p>
'''

def _render_types(type_, type_list):
    types = []
    for type in sorted(type_list, key=lambda x: x[f"{type_}-name"]):
        type_html = eval(f"_render_{type_}(type)")
        types.append(type_html)
    return '\n'.join(types)

def _render_type(type_, type_dict, type_ret=None, line_arg="", params=""):
    doc_attr = type_dict["documentation-attributes"][0] if "documentation-attributes" in type_dict and len(type_dict["documentation-attributes"]) > 0 else None
    line = doc_attr["line"] if doc_attr else None
    source_url = f'{doc_attr["fileName"]}#L{line}' if doc_attr else None
    
    if not type_ret:
        type_ret = f"{type_}-full-type-name"
    type_name = type_dict[type_ret] \
        if type_dict[type_ret] and type_dict[type_ret].split('.')[0] == "QuantConnect" \
        else type_dict[type_ret.replace("full", "short")]
    type_return = eval(f'_get_{type_}_return(type_name, type_dict["{type_}-description"], source_url, line)')
    type_description = extract_xml_content(type_dict[f"{type_}-description"])
    source_html = f"<a class=\"code-source\" href=\"{LEAN}/{source_url}\">[source]</a>" if source_url else ""
    
    type_html = f'''<div class=\"code-snippet\"><pre>{"" if type_ == "method" else '<span class="object-type">' + type_ + "</span> "}{type_dict[f"{type_}-name"]}{line_arg}{source_html}</pre></div>
<div class=\"subsection-content\">
<p>{type_description}</p>
{params}
{type_return}
</div>
'''
    if doc_attr:
        if doc_attr["tag"] not in DOCS_ATTR:
            DOCS_ATTR[doc_attr["tag"]] = []
        DOCS_ATTR[doc_attr["tag"]].append(type_html)

    return type_html

def _render_methods(method_list):
    return _render_types("method", method_list)

def _render_method(method_dict):
    line_arguments = ""
    if "method-arguments" in method_dict:
        line_arguments = ", ".join(f'{arg["argument-name"]}={arg["argument-default"]}' if arg["argument-optional"] else arg["argument-name"]
                               for arg in method_dict["method-arguments"])
        line_arguments = f"<span class=\"object-type\">{line_arguments}</span>"

    doc_attr = method_dict["documentation-attributes"][0] if "documentation-attributes" in method_dict and len(method_dict["documentation-attributes"]) > 0 else None
    line = doc_attr["line"] if doc_attr else None
    source_url = f'{doc_attr["fileName"]}#L{line}' if doc_attr else None
    method_params = _get_params(method_dict["method-arguments"], source_url, line)
    
    return _render_type("method", method_dict, "method-return-type-full-name", f"({line_arguments})", method_params)

def _get_params(args, source_url, line_num):
    if len(args) == 0:
        return ""

    arg_htmls = []
    for i, arg in enumerate(args):
        arg_type_name_selected = arg["argument-type-full-name"] \
            if arg["argument-type-full-name"] and arg["argument-type-full-name"].split('.')[0] == "QuantConnect" else arg["argument-type-short-name"]
        arg_type = _get_hyperlinked_type(arg_type_name_selected)
        argument_description = _get_param_description(source_url, line_num, i) if source_url else None
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

def _get_return(type_, return_type_name, description, source_url, line_num):
    return_description = _get_method_return_description(source_url, line_num) \
        if source_url and type_ == "method" else extract_xml_content(description) \
        if type_ != "method" else ""
    description_html = ""
    if return_description:
        description_html = f'''<div class=\"subsection-header\">Returns:</div>
<p class=\"subsection-content\">{return_description}</p>'''

    return_type = _get_hyperlinked_type(return_type_name)
    
    return f'''{description_html}
<div class=\"subsection-header\">Return type:</div>
<p class=\"subsection-content\">{return_type}</p>'''

def _get_method_return(return_type_name, description, source_url, line_num):
    if return_type_name == "void":
        return ""
    return _get_return("method", return_type_name, description, source_url, line_num)

def _get_method_return_description(source_url, line_num):
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

def _get_hyperlinked_type(type_name):
    _type = _type_conversion(type_name)
    split_type_name = _type.split('.')
    short_name = split_type_name[-1].replace("[]", "")
    
    if split_type_name[0] == "QuantConnect":
        if short_name[0] == "I" and short_name[1].isupper():
            return _type
        
        if split_type_name[1] == "Indicators":
            return f"<a href=\"/docs/v2/writing-algorithms/indicators/supported-indicators/{title_to_dash_linked_lower_case(short_name)}\">{_type}</a>"
        elif short_name == "CandlestickPatterns":
            return f"<a href=\"/docs/v2/writing-algorithms/indicators/supported-indicators/candlestick-patterns\">{_type}</a>"
        
        EXTRAS[short_name] = type_name
        return f"<a href=\"#{short_name}\">{_type}</a>"
    
    return _type
                
def _render_properties(property_list):
    return _render_types("property", property_list)

def _render_property(property_dict):
    return _render_type("property", property_dict)

def _get_property_return(return_type_name, description, source_url, line_num):
    return _get_return("property", return_type_name, description, source_url, line_num)
                
def _render_fields(field_list):
    return _render_types("field", field_list)

def _render_field(field_dict):
    return _render_type("field", field_dict)

def _get_field_return(return_type_name, description, source_url, line_num):
    return _get_return("field", return_type_name, description, source_url, line_num)

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

def extract_xml_content(xml_string):
    output = xml_string
    
    for pattern in XML_REGEX_PATTERNS:
        content = re.search(pattern, xml_string)
        if content:
            output = output.replace(content.group(0), content.group(1))
    
    return output.replace('<', '&lt;').replace('>', '&gt;')

def title_to_dash_linked_lower_case(title):
    lower_case = ""
    for i, char in enumerate(title):
        if i > 0 and char.isupper():
            lower_case += "-"
        lower_case += char.lower()
    return lower_case

    
if __name__ == '__main__':
    render_docs()