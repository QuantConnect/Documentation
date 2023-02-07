import base64
import json
import numpy as np
import os
import pathlib
import re
import shutil
from urllib.request import urlopen

names = {}
descriptions = {}
args = {}
plots = {}
updates = {}
update_value = {}
full_apis = {}

# get all indicator
raw = json.load(urlopen("https://www.quantconnect.com/services/inspector?type=T:QuantConnect.Algorithm.QCAlgorithm"))
methods = raw["methods"]
methods = [method for method in methods if 'QuantConnect.Indicators' in str(method["method-return-type-full-name"]) \
    and str(method["method-return-type-short-name"]) != 'IndicatorBase<IndicatorDataPoint>']

# get all candlestick pattern indicator
raw_candle = json.load(urlopen("https://www.quantconnect.com/services/inspector?type=T:QuantConnect.Algorithm.CandlestickPatterns"))
methods_candle = raw_candle["methods"]
candle = [str(method["method-return-type-short-name"]) for method in methods_candle]
methods += methods_candle

writing_algorithms = '03 Writing Algorithms'
root = f'{writing_algorithms}/28 Indicators/01 Supported Indicators'
temp_dir = f'tmp/{writing_algorithms}/28 Indicators/01 Supported Indicators'
if os.path.isdir(root):
    shutil.copytree(root, temp_dir, dirs_exist_ok=True,
                    ignore=lambda dir, files: [f for f in files if os.path.isfile(os.path.join(dir, f)) \
                                                and str(f) != "metadata.json" and str(f) != "00.json"])
    shutil.rmtree(root)
    shutil.copytree(temp_dir, root, dirs_exist_ok=True)
    shutil.rmtree(temp_dir)

for method in methods:
    # get name of indicator/candlestick pattern indicator
    item = str(method["method-return-type-short-name"])
    names[item] = str(method["method-name"])
    args[item] = tuple(x["argument-name"] for x in method["method-arguments"] if not x["argument-optional"])
    plots[item] = []
    
    # get description of indicator/candlestick pattern indicator
    if item not in candle:
        ind = json.load(urlopen(f"https://www.quantconnect.com/services/inspector?type=T:QuantConnect.Indicators.{item}"))
        detail_description = str(ind['description']).replace("Represents", "This indicator represents")
        if "Source: " in detail_description:
            link_split = detail_description.split("http")
            detail_description = link_split[0].replace("Source: ", f'<sup><a href="https{link_split[1]}">source</a></sup>'.replace("httpss", "https"))

    else:
        ind = json.load(urlopen(f"https://www.quantconnect.com/services/inspector?type=T:QuantConnect.Indicators.CandlestickPatterns.{item}"))
        detail_description = f"Create a new {ind['description']} to indicate the pattern's presence."

    descriptions[item] = detail_description
    
    # Get properties to be plotted of indicator
    for prop in ind["properties"]:
        prop_name = str(prop["property-name"])
        if "MovingAverageType" not in prop_name\
        and "IsReady" not in prop_name\
        and "WarmUpPeriod" not in prop_name\
        and "Name" not in prop_name\
        and "Period" not in prop_name\
        and "Samples" not in prop_name:
            plots[item].append(prop_name)
            
    # Set update method's arguments for different types of indicator
    while True:
        if "QuantConnect.Indicators.Indicator" in ind["base-type-full-name"]:
            updates[item] = (0, tuple(("data[symbol].EndTime", "data[symbol].High")))
            update_value[item] = "time/decimal pair"
            break
        
        elif "QuantConnect.Indicators.BarIndicator" in ind["base-type-full-name"]:
            updates[item] = (1, tuple(("data.QuoteBars[symbol]",)))
            update_value[item] = "a <code>TradeBar</code>, <code>QuoteBar</code>, or an <code>IndicatorDataPoint</code>"
            break
        
        elif "QuantConnect.Indicators.TradeBarIndicator" in ind["base-type-full-name"]\
            or "QuantConnect.Indicators.CandlestickPatterns.CandlestickPattern" in ind["base-type-full-name"]:
            updates[item] = (2, tuple(("data.Bars[symbol]",)))
            update_value[item] = "a <code>TradeBar</code>"
            break
        
        else:
            end = ind["base-type-full-name"].split(".")[-1]
            ind = json.load(urlopen(f"https://www.quantconnect.com/services/inspector?type=T:QuantConnect.Indicators.{end}"))

# Counter for # of indicators & candlestick pattern indicators
i = 0
k = 0

# Content to be added for indicators with argument of MA & SwissKnife enum
moving_average_table = '<? include(DOCS_RESOURCES."/enumerations/moving_average_type.html"); ?>'
swiss_kinfe_tool_table = '<? include(DOCS_RESOURCES."/enumerations/swiss_army_knife_tool.html"); ?>'
# Special content for TDD indicator
tddRegisterCSharp = lambda manual: f'''private RateOfChange _roc;
private TargetDownsideDeviation _tdd;

// In Initialize()
var symbol = AddEquity("SPY").Symbol;
_roc = {"new RateOfChange(1);" if manual else "ROC(symbol, 1);"}
_tdd = (new TargetDownsideDeviation(period)).Of(_roc);'''
tddRegisterPy = lambda manual: f'''tdd = TargetDownsideDeviation(period)
self.roc = {"RateOfChange(1)" if manual else "self.ROC(symbol, 1)"}
self.tdd = IndicatorExtensions.Of(tdd, self.roc)'''
# Plots that was captured in IDE not research
ide_plot = ["indicator-reference-ADR.png", "indicator-reference-ADVR.png", "indicator-reference-B.png", "indicator-reference-FilteredIdentity.png", "indicator-reference-IntradayVwap.png", "indicator-reference-TP.png", "indicator-reference-TRIN.png", "indicator-reference-VP.png"]

for full, short in dict(sorted(names.items())).items():
    name = " ".join(re.findall('[a-zA-Z][^A-Z]*', full))
   
    if full not in candle:
        i += 1
        base = f"{root}/{i:02} {name}"
        source_link = f"https://raw.githubusercontent.com/QuantConnect/Lean/master/Indicators/{'Stochastics' if full == 'Stochastic' else 'Momersion' if full == 'MomersionIndicator' else full}.cs"
    else:
        k += 1
        base = f"{root}/00 Candlestick Patterns/{k:02} {name}"
        source_link = f"https://raw.githubusercontent.com/QuantConnect/Lean/master/Indicators/CandlestickPatterns/{full}.cs"

    destination_folder = pathlib.Path(base)
    destination_folder.mkdir(parents=True, exist_ok=True)
    source = urlopen(source_link).read().decode("utf-8")
    lines = source.split("\n")
    
    for l in range(len(lines)):
        if f"public {full}" in lines[l]:
            j = l - 1
            temp = {"link": source_link.replace("raw.githubusercontent.com/QuantConnect/Lean", "github.com/QuantConnect/Lean/blob"), "line": l, "summary": "", "args": {}}
            if full in full_apis:
                temp["param"] = full_apis[full][-1]["param"]
            else:
                temp["param"] = {}
            
            while "///" in lines[j]:
                if "</param>" in lines[j] and "<param name=" in lines[j]:
                    param = lines[j].split('"')[1]
                    temp["param"][param] = lines[j].split(">")[1].split("<")[0]
                    
                    if not temp["param"][param].strip():
                        temp["param"][param] = "/"
                    elif temp["param"][param].strip()[-1] != ".":
                        temp["param"][param] = temp["param"][param].strip() + "."
                    
                elif "</summary>" in lines[j]:
                    temp["summary"] = lines[j-1].split("/// ")[-1].replace('<see cref="', '<code>').replace('"/>', '</code>').replace('" />', '</code>')
                    
                    if temp["summary"].strip()[-1] != ".":
                        temp["summary"] = temp["summary"].strip() + "."
                    
                j -= 1
            
            try:
                constructor_args_ = lines[l].split("(")[1].split(")")[0].split(", ")
                constructor_args = []
                current = ""
                on = True
                
                for num in range(len(constructor_args_)):
                    if "<" in constructor_args_[num] and ">" not in constructor_args_[num]:
                        current += constructor_args_[num] + ", "
                        on = False
                        
                    elif "<" not in constructor_args_[num] and ">" in constructor_args_[num]:
                        current += constructor_args_[num]
                        on = True
                        
                    else:
                        current = constructor_args_[num]
                        on = True
                        
                    if on:
                        constructor_args.append(current)
                        current = ""
                    
            except:
                constructor_args = None
                        
            if not constructor_args:
                temp["args"] = {}
                
            else: 
                for x in [a.split(" ") for a in constructor_args]:
                    if "=" in x:
                        ind = x.index("=")
                        temp["args"][f"*{''.join(x[:(ind-1)])}"] = x[ind - 1]
                        
                        if x[ind - 1] in temp["args"]:
                            temp["param"][x[ind - 1]] = f"<span class='qualifier'>(Optional)</span> {temp['param'][x[ind - 1]]} Default: {''.join(x[(ind+1):]).strip()}"
                            
                        else:
                            temp["param"][x[ind - 1]] = f"<span class='qualifier'>(Optional)</span> /. Default: {''.join(x[(ind+1):]).strip()}"
                        
                    elif len(x) == 2:
                        temp["args"][x[0]] = x[1]
            
            if full in full_apis:
                full_apis[full].append(temp)
            else:
                full_apis[full] = [temp]
    
    with open(destination_folder / "01 Introduction.html", "w", encoding="utf-8") as html_file:
        html_file.write(f"""<!-- Code generated by Indicator-Reference-Code-Generator.py -->
                        
<p>{descriptions[full]}</p>""")
    
    moving_average = False
    
    with open(destination_folder / "02 Create Manual Indicators.php", "w", encoding="utf-8") as html_file:
        html_file.write(f"""<!-- Code generated by Indicator-Reference-Code-Generator.py -->
                        
<? include(DOCS_RESOURCES."/qcalgorithm-api/_method_container.html"); ?>

<p>{"You can manually create a <code>" + full + "</code> indicator, so it doesn’t automatically update." if full not in candle else "A candlestick pattern indicator requires manual creation and update with a <code>TradeBar</code> object."} Manual indicators let you update their values with any data you choose. The following reference table describes the <code>{full}</code> constructor.</p>

<? include(DOCS_RESOURCES."/indicators/constructors/{short.lower()}.html"); ?>""")
        
        index_min = np.argmin(np.array([len(full_apis[full][n]["param"].items()) for n in range(len(full_apis[full]))]))
        
        if moving_average:
            html_file.write(moving_average_table)
        
        if full == "SwissArmyKnife":
            html_file.write(swiss_kinfe_tool_table)
            
    with open(f"Resources/indicators/constructors/{short.lower()}.html", "w", encoding="utf-8") as html_file:
        html_file.write(f"""<div class="method-container">
""")
            
        for e, code in enumerate(full_apis[full]):
            html_file.write(f"""
    <div class="method-header">
        <h3>{full}()<span class="method-order">{e+1}/{len(full_apis[full])}</span></h3>
        <pre>
            <font color="#8F9CA3">{full}</font> QuantConnect.Indicators.{"CandlestickPatterns." + full if full in candle else full} (
""")
                
            if len(code["args"].items()) > 0:
                if any(["MovingAverageType" in x for x in code["args"].keys()]):
                    moving_average = True
                
                length = max([len(x) for x in code["args"].keys()]) + 2
                for e, (arg_type, arg_name) in enumerate(code["args"].items()):
                    html_file.write(f"""&emsp;<code>{arg_type}</code>{" " * (length - len(arg_type))}{arg_name}{"," if e != len(code["args"])-1 else ""}
""")
                    
            html_file.write(f""")
        </pre>
    </div>
    
    <div class="method-description">
        <p>{code["summary"]}</p>
    </div>
    
    <div class="details-btn">
        <button class="show-hide-detail" onclick="ShowHide(event, '{full}-{code["line"]}')"><span>Show Details <img src='https://cdn.quantconnect.com/i/tu/api-chevron-show.svg' alt='arrow-show'></span></button>
    </div>
    
    <div class="method-details" id="{full}-{code["line"]}" style="display: none;" >
    
        <div class="parameter-list">
            <table class="parameter-table">
                <th><strong>Parameters</strong></th>
""")
                
            if len(code["args"].items()) == 0:
                html_file.write('            <tr><td colspan="3">This constructor does not take any argument.</td></tr>\n')
            
            else:
                for arg_type, arg_name in code["args"].items():
                    html_file.write(f"""                <tr><td><code>{arg_type}</code></td>
                <td>{arg_name}</td>
                <td>{code["param"][arg_name] if arg_name in code["param"] else "<span class='qualifier'>(Optional)</span> /" if "*" in arg_type else "/"}</td></tr>
""")
                
            html_file.write(f"""            </table>
        </div>

        <div class="method-return">
            <h4>Return</h4>
            <p><code>{full}</code> - The new <code>{full}</code> indicator object.
        </div>

        <div class="method-def">
            <p>Definition at <a href="{code['link']}#L{code['line']}">line {code['line']} of file {'/'.join(code['link'].split('/')[-2:])}.</a></p>
        </div>
        
    </div>
""")
            
        html_file.write("""
</div>""")

    with open(destination_folder / "03 Update Manual Indicators.html", "w", encoding="utf-8") as html_file:
        html_file.write(f"""<!-- Code generated by Indicator-Reference-Code-Generator.py -->
                        
<p>You can update the indicator automatically or manually.</p>

<h4>Automatic Update</h4>
<p>To register a manual indicator for automatic updates with the security data, call the <code>RegisterIndicator</code> method.</p>
        
<div class="section-example-container">
    <pre class="csharp">{"using QuantConnect.Indicators.CandlestickPatterns;" if full in candle else ""}

{f'''{tddRegisterCSharp(True)}
_tdd.Updated += IndicatorUpdateMethod;

RegisterIndicator(symbol, _roc, Resolution.Daily);''' if short.lower() == "tdd" else f'''private {full} _{short.lower()};

// In Initialize()
_{short.lower()} = new {full}{str(tuple(x for x, y in full_apis[full][index_min]["param"].items() if "Optional" not in y)[::-1]).replace("'", "").replace('"', '').replace(',)', ')')};
_{short.lower()}.Updated += IndicatorUpdateMethod;'''}

{'' if short.lower() == "tdd" else f'''RegisterIndicator(symbol, _{short.lower()}, Resolution.Daily);

'''}// In IndicatorUpdateMethod()
if (_{short.lower()}.IsReady)
{{
    var indicatorValue = _{short.lower()}.Current.Value;
}}</pre>
    <pre class="python">{"from QuantConnect.Indicators.CandlestickPatterns import " + full if full in candle else ""}
    
# In Initialize()
{f'''{tddRegisterPy(True)}
self.{short.lower()}.Updated += self.IndicatorUpdateMethod

self.RegisterIndicator(symbol, self.roc, Resolution.Daily)''' if short.lower() == "tdd" else f'''self.{short.lower()} = {full}{str(tuple(x for x, y in full_apis[full][index_min]["param"].items() if "Optional" not in y)[::-1]).replace("'", "").replace('"', '').replace(',)', ')')}
self.{short.lower()}.Updated += self.IndicatorUpdateMethod'''}

{'' if short.lower() == "tdd" else f'''self.RegisterIndicator(symbol, self.{short.lower()}, Resolution.Daily)

'''}# In IndicatorUpdateMethod()
if self.{short.lower()}.IsReady:
    indicator_value = self.{short.lower()}.Current.Value</pre>
</div>

<p>To customize the data that automatically updates the indicator, see <a href="/docs/v2/writing-algorithms/consolidating-data/updating-indicators#03-Custom-Indicator-Periods">Custom Indicator Periods</a> and <a href="/docs/v2/writing-algorithms/consolidating-data/updating-indicators#04-Custom-Indicator-Values">Custom Indicator Values</a>.</p>

<h4>Manual Update</h4>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code>Update</code> method with {update_value[full]}. The indicator will only be ready after you prime it with enough data.</p>

<div class="section-example-container">
    <pre class="csharp">{"using QuantConnect.Indicators.CandlestickPatterns;" if full in candle else ""}
    
{tddRegisterCSharp(True) if short.lower() == "tdd" else f'''private {full} _{short.lower()};
{'private Symbol symbol;' if 'symbol' in args[full] else 'private List&lt;Symbol&gt; symbols;' if 'symbols' in args[full] else ''}

// In Initialize()
_{short.lower()} = new {full}{str(tuple(x for x, y in full_apis[full][index_min]["param"].items() if "Optional" not in y)[::-1]).replace("'", "").replace('"', '').replace(',)', ')').replace('name, ', '').replace('name', '')};
{'symbol = AddEquity("SPY").Symbol;' if 'symbol' in args[full] else 'symbols = new List&lt;Symbol&gt; {AddEquity("SPY").Symbol, AddEquity("QQQ").Symbol};' if 'symbols' in args[full] else ''}'''}

// In OnData()
if ({"data" if updates[full][0] == 0 else "data.QuoteBars" if updates[full][0] == 1 else "data.Bars"}.ContainsKey(_symbol))
{{
    _{short.lower() if short.lower() != "tdd" else "roc"}.Update{str(updates[full][1]).replace("'", "").replace('"', '').replace(',)', ')')};
}}
if (_{short.lower()}.IsReady)
{{
    var indicatorValue = _{short.lower()}.Current.Value;
}}</pre>
    <pre class="python">{"from QuantConnect.Indicators.CandlestickPatterns import " + full if full in candle else ""}
    
# In Initialize()
{tddRegisterPy(True) if short.lower() == "tdd" else f'''self.{short.lower()} = {full}{str(tuple(x for x, y in full_apis[full][index_min]["param"].items() if "Optional" not in y)[::-1]).replace("'", "").replace('"', '').replace(',)', ')').replace('name, ', '').replace('name', '')}'''}
{'self.symbol = self.AddEquity("SPY").Symbol' if 'symbol' in args[full] else 'self.symbols = [self.AddEquity("SPY").Symbol, self.AddEquity("QQQ").Symbol]' if 'symbols' in args[full] else ''}

# In OnData()
if {"data" if updates[full][0] == 0 else "data.QuoteBars" if updates[full][0] == 1 else "data.Bars"}.ContainsKey(self.symbol):
    self.{short.lower() if short.lower() != "tdd" else "roc"}.Update{str(updates[full][1]).replace("'", "").replace('"', '').replace(',)', ')').replace("symbol", "self.symbol")}
if self.{short.lower()}.IsReady:
    indicator_value = self.{short.lower()}.Current.Value</pre>
</div>""")
        
    if full not in candle:
        with open(destination_folder / "04 Create Automatic Indicators.php", "w", encoding="utf-8") as html_file:
            html_file.write(f"""<!-- Code generated by Indicator-Reference-Code-Generator.py -->
                        
<? include(DOCS_RESOURCES."/qcalgorithm-api/_method_container.html"); ?>
                        
<p>The {short} method creates an {full} indicator, sets up a consolidator to update the indicator, and then returns the indicator so you can use it in your algorithm.</p>
<p>The following reference table describes the <code>{short}</code> method:</p>

<? include(DOCS_RESOURCES."/qcalgorithm-api/{short.lower()}.html"); ?>
<br/>{swiss_kinfe_tool_table if full == "SwissArmyKnife" else ""}{moving_average_table if moving_average else ""}

<p>If you don't provide a resolution, it defaults to the security resolution. If you provide a resolution, it must be greater than or equal to the resolution of the security. For instance, if you subscribe to hourly data for a security, you should update its indicator with data that spans 1 hour or longer.</p>
<p>For more information about the selector argument, see <a href="/docs/v2/writing-algorithms/indicators/automatic-indicators#07-Alternative-Price-Fields">Alternative Price Fields</a>.</p>""")
        
    with open(destination_folder / "05 Get Indicator Values.html", "w", encoding="utf-8") as html_file:
        html_file.write(f"""<!-- Code generated by Indicator-Reference-Code-Generator.py -->
                        
<p>To get the value of the indicator, use its <code>Current.Value</code> attribute.</p>

<div class="section-example-container">
    <pre class="csharp">{tddRegisterCSharp(False) if short.lower() == "tdd" else f'''private {full} _{short.lower()};

// In Initialize()
{'var symbol = AddEquity("SPY").Symbol;' if 'symbol' in args[full] else 'var symbols = new[] {AddEquity("SPY").Symbol, AddEquity("QQQ").Symbol};' if 'symbols' in args[full] else ''}
_{short.lower()} = {short}{str(args[full]).replace("'", "").replace('"', '').replace(',)', ')').replace('name, ', '').replace('name', '')};'''}

// In OnData()
if (_{short.lower()}.IsReady)
{{
""")
        
        for x in plots[full]:
            html_file.write(f'''    var {x[0].lower()+x[1:]} = _{short.lower()}.{x}{".Current" if x != "Current" else ""}.Value;
''')
                            
        html_file.write(f"""}}</pre>
    <pre class="python"># In Initialize()
{'symbol = self.AddEquity("SPY").Symbol' if 'symbol' in args[full] else 'symbols = [self.AddEquity("SPY").Symbol, self.AddEquity("QQQ").Symbol]' if 'symbols' in args[full] else ''}
{tddRegisterPy(False) if short.lower() == "tdd" else f'''self.{short.lower()} = self.{short}{str(args[full]).replace("'", "").replace('"', '').replace(',)', ')').replace('name, ', '').replace('name', '')}'''}

# In OnData()
if self.{short.lower()}.IsReady:
""")
                            
        for x in plots[full]:
            html_file.write(f'''    {"_".join([y.lower() for y in re.findall('[A-Z][^A-Z]*', x) if y])} = self.{short.lower()}.{x}{".Current" if x != "Current" else ""}.Value
''')
        
        html_file.write(f"""</pre>
</div>""")
    
    image_file = f"Resources/indicators/plots/indicator-reference-{short if full != 'IntradayVwap' else 'IntradayVwap'}.png"
    if os.path.isfile(image_file):
        with open(destination_folder / "06 Visualization.html", "w", encoding="utf-8") as html_file:
            html_file.write(f"""<!-- Code generated by Indicator-Reference-Code-Generator.py -->
                        
<p>To plot indicator values, in the <code>OnData</code> event handler, call the <code>Plot</code> method.</p>
                        
<div class="section-example-container">
    <pre class="csharp">{tddRegisterCSharp(False) if short.lower() == "tdd" else f'''private {full} _{short.lower()};

// In Initialize()
{'var symbol = AddEquity("SPY").Symbol;' if 'symbol' in args[full] else 'var symbols = new[] {AddEquity("SPY").Symbol, AddEquity("QQQ").Symbol};' if 'symbols' in args[full] else ''}
_{short.lower()} = {short}{str(args[full]).replace("'", "").replace('"', '').replace(',)', ')').replace('name, ', '').replace('name', '')};'''}

// In OnData()
if (_{short.lower()}.IsReady)
{{
""")
        
            for x in plots[full]:
                html_file.write(f'''    Plot("My Indicators", "{x.lower() if x != "Current" else full.lower()}", _{short.lower()}.{f'{x}.Current' if x != "Current" else x}.Value);
''')
                            
            html_file.write(f"""}}</pre>
    <pre class="python"># In Initialize()
{'symbol = self.AddEquity("SPY").Symbol' if 'symbol' in args[full] else 'symbols = [self.AddEquity("SPY").Symbol, self.AddEquity("QQQ").Symbol]' if 'symbols' in args[full] else ''}
{tddRegisterPy(False) if short.lower() == "tdd" else f'''self.{short.lower()} = self.{short}{str(args[full]).replace("'", "").replace('"', '').replace(',)', ')').replace('name, ', '').replace('name', '')}'''}

# In OnData()
if self.{short.lower()}.IsReady:
""")
                            
            for x in plots[full]:
                html_file.write(f'''    self.Plot("My Indicators", "{x.lower() if x != "Current" else full.lower()}", self.{short.lower()}.{f'{x}.Current' if x != "Current" else x}.Value)
''')
        
            with open(image_file, "rb") as image:
                encoded_string = base64.b64encode(image.read())                 
            html_file.write(f"""</pre>
</div>
{"<p>The following image shows the plot values in a <a href='/docs/v2/research-environment/charting/matplotlib'>matplotlib</a> plot. </p>" if image_file.split("/")[-1] not in ide_plot else ""}
<img class="docs-image" src="data:image/png;base64,{encoded_string.decode('utf-8')}">

<p>For more information about plotting indicators, see <a href="/docs/v2/writing-algorithms/indicators/plotting-indicators">Plotting Indicators</a>.</p>""")
            
    else:
        print(f"Image is not found for {short}, no visualization page is generated.")

# Log the number of indicators
with open("Resources/indicators/indicator_count.html", "w", encoding="utf-8") as html_file:
    html_file.write(f"There are {i} indicators.")

# Log the number of candlestick pattern indicators
with open("Resources/indicators/candlestick_pattern_count.html", "w", encoding="utf-8") as html_file:
    html_file.write(f"There are {k} candlestick pattern indicators.")

with open(f"{root}/00 Candlestick Patterns/00.json", "w", encoding="utf-8") as html_file:
    data = {f"{n:02}": "" for n in range(1, k)}
    html_file.write(f"""{{
  "type" : "landing",
  "heading" : "Candlestick Patterns",
  "subHeading" : "",
  "content" : "<p>You can use any of the following candlestick patterns. Click one to learn more.</p>",
  "alsoLinks" : [],
  "featureShortDescription": {str(data).replace("'", '"')}
}}""")
        
with open(f"{root}/00.json", "w", encoding="utf-8") as html_file:
    data = {f"{n:02}": "" for n in range(0, i)}
    html_file.write(f"""{{
  "type" : "landing",
  "heading" : "Supported Indicators",
  "subHeading" : "",
  "content" : "<p>Indicators translate a stream of data points into a numerical value you can use to detect trading opportunities. LEAN provides more than 100 pre-built technical indicators and candlestick patterns you can use in your algorithms. You can use any of the following indicators. Click one to learn more.</p>",
  "alsoLinks" : [],
  "featureShortDescription": {str(data).replace("'", '"')}
}}""")
