from urllib.request import urlopen

raw = urlopen("https://raw.githubusercontent.com/QuantConnect/Lean/master/Common/Orders/OrderResponseErrorCode.cs").read().decode("utf-8").split('\n')

description = ""
code = ""
enum = ""
html = '''<p>When an order fails to process it returns with a negative order-id. These error codes mean different things as described in the table below.</p>
<table class="table qc-table table-reflow">
<thead>
<tr><th style="width: 5%;">Enum</th><th style="width: 25%;">Error Code</th><th style="width: 70%;">Description</th></tr>
</thead>
<tbody>
'''

for line in raw:
    if "        /// " in line and "<" not in line:
        description = line.split("        /// ")[-1]
        
    if " = " in line:
        item = line.split(" = ")
        code = item[0].split(" ")[-1]
        enum = item[-1].split(",")[0]
        
    if line == "" and code != "":
        html += f'''<tr><td>{enum}</td><td>{code}</td><td>{description}</td></tr>
'''

html += """</tbody>
</table>"""

with open("02 Writing Algorithms/22 Trading and Orders/10 Order Errors/19 Order Error Code Reference.html", "w", encoding="utf-8") as file:
    file.write(html)