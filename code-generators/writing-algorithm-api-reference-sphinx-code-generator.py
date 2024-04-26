import copy
import json
from pathlib import Path
import re
import shutil
from urllib.request import urlopen

LEAN = "https://github.com/QuantConnect/Lean/blob/master"
RAW_LEAN = "https://raw.githubusercontent.com/QuantConnect/Lean/master"
LEAN_SERVICE = "https://www.quantconnect.com/services/inspector?language=python&type=T:%s"

WRITE_PATH = Path("03 Writing Algorithms/98 API Reference/")
METADATA = WRITE_PATH / "metadata.json"
DOCS_SECTION = {
    "QCAlgorithm API": "QuantConnect.Algorithm.QCAlgorithm"
}
MAX_RECURSION = 1
XML_REGEX_PATTERNS = {
    r'<see cref="(.*?)"(?:\s*/)?>': "<a href=\"#%s\">%s</a>", 
    r'<typeparam name="(.*?)"(?:\s*/)?>': "<i>%s</i>",
    r'<paramref name="(.*?)"(?:\s*/)?>': "<i>%s</i>",
}
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
        if METADATA.exists():
            with open(METADATA, mode='r') as f:
                content = f.read()
        shutil.rmtree(WRITE_PATH)
    WRITE_PATH.mkdir(parents=True, exist_ok=True)
    with open(METADATA, mode='w') as f:
        f.write(content)

    for i, (h3, type_json_url) in enumerate(DOCS_SECTION.items()):
        _render_section_docs(i, type_json_url, write=False)
        DONE.append(h3.strip().lower())
    i += 1
    
    for j, (tag, html_list) in enumerate(sorted(DOCS_ATTR.items(), key=lambda x: x[0])):
        with open(WRITE_PATH/ f"{i+j+1:02} {tag}.html", "w", encoding="utf-8") as file:
            file.write(f"<h4 id=\"{tag}\">{tag}</h4>" + "\n")
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
        
    with open(WRITE_PATH / f"{i+1:02} Types.html", 'a', encoding="utf-8") as file:
        file.write(STYLE)
        
    # clean up non-inclusive jump links
    for path in Path.glob(WRITE_PATH, "*.html"):
        with open(path, 'r', encoding="utf-8") as file:
            content = file.read()\
                .replace(f"<a href=\"#ICollection`1\">ICollection`1</a>", 'list')\
                .replace(f"<a href=\"#IDictionary`2\">IDictionary`2</a>", 'dictionary')\
                .replace(f"<a href=\"#IExtendedDictionary`2\">IExtendedDictionary`2</a>", 'dictionary') \
                .replace(f"an list", "a list").replace(f"an dictionary", "a dictionary")

            pattern = r"<a href=\"#(\w+)\">"
            matches = re.findall(pattern, content)
            
            to_remove = [x for x in matches if x.strip().lower() not in DONE]
            for match in to_remove:
                content = content.replace(f"<a href=\"#{match}\">{match}</a>", match)

        with open(path, 'w', encoding="utf-8") as file:
            file.write(content)

def _render_section_docs(i, type_json_url, write=False):
    content = json.loads(urlopen(LEAN_SERVICE % type_json_url).read())
    
    # Not a type, do not render
    if "type-name" not in content:
        return
    
    type_heading_html = _render_type_heading(content["type-name"], content["base-type-full-name"].split('.')[-1], content["description"], content["full-type-name"])
    methods = _render_methods(content["methods"], 'QuantConnect.Securities' in type_json_url)
    properties = _render_properties(content["properties"])
    fields = _render_fields(content["fields"])
    
    if write:
        with open(WRITE_PATH / f"{i+1:02} Types.html", 'a', encoding="utf-8") as file:
            html = type_heading_html
            for subsection in [methods, properties, fields]:
                if subsection:
                    html += f'''<div class=\"subsection-content\">
{subsection}
</div>
'''
            file.write(html)
    else:
        with open(WRITE_PATH / f"{i+1:02} Introduction.html", 'w', encoding="utf-8") as file:
            file.write(type_heading_html)
            file.write(STYLE)

def _render_type_heading(type_name, type_base_type, type_description, type_full_name):
    base_type = "enum" if type_base_type.lower() == "enum" else "class"
    return f'''<h4 id=\"{type_name}\">{type_name}</h4>
<div class=\"code-snippet\"><span class=\"object-type\">{base_type}</span> <code>{type_full_name}</code><a class=\"code-source\" href=\"https://github.com/search?q=repo%3AQuantConnect%2FLean+{type_name}&type=code\">[source]</a>
</div>
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
    
    type_html = f'''<div class=\"code-snippet\">{"" if type_ == "method" else '<span class="object-type">' + type_ + "</span> "}<code>{type_dict[f"{type_}-name"]}{line_arg}</code>{source_html}</div>
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

def _render_methods(method_list, special_type=False):
    backlist = ['compare_to', 'equals', 'to_string', 'get_hash_code']
    if special_type:
        backlist.extend(['add', 'get', 'remove'])
    types = []
    for name in sorted(set(x["method-name"] for x in method_list)):
        if name in backlist:
            continue
        type_html = _render_method([x for x in method_list if x["method-name"] == name])
        if 'Not meant for external use' in type_html:
            continue
        types.append(type_html)
    return '\n'.join(types)

def _render_method(method_dict_list):
    if len(method_dict_list) == 1:
        method_dicts = method_dict_list
    else:
        arg_names_sets = {}
        for method_dict in sorted(method_dict_list, key=lambda x: len(x["method-arguments"]), reverse=True):  # first sort so it only needs to compare in one-way
            arg_names = set(arg["argument-name"] for arg in method_dict["method-arguments"])
            if len(arg_names_sets) == 0 or all(not arg_names.issubset(set(prev_set)) and arg_names != set(prev_set) for prev_set in list(arg_names_sets.keys())):
                arg_names_sets[tuple(arg_names)] = method_dict
            else:
                for prev_key in list(arg_names_sets.keys()):
                    prev_set = set(prev_key)
                    if arg_names.issubset(prev_set) or arg_names == prev_set:
                        new_arg_dicts = []
                        for a in arg_names_sets[prev_key]["method-arguments"]:
                            new_arg_dict = a
                            for new_a in method_dict["method-arguments"]:
                                if new_a["argument-name"] == a["argument-name"]:
                                    new_arg_dict = _merge_args(a, new_a)
                            #if new_arg_dict == a:
                            #    new_arg_dict["argument-optional"] = True
                            new_arg_dicts.append(new_arg_dict)
                        arg_names_sets[prev_key]["method-arguments"] = new_arg_dicts
        method_dicts = list(arg_names_sets.values())
    
    method_html = ""
    doc_attr = None
    for method_dict in method_dicts:
        line_arguments = ""
        if "method-arguments" in method_dict and method_dict["method-arguments"] and len(method_dict["method-arguments"]) > 0:
            line_arguments = ", ".join(f'{arg["argument-name"]}={arg["argument-default"]}' if arg["argument-optional"] else arg["argument-name"]
                                for arg in method_dict["method-arguments"])
            line_arguments = f"<span class=\"object-type\">{line_arguments}</span>"

        doc_attr = method_dict["documentation-attributes"][0] if "documentation-attributes" in method_dict and len(method_dict["documentation-attributes"]) > 0 else doc_attr
        line = doc_attr["line"] if doc_attr else None
        source_url = f'{doc_attr["fileName"]}#L{line}' if doc_attr else None
        method_params = _get_params(method_dict["method-arguments"], source_url, line)
        method_html += _render_type("method", method_dict, "method-return-type-full-name", f"({line_arguments})", method_params)
    
    return method_html

def _merge_args(old_dict, new_dict):
    if not new_dict or old_dict == new_dict:
        return old_dict
    
    if isinstance(old_dict["argument-type-full-name"], list):
        full_type = old_dict["argument-type-full-name"] + [new_dict["argument-type-full-name"]]
        short_type = old_dict["argument-type-full-name"] + [new_dict["argument-type-full-name"]]
    else:
        full_type = [old_dict["argument-type-full-name"], new_dict["argument-type-full-name"]]
        short_type = [old_dict["argument-type-full-name"], new_dict["argument-type-full-name"]]
    full_type = list(set(full_type))
    short_type = list(set(short_type))
    
    return {
        "argument-name": old_dict["argument-name"],
        "argument-type-full-name": full_type,
        "argument-type-short-name": short_type,
        "argument-optional": old_dict["argument-optional"] and new_dict["argument-optional"],
        "argument-default": old_dict["argument-default"]
    }

def _get_params(args, source_url, line_num):
    if len(args) == 0:
        return ""

    arg_htmls = []
    for i, arg in enumerate(args):
        if isinstance(arg["argument-type-full-name"], list):
            arg_types = []
            for full_type, short_type in zip(arg["argument-type-full-name"], arg["argument-type-short-name"]):
                arg_type_name_selected_ = full_type if full_type else short_type
                arg_type_ = _get_hyperlinked_type(arg_type_name_selected_)
                arg_types.append(arg_type_)
            arg_type = " | ".join(set(arg_types))
        else:
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
        
    substr = '\n'.join(x.decode('utf-8') for x in raw[:line_num+1]).split("<summary>")[-1]
    
    while arg_num >= 0:
        description_char = substr.find("<param")
        if description_char == -1:
            return None
        substr = substr[description_char+6:]
        arg_num -= 1
    
    description = ' '.join(x.replace('/', '').strip() for x in substr.split("</param>")[0].split(">")[-1].split('\n'))
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
    if not type_name:
        return "None"

    _type = _type_conversion(type_name)
    split_type_name = _type.split('.')
    short_name = split_type_name[-1].replace("[]", "")
    
    if split_type_name[0] == "QuantConnect":
        if short_name[0] == "I" and short_name[1].isupper():
            return short_name
        
        if split_type_name[1] == "Indicators":
            if len(split_type_name) > 2:
                return f"<a href=\"/docs/v2/writing-algorithms/indicators/supported-indicators/{title_to_dash_linked_lower_case(short_name)}\">{short_name}</a>"
            return f"<a href=\"/docs/v2/writing-algorithms/indicators/supported-indicators/\">Indicator</a>"
        elif short_name == "CandlestickPatterns":
            return f"<a href=\"/docs/v2/writing-algorithms/indicators/supported-indicators/candlestick-patterns\">{short_name}</a>"
        
        EXTRAS[short_name] = type_name
        return f"<a href=\"#{short_name}\">{short_name}</a>"
    
    return short_name
                
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
    
    for pattern, replacement in XML_REGEX_PATTERNS.items():
        content = re.search(pattern, xml_string)
        if content:
            output = output.replace(content.group(0), replacement % ((content.group(1).split('.')[-1], ) * replacement.count("%s")))
    
    return output

def title_to_dash_linked_lower_case(title):
    lower_case = ""
    for i, char in enumerate(title):
        if i > 0 and char.isupper():
            lower_case += "-"
        lower_case += char.lower()
    return lower_case

    
if __name__ == '__main__':
    render_docs()