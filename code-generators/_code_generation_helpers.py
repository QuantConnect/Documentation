from base64 import b64encode
from concurrent.futures import ThreadPoolExecutor
from hashlib import sha256
from json import dumps, loads
from os import environ
from re import findall, finditer
from time import time
from typing import List
from urllib.request import Request, urlopen

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

QC_API = 'https://www.quantconnect.com/api/v2'
SECTION_GROUPS = ('about', 'documentation', 'examples')

def get_api_credentials() -> tuple:
    """User id and API token from the environment."""
    user_id, api_token = environ.get('QUANTCONNECT_USER_ID'), environ.get('QUANTCONNECT_API_TOKEN')
    if not user_id or not api_token:
        raise RuntimeError('Set QUANTCONNECT_USER_ID and QUANTCONNECT_API_TOKEN. In Actions '
                           'they are empty on a pull request from a fork.')
    return str(user_id), api_token

def api_post(endpoint: str, payload: dict = None) -> dict:
    """POST to the QuantConnect API v2 and return the envelope. Raises on failure."""
    user_id, api_token = get_api_credentials()
    timestamp = str(int(time()))
    hashed_token = sha256(f'{api_token}:{timestamp}'.encode('utf-8')).hexdigest()
    auth = b64encode(f'{user_id}:{hashed_token}'.encode('utf-8')).decode('ascii')
    request = Request(f'{QC_API}{endpoint}', data=dumps(payload or {}).encode('utf-8'),
                      headers={'Authorization': f'Basic {auth}', 'Timestamp': timestamp,
                               'Content-Type': 'application/json'})
    body = loads(urlopen(request, timeout=120).read())
    if not body.get('success'):
        raise RuntimeError(f'{endpoint} failed: {body.get("errors")}')
    return body

def get_organization_id() -> str:
    """The organization id that market/sections/read requires on every call.

    Any organization the account belongs to works; it need not own the dataset. Omitting
    it fails with the unhelpful 'Organization not found. '.
    """
    if environ.get('QUANTCONNECT_ORGANIZATION_ID'):
        return environ['QUANTCONNECT_ORGANIZATION_ID']
    organizations = api_post('/organizations/list').get('organizations', [])
    if not organizations:
        raise RuntimeError('The account belongs to no organizations.')
    print(f'note: QUANTCONNECT_ORGANIZATION_ID unset, using {organizations[0]["id"]} '
          f'({organizations[0].get("name")})')
    return organizations[0]['id']

DATASET_DUMP = 'https://s3.amazonaws.com/cdn.quantconnect.com/web/docs/alternative-data-dump-v2024-01-02.json'

def get_public_dataset_slugs() -> set:
    """Which datasets are public, per the dump. Nothing in the API records this."""
    return {d['url'].rsplit('/', 1)[-1] for d in loads(get_text_content(DATASET_DUMP))}

def get_dataset_listings() -> List[dict]:
    """Every public dataset listing, with its page sections read live from the API.

    The dump says which datasets are public; the API says what each page holds, so edits
    to a listing reach the docs without waiting for a dump re-upload. Each record carries
    what the dataset generators use:

        {"name": ..., "vendorName": ..., "url": "/datasets/<slug>",
         "about": [{"title": ..., "content": ...}, ...], "documentation": [...],
         "examples": [...]}

    Drift between the two sources is reported, not applied -- either direction needs a
    person to decide.
    """
    organization_id = get_organization_id()
    public = get_public_dataset_slugs()
    masters = {m['url']: m for m in api_post('/market/data/list')['list'] if not m.get('pending')}

    for slug in sorted(set(masters) - public):
        print(f'note: {slug} is a live listing but is not in the dump, so it gets no docs '
              f'page. Refresh the dump if it should be public.')
    for slug in sorted(public - set(masters)):
        print(f'note: {slug} is in the dump but no longer a listing; it has been retired.')

    def read(master):
        sections = api_post('/market/sections/read',
                            {'id': master['id'], 'organizationId': organization_id})['sections']
        listing = {'name': master.get('name'), 'vendorName': master.get('vendorName'),
                   'url': f'/datasets/{master.get("url")}'}
        for group in SECTION_GROUPS:
            # Sort by position: the generators index into `about` for the vendor landing
            # page, so reading order is load-bearing, not cosmetic.
            listing[group] = [{'title': s.get('title'), 'content': s.get('content')}
                              for s in sorted(sections.get(group) or [],
                                              key=lambda s: s.get('position') or 0)]
        return listing

    masters = [m for slug, m in masters.items() if slug in public]
    with ThreadPoolExecutor(8) as executor:
        return list(executor.map(read, masters))

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