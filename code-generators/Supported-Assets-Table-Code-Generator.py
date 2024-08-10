from pathlib import Path
from _code_generation_helpers import SPDB, get_text_content, get_type

if __name__ == '__main__':    
    spdb = get_text_content(SPDB)

    directory = "Resources/datasets/supported-securities/"
    backlist = ['market','ftx', 'ftxus', 'fxcm', 'gdax']
    markets = ['binance', 'binanceus', 'bitfinex', 'bybit', 'coinbase', 'kraken', 'oanda', 'interactivebrokers']
    results = {}

    # Organize data
    for line in spdb.split('\n'):
        if line.strip().startswith("#"):
            continue
        csv = line.split(',')
        if len(csv) < 3:
            continue
        market, ticker, security_type = csv[0:3]
        if market in backlist:
            continue
        if security_type not in results:
            results[security_type] = {}
        if market not in results[security_type]:
            results[security_type][market] = []
        if market in markets and '[*]' not in ticker:
            results[security_type][market].append(ticker)

    market = [[x.pop('field-name')
               for x in get_type("QuantConnect.Market", language).pop('fields')]
               for language in ["csharp", "python"]]

    market = dict([(cs.lower(), ','.join(sorted(set([cs,py])))) for cs,py in zip(market[0], market[1])])

    # Write files
    for security_type, result in results.items():
        path = Path(f'{directory}/{security_type}')
        path.mkdir(parents=True, exist_ok=True)

        with open(f"{path}/market.html", "w", encoding="utf-8") as html:
            name = "Futures" if security_type == "future" else security_type.title()\
                .replace("Option", "Options")\
                .replace("option", " Options")\
                .replace("Index", "Indices")\
                .replace("Indices Options", "Index Options")
            html.write(f"""<p>The following <code>Market</code> enumeration members are available for {name}:</p>
<div data-tree='QuantConnect.Market' data-fields='{",".join(sorted([market[k] for k in result.keys()]))}'></div>""")

        name = 'Contract' if security_type == 'cfd' else 'Pairs'
        
        for exchange, tickers in result.items():
            rows = ''
            count = len(tickers)
            if count == 0: continue
            tickers.sort()

            for i in range(0, count, 6):
                rows += '<tr>' + ''.join(f'<td>{ticker}</td>' for ticker in tickers[i:i+6]) + '</tr>\n'

            with open(f"{path}/{exchange}.html", "w", encoding="utf-8") as text:
                text.write(f'''<div>
<table class="table qc-table table-reflow ticker-table hidden-xs">
<thead><tr><th colspan="6">{name} Available ({count})</th></tr></thead>
<tbody>
{rows}</tbody>
</table>
</div>''')