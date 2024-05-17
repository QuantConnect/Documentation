import copy
import json
import os
from pathlib import Path
import re
import shutil
from urllib.request import urlopen
from _code_generation_helpers import _type_conversion, extract_xml_content, title_to_dash_linked_lower_case

LEAN = "https://github.com/QuantConnect/Lean/blob/master"
RAW_LEAN = "https://raw.githubusercontent.com/QuantConnect/Lean/master"
LEAN_SERVICE = "https://www.quantconnect.com/services/inspector?language=%s&type=T:%s"
TARGET = "/docs/v2/writing-algorithms/api-reference"

WRITE_PATH = Path("03 Writing Algorithms/98 API Reference/")
RESOURCE = Path("Resources/qcalgorithm-api")
INDICATOR_RESOURCE = Path("Resources/indicators/constructors")
METADATA = WRITE_PATH / "metadata.json"
DOCS_SECTION = {
    "QCAlgorithm API": "QuantConnect.Algorithm.QCAlgorithm",
    "Candlestick Patterns": "QuantConnect.Algorithm.CandlestickPatterns"
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

SUPPORTED_INDICATORS = [''.join(name.split(' ')[1:]) for name in os.listdir("03 Writing Algorithms/28 Indicators/01 Supported Indicators") 
                        if os.path.isdir("03 Writing Algorithms/28 Indicators/01 Supported Indicators/" + name)]
SUPPORTED_CANDLES = [x.group(1) for x in 
                     re.finditer(r"public (\w+) \1\(", urlopen("https://raw.githubusercontent.com/QuantConnect/Lean/master/Algorithm/CandlestickPatterns.cs").read().decode('utf-8'))]

INDICATORS = {title_to_dash_linked_lower_case(candle): f"QuantConnect.Indicators.CandlestickPatterns.{candle}" for candle in SUPPORTED_CANDLES}

def render_docs():
    if WRITE_PATH.exists():
        if METADATA.exists():
            with open(METADATA, mode='r') as f:
                content = f.read()
        shutil.rmtree(WRITE_PATH)
    WRITE_PATH.mkdir(parents=True, exist_ok=True)
    with open(METADATA, mode='w') as f:
        f.write(content)
    
    temp = {}
    if RESOURCE.exists():
        for f in Path.glob(RESOURCE, "_*.html"):
            with open(f, 'r', encoding="utf-8") as file:
                temp[f.stem] = file.read()
        shutil.rmtree(RESOURCE)
    RESOURCE.mkdir(parents=True, exist_ok=True)
    for f, content in temp.items():
        with open(RESOURCE / f"{f}.html", 'w', encoding="utf-8") as file:
            file.write(content)
            
    if INDICATOR_RESOURCE.exists():
        shutil.rmtree(INDICATOR_RESOURCE)
    INDICATOR_RESOURCE.mkdir(parents=True, exist_ok=True)

    for i, (h3, type_json_url) in enumerate(DOCS_SECTION.items()):
        if i == 0:
            write = False
        else:
            write = "partial"
        _render_section_docs(i, h3, type_json_url, write=write)
        DONE.append(h3.strip().lower())
    
    for j, (tag, html_list) in enumerate(sorted(DOCS_ATTR.items(), key=lambda x: x[0])):
        with open(WRITE_PATH/ f"{i+j+1:02} {tag}.php", "a", encoding="utf-8") as file:
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
            _render_section_docs(i, h3, type_json_url, write="full")
            DONE.append(h3.strip().lower())
        
        for x in list(EXTRAS.keys()):
            if x.strip().lower() in DONE:
                del EXTRAS[x]
                
        iters += 1
        
    with open(WRITE_PATH / f"{i+1:02} Types.php", 'a', encoding="utf-8") as file:
        file.write(STYLE)
        
    # clean up non-inclusive jump links
    for dir in [WRITE_PATH, RESOURCE, INDICATOR_RESOURCE]:
        for path in Path.glob(dir, "*.html"):
            with open(path, 'r', encoding="utf-8") as file:
                content = file.read().replace(f"an list", "a list").replace(f"an dictionary", "a dictionary")

                pattern = fr"<a href=\"{TARGET}#(\w+)\">"
                matches = re.findall(pattern, content)
                
                to_remove = [x for x in matches if x.strip().lower() not in DONE]
                for match in to_remove:
                    content = content.replace(f'<a href=\"#{match}\">{match}</a>', match).replace(f'<a href=\"#{match}\">{match}[]</a>', f"{match}[]")

            with open(path, 'w', encoding="utf-8") as file:
                file.write(content)
            
    # handle indicator references
    for html_filename, type_name in sorted(INDICATORS.items(), key=lambda x: x[0]):
        for language in ["python", "csharp"]:
            try:
                content = json.loads(urlopen(LEAN_SERVICE % (language, type_name)).read())
            except Exception as e:
                print(e)
                continue
            if "type-name" not in content:
                print(f"No \"type-name\" in {type_name}")
                continue
        
            type_heading_html = _render_type_heading(content["type-name"], content["base-type-full-name"].split('.')[-1], content["description"], content["full-type-name"])
            methods = _render_methods(content["methods"], language, True)
            properties = _render_properties(content["properties"], language)
            fields = _render_fields(content["fields"], language)
            
            with open(INDICATOR_RESOURCE / f"{html_filename}.html", 'a', encoding="utf-8") as file:
                html = f"<div class=\"{language}\">\n"
                html += type_heading_html
                for subsection in [methods, properties, fields]:
                    if subsection:
                        html += f'''<div class=\"subsection-content\">
{subsection}
</div>
'''
                html += "</div>\n"
                file.write(html)
                file.write(STYLE)
            
def _render_section_docs(i, h3, type_json_url, write=False):
    for lang in ["python", "csharp"]:
        _render_section_docs_by_language(i, h3, type_json_url, lang, write)

def _render_section_docs_by_language(i, h3, type_json_url, language, write=False):
    content = json.loads(urlopen(LEAN_SERVICE % (language, type_json_url)).read())
    
    # Not a type, do not render
    if "type-name" not in content:
        return
    
    type_heading_html = _render_type_heading(content["type-name"], content["base-type-full-name"].split('.')[-1], content["description"], content["full-type-name"])
    methods = _render_methods(content["methods"], language, 'QuantConnect.Securities' in type_json_url)
    properties = _render_properties(content["properties"], language)
    fields = _render_fields(content["fields"], language)
    
    if write:
        if language == "python":
            filename = title_to_dash_linked_lower_case(h3).replace('_', '-')
        else:
            filename = title_to_dash_linked_lower_case(h3)
        filename += ".html"
            
        if write == "full":
            if not (RESOURCE / filename).exists():
                with open(WRITE_PATH / f"{i+1:02} Types.php", 'a', encoding="utf-8") as file:
                    file.write(f"<? include(DOCS_RESOURCES.\"/qcalgorithm-api/{filename}\"); ?>\n")
            
        with open(RESOURCE / filename, 'a', encoding="utf-8") as file:
            html = f"<div class=\"{language}\">\n"
            html += type_heading_html
            for subsection in [methods, properties, fields]:
                if subsection:
                    html += f'''<div class=\"subsection-content\">
{subsection}
</div>
'''
            html += "</div>\n"
            file.write(html)
            
    else:
        path = WRITE_PATH / f"{i+1:02} Introduction.html"
        html = f"<div class=\"{language}\">\n"
        html += type_heading_html
        html += "</div>\n"
        if not path.exists():
            with open(WRITE_PATH / f"{i+1:02} Introduction.html", 'a', encoding="utf-8") as file:
                file.write(html)
                file.write(STYLE)
        else:
            with open(WRITE_PATH / f"{i+1:02} Introduction.html", 'a', encoding="utf-8") as file:
                file.write(html)

def _render_type_heading(type_name, type_base_type, type_description, type_full_name):
    base_type = "enum" if type_base_type.lower() == "enum" else "class"
    return f'''<h4 id=\"{type_name}\">{type_name}</h4>
<div class=\"code-snippet\"><span class=\"object-type\">{base_type}</span> <code>{type_full_name}</code><a class=\"code-source\" href=\"https://github.com/search?q=repo%3AQuantConnect%2FLean+{type_name}&type=code\">[source]</a>
</div>
<p>{extract_xml_content(type_description, XML_REGEX_PATTERNS)}</p>
'''

def _render_types(type_, type_list, language):
    types = []
    for type in sorted(type_list, key=lambda x: x[f"{type_}-name"]):
        type_html = eval(f"_render_{type_}(type, language)")
        types.append(type_html)
    return '\n'.join(types)

def _render_type(type_, type_dict, language, type_ret="short-type-name", line_arg="", params=""):
    doc_attr = type_dict["documentation-attributes"][0] if "documentation-attributes" in type_dict and len(type_dict["documentation-attributes"]) > 0 else None
    line = doc_attr["line"] if doc_attr else None
    source_url = f'{doc_attr["fileName"]}#L{line}' if doc_attr else None
    
    type_name = type_dict[f"{type_}-{type_ret}"]
    if type_name:
        type_return = eval(f'_get_{type_}_return(type_name, type_dict["{type_}-description"], source_url, line, language)')
    else:
        type_return = ""
    type_description = extract_xml_content(type_dict[f"{type_}-description"], XML_REGEX_PATTERNS)
    source_html = f"<a class=\"code-source\" href=\"{LEAN}/{source_url}\">[source]</a>" if source_url else ""
    
    type_html = f'''<div class=\"code-snippet\">{"" if type_ == "method" else '<span class="object-type">' + type_ + "</span> "}<code>{type_dict[f"{type_}-name"]}{line_arg}</code>{source_html}</div>
<div class=\"subsection-content\">
<p>{type_description}</p>
{params}
{type_return}
</div>
'''
    if doc_attr or (type_name and type_name.split('.')[-1] in SUPPORTED_CANDLES):
        if doc_attr:
            if language == "python":
                filename = f'qcalgorithm-{title_to_dash_linked_lower_case(type_dict[f"{type_}-name"].split(".")[-1]).replace("_", "-")}.html'
            else:
                filename = f'qcalgorithm-{title_to_dash_linked_lower_case(type_dict[f"{type_}-name"])}.html'
        
            if doc_attr["tag"] not in DOCS_ATTR:
                DOCS_ATTR[doc_attr["tag"]] = []
            
            if not (RESOURCE / filename).exists():
                DOCS_ATTR[doc_attr["tag"]].append(f"<? include(DOCS_RESOURCES.\"/qcalgorithm-api/{filename}\"); ?>")
                
        else:
            if language == "python":
                filename = f'qcalgorithm-{type_dict[f"{type_}-name"].split(".")[-1].replace("_", "").lower()}.html'
            else:
                filename = f'qcalgorithm-{type_dict[f"{type_}-name"].lower()}.html'
            
        with open(RESOURCE / filename, 'a', encoding="utf-8") as file:
            html = f"<div class=\"{language}\">\n"
            html += type_html
            html += "</div>\n"
            file.write(html)

    return type_html

def _render_methods(method_list, language, special_type=False):
    backlist = ['compare_to', 'equals', 'to_string', 'get_hash_code', 
                'CompareTo', 'Equals', 'ToString', 'GetHashCode']
    if special_type:
        backlist.extend(['add', 'get', 'remove', "Add", "Get", "Remove"])
    types = []
    for (name, return_type) in sorted(set((x["method-name"].strip(), x["method-return-type-short-name"].strip()) for x in method_list)):
        if name in backlist:
            continue
        type_html = _render_method([x for x in method_list if x["method-name"].strip() == name and x["method-return-type-short-name"].strip() == return_type], language)
        if 'Not meant for external use' in type_html:
            continue
        types.append(type_html)
    return '\n'.join(types)

def _render_method(method_dict_list, language):
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
                        arg_names_sets[prev_key]["method-return-type-full-name"] = _merge_return(arg_names_sets[prev_key]["method-return-type-full-name"], method_dict["method-return-type-full-name"])
                        arg_names_sets[prev_key]["method-return-type-short-name"] = _merge_return(arg_names_sets[prev_key]["method-return-type-short-name"], method_dict["method-return-type-short-name"])
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
        method_params = _get_params(method_dict["method-arguments"], source_url, line, language)
        method_html += _render_type("method", method_dict, language, "return-type-full-name", f"({line_arguments})", method_params)
    
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
    
def _merge_return(old_ret, new_ret):
    if not new_ret or new_ret == "void" or ".Void" in new_ret:
        return old_ret
    elif not old_ret or old_ret == "void" or ".Void" in old_ret:
        return new_ret
    
    if isinstance(old_ret, list):
        if new_ret in old_ret:
            return old_ret
        return old_ret + [new_ret]
    if new_ret == old_ret:
        return old_ret
    return [old_ret, new_ret]

def _get_params(args, source_url, line_num, language):
    if len(args) == 0:
        return ""

    arg_htmls = []
    for i, arg in enumerate(args):
        if isinstance(arg["argument-type-full-name"], list):
            arg_types = []
            for full_type, short_type in zip(arg["argument-type-full-name"], arg["argument-type-short-name"]):
                arg_type_name_selected_ = full_type if full_type else short_type
                arg_type_ = _get_hyperlinked_type(arg_type_name_selected_, language)
                arg_types.append(arg_type_)
            arg_type = " | ".join(set(arg_types))
        else:
            arg_type_name_selected = arg["argument-type-full-name"] \
                if arg["argument-type-full-name"] and arg["argument-type-full-name"].split('.')[0] == "QuantConnect" else arg["argument-type-short-name"]
            arg_type = _get_hyperlinked_type(arg_type_name_selected, language)
        
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
    return extract_xml_content(description, XML_REGEX_PATTERNS)

def _get_return(type_, return_type_name, description, source_url, line_num, language):
    return_description = _get_return_description(source_url, line_num) \
        if source_url and type_ == "method" else extract_xml_content(description, XML_REGEX_PATTERNS) \
        if type_ != "method" else ""
    description_html = ""
    if return_description:
        description_html = f'''<div class=\"subsection-header\">Returns:</div>
<p class=\"subsection-content\">{return_description}</p>'''

    if isinstance(return_type_name, list):
        return_type = " | ".join(_get_hyperlinked_type(r, language) for r in return_type_name)
    else:
        return_type = _get_hyperlinked_type(return_type_name, language)
    
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
    return extract_xml_content(description, XML_REGEX_PATTERNS)

def _get_method_return(return_type_name, description, source_url, line_num, language):
    def no_ret(name):
        return not name or "none" in name.lower() or "void" in name.lower()
    
    if isinstance(return_type_name, list) and all(no_ret(x) for x in return_type_name):
        return ""
    elif isinstance(return_type_name, str) and no_ret(return_type_name):
        return ""
    return _get_return("method", return_type_name, description, source_url, line_num, language)

def _get_hyperlinked_type(type_raw_name, language):
    if not type_raw_name:
        return "None"
    
    parts = re.findall(r'\[([^\]]+)\]', type_raw_name)
    last_substrings = []
    for part in parts:
        substrings = part.split(',')[0].split('.')[-1].split('`')[0]
        last_substrings.append(substrings)
    if last_substrings:
        last_substrings = ', '.join(last_substrings)
        initial_string = re.match(r'(.*)\[\[', type_raw_name).group(1)
        type_name = f'{initial_string.split(".")[-1].split("`")[0]}[{last_substrings}]'
    else:
        type_name = type_raw_name.split(".")[-1].split("`")[0]

    _type = _type_conversion(type_name, language)
    split_type_name = type_raw_name.split('.')
    
    if split_type_name[0] == "QuantConnect":
        if _type[0] == "I" and _type[1].isupper():
            return _type
        
        plain_type = _type.replace('[]', '')
        
        if language == "python":
            i_name = title_to_dash_linked_lower_case(plain_type).replace('_', '-')
        else:
            i_name = title_to_dash_linked_lower_case(plain_type)
        
        if split_type_name[1] == "Indicators":
            if "Indicator" in split_type_name[2]:
                return f"<a href=\"/docs/v2/writing-algorithms/indicators/supported-indicators/\">{_type}</a>"
            elif plain_type in SUPPORTED_INDICATORS:
                INDICATORS[i_name] = type_name.replace('[]', '')
                return f"<a href=\"/docs/v2/writing-algorithms/indicators/supported-indicators/{title_to_dash_linked_lower_case(plain_type)}\">{_type}</a>"
        elif "CandlestickPatterns" in type_raw_name:
            return f"<a href=\"/docs/v2/writing-algorithms/indicators/supported-indicators/candlestick-patterns\">{_type}</a>"
        
        EXTRAS[_type.replace('[]', '')] = type_raw_name.replace('[]', '')
        return f"<a href=\"{TARGET}#{_type.replace('[]', '')}\">{_type}</a>"
    
    return _type
                
def _render_properties(property_list, language):
    return _render_types("property", property_list, language)

def _render_property(property_dict, language):
    return _render_type("property", property_dict, language)

def _get_property_return(return_type_name, description, source_url, line_num, language):
    return _get_return("property", return_type_name, description, source_url, line_num, language)
                
def _render_fields(field_list, language):
    return _render_types("field", field_list, language)

def _render_field(field_dict, language):
    return _render_type("field", field_dict, language)

def _get_field_return(return_type_name, description, source_url, line_num, language):
    return _get_return("field", return_type_name, description, source_url, line_num, language)

    
if __name__ == '__main__':
    render_docs()