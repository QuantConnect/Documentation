import re
from urllib.request import urlopen

destination = "Resources/enumerations"
root_url = "https://raw.githubusercontent.com/QuantConnect/Lean/master/"
enum_objects = [
    "Algorithm/Portfolio/PortfolioBias.cs",
    "Common/Algorithm/Framework/Alphas/InsightScoreType.cs",
    "Common/Algorithm/Framework/Alphas/InsightType.cs",
    "Common/Algorithm/Framework/Alphas/InsightDirection.cs",
    "Common/Brokerages/BrokerageMessageType.cs",
    "Common/Chart.cs",
    "Common/Data/FileFormat.cs",
    "Common/Data/Market/BarDirection.cs",
    "Common/Data/Market/RenkoType.cs",
    "Common/Global.cs",
    "Common/Market.cs",
    "Common/Optimizer/OptimizationStatus.cs",
    "Common/Orders/IndiaOrderProperties.cs",
    "Common/Orders/OrderError.cs",
    "Common/Orders/OrderField.cs",
    "Common/Orders/OrderRequestStatus.cs",
    "Common/Orders/OrderRequestType.cs",
    "Common/Orders/OrderResponseErrorCode.cs",
    "Common/Orders/OrderTypes.cs",
    "Common/Securities/CashBook.cs",
    "Common/Securities/MarketHoursState.cs",
    "Common/Securities/Option/StrategyMatcher/PredicateTargetValue.cs",
    "Common/Series.cs",
    "Common/Statistics/TradeEnums.cs",
    "Common/TradingDay.cs",
    "Indicators/CandlestickPatterns/CandleEnums.cs",
    "Indicators/IndicatorStatus.cs",
    "Indicators/MovingAverageType.cs",
    "Indicators/PivotPointsHighLow.cs",
    "Indicators/SwissArmyKnife.cs",
    "Report/CrisisEvent.cs"
]

quotation = '\"'
market = None
market_data = None

def TableCreation(raw, namespace=""):
    object_ = ""
    enum_active = False
    static_class_active = False
    description = ""
    code = ""
    enum = ""

    current_object = {}     # dict is already ordered dict

    for i, line in enumerate(raw):
        if "namespace" in line:
            namespace = line.split("namespace ")[-1].strip()
            continue
        
        if "public enum" in line:
            object_ = line.split("public enum")[-1].strip()
            enum_active = True
            continue
        
        elif "public static class" in line:
            object_ = line.split("public static class")[-1].strip()
            continue
            
        if line.strip() != "" \
        and "{" not in line \
        and "}" not in line \
        and "///" not in line \
        and "[" not in line \
        and enum_active:
            if "=" in line:
                item = line.split(" = ")
                enum = item[0].strip()
                code = int(item[-1].split(",")[0].split("<<")[-1].strip())
                current_object[enum] = {"code": code, "description": description}
            
            else:
                enum = line.split(",")[0].strip()
                code = None
                current_object[enum] = {"code": code, "description": description}
                
        if "public const string" in line:
            static_class_active = True
            item = line.split(" = ")
            enum = item[0].split("public const string")[-1].strip()
            code = item[-1].split(";")[0].strip()
            current_object[enum] = {"code": code, "description": description}
            
        if "///" in line:
            description += line.split("///")[-1]\
                .replace("<remarks>", "").replace("</remarks>", "")\
                .replace("<summary>", "").replace("</summary>", "").strip()
            
            if ("remarks" not in line and "summary" not in line) \
            or ("remarks" in line and i < len(raw)-1 and "remarks" in raw[i+1]):
                description += " "
            
            continue
                
        elif "EnumMember" in line: 
            continue
        
        else:
            description = ""
                
        if "}" in line and (enum_active or static_class_active):
            enum_active = False
            static_class_active = False
            TableCreation(raw[i:], namespace)
            break
            
    if not current_object: return

    n = 0
    exist_n = [x["code"] for x in current_object.values()]
    
    for enum, content in current_object.items():
        if content["code"] is None:
            while n in exist_n:
                n += 1
            
            current_object[enum]["code"] = n
            n += 1
    try:
        sorted_ = sorted(current_object.items(), key=lambda x: int(x[1]["code"]))
    except:
        sorted_ = current_object.items()

    if object_ == "Market":
        sym_prop = urlopen("https://raw.githubusercontent.com/QuantConnect/Lean/master/Data/symbol-properties/symbol-properties-database.csv").read().decode("utf-8").split('\n')
        global market, market_data
        market = set({x.split(",")[0]: x.split(",")[2].lower().strip() for x in sym_prop if len(x.split(",")) > 2 and x.split(",")[2].lower().strip() != "type" and " " not in x.split(",")[2].lower().strip()}.items())
        market_data = sorted_

    html = HtmlGeneration(object_, sorted_)
        
    with open(f"{destination}/{'_'.join([x.lower() for x in re.findall('[a-zA-Z][^A-Z]*', object_)])}.html", "w", encoding="utf-8") as file:
        file.write(html)
        
def HtmlGeneration(obj_name, sorted_obj, extra=''):
    html = f'''<p>The following table describes the <code>{obj_name}</code> enumeration members{extra}:</p>

<table class="qc-table table" id="enum-table">
<thead>
    <tr>
        <th style="width: 25%;">Member</th>
        <th style="width: 5%;">Value</th>
        <th style="width: 70%;">Description</th>
    </tr>
</thead>
<tbody>
'''

    for enum, content in sorted_obj:
        html += f'''    <tr>
        <td>{enum}</td>
        <td>{content["code"]}</td>
        <td>{content["description"].replace("<see cref=" + quotation, "<code>").replace(quotation + "/>", "</code>")}</td>
    </tr>
'''

    html += """</tbody>
</table>
"""

    if all([str(y["code"]).replace("-", "").isnumeric() for x, y in sorted_obj]):
        html += '''
<style>
#enum-table td:nth-child(2),
#enum-table th:nth-child(2)
{ text-align: right; }
</style>'''

    return html

for url in enum_objects:
    print(url)
    raw = urlopen(f'{root_url}{url}').read().decode("utf-8").split('\n')
    html = TableCreation(raw)
    
for x in set([m[1] for m in market]):
    items = [y[0] for y in market if y[1] == x]
    html = HtmlGeneration("Market", [y for y in market_data if y[0].lower() in items], f' for {x.replace("option", " Option").title()} markets')
    
    with open(f"{destination}/market-{x}.html", "w", encoding="utf-8") as file:
        file.write(html)