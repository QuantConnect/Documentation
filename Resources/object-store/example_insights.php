<p>Follow these steps to use the Object Store to preserve the algorithm state across live deployments:</p>

<ol>
    <li>Create an algorithm that adds insights to the <a href='/docs/v2/writing-algorithms/algorithm-framework/insight-manager'>Insight Manager</a> and defines an Object Store key for the Insight objects.</li>
    <div class='section-example-container'>
    <pre class='csharp'>public class ObjectStoreChartingAlgorithm : QCAlgorithm
{
    private string _insightKey = $"{ProjectId}/insights";
    public override void Initialize()
    {
        SetUniverseSelection(new ManualUniverseSelectionModel(QuantConnect.Symbol.Create("SPY", SecurityType.Equity, Market.USA)));
        SetAlpha(new ConstantAlphaModel(InsightType.Price, InsightDirection.Up, TimeSpan.FromDays(5), 0.025, null));    
    }
}</pre>
    <pre class='python'>class ObjectStoreChartingAlgorithm(QCAlgorithm):
    def Initialize(self):
        self.insight_key = f"{self.ProjectId}/insights"
        self.SetUniverseSelection(ManualUniverseSelectionModel([ Symbol.Create("SPY", SecurityType.Equity, Market.USA) ]))
        self.SetAlpha(ConstantAlphaModel(InsightType.Price, InsightDirection.Up, timedelta(5), 0.025, None))</pre>
    </div>
    
    <li class='python'>At the top of the algorithm file, add the following imports:</li>
    
    <div class='python section-example-container'>
        <pre class='python'>from Newtonsoft.Json import JsonConvert
from System.Collections.Generic import List</pre>
    </div>
    
    <p class='python'><code>Insight</code> objects are a C# objects, so you need the preceding C# libraries to serialize and deserialize them.</p>

    <li>In the <a href='/docs/v2/writing-algorithms/key-concepts/event-handlers#15-End-Of-Algorithm-Events'>OnEndOfAlgorithm</a> event handler of the algorithm, <a href='/docs/v2/writing-algorithms/algorithm-framework/insight-manager#04-Get-Insights'>get the Insight objects</a> and save them in the Object Store as a JSON object.</li>
    <div class='section-example-container'>
    <pre class='csharp'>public override void OnEndOfAlgorithm()
{
    var insights = Insights.GetInsights(x => x.IsActive(UtcTime));
    ObjectStore.SaveJson(_insightKey, insights);
}</pre>
    <pre class='python'>def OnEndOfAlgorithm(self):
    insights = self.Insights.GetInsights(lambda x: x.IsActive(self.UtcTime))
    content = ','.join([JsonConvert.SerializeObject(x) for x in insights])
    self.ObjectStore.Save(self.insight_key, f'[{content}]')</pre>
    </div>

    <li>At the bottom of the <code>Initialize</code> method, read the Insight objects from the Object Store and <a href='/docs/v2/writing-algorithms/algorithm-framework/insight-manager#02-Add-Insights'>add them to the Insight Manager</a>.</li>
    <div class='section-example-container'>
    <pre class='csharp'>if (ObjectStore.ContainsKey(_insightKey))
{
    var insights = ObjectStore.ReadJson&lt;List&lt;Insight&gt;&gt;(_insightKey);
    Insights.AddRange(insights);
}</pre>
    <pre class='python'>if self.ObjectStore.ContainsKey(self.insight_key):
    insights = self.ObjectStore.ReadJson[List[Insight]](self.insight_key)
    self.Insights.AddRange(insights)</pre>
    </div>
</ol>

<p>The following algorithm provides a full example of preserving the Insight state between deployments:</p>

<div class="qc-embed-frame" style="display: inline-block; position: relative; width: 100%; min-height: 100px; min-width: 300px;">
    <div class="qc-embed-dummy" style="padding-top: 56.25%;"></div>
    <div class="qc-embed-element" style="position: absolute; top: 0; bottom: 0; left: 0; right: 0;">
    <iframe class="csharp qc-embed-backtest" height="100%" width="100%" style="border: 1px solid #ccc; padding: 0; margin: 0;" src="https://www.quantconnect.com/terminal/processCache?request=embedded_backtest_2c647eaeef59f2db0b9b957e7a4c014f.html"></iframe>
    <iframe class="python qc-embed-backtest" height="100%" width="100%" style="border: 1px solid #ccc; padding: 0; margin: 0;" src="https://www.quantconnect.com/terminal/processCache?request=embedded_backtest_5042a67266802c124cacc99ce9ab2499.html"></iframe>
    </div>
</div>
