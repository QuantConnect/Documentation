from urllib.request import urlopen

raw = urlopen("https://raw.githubusercontent.com/QuantConnect/Lean.DataSource.USEnergy/master/USEnergy.Category.cs").read().decode("utf-8").split('\n')

description = ""
code = ""
country = ""
symbol = ""
html = '''<h4>Reference Table</h4>
<table class="table qc-table table-reflow">
<thead>
<tr><th style="width: 45%;">symbol</th><th style="width: 10%;">Error Code</th><th style="width: 45%;">Description</th></tr>
</thead>
<tbody>
'''

for line in raw:
    if "                /// " in line and "<" not in line:
        description = line.split("                /// ")[-1]
        
    if "           public static class " in line:
        country = [x for x in line.split(' ') if x != ''][-1]
        
    if " = " in line:
        item = line.split(" = ")
        code = item[0].split(" ")[-1]
        symbol = item[-1].replace('"', "").replace(";", "").replace(" ", "")
        
        html += f'''<tr><td>USEnergy.{country}.{code}</td><td>{symbol}</td><td>{description}</td></tr>
'''

html += """</tbody>
</table>"""

with open("Resources/datasets/data-point-attributes/us-energy/supported-indicators.html", "w", encoding="utf-8") as file:
    file.write(html)