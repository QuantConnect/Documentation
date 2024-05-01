<p>A coarse universe enables you pick a set of stocks based on their trading volume, price, or whether they have fundamental data. To add a coarse universe, in the <code class="csharp">Initialize</code><code class="python">initialize</code> method, pass a filter function to the <code class="csharp">AddUniverse</code><code class="python">add_universe</code> method. The coarse filter function receives a list of <code>CoarseFundamental</code> objects and must return a list of <code>Symbol</code> objects. The <code>Symbol</code> objects you return from the function are the constituents of the universe and LEAN automatically creates subscriptions for them. Don't call <code class="csharp">AddEquity</code><code class="python">add_equity</code> in the filter function.</p>

<div class="section-example-container">
<pre class="csharp">public class MyCoarseUniverseAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        UniverseSettings.Asynchronous = true;
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
    def initialize(self) -&gt; None:
        self.universe_settings.asynchronous = True
        self.add_universe(self.coarse_filter_function)

    def coarse_filter_function(self, coarse: List[CoarseFundamental]) -&gt; List[Symbol]:
        sorted_by_dollar_volume = sorted(coarse, key=lambda x: x.dollar_volume, reverse=True) 
        return [c.symbol for c in sorted_by_dollar_volume[:100]]</pre>
</div>

<p><code>CoarseFundamental</code> objects have the following attributes:</p>

<div data-tree='QuantConnect.Data.UniverseSelection.CoarseFundamental'></div>

<p>The total number of stocks in the <a href='/datasets/quantconnect-us-equity-security-master'>US Equity Security Master dataset</a> is <?=$kpi["us-equity-security-master-size"] ?> but your coarse filter function won't receive all of these at one time because the US Equity Security Master dataset is free of survivorship bias and some of the securities have delisted over time. The number of securities that are passed into your coarse filter function depends on the date of your algorithm. Currently, there are about <?=$kpi["coarse-universe-size"] ?> securities that LEAN passes into your coarse filter function.</p>
