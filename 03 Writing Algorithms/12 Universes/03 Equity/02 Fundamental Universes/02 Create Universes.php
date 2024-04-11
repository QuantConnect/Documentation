    
<p>To add a fundamental universe, in the <code>Initialize</code> method, pass a filter function to the <code>AddUniverse</code> method. The filter function receives a list of <code>Fundamental</code> objects and must return a list of <code>Symbol</code> objects. The <code>Symbol</code> objects you return from the function are the constituents of the fundamental universe and LEAN automatically creates subscriptions for them. Don't call <code>AddEquity</code> in the filter function.</p>
    
<div class="section-example-container">
    <pre class="csharp">public class MyUniverseAlgorithm : QCAlgorithm {
    private Universe _universe;
    public override void Initialize() 
    {
        UniverseSettings.Asynchronous = true;
        _universe = AddUniverse(FundamentalFilterFunction);
    }
        
    private IEnumerable&lt;Symbol&gt; FundamentalFilterFunction(IEnumerable&lt;Fundamental&gt; fundamental) 
    {
         return (from f in fundamental
                where f.HasFundamentalData
                select f.Symbol);
    }
}</pre>
    <pre class="python">class MyUniverseAlgorithm(QCAlgorithm):
    def initialize(self) -&gt; None:
        self.universe_settings.asynchronous = True
        self.universe = self.add_universe(self.fundamental_function)
    
    def fundamental_function(self, fundamental: List[Fundamental]) -&gt; List[Symbol]:
        return [c.symbol for c in fundamental if c.has_fundamental_data]</pre></div>
    
<p><code>Fundamental</code> objects have the following attributes:</p>
<div data-tree='QuantConnect.Data.Fundamental.Fundamental'></div>

<h4>Example</h4>
<p>
The simplest example of accessing the fundamental object would be harnessing the iconic PE ratio for a stock. This is a ratio of the price it commands to the earnings of a stock. The lower the PE ratio for a stock, the more affordable it appears.
</p>
    
<div class="section-example-container">
    <pre class="csharp">// Take the top 50 by dollar volume using fundamental
// Then the top 10 by PERatio using fine
UniverseSettings.Asynchronous = true;
_universe = AddUniverse(
    fundamental =&gt; (from f in fundamental
        where f.Price &gt; 10 &amp;&amp; f.HasFundamentalData &amp;&amp; !Double.IsNaN(f.ValuationRatios.PERatio)
        orderby f.DollarVolume descending).Take(100)
        .OrderBy(f =&gt; f.ValuationRatios.PERatio).Take(10)
        .Select(f =&gt; f.Symbol));</pre>
    <pre class="python"># In Initialize:
self.universe_settings.asynchronous = True
self.universe = self.add_universe(self.fundamental_selection_function)
    
def fundamental_selection_function(self, fundamental: List[Fundamental]) -&gt; List[Symbol]:
    filtered = [f for f in fundamental if f.price &gt; 10 and f.has_fundamental_data and not np.isnan(f.valuation_ratios.pe_ratio)]
    sortedByDollarVolume = sorted(filtered, key=lambda f: f.dollar_volume, reverse=True)[:100]
    sortedByPeRatio = sorted(sortedByDollarVolume, key=lambda f: f.valuation_ratios.pe_ratio, reverse=False)[:10]
    return [f.symbol for f in sortedByPeRatio]</pre>
</div>
    
<h4>Asset Categories</h4>
<p>In addition to valuation ratios, the <a href="https://www.quantconnect.com/datasets/morning-star-us-fundamentals">US Fundamental Data from Morningstar</a> has many other data point attributes, including over 200 different categorization fields for each US stock. Morningstar groups these fields into sectors, industry groups, and industries.</p>

<p>Sectors are large super categories of data. To get the sector of a stock, use the <code>MorningstarSectorCode</code> property.</p>
<div class="section-example-container">
<pre class="csharp">var tech = fundamental.Where(x =&gt; x.AssetClassification.MorningstarSectorCode == MorningstarSectorCode.Technology);</pre>
<pre class="python">tech = [x for x in fundamental if x.asset_classification.morningstar_sector_code == MorningstarSectorCode.technology]
</pre>
</div>

<p>Industry groups are clusters of related industries that tie together. To get the industry group of a stock, use the <code>MorningstarIndustryGroupCode</code> property.</p>
<div class="section-example-container">
<pre class="csharp">var ag = fundamental.Where(x =&gt; x.AssetClassification.MorningstarIndustryGroupCode == MorningstarIndustryGroupCode.Agriculture);</pre>
<pre class="python">ag = [x for x in fundamental if x.asset_classification.morningstar_industry_group_code == MorningstarIndustryGroupCode.agriculture]
</pre>
</div>

<p>Industries are the finest level of classification available. They are the individual industries according to the Morningstar classification system. To get the industry of a stock, use the <code>MorningstarIndustryCode</code>.</p>
<div class="section-example-container">
<pre class="csharp">var coal = fundamental.Where(x =&gt; x.AssetClassification.MorningstarIndustryCode == MorningstarSectorCode.Coal);</pre>
<pre class="python">coal = [x for x in fundamental if x.asset_classification.morningstar_industry_code == MorningstarSectorCode.coal]
</pre>
</div>


<h4>Practical Limitations</h4>
<p>
Fundamental universes allow you to select an unlimited universe of assets to analyze. Each asset in the universe consumes approximately 5MB of RAM, so you may quickly run out of memory if your universe filter selects many assets. If you backtest your algorithms in the Algorithm Lab, familiarize yourself with the RAM capacity of your <a href='/docs/v2/cloud-platform/organizations/resources#02-Backtesting-Nodes'>backtesting</a> and <a href='/docs/v2/cloud-platform/organizations/resources#04-Live-Trading-Nodes'>live trading nodes</a>. To keep your algorithm fast and efficient, only subscribe to the assets you need.
</p>


<h4>Data Availability</h4>
<p><code>Fundamental</code> objects can have NaN values for some of their properties. Before you sort the <code>Fundamental</code> objects by one of the properties, filter out the objects that have a NaN value for the property.</p>

<div class="section-example-container">
    <pre class="csharp">private IEnumerable&lt;Symbol&gt; FundamentalFilterFunction(IEnumerable&lt;Fundamental&gt; fundamentals) 
{
    return fundamentals
        .Where(f => f.HasFundamentalData && !Double.IsNaN(f.ValuationRatios.PERatio))
        .OrderBy(f => f.ValuationRatios.PERatio)
        .Take(10)
        .Select(x => x.Symbol);
}</pre>
    <pre class="python">def fundamental_selection_function(self, fundamental: List[Fundamental]) -&gt; List[Symbol]:
    filtered = [f for f in fundamental if f.has_fundamental_data and not np.isnan(f.valuation_ratios.pe_ratio)]
    sorted_by_pe_ratio = sorted(filtered, key=lambda f: f.valuation_ratios.pe_ratio)
    return [f.symbol for f in sortedByPeRatio[:10] ]</pre>
</div>