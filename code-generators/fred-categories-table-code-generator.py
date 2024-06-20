from json import loads
from urllib.request import urlopen

destination = "Resources/datasets/data-point-attributes/fred/supported-indicators.html"
LEAN_SERVICE = "https://www.quantconnect.com/services/inspector?language=%s&type=T:QuantConnect.DataSource.Fred.%s"

def get_data(type: str) -> str:
    data = {}
    languages = {'csharp':'language-cs', 'python':'language-python'}
    for language, css_class in languages.items():
        fields = loads(urlopen(LEAN_SERVICE % (language, type)).read()).get('fields')
        for field in fields:
            ticker = field.pop('field-default-value')
            data[ticker] = data.get(ticker, {})
            #data[ticker][language] = f"<b class='{css_class}'>Fred.{type}.{field.pop('field-name')}</b>"
            data[ticker][language] = f"<b>Fred.{type}.{field.pop('field-name')}</b>"
            data[ticker]['summary'] = field.pop('field-description')

    return f'<p>Category: <b>{type}</b></p>\n<ul>\n' + '\n'.join(
        [f"<li>{info['csharp']}: {info['summary']} (<b>{ticker}</b>)</li>"
         for ticker, info in data.items()]) + '\n</ul>'

with open(destination, "w", encoding="utf-8") as text:
    html = '\n'.join([get_data(cat) for cat in ["CBOE", "CentralBankInterventions", "CommercialPaper", "ICEBofAML", "LIBOR", "OECDRecessionIndicators", "TradeWeightedIndexes", "Wilshire"]])
    text.write(html)