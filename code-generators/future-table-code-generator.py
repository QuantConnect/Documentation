from pathlib import Path
from json import loads
from _code_generation_helpers import urlopen

LEAN_SERVICE = "https://www.quantconnect.com/services/inspector?language=%s&type=T:QuantConnect.Securities.Futures.%s"

TICKERS_BY_MARKET = {
    "CBOT" : ["10Y","2YY","30Y","5YY","AW","BCF","BWF","EH","F1U","KE","MYM","TN","UB","YM","ZB","ZC","ZF","ZL","ZM","ZN","ZO","ZS","ZT","ZW"],
    "CFE" : ["VX"],
    "CME" : ["6A","6B","6C","6E","6J","6L","6M","6N","6R","6S","6Z","ACD","AJY","ANE","BIO","BTC","CB","CJY","CNH","CSC","DC","DY","E7","EAD","ECD","EI","EMD","ES","ESK","ETH","GD","GDK","GE","GF","GNF","HE","IBV","J7","LBR","LBS","LE","M2K","M6A","M6B","M6C","M6E","M6J","M6S","MBT","MCD","MES","MET","MIB","MIR","MJY","MNH","MNQ","MRB","MSF","NIY","NKD","NQ","RS1","RTY","RX","SDA","TPY"],
    "COMEX" : ["AUP","EDP","GC","HG","MGC","MGT","SI","SIL"],
    "ICE" : ["SB"],
    "NYMEX" : ["A0D","A0F","A1R","A3G","A7E","A7Q","A8K","A9N","AA6","AA8","AC0","AD0","ADB","AE5","AGA","AJL","AJS","AKL","APS","ARE","AYV","AYX","AZ1","B0","B7H","BK","BOO","BZ","CL","CRB","CSW","CSX","CU","D1N","DCB","EN","EPN","EVC","EWG","EWN","EXR","FO","FRC","FSS","GCU","HCL","HH","HO","HP","HRC","HTT","M1B","MAF","MCL","MEF","NG","PA","PAM","PL","R5O","RB","S5O","YO"]
}

FUTURE_OPTIONS = {
    "DC":"DC", "ES":"ES", "CL":"LO", "NQ":"NQ", "SI":"SO",
    "RB":"OB", "GC":"OG", "HO":"OH", "NG":"ON", "HG":"HXE",
    "ZB":"OZB", "ZC":"OZC", "ZS":"OZS", "ZT":"OZT", "ZW":"OZW"
}

def get_data() -> dict:
    data = {}
    for category in ['Currencies', 'Dairy', 'Energy', 'Financials', 'Forestry',
                     'Grains', 'Indices', 'Meats', 'Metals', 'Softs']:
        for language in ['csharp', 'python']:
            fields = loads(urlopen(LEAN_SERVICE % (language, category)).read()).get('fields')
            for field in fields:
                ticker = field.pop('field-default-value')
                data[ticker] = data.get(ticker, {})
                data[ticker]['ticker'] = ticker
                data[ticker]['summary'] = field.pop('field-description')
                data[ticker][language] = f"Futures.{category}.{field.pop('field-name')}"
    return data

if __name__ == '__main__':

    supported = {}
    properties = get_data()

    for market, tickers in TICKERS_BY_MARKET.items():
        for ticker in tickers:
            property = properties.pop(ticker, None)
            if not property:
                continue
            property['market'] = market
            property['option'] = FUTURE_OPTIONS.get(ticker)
            supported[ticker] = property

    for ticker, property in supported.items():
        cs, py = property.get('csharp'), property.get('python')
        property['accessor'] = f"<b>{cs}</b>" if cs == py else \
                f"<b class='language-cs'>{cs}</b><b class='language-python'>{py}</b>"

    ###
    ### Write Future Table
    ###
    path = Path('Resources/datasets/supported-securities/future/')
    path.mkdir(parents=True, exist_ok=True)

    with open(f'{path}/supported-contracts.html', 'w', encoding='utf-8') as fp:
        html = '''<ul>
    '''
        for x in sorted(supported.values(), key=lambda x: x['python']):
            html += f'''<li>{x['accessor']}: {x['summary']} ({x['market']}: {x['ticker']})</li>
    '''
        fp.write(f"<p>The following list shows the available ({len(supported)}) Futures:</p>{html}</ul>")

    ###
    ### Write Future Option Table
    ###
    path = Path('Resources/datasets/supported-securities/futureoption/')
    path.mkdir(parents=True, exist_ok=True)

    with open(f'{path}/supported-contracts.html', 'w', encoding='utf-8') as fp:
        html = '''<ul>
    '''
        for x in sorted(supported.values(), key=lambda x: x['python']):
            option, ticker = x.get("option"), x.get("ticker")
            if not option:
                continue
            underlying = '' if option == ticker else f' | Underlying: {ticker}'
            html += f'''<li><b>{x['accessor']}</b>: {x['summary']} ({x['market']}: {option}{underlying})</li>
    '''
        fp.write(f"<p>The following list shows the available ({len(FUTURE_OPTIONS)}) Futures Options:</p>{html}</ul>")