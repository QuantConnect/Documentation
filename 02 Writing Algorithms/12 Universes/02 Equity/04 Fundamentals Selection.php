<meta name="tag" content="universes">
<meta name="tag" content="coarse universes">
<meta name="tag" content="fine universes">
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

<p>
The universe API supports performing universe selection based on corporate fundamental data. This data is powered by <a href="/datasets/morning-star-us-fundamentals">Morningstar®</a> and includes approximately <?php include(DOCS_RESOURCES."/kpis/fundamental-universe-size.php");?> tickers with 900 properties each. The data comes delivered as a  $[FineFundamental,T:QuantConnect.Data.Fundamental.FineFundamental] type.
</p>

<p>Due to the sheer volume of information, Fundamental selection is performed on the <i>output</i> of the coarse universe. You can think of this as a 2-stage filter; first, coarse universe can select all of the liquid assets, then fine fundamental universe can select those which meet your targets.
</p>


<figure>
<img src="https://cdn.quantconnect.com/docs/i/filters.png" class="img-responsive">
<figcaption>QuantConnect Coarse and Fine Universe Selection</figcaption>
</figure>

<p>
To view all of the $[FineFundamental, T:QuantConnect.Data.Fundamental.FineFundamental] properties, see the <a href="/docs/v2/writing-algorithms/datasets/morningstar/us-fundamental-data#05-Data-Point-Attributes">US Fundmantal Data Point Attributes</a>.
</p>

<div class="tip">
  <i class="fa fa-lightbulb-o"></i><span class="tip-title">Tip:</span>
  <p>Only <?php include(DOCS_RESOURCES."/kpis/fundamental-universe-size.php");?> assets have fundamental data. When working with fundamental data, you should always include the <code>HasFundamentalData</code> filter in your Coarse Universe filter. See the example below for how to do this in your algorithm.</p>
</div>

<h4>Requesting a Fundamental Universe</h4>
<p>
To request a fundamental universe, pass a second filter-function into the <code>AddUniverse()</code> method. The second function handles the filtering of your FineFundamental objects:
</p>
<div class="section-example-container">
<pre class="csharp">
public class MyUniverseAlgorithm : QCAlgorithm {
    public override void Initialize() {
        AddUniverse(MyCoarseFilterFunction, MyFineFundamentalFilterFunction);
    }
    // filter based on CoarseFundamental
    IEnumerable&lt;Symbol&gt; MyCoarseFilterFunction(IEnumerable&lt;CoarseFundamental&gt; coarse) {
         // return list of symbols
    }
    // filter based on FineFundamental
    public IEnumerable&lt;Symbol&gt; FineSelectionFunction(IEnumerable&lt;FineFundamental&gt; fine)
    {
        // return list of symbols
    }
}
</pre>
<pre class="python">
class MyUniverseAlgorithm(QCAlgorithm):
     def Initialize(self):
         self.AddUniverse(self.MyCoarseFilterFunction, self.MyFineFundamentalFunction)

    def MyCoarseFilterFunction(self, coarse):
         pass

    def MyFineFundamentalFunction(self, fine):
         pass
</pre>
</div>

<p>Similar to coarse universe selection, the fine filter function is provided a list of { FineFundamental} objects and must return a list of Symbol objects. If you don't want to make any changes to the current universe, you can return <code>Universe.Unchanged</code>. The Equities you return from the fine filter function are automatically added to your universe. Don't call <code>AddEquity</code>.

<h4>Example 1: From the top 50 stocks with the highest volume, take 10 with lowest PE-ratio.</h4>
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

def CoarseSelectionFunction(self, coarse):
    sortedByDollarVolume = sorted(coarse, key=lambda x: x.DollarVolume, reverse=True)
    filtered = [ x.Symbol for x in sortedByDollarVolume if x.HasFundamentalData ]
    return filtered[:50]

def FineSelectionFunction(self, fine):
    sortedByPeRatio = sorted(fine, key=lambda x: x.ValuationRatios.PERatio, reverse=False)
    return [ x.Symbol for x in sortedByPeRatio[:10] ]
</pre>
</div>
<p>
There are 900 properties you can use to perform your own filtering. We recommend you review the <a href="https://www.quantconnect.com/data#fundamentals/usa/morningstar">data library</a> page dedicated to this data to fully understand each property.
</p>

<h4>Example 2: The "QC-500", 500 companies which are liquid, profitable and more than 1B volume.
<p>
Due to licensing restrictions, QuantConnect does not have the iconic S&amp;P500 index list, however, we have reconstructed a homemade version which is a 90% replication which we call the QC-500. The QC-500 is too large to paste into this documentation, but we have open sourced the implementation for educational purposes. For more information, see the 
<span class="python"><a href="https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/ConstituentsQC500GeneratorAlgorithm.py" target="_BLANK">QC500 example algorithm</a></span>
<span class="csharp"><a href="https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/ConstituentsQC500GeneratorAlgorithm.cs" target="_BLANK">QC500 example algorithm</a></span>.
</p>

<h4>Practical Limitations</h4>
<p>
	Like coarse universes, fine universes allow you to select an unlimited universe of symbols to analyze. Each asset added consumes approximately 5MB of RAM, so you may quickly run out of memory if your universe filter selects many symbols. If you backtest your algorithms in the Algorithm Lab, familiarize yourself with the RAM capacity of your <a href='/docs/v2/our-platform/organizations/resources#02-Backtesting-Nodes'>backtesting</a> and <a href='/docs/v2/our-platform/organizations/resources#04-Live-Trading-Nodes'>live trading nodes</a>. You can help keep your algorithm fast and efficient by only subscribing to the assets you need.
</p>
