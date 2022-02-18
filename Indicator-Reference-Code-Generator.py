import pathlib
import re
from urllib.request import urlopen

raw = urlopen("https://www.quantconnect.com/services/inspector?type=T:QuantConnect.Algorithm.QCAlgorithm").read().decode("utf-8") \
    .replace("true", "True") \
    .replace("false", "False") \
    .replace("null", "None")
raw_dict = eval(raw)
methods = raw_dict["methods"]

names = {}
descriptions = {}
args = {}
plots = {}
updates = {}

for method in methods:
    if 'QuantConnect.Indicators' in str(method["method-return-type-full-name"]) \
    and str(method["method-return-type-short-name"]) != 'IndicatorBase<IndicatorDataPoint>':
        item = str(method["method-return-type-short-name"])
        names[item] = str(method["method-name"])
        descriptions[item] = str(method["method-description"])
        args[item] = tuple(x["argument-name"] for x in method["method-arguments"])
        plots[item] = []
        
        ind = urlopen(f"https://www.quantconnect.com/services/inspector?type=T:QuantConnect.Indicators.{item}").read().decode("utf-8") \
                .replace("true", "True") \
                .replace("false", "False") \
                .replace("null", "None")
        ind_dict = eval(ind)
        
        for prop in ind_dict["properties"]:
            prop_name = str(prop["property-name"])
            if prop_name != "MovingAverageType" \
            or prop_name != "IsReady" \
            or prop_name != "WarmUpPeriod" \
            or prop_name != "Name" \
            or prop_name != "Samples":
                plots[item].append(prop_name)
                
        for m in ind_dict["methods"]:
            if m["method-name"] == "Update":
                updates[item] = tuple(x["argument-name"] for x in m["method-arguments"])
        
i = 1

for full, short in dict(sorted(names.items())).items():
    name = " ".join(re.split('(?=[A-Z])', full))
    base = f"02 Writing Algorithms/02 User Guides/10 Indicators/07 Indicator Reference/{i:02}{name}"
    destination_folder = pathlib.Path(base)
    destination_folder.mkdir(parents=True, exist_ok=True)
    
    with open(destination_folder / "01 Introduction.html", "w", encoding="utf-8") as html_file:
        html_file.write(f"""<p>
    {descriptions[full]}
</p>""")
        
    with open(destination_folder / "02 Automatic Usage.html", "w", encoding="utf-8") as html_file:
        html_file.write(f"""<p><code>QCAlgorithm</code> provides a shortcut method for each indicator available. Each method creates an indicator object, hooks it up for automatic updates, and returns it to be used in your algorithm.</p>

<p>You can determine the specific requirements of the indicator from the reference table below.</p>

<p>The indicator resolution can be different from the resolution of your securities data. However, the resolution of the indicator should be equal to or higher than the resolution of your security. In most cases, this usage should be in the Initialize method. If you call this method several times, it will create a new indicator that is not ready to use.</p>

<p>To retrieve the numerical value of any indicator, you can use the <code>Current.Value</code> attribute of the indicator.</p>

<div class="section-example-container">
    <pre class="csharp">private {full} _{short.lower()};
// In Initialize()
_{short.lower()} = {short}{str(args[full]).replace("'", "").replace('"', '')};

// In OnData()
if (_{short.lower()}.IsReady)
{{
    var indicatorValue = _{short.lower()}.Current.Value;
}}</pre>
    <pre class="python"># In Initialize()
self.{short.lower()} = self.{short}{str(args[full]).replace("'", "").replace('"', '')}

# In OnData()
if self.{short.lower()}.IsReady:
    indicator_value = self.{short.lower()}.Current.Value
</pre>
</div>""")
        
    with open(destination_folder / "03 Manual Usage.html", "w", encoding="utf-8") as html_file:
        html_file.write(f"""<p>You can create an indicator object that is not bound to any automatic update and choose which data it should use. To use an indicator like this, you create an indicator with its constructor.</p>

<p>To see the LEAN indicator classes available and their constructor arguments, please look them up in the reference table below.</p>

<p>You can use two methods to update the indicator: automatic or manual.</p>

<h4>Automatic Update</h4>
<p>In this method, you will recreate the basic indicator usage: create an indicator with its constructor and register the indicator for automatic updates with the <code>RegisterIndicator()</code> method.</p>
        
<div class="section-example-container">
    <pre class="csharp">private {full} _{short.lower()};
// In Initialize()
_{short.lower()} = new {full}{str(tuple(args[full][i] for i in range(len(args[full])) if i != 0))};
_{short.lower()}.Updated += IndicatorUpdateMethod;

var thirtyMinuteConsolidator = new TradeBarConsolidator(TimeSpan.FromMinutes(30));
SubscriptionManager.AddConsolidator(symbol, thirtyMinuteConsolidator);

RegisterIndicator(symbol, _{short.lower()}, thirtyMinuteConsolidator);

// In IndicatorUpdateMethod()
if (_{short.lower()}.IsReady)
{{
    var indicatorValue = _{short.lower()}.Current.Value;
}}</pre>
    <pre class="python"># In Initialize()
self.{short.lower()} = {full}{str(tuple(args[full][i] for i in range(len(args[full])) if i != 0))}
self.{short.lower()}.Updated += self.IndicatorUpdateMethod

thirty_minute_consolidator = TradeBarConsolidator(timedelta(minutes=30))
self.SubscriptionManager.AddConsolidator(symbol, thirty_minute_consolidator)

self.RegisterIndicator(symbol, self.{short.lower()}, thirty_minute_consolidator)

# In IndicatorUpdateMethod()
if self.{short.lower()}.IsReady:
    indicator_value = self.{short.lower()}.Current.Value</pre>
</div>

<h4>Manual Update</h4>
<p>Updating your indicator manually allows you to control which data is used and create indicators of other non-price fields. For instance, you can use the 3:30 pm price in your daily moving average instead of the after-market closing price, or you may want to use the maximum temperature of the past 10 cloudy days.</p>

<p>The indicator objects have the Update() method that updates the state of an indicator with the given value. Depending on the different types of indicators, this value can be the time/decimal pair, a trade bar, a quote bar, or a custom data bar.</p>

<p>With this method, the indicator will only be ready after the Update() method has been used to pump enough data. For example, a 10-period daily moving average needs to receive ten daily data points through the Update() method.</p>

<div class="section-example-container">
    <pre class="csharp">private {full} _{short.lower()};
// In Initialize()
_{short.lower()} = new {full}{str(tuple(args[full][i] for i in range(len(args[full])) if i != 0)).replace("'", "").replace('"', '')};

// In OnData()
if (data.Bars.ContainsKey(symbol))
{{
    _{short.lower()}.Update{str(updates[full])};
}}
if (_{short.lower()}.IsReady)
{{
    var indicatorValue = _{short.lower()}.Current.Value;
}}</pre>
    <pre class="python"># In Initialize()
self.{short.lower()} = {full}{str(tuple(args[full][i] for i in range(len(args[full])) if i != 0)).replace("'", "").replace('"', '')}

# In OnData()
if data.Bars.ContainsKey(symbol):
    self.{short.lower()}.Update{str(updates[full])}
if self.{short.lower()}.IsReady:
    indicator_value = self.{short.lower()}.Current.Value</pre>
</div>""")
        
    with open(destination_folder / "04 Visualization.html", "w", encoding="utf-8") as html_file:
        html_file.write(f"""<p>We provide a helper method which aims to make plotting indicators simple. For further information on the <a href="/docs/v2/writing-algorithms/user-guides/charting">charting</a> API please see our Charting section.</p>
                        
<div class="section-example-container">
    <pre class="csharp">private {full} _{short.lower()};
// In Initialize()
_{short.lower()} = {short}{str(args[full]).replace("'", "").replace('"', '')};

// In OnData()
if (_{short.lower()}.IsReady)
{{
""")
        
        for x in plots[full]:
            html_file.write(f'''    Plot("My Indicators", "{short}{" ".join(re.split("(?=[A-Z])", x))}", _{short.lower()}.{x});
''')
                            
        html_file.write(f"""}}</pre>
    <pre class="python"># In Initialize()
self.{short.lower()} = self.{short}{str(args[full]).replace("'", "").replace('"', '')}

# In OnData()
if self.{short.lower()}.IsReady:
""")
                            
        for x in plots[full]:
            html_file.write(f'''    self.Plot("My Indicators", "{short}{" ".join(re.split("(?=[A-Z])", x))}", self.{short.lower()}.{x});
''')
                            
        html_file.write(f"""}}</pre>
</div>""")
        
    i += 1