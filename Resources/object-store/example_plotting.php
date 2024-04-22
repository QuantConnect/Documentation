<p>You can use the Object Store to plot data from your backtests and live algorithm in the Research Environment. The following example demonstrates how to plot a <a href="/docs/v2/writing-algorithms/indicators/supported-indicators/simple-moving-average">Simple Moving Average</a> indicator that's generated during a backtest.</p>

<ol>
    <li>Create a algorithm, add a data subscription, and add a simple moving average indicator.</li>
    <div class='section-example-container'>
    <pre class='csharp'>public class ObjectStoreChartingAlgorithm : QCAlgorithm
{
    private SimpleMovingAverage _sma;
    private string _content;

    public override void Initialize()
    {
        AddEquity("SPY", Resolution.Minute);
        _sma = SMA("SPY", 22);
    }
}</pre>
    <pre class='python'>class ObjectStoreChartingAlgorithm(QCAlgorithm):
    def initialize(self):
        self.add_equity("SPY")
    
        self.content = ''
        self.sma = self.SMA("SPY", 22)</pre>
    </div>

    <p>The algorithm will save <code class='csharp'>_content</code><code class='python'>self.content</code> to the Object Store.</p>

    <li>Save the indicator data as <code>string</code> in <code class='csharp'>_content</code><code class='python'>self.content</code>.</li>
    <div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice data)
{
    _content += $"{_sma.Current.EndTime},{_sma}\n";
}</pre>
    <pre class='python'>def on_data(self, data: Slice):
    self.plot('SMA', 'Value', self.sma.current.value)
    self.content += f'{self.sma.current.end_time},{self.sma.current.value}\n'</pre>
    </div>
    
    <li>In the <a href='/docs/v2/writing-algorithms/key-concepts/event-handlers#15-End-Of-Algorithm-Events'>OnEndOfAlgorithm</a> method, save the indicator data to the Object Store.</li>
    <div class='section-example-container'>
    <pre class='csharp'>public override void OnEndOfAlgorithm()
{
    ObjectStore.Save("sma_values_csharp", _content);
}</pre>
    <pre class='python'>def on_end_of_algorithm(self):
    self.object_store.save('sma_values_python', self.content)</pre>
    </div>
    
    <li><a href='/docs/v2/research-environment/key-concepts/getting-started#03-Open-Notebooks'>Open the Research Environment</a> and create a <code>QuantBook</code>.</li>

    <div class='section-example-container'>
    <pre class='csharp'>// Execute the following command in first
#load "../Initialize.csx"

// Create a QuantBook object
#load "../QuantConnect.csx"
using QuantConnect;
using QuantConnect.Research;

var qb = new QuantBook();</pre>
    <pre class='python'>qb = QuantBook()</pre>
    </div>

    <li>Read the indicator data from the Object Store.</li>

    <div class='section-example-container'>
    <pre class='csharp'>var content = qb.ObjectStore.Read("sma_values_csharp");</pre>
    <pre class='python'>content = qb.object_store.read("sma_values_python")</pre>
    </div>

    <p>The key you provide must be the same key you used to save the object.</p>

    <li class='python'>Convert the data to a pandas object and create a chart.</li>
    <div class="python section-example-container">
    <pre class="python">data = {}
for line in content.split('\n'):
    csv = line.split(',')
    if len(csv) &gt; 1:
        data[csv[0]] = float(csv[1])

series = pd.Series(data, index=data.keys())
series.plot()</pre>
    </div>

    <li class='csharp'>Import the <code>Plotly.NET</code> and <code>Plotly.NET.LayoutObjects</code> packages.</li>
    <div class="csharp section-example-container">
    <pre class="csharp">#r "../Plotly.NET.dll"
using Plotly.NET;
using Plotly.NET.LayoutObjects;</pre>
    </div>

    <li class='csharp'>Create the <code>Layout</code> object and set the <code>title</code>, <code>xaxis</code>, and <code>yaxis</code> properties.</li>
    <div class="csharp section-example-container">
    <pre class="csharp">var layout = new Layout();
layout.SetValue("title", Title.init("SMA"));

var xAxis = new LinearAxis();
xAxis.SetValue("title", "Time");
layout.SetValue("xaxis", xAxis);

var yAxis = new LinearAxis();
yAxis.SetValue("title", "SMA");
layout.SetValue("yaxis", yAxis);</pre>
    </div>

    <li class='csharp'>Convert the data to a list of <code>DateTime</code> objects for the chart x-axis and a list of <code>decimal</code> objects for the chart y-axis, then create a <code>Chart2D.Chart.Line</code> object with the data.</li>
    <div class="csharp section-example-container">
    <pre class="csharp">var index = new List&lt;DateTimee&gt;();
var values = new List&lt;decimal&gt;();

foreach (var line in content.Split('\n'))
{
    var csv = line.Split(',');
    if (csv.Length &gt; 1)
    {
        index.Add(Parse.DateTime(csv[0]));
        values.Add(decimal.Parse(csv[1]));
    }
}

var chart = Chart2D.Chart.Linee&lt;DateTime, decimal, stringe&gt;(index, values);</pre>
    </div>

    <li class='csharp'>Apply the layout to the <code>Line</code> object and create the <code>HTML</code> object.</li>
    <div class="csharp section-example-container">
    <pre class="csharp">chart.WithLayout(layout);
var result = HTML(GenericChart.toChartHTML(chart));</pre>
    </div>
</ol>

<div class="qc-embed-frame" style="display: inline-block; position: relative; width: 100%; min-height: 100px; min-width: 300px;">
    <div class="qc-embed-dummy" style="padding-top: 56.25%;"></div>
    <div class="qc-embed-element" style="position: absolute; top: 0; bottom: 0; left: 0; right: 0;">
    <iframe class="csharp qc-embed-backtest" height="100%" width="100%" style="border: 1px solid #ccc; padding: 0; margin: 0;" src="https://www.quantconnect.com/terminal/processCache/?request=embedded_backtest_5fb5f5b1bb065a42ef70d4736b10c806.html"></iframe>
    <iframe class="python qc-embed-backtest" height="100%" width="100%" style="border: 1px solid #ccc; padding: 0; margin: 0;" src="https://www.quantconnect.com/terminal/processCache?request=embedded_backtest_c1fd8405f031751c0c18b47f06e95280.html"></iframe>
    </div>
</div>
