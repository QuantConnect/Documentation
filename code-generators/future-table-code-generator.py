from pathlib import Path
from _code_generation_helpers import *
TICKERS_BY_MARKET = {
    "CBOT" : ["10Y","2YY","30Y","5YY","AW","BCF","BWF","EH","F1U","KE","MYM","TN","UB","YM","ZB","ZC","ZF","ZL","ZM","ZN","ZO","ZS","ZT","ZW"],
    "CFE" : ["VX"],
    "CME" : ["6A","6B","6C","6E","6J","6L","6M","6N","6R","6S","6Z","ACD","AJY","ANE","BIO","BTC","CB","CJY","CNH","CSC","DC","DY","E7","EAD","ECD","EI","EMD","ES","ESK","ETH","GD","GDK","GE","GF","GNF","HE","IBV","J7","LBR","LBS","LE","M2K","M6A","M6B","M6C","M6E","M6J","M6S","MBT","MCD","MES","MET","MIB","MIR","MJY","MNH","MNQ","MRB","MSF","NIY","NKD","NQ","RS1","RTY","RX","SDA","TPY"],
    "COMEX" : ["AUP","EDP","GC","HG","MGC","MGT","SI","SIL"],
    "ICE" : ["SB"],
    "NYMEX" : ["A0D","A0F","A1R","A3G","A7E","A7Q","A8K","A9N","AA6","AA8","AC0","AD0","ADB","AE5","AGA","AJL","AJS","AKL","APS","ARE","AYV","AYX","AZ1","B0","B7H","BK","BOO","BZ","CL","CRB","CSW","CSX","CU","D1N","DCB","EN","EPN","EVC","EWG","EWN","EXR","FO","FRC","FSS","GCU","HCL","HH","HO","HP","HRC","HTT","M1B","MAF","MCL","MEF","NG","PA","PAM","PL","R5O","RB","S5O","YO"]
    }

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
            market, ticker = csv[0].upper(), csv[1].upper()
            if ticker not in TICKERS_BY_MARKET.get(market, []):
                continue
            properties[csv[1].upper()] = FutureProperties(ticker, market, csv[3])

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
        html = '''<ul>
    '''
        count = 0
        for x in sorted(properties.values(), key=lambda x: (x.category, x.ticker)):
            if not x.category: continue
            count +=1
            html += f'''<li><b>{x.full_accessor}</b>: {x.name} ({x.market}: {x.ticker})</li>
    '''
        fp.write(f"<p>The following list shows the available ({count}) Futures:</p>{html}</ul>")

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

        html = '''<ul>
    '''
        count = 0
        for x in sorted(properties.values(), key=lambda x: (x.category, x.ticker)):
            if not x.category: continue
            count += 1
            underlying = '' if x.option_ticker == x.ticker else f' | Underlying: {x.ticker}'
            html += f'''<li><b>{x.full_accessor}</b>: {x.name} ({x.market}: {x.option_ticker}{underlying})</li>
    '''
        fp.write(f"<p>The following list shows the available ({count}) Futures Options:</p>{html}</ul>")