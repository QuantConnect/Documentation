import re
from urllib.request import urlopen

raw = urlopen("https://raw.githubusercontent.com/QuantConnect/Lean.DataSource.EODHD/master/EODHD.MacroIndicators.cs").read().decode("utf-8").split('\n')
destination = "Resources/datasets/data-point-attributes/eodhd/supported-macro-indicators.html"

def to_snake_case(name):
    s = re.sub(r'([A-Z])', r'_\1', name).upper()
    return s.lstrip('_')

description = ""
country = ""
html = '''<h4>Reference Table</h4>
<table class="table qc-table table-reflow">
<thead>
<tr><th style="width: 10%;">Symbol</th><th style="width: 45%;">Accessor Code</th><th style="width: 45%;">Description</th></tr>
</thead>
<tbody>
'''

for line in raw:
    if "            /// " in line and "<" not in line:
        description = line.split("            /// ")[-1]

    if "        public static class " in line and "MacroIndicators" not in line:
        country = [x for x in line.split(' ') if x][-1]
        display_name = re.sub(r'([a-z])([A-Z])', r'\1 \2', country)
        html += f"""<tr><td colspan="3"><b>{display_name}</b></td></tr>
"""

    if " = " in line and "public const string" in line:
        item = line.split(" = ")
        code = item[0].split(" ")[-1]
        symbol = item[-1].replace('"', "").replace(";", "").replace(" ", "")
        python_code = to_snake_case(code)
        cs_accessor = f"EODHD.MacroIndicators.{country}.{code}"
        py_accessor = f"EODHD.MacroIndicators.{country}.{python_code}"

        if cs_accessor == py_accessor:
            accessor = f"<code>{cs_accessor}</code>"
        else:
            accessor = f"<code class='language-cs'>{cs_accessor}</code><code class='language-python'>{py_accessor}</code>"

        html += f'''<tr><td>{symbol}</td><td>{accessor}</td><td>{description}</td></tr>
'''

html += """</tbody>
</table>"""

with open(destination, "w", encoding="utf-8") as file:
    file.write(html)
