<p>A coarse universe enables you pick a set of stocks based on their trading volume, price, or whether they have fundamental data. To add a coarse universe, in the <code>Initialize</code> method, pass a filter function to the <code>AddUniverse</code> method. The coarse filter function receives a list of <code>CoarseFundamental</code> objects and must return a list of <code>Symbol</code> objects. The <code>Symbol</code> objects you return from the function are the constituents of the universe and LEAN automatically creates subscriptions for them. Don't call <code>AddEquity</code> in the filter function.</p>

<div class="section-example-container">
<pre class="csharp">public class MyCoarseUniverseAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        AddUniverse(CoarseFilterFunction);
    }

    private IEnumerable&lt;Symbol&gt; CoarseFilterFunction(IEnumerable&lt;CoarseFundamental&gt; coarse)
    {
        return (from c in coarse
            orderby c.DollarVolume descending
            select c.Symbol).Take(100);
    }
}</pre>
<pre class="python">class MyCoarseUniverseAlgorithm(QCAlgorithm):
    def Initialize(self) -&gt; None:
        self.AddUniverse(self.CoarseFilterFunction)

    def CoarseFilterFunction(self, coarse: List[CoarseFundamental]) -&gt; List[Symbol]:
        sorted_by_dollar_volume = sorted(coarse, key=lambda x: x.DollarVolume, reverse=True) 
        return [c.Symbol for c in sorted_by_dollar_volume[:100]]</pre>
</div>

<p><code>CoarseFundamental</code> objects have the following attributes:</p>

<div data-tree='QuantConnect.Data.UniverseSelection.CoarseFundamental'></div>

<p>The total number of stocks in the <a href='/datasets/quantconnect-us-equity-security-master'>US Equity Security Master dataset</a> is <?=$kpi["us-equity-security-master-size"] ?> but your coarse filter function won't receive all of these at one time because the US Equity Security Master dataset is free of survivorship bias and some of the securities have delisted over time. The number of securities that are passed into your coarse filter function depends on the date of your algorithm. Currently, there are about <?=$kpi["coarse-universe-size"] ?> securities that LEAN passes into your coarse filter function.</p>


<style>
.work-in-progress {
    width: 100%;
    border: 1px solid #f5ae29;
    border-radius: 5px;
    padding: 15px;
    color: #f5ae29;
}
.tip {
    width: 100%;
    border: 1px solid #f5ae29;
    border-radius: 5px;
    padding: 15px;
    margin-bottom: 20px;
    margin-top: 20px;
}
.tip i {
    color: #f5ae29;
}
.tip-title { 
 font-weight: bold;
color: #f5ae29;
margin-left: 5px;
margin-right: 5px;
}
.tip p { display: inline; }
table th i {
    color: #f5ae29;
}
th.summary {
   font-family: "Courier New"; 
   font-weight: normal;
}
.table.qc-table tbody tr td {
   text-align: left;
}
.implementation {
    font-family: "Courier New";
}
</style>

<p>A fundamental universe lets you select stocks based on corporate fundamental data. This data is powered by <a href="/datasets/morning-star-us-fundamentals">Morningstar®</a> and includes approximately <?=$kpi["fundamental-universe-size"] ?> tickers with 900 properties each. Due to the sheer volume of information, fundamental selection is performed on the output of another universe filter. Think of this process as a 2-stage filter. An initial filter function selects a set of stocks and then a fine fundamental filter function selects a subset of those stocks.</p>


<figure>
<img src="https://cdn.quantconnect.com/docs/i/filters.png" class="img-responsive" alt="Fundamental selection process">
<figcaption>QuantConnect Coarse and Fine Universe Selection</figcaption>
</figure>

<p>To add a fundamental universe, in the <code>Initialize</code> method, pass two filter functions to the <code>AddUniverse</code> method. The first filter function can be a <a href='/docs/v2/writing-algorithms/universes/equity#02-Coarse-Universe-Selection'>coarse universe filter</a>, <a href='/docs/v2/writing-algorithms/universes/equity#03-Dollar-Volume-Selection'>dollar volume filter</a>, or an <a href='/docs/v2/writing-algorithms/universes/equity#04-ETF-Constituents-Selection'>ETF constituents filter</a>. The second filter function receives a list of <code>FineFundamental</code> objects and must return a list of <code>Symbol</code> objects. The list of <code>FineFundamental</code> objects contains a subset of the <code>Symbol</code> objects that the first filter function returned. The <code>Symbol</code> objects you return from the second function are the constituents of the fundamental universe and LEAN automatically creates subscriptions for them. Don't call <code>AddEquity</code> in the filter function.</p>

<div class="tip">
  <i class="fa fa-lightbulb-o"></i><span class="tip-title">Tip:</span>
  <p>Only <?=$kpi["fundamental-universe-size"] ?> assets have fundamental data. If your first filter function receives <code>CoarseFundamental</code> data, you should only select assets that have a true value for their <code>HasFundamentalData</code> property.</p>
</div>

<div class="section-example-container">
<pre class="csharp">
public class MyUniverseAlgorithm : QCAlgorithm {
    public override void Initialize() 
    {
        AddUniverse(CoarseFilterFunction, FineFundamentalFilterFunction);
    }
    // filter based on CoarseFundamental
    IEnumerable&lt;Symbol&gt; CoarseFilterFunction(IEnumerable&lt;CoarseFundamental&gt; coarse) 
    {
         // In addition to further coarse universe selection, ensure the security has fundamental data
         return (from c in coarse
             where c.HasFundamentalData
             select c.Symbol);
    }
    // filter based on FineFundamental
    public IEnumerable&lt;Symbol&gt; FineFundamentalFilterFunction(IEnumerable&lt;FineFundamental&gt; fine)
    {
        // Return a list of Symbols
    }
}
</pre>
<pre class="python">
class MyUniverseAlgorithm(QCAlgorithm):
    def Initialize(self) -&gt; None:
        self.AddUniverse(self.CoarseFilterFunction, self.FineFundamentalFunction)

    def CoarseFilterFunction(self, coarse: List[CoarseFundamental]) -&gt; List[Symbol]:
        # In addition to further coarse universe selection, ensure the security has fundamental data
        return [c.Symbol for c in coarse if c.HasFundamentalData]

    def FineFundamentalFunction(self, fine: List[FineFundamental]) -&gt; List[Symbol]:
        # Return a list of Symbols
</pre>
</div>

<p><code>FineFundamental</code> objects have the following attributes:</p>
<div data-tree='QuantConnect.Data.Fundamental.FineFundamental'></div>



<h4>Example</h4>
<p>
The simplest example of accessing the fundamental object would be harnessing the iconic PE ratio for a stock. This is a ratio of the price it commands to the earnings of a stock. The lower the PE ratio for a stock, the more affordable it appears.
</p>

<div class="section-example-container">
	<pre class="csharp">// Take the top 50 by dollar volume using coarse
// Then the top 10 by PERatio using fine
AddUniverse(
    coarse =&gt; {
        return (from c in coarse
            where c.Price &gt; 10 &amp;&amp; c.HasFundamentalData
            orderby c.DollarVolume descending
            select c.Symbol).Take(50);
    },
    fine =&gt; {
        return (from f in fine
            orderby f.ValuationRatios.PERatio ascending
            select f.Symbol).Take(10);
    });
</pre>
	<pre class="python"># In Initialize:
self.AddUniverse(self.CoarseSelectionFunction, self.FineSelectionFunction)

def CoarseSelectionFunction(self, coarse: List[CoarseFundamental]) -&gt; List[Symbol]:
    sortedByDollarVolume = sorted(coarse, key=lambda x: x.DollarVolume, reverse=True)
    filtered = [x.Symbol for x in sortedByDollarVolume if x.HasFundamentalData]
    return filtered[:50]

def FineSelectionFunction(self, fine: List[FineFundamental]) -&gt; List[Symbol]:
    sortedByPeRatio = sorted(fine, key=lambda x: x.ValuationRatios.PERatio, reverse=False)
    return [x.Symbol for x in sortedByPeRatio[:10]]
</pre>
</div>

<h4>Asset Categories</h4>
<p>In addition to valuation ratios, the <a href="https://www.quantconnect.com/datasets/morning-star-us-fundamentals">US Fundamental Data from Morningstar</a> has many other data point attributes, including over 200 different categorization fields for each US stock. Morningstar groups these fields into sectors, industry groups, and industries.</p>

<p>Sectors are large super categories of data. To get the sector of a stock, use the <code>MorningstarSectorCode</code> property.</p>
<div class="section-example-container">
<pre class="csharp">var tech = fine.Where(x =&gt; x.AssetClassification.MorningstarSectorCode == MorningstarSectorCode.Technology);</pre>
<pre class="python">tech = [x for x in fine if x.AssetClassification.MorningstarSectorCode == MorningstarSectorCode.Technology]
</pre>
</div>

<p>Industry groups are clusters of related industries that tie together. To get the industry group of a stock, use the <code>MorningstarIndustryGroupCode</code> property.</p>
<div class="section-example-container">
<pre class="csharp">var ag = fine.Where(x =&gt; x.AssetClassification.MorningstarIndustryGroupCode == MorningstarIndustryGroupCode.Agriculture);</pre>
<pre class="python">ag = [x for x in fine if x.AssetClassification.MorningstarIndustryGroupCode == MorningstarIndustryGroupCode.Agriculture]
</pre>
</div>

<p>Industries are the finest level of classification available. They are the individual industries according to the Morningstar classification system. To get the industry of a stock, use the <code>MorningstarIndustryCode</code>.</p>
<div class="section-example-container">
<pre class="csharp">var coal = fine.Where(x =&gt; x.AssetClassification.MorningstarIndustryCode == MorningstarSectorCode.Coal);</pre>
<pre class="python">coal = [x for x in fine if x.AssetClassification.MorningstarIndustryCode == MorningstarSectorCode.Coal]
</pre>
</div>


<h4>Practical Limitations</h4>
<p>
Like coarse universes, fine universes allow you to select an unlimited universe of assets to analyze. Each asset in the universe consumes approximately 5MB of RAM, so you may quickly run out of memory if your universe filter selects many assets. If you backtest your algorithms in the Algorithm Lab, familiarize yourself with the RAM capacity of your <a href='/docs/v2/cloud-platform/organizations/resources#02-Backtesting-Nodes'>backtesting</a> and <a href='/docs/v2/cloud-platform/organizations/resources#04-Live-Trading-Nodes'>live trading nodes</a>. To keep your algorithm fast and efficient, only subscribe to the assets you need.
</p>