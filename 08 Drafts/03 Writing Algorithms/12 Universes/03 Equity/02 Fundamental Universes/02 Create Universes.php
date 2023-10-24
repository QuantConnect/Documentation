    
    <p>To add a fundamental universe, in the <code>Initialize</code> method, pass a filter function to the <code>AddUniverse</code> method. The filter function receives a list of <code>Fundamental</code> objects and must return a list of <code>Symbol</code> objects. The <code>Symbol</code> objects you return from the function are the constituents of the fundamental universe and LEAN automatically creates subscriptions for them. Don't call <code>AddEquity</code> in the filter function.</p>
    
    <div class="section-example-container">
    <pre class="csharp">public class MyUniverseAlgorithm : QCAlgorithm {
    public override void Initialize() 
    {
        AddUniverse(FundamentalFilterFunction);
    }
        
    private IEnumerable&lt;Symbol&gt; FundamentalFilterFunction(IEnumerable&lt;Fundamental&gt; fundamental) 
    {
         return (from f in fundamental
                where f.HasFundamentalData
                select f.Symbol);
    }
}</pre>
    <pre class="python">class MyUniverseAlgorithm(QCAlgorithm):
    def Initialize(self) -&gt; None:
        self.AddUniverse(self.FundamentalFunction)
    
    def FundamentalFunction(self, fundamental: List[Fundamental]) -&gt; List[Symbol]:
        return [c.Symbol for c in fundamental if c.HasFundamentalData]</pre></div>
    
    <p><code>Fundamental</code> objects have the following attributes:</p>
    <div data-tree='QuantConnect.Data.Fundamental.Fundamentals'></div>

    <p>Many of the MorningStar values are <code>MultiPeriodField</code> objects. These objects represent a timespan of data, normally either <code>OneMonth</code>, <code>ThreeMonths</code>, <code>SixMonths</code>, or <code>TwelveMonths</code>. To view the objects, see the the <a href='https://raw.githubusercontent.com/QuantConnect/Lean/master/Common/Data/Fundamental/Generated/MultiPeriodValueTypes.cs' rel='nofollow' target='_blank'>auto-generated classes</a> in the LEAN GitHub repository.</p>
    
    <h4>Example</h4>
    <p>
    The simplest example of accessing the fundamental object would be harnessing the iconic PE ratio for a stock. This is a ratio of the price it commands to the earnings of a stock. The lower the PE ratio for a stock, the more affordable it appears.
    </p>
    
    <div class="section-example-container">
        <pre class="csharp">// Take the top 50 by dollar volume using coarse
// Then the top 10 by PERatio using fine
AddUniverse(
    fundamental =&gt; (from f in fundamental
        where f.Price &gt; 10 &amp;&amp; f.HasFundamentalData
        orderby f.DollarVolume descending).Take(100)
        .OrderBy(f =&gt; f.ValuationRatios.PERatio).Take(10)
        .Select(f =&gt; f.Symbol));</pre>
    <pre class="python"># In Initialize:
self.AddUniverse(self.FundamentalSelectionFunction)
    
def FundamentalSelectionFunction(self, fundamental: List[Fundamental]) -&gt; List[Symbol]:
    filtered = [f for f in fundamental if f.Price &gt; 10 and f.HasFundamentalData]
    sortedByDollarVolume = sorted(filtered, key=lambda f: f.DollarVolume, reverse=True)[:100]
    sortedByPeRatio = sorted(sortedByDollarVolume, key=lambda f: f.ValuationRatios.PERatio, reverse=False)[:10]
    return [f.Symbol for f in sortedByPeRatio]</pre>
    </div>
    
    <h4>Asset Categories</h4>
    <p>In addition to valuation ratios, the <a href="https://www.quantconnect.com/datasets/morning-star-us-fundamentals">US Fundamental Data from Morningstar</a> has many other data point attributes, including over 200 different categorization fields for each US stock. Morningstar groups these fields into sectors, industry groups, and industries.</p>
    
    <p>Sectors are large super categories of data. To get the sector of a stock, use the <code>MorningstarSectorCode</code> property.</p>
    <div class="section-example-container">
    <pre class="csharp">var tech = fundamental.Where(x =&gt; x.AssetClassification.MorningstarSectorCode == MorningstarSectorCode.Technology);</pre>
    <pre class="python">tech = [x for x in fundamental if x.AssetClassification.MorningstarSectorCode == MorningstarSectorCode.Technology]
    </pre>
    </div>
    
    <p>Industry groups are clusters of related industries that tie together. To get the industry group of a stock, use the <code>MorningstarIndustryGroupCode</code> property.</p>
    <div class="section-example-container">
    <pre class="csharp">var ag = fundamental.Where(x =&gt; x.AssetClassification.MorningstarIndustryGroupCode == MorningstarIndustryGroupCode.Agriculture);</pre>
    <pre class="python">ag = [x for x in fundamental if x.AssetClassification.MorningstarIndustryGroupCode == MorningstarIndustryGroupCode.Agriculture]
    </pre>
    </div>
    
    <p>Industries are the finest level of classification available. They are the individual industries according to the Morningstar classification system. To get the industry of a stock, use the <code>MorningstarIndustryCode</code>.</p>
    <div class="section-example-container">
    <pre class="csharp">var coal = fundamental.Where(x =&gt; x.AssetClassification.MorningstarIndustryCode == MorningstarSectorCode.Coal);</pre>
    <pre class="python">coal = [x for x in fundamental if x.AssetClassification.MorningstarIndustryCode == MorningstarSectorCode.Coal]
    </pre>
    </div>
    
    
    <h4>Practical Limitations</h4>
    <p>
    Fundamental universes allow you to select an unlimited universe of assets to analyze. Each asset in the universe consumes approximately 5MB of RAM, so you may quickly run out of memory if your universe filter selects many assets. If you backtest your algorithms in the Algorithm Lab, familiarize yourself with the RAM capacity of your <a href='/docs/v2/cloud-platform/organizations/resources#02-Backtesting-Nodes'>backtesting</a> and <a href='/docs/v2/cloud-platform/organizations/resources#04-Live-Trading-Nodes'>live trading nodes</a>. To keep your algorithm fast and efficient, only subscribe to the assets you need.
    </p>
