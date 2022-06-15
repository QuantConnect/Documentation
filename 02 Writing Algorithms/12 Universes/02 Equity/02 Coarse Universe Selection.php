<p>A coarse universe enables you pick a set of stocks based on their trading volume, price, or whether they have fundamental data. To add a coarse universe, in the <code>Initialize</code> method, pass a filter function to the <code>AddUniverse</code> method. The coarse filter function receives a list of <code>CoarseFundamental</code> objects and must return a list of <code>Symbol</code> objects. The <code>Symbol</code> objects you return from the function are the constituents of the universe and LEAN automatically creates subscriptions for them. Don't call <code>AddEquity</code> in the filter function.</p>

<div class="section-example-container">
<pre class="csharp">public class MyCoarseUniverseAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        AddUniverse(MyCoarseFilterFunction);
    }

    private IEnumerable&lt;Symbol&gt; MyCoarseFilterFunction(IEnumerable&lt;CoarseFundamental&gt; coarse)
    {
        return ;
    }
}
</pre>
<pre class="python">class MyCoarseUniverseAlgorithm(QCAlgorithm):
    def Initialize(self) -&gt; None:
        self.AddUniverse(self.MyCoarseFilterFunction)

    def MyCoarseFilterFunction(self, coarse: List[CoarseFundamental]) -&gt; List[Symbol]:
        sorted_by_dollar_volume = sorted(coarse, key=lambda x: x.DollarVolume, reverse=True) 
        return [c.Symbol for c in sorted_by_dollar_volume[:100]]</pre>
</div>

<p><code>CoarseFundamental</code> objects have the following attributes:</p>

<div data-tree='QuantConnect.Data.UniverseSelection.CoarseFundamental'></div>

<p>The total number of stocks in the <a href='/datasets/quantconnect-us-equity-security-master'>US Equity Security Master dataset</a> is <?php include(DOCS_RESOURCES."/kpis/us-equity-security-master-size.php");?> but your coarse filter function won't receive all of these at one time because the US Equity Security Master dataset is free of survivorship bias and some of the securities have delisted over time. The number of securities that are passed into your coarse filter function depends on the date of your algorithm. Currently, there are about <?php include(DOCS_RESOURCES."/kpis/coarse-universe-size.php"); ?> securities that LEAN passes into your coarse filter function.</p>
