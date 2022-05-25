from urllib.request import urlopen
from pathlib import Path

OUTPUT_DIR = Path("Resources/securities")
OUTPUT_DIR.mkdir(parents=True, exist_ok=True)

links = {
    "Security": "https://www.quantconnect.com/services/inspector?type=T:QuantConnect.Securities.Security",
    "SecurityPortfolioManager": "https://www.quantconnect.com/services/inspector?type=T:QuantConnect.Securities.SecurityPortfolioManager",
    "SecurityHolding": "https://www.quantconnect.com/services/inspector?type=T:QuantConnect.Securities.SecurityHolding",
    "SymbolProperties": "https://www.quantconnect.com/services/inspector?type=T:QuantConnect.Securities.SymbolProperties"
}

for item, url in links.items():
    json = urlopen(url).read().decode("utf-8") \
            .replace("true", "True") \
            .replace("false", "False") \
            .replace("null", "None")
    json_dict = eval(json)

    html_code = f"""<p>{json_dict["description"]}</p>
    
<table class="table qc-table">
<thead>
<tr><th colspan="2"><code>{item}</code></th></tr>
</thead>
"""

    for attr in json_dict["properties"]:
        html_code += f"""<tr><td width="20%">{attr['property-name']}</td> <td><code>{attr['property-short-type-name']}</code> <br/> {attr['property-description']}</td></tr>
"""

    html_code += "</table>"
    
    with open(OUTPUT_DIR / f"{item.lower()}.html", "w", encoding="utf-8") as html_file:
        html_file.write(html_code)