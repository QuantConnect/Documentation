from datetime import datetime, timedelta
from pathlib import Path
from urllib.request import urlopen

raw = urlopen("https://raw.githubusercontent.com/QuantConnect/Lean/master/Data/market-hours/market-hours-database.json").read().decode("utf-8") \
    .replace("true", "True") \
    .replace("false", "False") \
    .replace("null", "None")
raw_dict = eval(raw)
entries = raw_dict["entries"]
sorted_assets = {}

days = ["sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday"]
# j = 1
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

for asset_class, assets in sorted_assets.items():
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
            if (asset_class == "Index" or asset_class == "IndexOption") and exchange == "usa" and symbol != "[*]": continue
        
            if count == 6:
                html += '</tr>\n<tr>'
                
                count = 0
                
            html += f'<td><a href="{asset_class.lower()}/{exchange}/time-zone.html">{symbol}</a></td>'
        
            count += 1
                
            symbol_path = destination_folder / asset_class.lower() / exchange / symbol.replace("[*]", "generic")
            symbol_path.mkdir(parents=True, exist_ok=True)
            
            entry = entries[f"{asset_class}-{exchange}-{symbol}"]
            
            premarket_html =  {day: [] for day in days}
            market_html =  {day: [] for day in days}
            postmarket_html = {day: [] for day in days}
            
            for day in days:
                if day in entry:
                    for x in entry[day]:
                        if x['state'] == "premarket":
                            premarket_html[day].append(f'{x["start"]} to {x["end"].replace("1.00", "24")}')
                        elif x['state'] == "market":
                            market_html[day].append(f'{x["start"]} to {x["end"].replace("1.00", "24")}')
                        elif x['state'] == "ppostmarket":
                            postmarket_html[day].append(f'{x["start"]} to {x["end"].replace("1.00", "24")}')
                        
            holiday_html = ""
            if "holidays" not in entry or not entry["holidays"]:
                holiday_html += f'<p>There is no holidays for {symbol if "[*]" not in symbol else exchange} {asset_class}</p>'
            
            else:
                holidays = sorted([datetime.strptime(holiday, "%m/%d/%Y") for holiday in entry["holidays"]
                                if datetime.today() <= datetime.strptime(holiday, "%m/%d/%Y") <= datetime.today() + timedelta(days=365)])
                
                if holidays:
                    holiday_html += '''<table class="table qc-table table-reflow">
<thead>
<tr><th colspan="5">Date</th></tr>
</thead>
<tbody>
<tr>'''
                    counter = 0
                    for holiday in holidays:
                        if counter == 5:
                            holiday_html += '</tr>\n<tr>'
                            
                            counter = 0
                        
                        holiday_html += f'<td>{holiday.strftime("%Y-%m-%d")}</td>'
                    
                        counter += 1
                        
                    holiday_html += '''</tr>
</tbody>
</table>'''
                else:
                    holiday_html += f'<p>There is no holidays for {symbol if "[*]" not in symbol else exchange} {asset_class}</p>'

            early_close_html = ""
            if "earlyCloses" not in entry or not entry["earlyCloses"]:
                early_close_html += '<p>There are no days with early closes.</p>'
            
            else:
                early_closes = sorted([(datetime.strptime(date, "%m/%d/%Y"), time) for date, time in entry["earlyCloses"].items()
                                if datetime.today() <= datetime.strptime(date, "%m/%d/%Y") <= datetime.today() + timedelta(days=365)], key=lambda x: x[0])
                
                if early_closes:
                    early_close_html += f'''<table class="table qc-table table-reflow">
<thead>
<tr><th style="width: 50%;">Date</th><th style="width: 50%;">Time Of Market Close ({entry["exchangeTimeZone"].replace("_", " ")})</th></tr>
</thead>
<tbody>
'''
                    for date, time in early_closes:
                        early_close_html += f'''<tr><td>{date.strftime("%Y-%m-%d")}</td><td>{time}</td></tr>
'''
                            
                    early_close_html += '''</tbody>
</table>'''
                else:
                    early_close_html += '<p>There are no days with early closes.</p>'

            late_open_html = ""
            if "lateOpens" not in entry or not entry["lateOpens"]:
                late_open_html += '<p>There are no days with late opens.</p>'
            
            else:
                late_opens = sorted([(datetime.strptime(date, "%m/%d/%Y"), time) for date, time in entry["lateOpens"].items()
                                if datetime.today() <= datetime.strptime(date, "%m/%d/%Y") <= datetime.today() + timedelta(days=365)], key=lambda x: x[0])
                
                if late_opens:
                    late_open_html += f'''<table class="table qc-table table-reflow">
<thead>
<tr><th style="width: 50%;">Date</th><th style="width: 50%;">Time Of Market Open ({entry["exchangeTimeZone"].replace("_", " ")})</th></tr>
</thead>
<tbody>
'''
                    for date, time in late_opens:
                        late_open_html += f'''<tr><td>{date.strftime("%Y-%m-%d")}</td><td>{time}</td></tr>
'''
                            
                    late_open_html += '''</tbody>
</table>'''
                else:
                    late_open_html += '<p>There are no days with late opens.</p>'
                
            with open(symbol_path / "time-zone.html", "w", encoding="utf-8") as html_file:
                html_file.write(f'''<!-- Code generated by market-hour-code-generator.py -->

<p>The {symbol if "[*]" not in symbol else exchange} {asset_class} is traded in the <code>{entry["exchangeTimeZone"].replace("_", " ")}</code> time zone.</p>''')
            
            for page_name, hour_list in {"pre-market-hours": premarket_html, "market-opening-hours": market_html, "post-market-hours": postmarket_html}.items():
                if all([x == [] for x in hour_list.values()]):
                    with open(symbol_path / f"{page_name}.html", "w", encoding="utf-8") as html_file:
                        html_file.write(f'''<!-- Code generated by market-hour-code-generator.py -->

<p>{"Pre-market" if page_name == "pre-market-hours" else "Post-market"} trading is not available.</p>''')
                        
                    continue
                
                with open(symbol_path / f"{page_name}.html", "w", encoding="utf-8") as html_file:
                    html_file.write(f'''<!-- Code generated by market-hour-code-generator.py -->
                                    
<p>The following table shows the {"pre-market" if page_name == "pre-market-hours" else "post-market" if page_name == "post-market-hours" else "regular" if asset_class == "Index" else "regular trading"} hours for the {symbol if "[*]" not in symbol else exchange} {asset_class}{"" if asset_class == "Index" else " market"}:</p>

<table class="table qc-table table-reflow">
<thead>
<tr><th style="width: 20%;">Weekday</th><th style="width: 80%;">Time ({entry["exchangeTimeZone"].replace("_", " ")})</th></tr>
</thead>
<tbody>
''')
                    for day, hour in hour_list.items():
                        if hour:
                            html_file.write(f'''<tr><td>{day.title()}</td><td>{", ".join(hour)}</td></tr>
''')
                    html_file.write('''</tbody>
</table>''')

            with open(symbol_path / "holidays.html", "w", encoding="utf-8") as html_file:
                html_file.write("<!-- Code generated by market-hour-code-generator.py -->")
                    
                if "table" in holiday_html and asset_class != "Forex":
                    html_file.write(f"""
<p>The following table shows the dates of holidays for the {symbol if "[*]" not in symbol else exchange} {asset_class}.</p>

{holiday_html}""")
                else:
                    html_file.write(f"""
{holiday_html}""")
                
            for page_name, hour_html in {"early-closes": early_close_html, "late-opens": late_open_html}.items():
                with open(symbol_path / f"{page_name}.html", "w", encoding="utf-8") as html_file:
                    html_file.write("<!-- Code generated by market-hour-code-generator.py -->")
                    
                    if "table" in hour_html and asset_class != "Forex":
                        html_file.write(f"""
<p>The following table shows the {page_name.replace("-", " ")} for the {symbol if "[*]" not in symbol else exchange} {asset_class}.</p>

{hour_html}""")
                    
                    else:
                        html_file.write(f"""
{hour_html}""")
                
            # j += 1
    
        html += """</tr>
</tbody>
</table>
"""

    with open(destination_folder / f"{asset_class}.html", "w", encoding="utf-8") as html_file:
        html_file.write(html.replace('[*]', 'Generic'))

for asset_class in ["Index", "IndexOption"]:
    with open(destination_folder / asset_class.lower() / "usa" / "generic"/ "market-opening-hours.html", "w", encoding="utf-8") as html_file:
        exchange = "usa"
        
        html_file.write(f'''<!-- Code generated by market-hour-code-generator.py -->
                                        
<p>The following table shows the regular hours for the USA Index:</p>

<table class="table qc-table table-reflow">
<thead>
<tr><th style="width: 10%;">Weekday</th>''')
        
        market_html = {}
                
        for symbol in ["SPX", "NDX", "VIX"]:
            market_html[symbol] = {day: [] for day in days}
            entry = entries[f"{asset_class}-{exchange}-{symbol}"]
            
            html_file.write(f'<th style="width: 30%;">{symbol} ({entry["exchangeTimeZone"].replace("_", " ")})</th>')
        
            for day in days:
                if day in entry:
                    for x in entry[day]:
                        if x['state'] == "market":
                            market_html[symbol][day].append(f'{x["start"]} to {x["end"].replace("1.00", "24")}')
                            
        html_file.write(f'''
</thead>
<tbody>
{f'<tr><td>Sunday</td><td>{", ".join(market_html["SPX"]["sunday"])}</td><td>{", ".join(market_html["NDX"]["sunday"])}</td><td>{", ".join(market_html["VIX"]["sunday"])}</td></tr>' if any([x != [] for x in [market_html["SPX"]["sunday"], market_html["NDX"]["sunday"], market_html["VIX"]["sunday"]]]) else ''}
<tr><td>Monday</td><td>{", ".join(market_html["SPX"]["monday"])}</td><td>{", ".join(market_html["NDX"]["monday"])}</td><td>{", ".join(market_html["VIX"]["monday"])}</td></tr>
<tr><td>Tuesday</td><td>{", ".join(market_html["SPX"]["tuesday"])}</td><td>{", ".join(market_html["NDX"]["tuesday"])}</td><td>{", ".join(market_html["VIX"]["tuesday"])}</td></tr>
<tr><td>Wednesday</td><td>{", ".join(market_html["SPX"]["wednesday"])}</td><td>{", ".join(market_html["NDX"]["wednesday"])}</td><td>{", ".join(market_html["VIX"]["wednesday"])}</td></tr>
<tr><td>Thursday</td><td>{", ".join(market_html["SPX"]["thursday"])}</td><td>{", ".join(market_html["NDX"]["thursday"])}</td><td>{", ".join(market_html["VIX"]["thursday"])}</td></tr>
<tr><td>Friday</td><td>{", ".join(market_html["SPX"]["friday"])}</td><td>{", ".join(market_html["NDX"]["friday"])}</td><td>{", ".join(market_html["VIX"]["friday"])}</td></tr>
{f'<tr><td>Saturday</td><td>{", ".join(market_html["SPX"]["saturday"])}</td><td>{", ".join(market_html["NDX"]["saturday"])}</td><td>{", ".join(market_html["VIX"]["saturday"])}</td></tr>' if any([x != [] for x in [market_html["SPX"]["saturday"], market_html["NDX"]["saturday"], market_html["VIX"]["saturday"]]]) else ''}
</tbody>
</table>''')