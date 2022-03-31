from urllib.request import urlopen

base = "https://raw.githubusercontent.com/QuantConnect/Lean.DataSource.FRED/master/"
subcategories = ["CBOE", "CentralBankInterventions", "CommercialPaper", "ICEBofAML", "LIBOR", "OECDRecessionIndicators", "TradeWeightedIndexes", "Wilshire"]
summaries = {}

for cat in subcategories:
    summaries[cat] = {}
    
    lines = urlopen(f"{base}{cat}.cs").read().decode("utf-8").split('\n')
    
    for i in range(len(lines)):
        if "public static string " in lines[i]:
            symbol_line = lines[i].split("public static string ")[1].split(" = ")
            key = symbol_line[0]
            ticker = symbol_line[1].replace('"', "")[:-1]
            
            j = i
            while "/// </summary>" not in lines[j]:
                j -= 1
            summary = lines[j-1].split("/// ")[1]
        
            key = f"Fred.{cat}.{key}"
            summaries[cat][key] = (ticker, summary)

html = '''<table class="table qc-table table-reflow">
<thead>
<tr><th style="width: 10%;">Symbol</th><th style="width: 30%;">Accessor Code</th><th style="width: 60%;">Summary</th></tr>
</thead>
<tbody>
'''

for cat, key_dict in summaries.items():
    html += f'''<tr><td colspan="3"><b>{cat}</b></td></tr>
'''

    for key, (ticker, summary) in key_dict.items():
        html += f'''<tr><td>{ticker}</td><td><code>{key}</code></td><td>{summary}</td></tr>
'''

html += """</tbody>
</table>"""

with open("02 Writing Algorithms/14 Datasets/17 FRED/01 US Federal Reserve (FRED)/05 Data Point Attributes.html", "w", encoding="utf-8") as text:
    text.write("""<h4>Fred Attributes</h4>
<div data-tree="QuantConnect.DataSource.Fred"></div>

<h4>Reference Table</h4>
""")
    text.write(html)