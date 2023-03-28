from pathlib import Path
from _code_generation_helpers import get_text_content

if __name__ == '__main__':
    spdb = get_text_content("https://raw.githubusercontent.com/QuantConnect/Lean/master/Data/symbol-properties/symbol-properties-database.csv")

    directory = "Resources/datasets/supported-securities/"
    markets = ['binance','binanceus','bitfinex','gdax','kraken','oanda']
    results = {}

    # Organize data
    for line in spdb.split('\n'):
        csv = line.split(',')
        market = csv[0]
        if market not in markets:
            continue
        ticker, security_type = csv[1:3]
        if security_type not in results:
            results[security_type] = {}
        if market not in results[security_type]:
            results[security_type][market] = []
        results[security_type][market].append(ticker)

    # Write files
    for security_type, result in results.items():
        path = Path(f'{directory}/{security_type}')
        path.mkdir(parents=True, exist_ok=True)
        
        name = 'Contract' if security_type == 'cfd' else 'Pairs'
        
        for exchange, tickers in result.items():
            
            rows = ''
            tickers.sort()
            for i in range(0, len(tickers), 6):
                rows += '<tr>' + ''.join(f'<td>{ticker}</td>' for ticker in tickers[i:i+6]) + '</tr>\n'

            with open(f"{path}/{exchange}.html", "w", encoding="utf-8") as text:
                text.write(f'''<div>
<table class="table qc-table table-reflow ticker-table hidden-xs">
<thead><tr><th colspan="6">{name} Available</th></tr></thead>
<tbody>
{rows}
</tbody>
</table>
</div>''')