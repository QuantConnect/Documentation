import numpy as np
from pathlib import Path
import re
from urllib.request import urlopen

raw = urlopen("https://raw.githubusercontent.com/QuantConnect/Lean/master/Data/market-hours/market-hours-database.json").read().decode("utf-8") \
    .replace("true", "True") \
    .replace("false", "False") \
    .replace("null", "None")
raw_dict = eval(raw)
entries = raw_dict["entries"]
sorted_assets = {}

json_script = '''<script>
function ShowHide(event, idName) {{
    var x = document.getElementById(idName);
    if (x.style.display == "none") {{
        x.style.display = "block";
        event.target.innerHTML = "<span>Hide Details <img src='https://cdn.quantconnect.com/i/tu/api-chevron-hide.svg' alt='arrow-hide'></span>";
    }}
    else {{
        x.style.display = "none";
        event.target.innerHTML = "<span>Show Details <img src='https://cdn.quantconnect.com/i/tu/api-chevron-show.svg' alt='arrow-show'></span>";
    }}
}};
</script>'''

days = ["sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday"]
j = 1
destination_folder = Path("Resources/datasets/market-hours")

for asset in entries:
    new_asset_class = asset.split("-")
    
    if new_asset_class[0] == "Base": continue
    
    elif new_asset_class[0] not in sorted_assets:
        sorted_assets[new_asset_class[0]] = {}
        
    if new_asset_class[1] not in sorted_assets[new_asset_class[0]]:
        sorted_assets[new_asset_class[0]][new_asset_class[1]] = []
        
    sorted_assets[new_asset_class[0]][new_asset_class[1]].append(new_asset_class[-1])
    
for x, y in sorted_assets.items():
    for k, v in y.items():
        sorted_assets[x][k] = sorted(v)

for i, (asset_class, assets) in enumerate(sorted_assets.items()):
    html = ""
    
    for exchange, sec in assets.items():
        html += '''<h4>%s</h4>
<table class="table qc-table table-reflow">
<thead>
<tr><th colspan="6">Security Symbol</th></tr>
</thead>
<tbody>
<tr>''' % exchange
        
        count = 0

        for symbol in sec:
            if count == 6:
                html += '</tr>\n<tr>'
                
                count = 0
                
            html += f'<td><a href="/{j:02} {asset_class} {exchange}/01 Time Zone.html">{symbol}</a></td>'
        
            count += 1
                
            symbol_path = destination_folder / f'{j:02} {asset_class} {exchange} {symbol.replace("[*]", "generic")}'
            symbol_path.mkdir(parents=True, exist_ok=True)
            
            entry = entries[f"{asset_class}-{exchange}-{symbol}"]
            
            premarket_html =  {day: [] for day in days}
            market_html =  {day: [] for day in days}
            postmarket_html = {day: [] for day in days}
            
            for day in days:
                if day in entry:
                    for x in entry[day]:
                        if x['state'] == "premarket":
                            premarket_html[day].append(f'{x["start"][:-3]} - {x["end"][:-3].replace("1.00", "24")}')
                        elif x['state'] == "market":
                            market_html[day].append(f'{x["start"][:-3]} - {x["end"][:-3].replace("1.00", "24")}')
                        elif x['state'] == "ppostmarket":
                            postmarket_html[day].append(f'{x["start"][:-3]} - {x["end"][:-3].replace("1.00", "24")}')
                        
            holiday_html = ""
            if "holidays" not in entry or not entry["holidays"]:
                holiday_html += f'<p>There is no market close date for {symbol.replace("[*]", "generic")} {asset_class}</p>'
            
            else:
                holiday_html += '''<table class="table qc-table table-reflow">
<thead>
<tr><th colspan="5">Security Symbol</th></tr>
</thead>
<tbody>
<tr>'''
                counter = 0
                for holiday in entry["holidays"]:
                    if counter == 5:
                        holiday_html += '</tr>\n<tr>'
                        
                        counter = 0
                    
                    holiday_html += f'<td>{holiday}</td>'
                
                    counter += 1
                       
                holiday_html += '''</tr>
</tbody>
</table>'''

            early_close_html = ""
            if "earlyCloses" not in entry or not entry["earlyCloses"]:
                early_close_html += f'<p>There is no early close date for {symbol.replace("[*]", "generic")} {asset_class}</p>'
            
            else:
                early_close_html += '''<table class="table qc-table table-reflow">
<thead>
<tr><th style="width: 50%;">Date</th><th style="width: 50%;">Time of market close</th></tr>
</thead>
<tbody>
'''
                for date, time in entry["earlyCloses"].items():
                    early_close_html += f'''<tr><td>{date}</td><td>{time[:-3]}</td></tr>
    '''
                        
                early_close_html += '''</tbody>
</table>'''

            late_open_html = ""
            if "lateOpens" not in entry or not entry["lateOpens"]:
                late_open_html += f'<p>There is no late open date for {symbol.replace("[*]", "generic")} {asset_class}</p>'
            
            else:
                late_open_html += '''<table class="table qc-table table-reflow">
<thead>
<tr><th style="width: 50%;">Date</th><th style="width: 50%;">Time of market close</th></tr>
</thead>
<tbody>
'''
                for date, time in entry["lateOpens"].items():
                    late_open_html += f'''<tr><td>{date}</td><td>{time[:-3]}</td></tr>
    '''
                        
                late_open_html += '''</tbody>
</table>'''
            
            with open(symbol_path / "01 Time Zone.html", "w", encoding="utf-8") as html_file:
                html_file.write(f'''<!-- Code generated by market-hour-code-generator.py -->

<p>The {symbol.replace("[*]", "generic")} {asset_class} is traded in the <code>{entry["exchangeTimeZone"]}</code> time zone.</p>''')
            
            for k, (page_name, hour_list) in enumerate({"Pre-market Hours": premarket_html, "Market Opening Hours": market_html, "Post-market Hours": postmarket_html}.items()):
                if all([x == [] for x in hour_list.values()]):
                    with open(symbol_path / f"{k+2:02} {page_name}.html", "w", encoding="utf-8") as html_file:
                        html_file.write(f'''<!-- Code generated by market-hour-code-generator.py -->

<p>{page_name} trading is not available for {symbol.replace("[*]", "generic")} {asset_class}.</p>''')
                        
                    continue
                
                with open(symbol_path / f"{k+2:02} {page_name}.html", "w", encoding="utf-8") as html_file:
                    html_file.write(f'''<!-- Code generated by market-hour-code-generator.py -->

<table class="table qc-table table-reflow">
<thead>
<tr><th style="width: 30%;">Weekday</th><th style="width: 70%;">Time ({entry["exchangeTimeZone"]})</th></tr>
</thead>
<tbody>
''')
                    for day, hour in hour_list.items():
                        html_file.write(f'''<tr><td>{day}</td><td>{", ".join(hour) if hour else "/"}</td></tr>
''')
                    html_file.write('''</tbody>
</table>''')

            with open(symbol_path / "05 Holidays.html", "w", encoding="utf-8") as html_file:
                html_file.write("<!-- Code generated by market-hour-code-generator.py -->")
                    
                if "table" in holiday_html:
                    html_file.write(f"""                        
{json_script}
<p>The following days are non-trading days (market closed) for {symbol.replace("[*]", "generic")} {asset_class}.</p>

<div class="details-btn">
    <button class="show-hide-detail" onclick="ShowHide(event, 'holiday')"><span>Show Details <img src='https://cdn.quantconnect.com/i/tu/api-chevron-show.svg' alt='arrow-show'></span></button>
</div>

<div class="method-details" id="holiday" style="display: none;" >
{holiday_html}
</div>""")
                else:
                    html_file.write(f"""
{holiday_html}""")
                
            for k, (page_name, hour_html) in enumerate({"Early Closes": early_close_html, "Late Opens": late_open_html}.items()):
                with open(symbol_path / f"{k+6:02} {page_name}.html", "w", encoding="utf-8") as html_file:
                    html_file.write("<!-- Code generated by market-hour-code-generator.py -->")
                    
                    if "table" in hour_html:
                        html_file.write(f"""
{json_script}
<p>The following days are non-trading days (market closed) for {symbol.replace("[*]", "generic")} {asset_class}.</p>

<div class="details-btn">
    <button class="show-hide-detail" onclick="ShowHide(event, 'holiday')"><span>Show Details <img src='https://cdn.quantconnect.com/i/tu/api-chevron-show.svg' alt='arrow-show'></span></button>
</div>

<div class="method-details" id="holiday" style="display: none;" >
{hour_html}
</div>""")
                    
                    else:
                        html_file.write(f"""
{hour_html}""")
                
            j += 1
    
        html += """</tr>
</tbody>
</table>
"""

    with open(destination_folder / f"{i+1:02} {asset_class}.html", "w", encoding="utf-8") as html_file:
        html_file.write(html.replace('[*]', 'Generic'))