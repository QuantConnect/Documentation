from urllib.request import urlopen

futs = urlopen("https://raw.githubusercontent.com/QuantConnect/Lean/master/Common/Securities/Future/Futures.cs").read().decode("utf-8").split('\n')

one = "Futures"
two = ""
third = ""
symbols = {}

for x in futs:
    if "            " in x and "{" not in x and "}" not in x and '"' in x:
        symbol = x.split('"')[1]
        third = x.split(" = ")[0].split(" ")[-1]
        
        symbols[symbol] = f'{one}.{two}.{third}'
        
    elif "            " not in x and "        " in x and "{" not in x and "}" not in x:
        two = x.split(" ")[-1]
        
    else: continue

symbols = dict(sorted(symbols.items(), key=lambda x: "Future.Indices" in x[1], reverse=True))

raw = urlopen("https://raw.githubusercontent.com/QuantConnect/Lean/master/Data/symbol-properties/symbol-properties-database.csv").read().decode("utf-8").split('\n')

with open("09 Supported Markets.html", "w", encoding="utf-8") as text:
    text.write("")

html = '''<table class="table qc-table table-reflow">
<thead>
<tr><th colspan="3" style="width: 100%;">Name</th></tr>
<tr><th style="width: 15%;">Symbol</th><th style="width: 15%;">Market</th><th style="width: 70%;">Accessor Code</th></tr>
</thead>
<tbody>
'''

html_ = {}

for x in raw:
    splits = x.split(",")
    if len(splits) < 3: continue
    
    if str(splits[2]) == "future":
        html_[splits[1]] = f'''<tr><th colspan="3" style="width: 100%;">{splits[3]}</th></tr>
<tr><td style="width: 15%;">{splits[1].upper()}</td><td style="width: 15%;">{splits[0].upper()}</td><td style="width: 70%;"><code>{symbols[splits[1]] if splits[1] in symbols else "/"}</code></td></tr>
'''

for key in symbols.keys():
    html += html_[key]

html += """</tbody>
</table>"""

with open("09 Supported Markets.html", "a", encoding="utf-8") as text:
    text.write(html)