from json import dumps
from pathlib import Path
from _code_generation_helpers import SPDB, MHDB, get_json_content, get_text_content

root = Path("Resources/datasets/market-hours")
root.mkdir(exist_ok=True, parents=True)

days = ["sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday"]

# Get contract names from SPDB
# CSV columns: market, symbol, type, description, ...
contracts_real = {}
for line in get_text_content(SPDB).split('\n'):
    csv = line.split(',')
    if len(csv) < 4 or csv[0].startswith('market') or csv[1] == "[*]":
        continue
    # For forex, col 0 (market) is the pair name; otherwise col 3 (description)
    i = 0 if csv[2].lower() == 'forex' else 3
    if csv[i].strip():
        contracts_real[csv[1]] = csv[i].strip()
contracts_real.update({
    'SPX': 'S&P 500 Index',
    'RUT': 'Russell 2000 Index',
    'NDX': 'Nasdaq 100 Index',
    'VIX': 'CBOE Volatility Index',
    'HSI': 'Hang Seng Index'
})

# Fetch and parse MHDB
raw_dict = get_json_content(MHDB)
entries = raw_dict["entries"]

SKIP = ["IndexOption-usa-RUTW", "IndexOption-usa-SPXW"]
EXCEPTIONS = ['base', 'crypto', 'index-india', 'option-india-', 'fxcm', 'spxw']

# Mapping: security-type-exchange -> JS filename
JS_FILE_MAP = {
    'equity-usa':              'us-equity-market-hours.js',
    'equity-india':            'india-equity-market-hours.js',
    'option-usa':              'equity-options-market-hours.js',
    'forex-oanda':             'forex-market-hours.js',
    'index-usa':               'index-market-hours.js',
    'index-eurex':             'index-market-hours.js',
    'index-hkfe':              'index-market-hours.js',
    'index-ose':               'index-market-hours.js',
    'indexoption-usa':         'index-options-market-hours.js',
    'cfd-interactivebrokers':  'cfd-market-hours.js',
    'cfd-oanda':               'cfd-market-hours.js',
}

# Group entries by JS file
grouped = {}  # js_filename -> { mhdb_key: cleaned_entry }

for key, entry in entries.items():
    if key in SKIP:
        continue
    if any(x in key.lower() for x in EXCEPTIONS):
        continue

    tmp = key.split("-")
    mapping_key = '-'.join(tmp[0:2]).lower()
    js_file = JS_FILE_MAP.get(mapping_key, 'futures-market-hours.js')

    # Keep only the fields the JS rendering needs
    clean = {'exchangeTimeZone': entry.get('exchangeTimeZone', '')}
    for day in days:
        if day in entry and entry[day]:
            clean[day] = entry[day]
    if 'holidays' in entry:
        clean['holidays'] = entry['holidays']
    if 'earlyCloses' in entry:
        clean['earlyCloses'] = entry['earlyCloses']
    if 'lateOpens' in entry:
        clean['lateOpens'] = entry['lateOpens']

    grouped.setdefault(js_file, {})[key] = clean

# Resolve holiday/earlyClose/lateOpen fallbacks
# Option-usa-[*] and Index-usa-[*] fall back to Equity-usa-[*]
equity_usa = grouped.get('us-equity-market-hours.js', {}).get('Equity-usa-[*]', {})
for fallback_key in ['Option-usa-[*]', 'Index-usa-[*]']:
    for data in grouped.values():
        if fallback_key in data:
            entry = data[fallback_key]
            for field in ['holidays', 'earlyCloses', 'lateOpens']:
                if not entry.get(field) and equity_usa.get(field):
                    entry[field] = equity_usa[field]

# Build NAMES maps per JS file (symbol -> display name)
names_per_file = {}
for js_file, data in grouped.items():
    names = {}
    for key in data:
        symbol = key.split('-')[-1]
        if symbol != '[*]' and symbol in contracts_real:
            names[symbol] = contracts_real[symbol]
    if names:
        names_per_file[js_file] = names

# Default MHDB key and div ID prefix per JS file
default_keys = {
    'us-equity-market-hours.js':       'Equity-usa-[*]',
    'india-equity-market-hours.js':    'Equity-india-[*]',
    'equity-options-market-hours.js':  'Option-usa-[*]',
    'forex-market-hours.js':           'Forex-oanda-[*]',
    'futures-market-hours.js':         'Future-cme-[*]',
    'index-market-hours.js':           'Index-usa-[*]',
    'index-options-market-hours.js':   'IndexOption-usa-[*]',
    'cfd-market-hours.js':             'Cfd-interactivebrokers-[*]',
}

id_prefixes = {
    'us-equity-market-hours.js':       'us-equity',
    'india-equity-market-hours.js':    'india-equity',
    'equity-options-market-hours.js':  'equity-options',
    'forex-market-hours.js':           'forex',
    'futures-market-hours.js':         'futures',
    'index-market-hours.js':           'index',
    'index-options-market-hours.js':   'index-options',
    'cfd-market-hours.js':             'cfd',
}

# Write JS files with embedded data
for js_file, data in grouped.items():
    default_key = default_keys.get(js_file, list(data.keys())[0])
    prefix = id_prefixes.get(js_file, 'market')
    names = names_per_file.get(js_file, {})

    parts = [
        f'const DATA = {dumps(data, indent=2)};',
        '',
        f'const NAMES = {dumps(names, indent=2)};',
        '',
        f"const DEFAULT_KEY = '{default_key}';",
        f"const ID_PREFIX = '{prefix}';",
    ]

    output_path = root / js_file
    with open(output_path, 'w', encoding='utf-8') as fp:
        fp.write('\n'.join(parts) + '\n')
    print(f'  wrote {output_path} ({len(data)} entries, {len(names)} names)')

print('Done.')
