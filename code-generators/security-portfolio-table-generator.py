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

filenames = {
    "Security": "security.html",
    "SecurityPortfolioManager": "security_portfolio_manager.html",
    "SecurityHolding": "security_holding.html",
    "SymbolProperties": "symbol_properties.html"
}

cs2py = {
    "decimal": "float",
    "DateTime": "datetime",
    "TimeSpan": "timedelta",
}

for item, url in links.items():
    json = urlopen(url).read().decode("utf-8") \
            .replace("true", "True") \
            .replace("false", "False") \
            .replace("null", "None")
    json_dict = eval(json)

    html_code = f"""<p>The following table describes the properties of the <code>{item}</code> class:</p>

<table class="table qc-table">
<thead><tr><th style="white-space:nowrap">Property</th><th style="white-space:nowrap">Data Type</th><th>Description</th></tr></thead>
"""
    for attr in sorted(json_dict["properties"], key=lambda attr: attr['property-name']):
        type_name = attr['property-short-type-name']
        if type_name in cs2py:
            type_name = f'<code class="csharp">{type_name}</code><code class="python">{cs2py[type_name]}</code>'
        else:
            type_name = f'<code>{type_name}</code>'

        html_code += f"""<tr><td><code>{attr['property-name']}</code></td><td>{type_name}</td><td>{attr['property-description']}</td></tr>
"""
    html_code += "</table>"
    
    with open(OUTPUT_DIR / filenames[item], "w", encoding="utf-8") as html_file:
        html_file.write(html_code)