from urllib.request import urlopen

raw = urlopen("https://raw.githubusercontent.com/QuantConnect/Lean/master/Data/symbol-properties/symbol-properties-database.csv").read().decode("utf-8").split('\n')

exchanges = {"binance": "binance", "bitfinex": "Bitfinex", "gdax": "Coinbase Pro", "ftx": "FTX", "ftxusd": "FTX-US", "kraken": "Kraken"}

count = 0

with open("02 Writing Algorithms/02 User Guides/02 Datasets/04 Crypto/06 Supported Pairs.html", "w", encoding="utf-8") as text:
    text.write("")

for exchange, name in exchanges.items():
    count = 0

    html = '''<h4>%s</h4>
<div>
<table class="table qc-table table-reflow ticker-table hidden-xs">
<thead>
<tr><th colspan="6">Pairs Available</th></tr>
</thead>
<tbody>
<tr>''' % name
        
    for x in raw:
        splits = x.split(",")
        
        if exchange == str(splits[0]):
            html += f'<td><a href="https://www.quantconnect.com/data/tree/crypto/{exchange}/minute/{splits[1]}">{splits[1]}</a></td>'
            
            count += 1
            
            if count == 6:
                html += '</tr>\n<tr>'
                
                count = 0
                
    html += """</tr>
</tbody>
</table>
</div>\n"""
    
    with open("02 Writing Algorithms/02 User Guides/02 Datasets/04 Crypto/06 Supported Pairs.html", "a", encoding="utf-8") as text:
        text.write(html)