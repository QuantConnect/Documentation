from pathlib import Path
from _code_generation_helpers import *

class FutureProperties:
    def __init__(self, ticker, market, name):
        self.category, self.accessor = '',''
        self.full_accessor = '/'
        self.option_ticker = ''
        self.ticker = ticker
        self.market = market
        self.name = name

    def SetAccessor(self, category, accessor):
        self.category = category
        self.accessor = accessor
        self.full_accessor = f'Futures.{category}.{accessor}'
    
    def SetOptionTicker(self, option_ticker):
        self.option_ticker = option_ticker

    def __repr__(self) -> str:
        return f'{self.market},{self.ticker},{self.full_accessor},{self.name}'

if __name__ == '__main__':
    lean_master = 'https://raw.githubusercontent.com/QuantConnect/Lean/master'

    properties = {}
    spdb = get_text_content(SPDB)
    for line in spdb.split('\n'):
        csv = line.split(',')
        if len(csv) > 2 and csv[2] == 'future':
            ticker = csv[1].upper()
            properties[csv[1].upper()] = FutureProperties(ticker, csv[0].upper(), csv[3])

    category = ""

    futures = get_text_content(f'{lean_master}/Common/Securities/Future/Futures.cs')
    for x in futures.split('\n'):
        if '=' in x and '"' in x:
            tmp = x.split(" = ")
            accessor =  f'{tmp[0].split(" ")[-1]}'
            ticker = tmp[1][1:-2].upper()
            property = properties.get(ticker)
            if property:
                property.SetAccessor(category, accessor) 

        if 'public static class' in x:
            category = x.split(" ")[-1]

    ###
    ### Write Future  Table
    ###
    path = Path('Resources/datasets/supported-securities/future/')
    path.mkdir(parents=True, exist_ok=True)

    with open(f'{path}/supported-contracts.html', 'w', encoding='utf-8') as fp:
        html = '''<table class="table qc-table table-reflow">
    <thead>
    <tr><th colspan="3" style="width: 100%;">Name</th></tr>
    <tr><th style="width: 15%;">Symbol</th><th style="width: 15%;">Market</th><th style="width: 70%;">Accessor Code</th></tr>
    </thead>
    <tbody>
    '''
        for x in sorted(properties.values(), key=lambda x: (x.category, x.ticker)):
            if not x.category: continue
            html += f'''<tr><td colspan="3" style="width: 100%;">{x.name}</td></tr>
    <tr><td style="width: 15%;">{x.ticker}</td><td style="width: 15%;">{x.market}</td><td style="width: 70%;"><code>{x.full_accessor}</code></td></tr>
    '''
        fp.write(html + '''</tbody>
    </table>''')

    ###
    ### Write Future Option Table
    ###
    path = Path('Resources/datasets/supported-securities/futureoption/')
    path.mkdir(parents=True, exist_ok=True)

    mapping = {
        "DC":"DC", "ES":"ES", "LO":"CL", "NQ":"NQ", "SO":"SI",
        "OB":"RB", "OG":"GC", "OH":"HO", "ON":"NG", "HXE":"HG",
        "OZB":"ZB", "OZC":"ZC", "OZS":"ZS", "OZT":"ZT", "OZW":"ZW"    
    }

    tmp = {}
    for option_ticker, ticker in mapping.items():
        property = properties.pop(ticker)
        if property:
            property.SetOptionTicker(option_ticker)
            tmp[ticker] = property
    properties = tmp

    with open(f'{path}/supported-contracts.html', 'w', encoding='utf-8') as fp:

        html = '''<table class="table qc-table table-reflow">
    <thead>
    <tr><th colspan="4" style="width: 100%;">Name</th></tr>
    <tr><th style="width: 15%;">Symbol</th><th style="width: 15%;">Underlying</th><th style="width: 15%;">Market</th><th style="width: 70%;">Accessor Code</th></tr>
    </thead>
    <tbody>
    '''
        for x in sorted(properties.values(), key=lambda x: (x.category, x.ticker)):
            if not x.category: continue
            html += f'''<tr><td colspan="4" style="width: 100%;">{x.name}</td></tr>
    <tr><td style="width: 15%;">{x.option_ticker}</td><td style="width: 15%;">{x.ticker}</td><td style="width: 15%;">{x.market}</td><td style="width: 70%;"><code>{x.full_accessor}</code></td></tr>
    '''
        fp.write(html + '''</tbody>
    </table>''')