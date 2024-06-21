from json import loads
from urllib.request import urlopen

LEAN_SERVICE = "https://www.quantconnect.com/services/inspector?language=%s&type=T:QuantConnect.DataSource.Fred.%s"

def get_data(type: str) -> str:
    data = {}
    for language in ['csharp', 'python']:
        fields = loads(urlopen(LEAN_SERVICE % (language, type)).read()).get('fields')
        for field in fields:
            ticker = field.pop('field-default-value')
            data[ticker] = data.get(ticker, {})
            data[ticker][language] = f"Fred.{type}.{field.pop('field-name')}"
            data[ticker]['summary'] = field.pop('field-description')

    for ticker, property in data.items():
        cs, py = property.get('csharp'), property.get('python')
        accessor = f"<b>{cs}</b>" if cs == py else \
            f"<b class='language-cs'>{cs}</b><b class='language-python'>{py}</b>"
        property['summary'] = f"<li>{accessor}: {property['summary']} (<b>{ticker}</b>)</li>"

    return f'<H4>{type}</H4>\n<ul>\n' + '\n'.join(
        [property['summary'] for property in data.values()]) + '\n</ul>'

with open("Resources/datasets/data-point-attributes/fred/supported-indicators.html", "w", encoding="utf-8") as text:
    html = "<p>The following list shows the accessor code you need to add each FRED dataset to your algorithm:</p>\n"
    html += '\n'.join([get_data(cat) for cat in ["CBOE", "CentralBankInterventions", "CommercialPaper", "ICEBofAML", "LIBOR", "OECDRecessionIndicators", "TradeWeightedIndexes", "Wilshire"]])
    text.write(html)