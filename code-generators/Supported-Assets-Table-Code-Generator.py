from pathlib import Path
from _code_generation_helpers import SPDB, get_text_content, get_type

if __name__ == '__main__':    
    spdb = get_text_content(SPDB)

    directory = Path("Resources/datasets/supported-securities/")
    directory.mkdir(parents=True, exist_ok=True)
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

    rows = ''
    security_types = ['EQUITY','OPTION','CRYPTO','CRYPTO_FUTURE','FOREX','FUTURE','FUTURE_OPTION','FUTURE_INDEX','INDEX','INDEX_OPTION','CFD']
    for security_type in security_types:
        csharp_name = security_type.title().replace('_','')
        result = results.get(csharp_name.lower())
        if not result:
            continue
        type_cell = f'<code class="csharp">SecurityType.{csharp_name}</code><code class="python">SecurityType.{security_type}</code>'
        csharp_market_cell = []
        python_market_cell = []
        for key in result.keys():
            market_cell = market[key].split(',')
            python_market_cell.append(market_cell[0])
            csharp_market_cell.append(market_cell[-1])
        csharp_market_cell = '<br>'.join(f'Market.{x}' for x in sorted(csharp_market_cell))
        python_market_cell = '<br>'.join(f'Market.{x}' for x in sorted(python_market_cell))
        rows += f'<tr><td>{type_cell}</td><td><code class="csharp">{csharp_market_cell}</code><code class="python">{python_market_cell}</code></td></tr>\n'
    with open(f"{directory}/markets.html", "w", encoding="utf-8") as html:
        html.write(f'''<div>
<table class="table qc-table table-reflow ticker-table hidden-xs">
<thead><tr><th>Security Type</th><th>Market(s)</th></tr></thead>
<tbody>
{rows}</tbody>
</table>
</div>''')

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