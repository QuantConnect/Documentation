from json import dumps
from re import findall, finditer
from typing import List
from urllib.request import urlopen

WRITING_ALGORITHMS = '03 Writing Algorithms'
INDICATORS = f'{WRITING_ALGORITHMS}/28 Indicators/01 Supported Indicators'
API_REFERENCE = f'{WRITING_ALGORITHMS}/98 API Reference/'
MHDB = "https://raw.githubusercontent.com/QuantConnect/Lean/master/Data/market-hours/market-hours-database.json"
SPDB = "https://raw.githubusercontent.com/QuantConnect/Lean/master/Data/symbol-properties/symbol-properties-database.csv"
KNOWN_MISSING_INDICATORS = ["ValueAtRisk"]

class MARKET_HOUR:
    INTRODUCTION = "introduction"
    PRE_MARKET = "pre-market-hours"
    REGULAR = "regular-trading-hours"
    POST_MARKET = "post-market-hours"
    HOLIDAY = "holidays"
    EARLY_CLOSE = "early-closes"
    LATE_OPEN = "late-opens"
    TIME_ZONE = "time-zone"
    
def get_all_indicators() -> list[str]:
    methods = get_type("QuantConnect.Algorithm.QCAlgorithm")["methods"]
    selected = set(x["method-return-type-short-name"] for x in methods
                if x["documentation-attributes"] and len(x["documentation-attributes"]) == 1 and x["documentation-attributes"][0]["tag"] == "Indicators" and x["method-return-type-full-name"].split('.')[0] != "System")
    return list(selected) + KNOWN_MISSING_INDICATORS

def get_text_content(url: str) -> str:
    return urlopen(url).read().decode('utf-8')

LEAN_CLI_README = 'https://raw.githubusercontent.com/QuantConnect/lean-cli/master/README.md'

def get_lean_cli_command_names() -> set:
    """Fetch the set of LEAN CLI command names from the repository README."""
    return set(get_lean_cli_commands().keys())

def get_lean_cli_commands() -> dict:
    """Fetch LEAN CLI commands and their descriptions from the repository README.
    Returns a dict mapping command name to its short description."""
    source = get_text_content(LEAN_CLI_README)
    commands = {}
    lines = source.split('\n')
    for i, line in enumerate(lines):
        if line.startswith('### '):
            name = line[5:-1]
            desc = lines[i + 2] if i + 2 < len(lines) else ''
            commands[name] = desc.rstrip('.')
    return commands

def get_json_content(url: str) -> List:
    content = get_text_content(url) \
        .replace("null", "None").replace("true", "True").replace("false", "False")
    return eval(content)

_type_cache = {}

def get_type(_type: str, language: str = None) -> List:
    cache_key = (_type, language)
    if cache_key in _type_cache:
        return _type_cache[cache_key]
    url = f'https://www.quantconnect.com/services/inspector?type=T:{_type}'
    if language:
        url += f'&language={language}'
    result = get_json_content(url)
    _type_cache[cache_key] = result
    return result

def prefetch_types(type_language_pairs: list) -> None:
    """Fetch multiple types in parallel and cache the results."""
    from concurrent.futures import ThreadPoolExecutor, as_completed
    to_fetch = [(t, l) for t, l in type_language_pairs if (t, l) not in _type_cache]
    if not to_fetch:
        return
    def fetch(pair):
        _type, language = pair
        url = f'https://www.quantconnect.com/services/inspector?type=T:{_type}'
        if language:
            url += f'&language={language}'
        return pair, get_json_content(url)
    with ThreadPoolExecutor(max_workers=16) as executor:
        futures = {executor.submit(fetch, pair): pair for pair in to_fetch}
        for future in as_completed(futures):
            pair, result = future.result()
            _type_cache[pair] = result

def to_key(name: str) -> str:
    key = name
    if not key.isupper():
        key = '-'.join(findall('[a-zA-Z][^A-Z]*', name))
    return key.lower()

def _type_conversion(type, language):
    if language == "csharp":
        return type.replace('<', '&lt;').replace('>', '&gt;')

    type_replacement = {
        "IEnumerable<KeyValuePair": "Dict",
        "ConcurrentDictionary": "Dict",
        "IExtendedDictionary": "Dict",
        "IReadOnlyDict": "Dict",
        "IDictionary": "Dict",
        "ConcurrentQueue": "List",
        "IReadOnlyList": "List",
        "IEnumerable": "List",
        "ICollection": "List",
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

def extract_xml_content(xml_string, patterns):
    output = xml_string
    
    for pattern, replacement in patterns.items():
        for content in finditer(pattern, xml_string):
            output = output.replace(content.group(0), replacement % ((content.group(1).split('.')[-1].split('`')[0], ) * replacement.count("%s")))
    
    return output

def title_to_dash_linked_lower_case(title):
    if title.isupper():
        return title.lower()
    
    lower_case = ""
    for i, char in enumerate(title):
        if i > 0 and char.isupper():
            lower_case += "-"
        lower_case += char.lower()
    return lower_case

def generate_landing_page(start: int, stop: int, path: str, heading: str, content:str) -> None:
    landing = {
        'type' : 'landing',
        'heading' : heading,
        'subHeading' : '',
        'content' : content,
        'alsoLinks' : [],
        'featureShortDescription': {f'{n:02}': '' for n in range(start, stop)}
    }
    with open(f'{path}/00.json', 'w', encoding='utf-8') as fp:
        fp.write(dumps(landing, indent=4))