    
<p>To add a fundamental universe, in the <code class="csharp">Initialize</code><code class="python">initialize</code> method, pass a filter function to the <code class="csharp">AddUniverse</code><code class="python">add_universe</code> method. The filter function receives a list of <code>Fundamental</code> objects and must return a list of <code>Symbol</code> objects. The <code>Symbol</code> objects you return from the function are the constituents of the fundamental universe and LEAN automatically creates subscriptions for them. Don't call <code class="csharp">AddEquity</code><code class="python">add_equity</code> in the filter function.</p>
    
<div class="section-example-container">
    <pre class="csharp">public class MyUniverseAlgorithm : QCAlgorithm {
    private Universe _universe;
    public override void Initialize() 
    {
        UniverseSettings.Asynchronous = true;
        // Adds to universe returned symbols from FunamentalFilterFunction
        _universe = AddUniverse(FundamentalFilterFunction);
    }
        
    private IEnumerable&lt;Symbol&gt; FundamentalFilterFunction(IEnumerable&lt;Fundamental&gt; fundamental) 
    {   // Returns symbols that have fundamental data. 
         return (from f in fundamental
                where f.HasFundamentalData
                select f.Symbol);
    }
}</pre>
    <pre class="python">class MyUniverseAlgorithm(QCAlgorithm):
    def initialize(self) -&gt; None:
        # Algo can run while universe is being updated.
        self.universe_settings.asynchronous = True
        # Filter function that adds symbols to custom universe of securities with fundamentals.
        self._universe = self.add_universe(self._fundamental_function)
    
    def _fundamental_function(self, fundamental: List[Fundamental]) -&gt; List[Symbol]:
        # Returns symbols that have fundamental data. 
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
        orderby f.DollarVolume descending
        select f).Take(100)
        .OrderBy(f =&gt; f.ValuationRatios.PERatio).Take(10)
        .Select(f =&gt; f.Symbol));</pre>
    <pre class="python"># In Initialize:
self.universe_settings.asynchronous = True
# Custom universe created by calling on function (_fundamental_selection_function).
self._universe = self.add_universe(self._fundamental_selection_function)
    
def _fundamental_selection_function(self, fundamental: List[Fundamental]) -&gt; List[Symbol]:
    # Filter for securities with (price &gt; $10), has fundamental data, and has a price_to_earnings ratio. 
    filtered = [f for f in fundamental if f.price &gt; 10 and f.has_fundamental_data and not np.isnan(f.valuation_ratios.pe_ratio)]
    # Sort filtered securities by dollar volume in decending order, keep the top 100 (securities with the most dollar volume). 
    sorted_by_dollar_volume = sorted(filtered, key=lambda f: f.dollar_volume, reverse=True)[:100]
    # Sort the previously sorted 100 by P/E ratio, keep the securities with the lowest 10 P/E ratios. 
    sorted_by_pe_ratio = sorted(sorted_by_dollar_volume, key=lambda f: f.valuation_ratios.pe_ratio, reverse=False)[:10]
    # Return the final selected securities
    return [f.symbol for f in sorted_by_pe_ratio]</pre>
</div>
    
<h4>Asset Categories</h4>
<p>In addition to valuation ratios, the <a href="https://www.quantconnect.com/datasets/morning-star-us-fundamentals">US Fundamental Data from Morningstar</a> has many other data point attributes, including over 200 different categorization fields for each US stock. Morningstar groups these fields into sectors, industry groups, and industries.</p>

<p>Sectors are large super categories of data. To get the sector of a stock, use the <code>MorningstarSectorCode</code> property.</p>
<div class="section-example-container">
<pre class="csharp">// List of securities in the Technology sector.
var tech = fundamental.Where(x =&gt; x.AssetClassification.MorningstarSectorCode == MorningstarSectorCode.Technology);</pre>
<pre class="python"># List of securities in Technology sector. 
tech = [x for x in fundamental if x.asset_classification.morningstar_sector_code == MorningstarSectorCode.TECHNOLOGY]
</pre>
</div>

<p>Industry groups are clusters of related industries that tie together. To get the industry group of a stock, use the <code>MorningstarIndustryGroupCode</code> property.</p>
<div class="section-example-container">
<pre class="csharp">// List of securities in the Agricluture industry group (more generally tied together). 
var ag = fundamental.Where(x =&gt; x.AssetClassification.MorningstarIndustryGroupCode == MorningstarIndustryGroupCode.Agriculture);</pre>
<pre class="python"># List of securities in the Agricluture industry group (more generally tied together). 
ag = [x for x in fundamental if x.asset_classification.morningstar_industry_group_code == MorningstarIndustryGroupCode.AGRICULTURE]
</pre>
</div>

<p>Industries are the finest level of classification available. They are the individual industries according to the Morningstar classification system. To get the industry of a stock, use the <code>MorningstarIndustryCode</code>.</p>
<div class="section-example-container">
<pre class="csharp">// List of securities in the Coal industry (more specific grouping according to Morningstar). 
var coal = fundamental.Where(x =&gt; x.AssetClassification.MorningstarIndustryCode == MorningstarSectorCode.Coal);</pre>
<pre class="python"># List of securities in the Coal industry (more specific grouping according to Morningstar). 
coal = [x for x in fundamental if x.asset_classification.morningstar_industry_code == MorningstarSectorCode.COAL]
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
    // Filtering for securities with P/E ratios without NaN's. 
    // Sort filtered list by ascending order according to P/E ratio.
    // Return symbols of top 10 with the lowest P/E ratio. 
    return fundamentals
        .Where(f => f.HasFundamentalData && !Double.IsNaN(f.ValuationRatios.PERatio))
        .OrderBy(f => f.ValuationRatios.PERatio)
        .Take(10)
        .Select(x => x.Symbol);
}</pre>
    <pre class="python"># Filtering out NaN's. 
def _fundamental_selection_function(self, fundamental: List[Fundamental]) -&gt; List[Symbol]:
    # Filtering for securities with P/E ratios without NaN's. 
    filtered = [f for f in fundamental if f.has_fundamental_data and not np.isnan(f.valuation_ratios.pe_ratio)]
    # Sort filtered list by ascending order according to P/E ratio. 
    sorted_by_pe_ratio = sorted(filtered, key=lambda f: f.valuation_ratios.pe_ratio)
    # Return symbols of top 10 with the lowest P/E ratio. 
    return [f.symbol for f in sortedByPeRatio[:10] ]</pre>
</div>
