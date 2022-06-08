<p>Coarse Universe selection lets you pick a set of stocks based on <code>CoarseFundamental</code> data. <code>CoarseFundamental</code> objects have the following attributes:</p>

<div data-tree='QuantConnect.Data.UniverseSelection.CoarseFundamental'></div>

<p>
To use a coarse universe, you must request it using an $[AddUniverse(), M:QuantConnect.Algorithm.QCAlgorithm.AddUniverse] call from the Initialize() method of your algorithm. You should pass in a function that will be used to filter the stocks down to the assets you are interested in using.
</p>
<div class="section-example-container">
<pre class="csharp">public class MyCoarseUniverseAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        AddUniverse(MyCoarseFilterFunction);
    }
}
</pre>
<pre class="python">class MyCoarseUniverseAlgorithm(QCAlgorithm):
    def Initialize(self):
        self.AddUniverse(self.MyCoarseFilterFunction)
</pre>
</div>

<p>
The coarse filter function is provided a list of $[CoarseFundamental,T:QuantConnect.Data.UniverseSelection.CoarseFundamental] objects and must return a list of Symbol objects. If you don't want to make any changes to the current universe, you can return <code>Universe.Unchanged</code>. If you only use a coarse filter in your universe selection process, the Equities you return from the function are automatically added to your universe. Don't call <code>AddEquity</code> in this method.</p>

<div class="section-example-container">
<pre class="csharp">public class MyCoarseUniverseAlgorithm : QCAlgorithm
{
    // Coarse Filter Function accepts a list of CoarseFundamental Objects. 
    private IEnumerable&lt;Symbol&gt; MyCoarseFilterFunction(IEnumerable&lt;CoarseFundamental&gt; coarse)
    {
        return Universe.Unchanged;
    }
}
</pre>
<pre class="python">class MyCoarseUniverseAlgorithm(QCAlgorithm):
    def MyCoarseFilterFunction(self, coarse):
         return Universe.Unchanged
</pre>
</div>

<p>The total number of stocks in the <a href='/datasets/quantconnect-us-equity-security-master'>US Equity Security Master dataset</a> is <?php include(DOCS_RESOURCES."/kpis/us-equity-security-master-size.php");?>, but your coarse filter function won't receive all of these at one time because the US Equity Security Master dataset is free of survivorship-bias and some of the securities have delisted over time. The number of securities that are passed into your coarse filter function depends on the date of your algorithm. Currently, there are about <?php include(DOCS_RESOURCES."/kpis/coarse-universe-size.php"); ?> securities passed into your coarse filter function. The most important properties of the $[CoarseFundamental,T:QuantConnect.Data.UniverseSelection.CoarseFundamental] object are: <code>Price</code> (raw), <code>DollarVolume</code> and <code>HasFundamentaData</code>.</p>