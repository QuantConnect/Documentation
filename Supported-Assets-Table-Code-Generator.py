from urllib.request import urlopen

raw = urlopen("https://raw.githubusercontent.com/QuantConnect/Lean/master/Data/symbol-properties/symbol-properties-database.csv").read().decode("utf-8").split('\n')

cryptos_exchange = {"binance": "Binance", "bitfinex": "Bitfinex", "gdax": "Coinbase Pro", "ftx": "FTX", "ftxusd": "FTX-US", "kraken": "Kraken"}
forex_exchanges = {"oanda": "Oanda"}
cfd_exchanges = {"oanda": "Oanda"}

with open("02 Writing Algorithms/02 User Guides/02 Datasets/04 Crypto/06 Supported Pairs.html", "w", encoding="utf-8") as text:
    text.write("")

for exchange, name in cryptos_exchange.items():
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
        
        if exchange == str(splits[0]) and str(splits[2]) == "crypto":
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
        
with open("02 Writing Algorithms/02 User Guides/02 Datasets/05 Forex/05 Supported Pairs.html", "w", encoding="utf-8") as text:
    text.write("")

for exchange, name in forex_exchanges.items():
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
        
        if exchange == str(splits[0]) and str(splits[2]) == "forex":
            html += f'<td><a href="https://www.quantconnect.com/data/tree/forex/{exchange}/minute/{splits[1]}">{splits[1]}</a></td>'
            
            count += 1
            
            if count == 6:
                html += '</tr>\n<tr>'
                
                count = 0
                
    html += """</tr>
</tbody>
</table>
</div>\n"""
    
    with open("02 Writing Algorithms/02 User Guides/02 Datasets/05 Forex/05 Supported Pairs.html", "a", encoding="utf-8") as text:
        text.write(html)
        
with open("02 Writing Algorithms/02 User Guides/02 Datasets/10 CFD/05 Supported Contracts.html", "w", encoding="utf-8") as text:
    text.write("")

for exchange, name in cfd_exchanges.items():
    count = 0

    html = '''<h4>%s</h4>
<div>
<table class="table qc-table table-reflow ticker-table hidden-xs">
<thead>
<tr><th colspan="6">Contract Available</th></tr>
</thead>
<tbody>
<tr>''' % name
        
    for x in raw:
        splits = x.split(",")
        
        if exchange == str(splits[0]) and str(splits[2]) == "cfd":
            html += f'<td><a href="https://www.quantconnect.com/data/tree/cfd/{exchange}/minute/{splits[1]}">{splits[1]}</a></td>'
            
            count += 1
            
            if count == 6:
                html += '</tr>\n<tr>'
                
                count = 0
                
    html += """</tr>
</tbody>
</table>
</div>\n"""
    
    with open("02 Writing Algorithms/02 User Guides/02 Datasets/10 CFD/05 Supported Contracts.html", "a", encoding="utf-8") as text:
        text.write(html)