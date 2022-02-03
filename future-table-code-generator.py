from urllib.request import urlopen

futs = urlopen("https://raw.githubusercontent.com/QuantConnect/Lean/master/Common/Securities/Future/Futures.cs").read().decode("utf-8").split('\n')

one = "Future"
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
    
raw = urlopen("https://raw.githubusercontent.com/QuantConnect/Lean/master/Data/symbol-properties/symbol-properties-database.csv").read().decode("utf-8").split('\n')

with open("02 Writing Algorithms/02 User Guides/02 Datasets/06 Futures/09 Supported Markets.html", "w", encoding="utf-8") as text:
    text.write("")

html = '''<table class="table qc-table table-reflow">
<thead>
<tr><th colspan="3" align="left" width="100%">Name</th></tr>
<tr><th align="left" width="20%">Symbol</th><th align="left" width="60%">Accessor Code</th><th align="left" width="20%">Market</th></tr>
</thead>
<tbody>
'''

for x in raw:
    splits = x.split(",")
    if len(splits) < 3: continue
    
    if str(splits[2]) == "future":
        html += f'''<tr><th colspan="3" align="left" width="100%">{splits[3]}</th></tr>
<tr><td width="20%">{splits[1].upper()}</td><td width="60%"><code>{symbols[splits[1]] if splits[1] in symbols else "/"}</code></td><td width="20%">{splits[0].upper()}</td></tr>
'''

html += """</tbody>
</table>"""

with open("02 Writing Algorithms/02 User Guides/02 Datasets/06 Futures/09 Supported Markets.html", "a", encoding="utf-8") as text:
    text.write(html)