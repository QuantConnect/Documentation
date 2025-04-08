<p>Follow these steps to use the Object Store to preserve the algorithm state across live deployments:</p>

<ol>
    <li>Create an algorithm that defines a storage key and adds insights to the <a href='/docs/v2/writing-algorithms/algorithm-framework/insight-manager'>Insight Manager</a>.</li>
    <div class='section-example-container'>
    <pre class='csharp'>public class ObjectStoreChartingAlgorithm : QCAlgorithm
{
    private string _insightKey;
    public override void Initialize()
    {
        _insightKey = $"{ProjectId}/insights";
        SetUniverseSelection(new ManualUniverseSelectionModel(QuantConnect.Symbol.Create("SPY", SecurityType.Equity, Market.USA)));
        SetAlpha(new ConstantAlphaModel(InsightType.Price, InsightDirection.Up, TimeSpan.FromDays(5), 0.025, null));    
    }
}</pre>
    <pre class='python'>class ObjectStoreChartingAlgorithm(QCAlgorithm):
    def initialize(self):
        self.insight_key = f"{self.project_id}/insights"
        self.set_universe_selection(ManualUniverseSelectionModel([ Symbol.create("SPY", SecurityType.EQUITY, Market.USA) ]))
        self.set_alpha(ConstantAlphaModel(InsightType.PRICE, InsightDirection.UP, timedelta(5), 0.025, None))</pre>
    </div>
    
    <li class='python'>At the top of the algorithm file, add the following imports:</li>
    
    <div class='python section-example-container'>
        <pre class='python'>from Newtonsoft.Json import JsonConvert
from System.Collections.Generic import List</pre>
    </div>
    
    <p class='python'><code>Insight</code> objects are a C# objects, so you need the preceding C# libraries to serialize and deserialize them.</p>

    <li>In the <a href='/docs/v2/writing-algorithms/key-concepts/event-handlers#15-End-Of-Algorithm-Events'><span class="csharp">OnEndOfAlgorithm</span><span class="python">on_end_of_algorithm</span></a> event handler of the algorithm, <a href='/docs/v2/writing-algorithms/algorithm-framework/insight-manager#04-Get-Insights'>get the Insight objects</a> and save them in the Object Store as a JSON object.</li>
    <div class='section-example-container'>
    <pre class='csharp'>public override void OnEndOfAlgorithm()
{
    var insights = Insights.GetInsights(x => x.IsActive(UtcTime));
    ObjectStore.SaveJson(_insightKey, insights);
}</pre>
    <pre class='python'>def on_end_of_algorithm(self):
    insights = self.insights.get_insights(lambda x: x.is_active(self.utc_time))
    content = ','.join([JsonConvert.SerializeObject(x) for x in insights])
    self.object_store.save(self.insight_key, f'[{content}]')</pre>
    </div>

    <li>At the bottom of the <code class="csharp">Initialize</code><code class="python">initialize</code> method, read the Insight objects from the Object Store and <a href='/docs/v2/writing-algorithms/algorithm-framework/insight-manager#11-Add-Insights-to-Self-Managed-Insight-Collections'>add them to the Insight Manager</a>.</li>
    <div class='section-example-container'>
    <pre class='csharp'>if (ObjectStore.ContainsKey(_insightKey))
{
    var insights = ObjectStore.ReadJson&lt;List&lt;Insight&gt;&gt;(_insightKey);
    Insights.AddRange(insights);
}</pre>
    <pre class='python'>if self.object_store.contains_key(self.insight_key):
    insights = self.object_store.read_json[list[Insight]](self.insight_key)
    self.insights.add_range(insights)</pre>
    </div>
</ol>

<p>The following algorithm provides a full example of preserving the Insight state between deployments:</p>

<div class="section-example-container">
    <pre class="csharp">public class ObjectStoreInsightsAlgorithm : QCAlgorithm
{
    private string _insightsKey = "insights";

    public override void Initialize()
    {
        UniverseSettings.Resolution = Resolution.Daily;

        SetStartDate(2023, 4, 1);
        SetEndDate(2023, 4, 11);
        SetCash(100000);

        _insightsKey = $"{ProjectId}/{_insightsKey}";

        // Read the file with the insights
        if (ObjectStore.ContainsKey(_insightsKey))
        {
            // Load cached JSON key value pair from object store, then add to algorithm insights.
            var insights = ObjectStore.ReadJson&lt;List&lt;Insight&gt;&gt;(_insightsKey);
            Log($"Read {insights.Count} insight(s) from the Object Store");
            Insights.AddRange(insights);

            // Delete the key to reuse it
            ObjectStore.Delete(_insightsKey);
        }

        SetUniverseSelection(new ManualUniverseSelectionModel(QuantConnect.Symbol.Create("SPY", SecurityType.Equity, Market.USA)));
        SetAlpha(new ConstantAlphaModel(InsightType.Price, InsightDirection.Up, TimeSpan.FromDays(5), 0.025, null));
        SetPortfolioConstruction(new EqualWeightingPortfolioConstructionModel(Resolution.Daily));
    }

    public override void OnEndOfAlgorithm()
    {
        // Get all active insights.
        var insights = Insights.GetInsights(x =&gt; x.IsActive(UtcTime));
        // If we want to save all insights (expired and active), we can use.
        // var insights = Insights.GetInsights(x =&gt; true);
        Log($"Save {insights.Count} insight(s) to the Object Store.");
        ObjectStore.SaveJson(_insightsKey, insights);
    }
}</pre>
    <pre class="python">class ObjectStoreInsightsAlgorithm(QCAlgorithm):
    def initialize(self) -&gt; None:
        self.universe_settings.resolution = Resolution.DAILY

        self.set_start_date(2023,4,1)
        self.set_end_date(2023,4,11)
        self.set_cash(100000)

        self.insights_key = f"{self.project_id}/insights"

        # Read the file with the insights
        if self.object_store.contains_key(self.insights_key):
            # Load cached JSON key value pair from object store, then add to algorithm insights.
            insights = self.object_store.read_json[list[Insight]](self.insights_key)
            self.log(f"Read {len(insights)} insight(s) from the Object Store")
            self.insights.add_range(insights)

            # Delete the key to reuse it
            self.object_store.delete(self.insights_key)

        self.set_universe_selection(ManualUniverseSelectionModel([ Symbol.create("SPY", SecurityType.EQUITY, Market.USA) ]))
        self.set_alpha(ConstantAlphaModel(InsightType.PRICE, InsightDirection.UP, timedelta(5), 0.025, None))
        self.set_portfolio_construction(EqualWeightingPortfolioConstructionModel(Resolution.DAILY))

    def on_end_of_algorithm(self) -&gt; None:
        # Get all active insights.
        insights = self.insights.get_insights(lambda x: x.is_active(self.utc_time))
        # If we want to save all insights (expired and active), we can use
        # insights = self.insights.get_insights(lambda x: True)
        self.log(f"Save {len(insights)} insight(s) to the Object Store.")
        content = ','.join([JsonConvert.SerializeObject(x) for x in insights])
        self.object_store.save(self.insights_key, f'[{content}]')</pre>
</div>