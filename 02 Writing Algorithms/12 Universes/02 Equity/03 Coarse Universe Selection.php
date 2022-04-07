<meta name="tag" content="universes">
<meta name="tag" content="coarse universes">

<p>
Coarse Universe selection allows you to pick a set of stocks by its volume, price, or whether it has fundamental data. This is helpful to narrow down your universe to liquid assets, or assets which pass a technical indicator filter. 
</p>

<p>
To use a coarse universe, you must request it using an $[AddUniverse(), M:QuantConnect.Algorithm.QCAlgorithm.AddUniverse] call from the Initialize() method of your algorithm. You should pass in a function that will be used to filter the stocks down to the assets you are interested in using.
</p>
<div class="section-example-container">
<pre class="csharp">public class MyCoarseUniverseAlgorithm : QCAlgorithm {
    public override void Initialize() {
        AddUniverse(MyCoarseFilterFunction);
    }
    // Coarse Filter Function accepts a list of CoarseFundamental Objects. 
    IEnumerable&lt;Symbol&gt; MyCoarseFilterFunction(IEnumerable&lt;CoarseFundamental&gt; coarse) {
         
    }
}
</pre>
<pre class="python">class MyCoarseUniverseAlgorithm(QCAlgorithm):
     def Initialize(self):
         self.AddUniverse(self.MyCoarseFilterFunction)

    def MyCoarseFilterFunction(self, coarse):
         pass
</pre>
</div>

<p>
The coarse filter function is provided a list of $[CoarseFundamental,T:QuantConnect.Data.UniverseSelection.CoarseFundamental] objects. The total number of stocks in the <a href='/datasets/quantconnect-us-equity-security-master'>US Equity Security Master dataset</a> is <?php include(DOCS_RESOURCES."/kpis/us-equity-security-master-size.php");?>, but you won't receive all of these at one time because the US Equity Security Master dataset is free of survivorship-bias and some of the securities have delisted over time. The number of securities that are passed into your coarse filter function depends on the date of your algorithm. Currently, there are about <?php include(DOCS_RESOURCES."/kpis/coarse-universe-size.php"); ?> securities passed into your coarse filter function. The most important properties of the $[CoarseFundamental,T:QuantConnect.Data.UniverseSelection.CoarseFundamental] object are: <code>Price</code> (raw), <code>DollarVolume</code> and <code>HasFundamentaData</code>. <br></p>

<p>Typical examples of filter functions you might want sound like: <br></p>
<h4>Example 1: Take 500 stocks worth more than $10, with more than $10M daily trading volume.</h4>
<p>
The most common use case is selecting a lot of liquid stocks. With coarse this is simple and fast. This example below of a coarse filter function selects the top most liquid 500 stocks over $10 per share.
</p>
<div class="section-example-container">
<pre class="csharp">    IEnumerable&lt;Symbol&gt; MyCoarseFilterFunction(IEnumerable&lt;CoarseFundamental&gt; coarse) {
        // Linq makes this a piece of cake;
        var stocks = (from c in coarse
            where c.DollarVolume &gt; 10000000 &amp;&amp;
                  c.Price &gt; 10
            orderby c.DollarVolume descending
            select c.Symbol).Take(500).ToList();
        return stocks;
    }
</pre>
<pre class="python">    def MyCoarseFilterFunction(self, coarse):
         sortedByDollarVolume = sorted(coarse, key=lambda x: x.DollarVolume, reverse=True)
         filtered = [ x.Symbol for x in sortedByDollarVolume 
                      if x.Price &gt; 10 and x.DollarVolume &gt; 10000000 ]
         return filtered[:500]
</pre>
</div>


<h4>Example 2: Take 10 stocks above their 200-Day EMA with more than $1B daily trading volume.</h4>
<p>
Another common request is to filter the universe by a technical indicator, such as only picking those above their 200-day EMA. The coarse fundamental object has adjusted price and volume information so we can do any price related analysis and return the symbols which pass our filter.
</p>
<div class="section-example-container">
<pre class="csharp">    ConcurrentDictionary&lt;Symbol, SelectionData&gt;
        _stateData = new ConcurrentDictionary&lt;Symbol, SelectionData&gt;();

    // Coarse filter function
    IEnumerable&lt;Symbol&gt; MyCoarseFilterFunction(IEnumerable&lt;CoarseFundamental&gt; coarse) {
        // Linq makes this a piece of cake;
        var stocks = (from c in coarse
            let avg = _stateData.GetOrAdd(c.Symbol, sym =&gt; new SelectionData(200))
            where avg.Update(c.EndTime, c.AdjustedPrice)
            where c.DollarVolume &gt; 1000000000 &amp;&amp;
                  c.Price &gt; avg.Ema
            orderby c.DollarVolume descending
            select c.Symbol).Take(10).ToList();
        return stocks;
    }
</pre>
<pre class="python">    # setup state storage in initialize method
    self.stateData = { }

    def MyCoarseFilterFunction(self, coarse):
        # We are going to use a dictionary to refer the object that will keep the moving averages
        for c in coarse:
            if c.Symbol not in self.stateData:
                self.stateData[c.Symbol] = SelectionData(c.Symbol, 200)

            # Updates the SymbolData object with current EOD price
            avg = self.stateData[c.Symbol]
            avg.update(c.EndTime, c.AdjustedPrice, c.DollarVolume)

        # Filter the values of the dict to those above EMA and more than $1B vol.
        values = [x for x in self.stateData.values() if x.is_above_ema and x.volume &gt; 1000000000]
        
        # sort by the largest in volume.
        values.sort(key=lambda x: x.volume, reverse=True)

        # we need to return only the symbol objects
        return [ x.symbol for x in values[:10] ]
</pre>
</div>
<p>
In this example, we've used a new defined <code>SelectionData</code> class. This is a tidy way to group variables for our universe selection and update any indicators all in a few lines of  code. We highly recommend following this pattern to keep your algorithm tidy and bug free! Below we've put an example of a SelectionData class, but you can make this whatever you need to store your custom universe filters.
</p>
<div class="section-example-container">
<pre class="python">class SelectionData(object):
    def __init__(self, symbol, period):
        self.symbol = symbol
        self.ema = ExponentialMovingAverage(period)
        self.is_above_ema = False
        self.volume = 0

    def update(self, time, price, volume):
        self.volume = volume
        if self.ema.Update(time, price):
            self.is_above_ema = price &gt; ema
</pre>
<pre class="csharp">// example selection data class
private class SelectionData
{
    // variables you need for selection
    public readonly ExponentialMovingAverage Ema;

    // initialize your variables and indicators.
    public SelectionData(int period)
    {
        Ema = new ExponentialMovingAverage(period);
    }

    // update your variables and indicators with the latest data.
    // you may also want to use the History API here.
    public bool Update(DateTime time, decimal value)
    {
        return Ema.Update(time, value);
    }
}
</pre>
</div>

<p>Note that the preceding SelectionData class uses a <a href='/docs/v2/writing-algorithms/indicators/manual-indicators'>manual</a> EMA indicator instead of the <a href='/docs/v2/writing-algorithms/indicators/automatic-indicators'>automatic version</a>.

<h4>Example 3: Take 10 stocks the furthest above their 10 day SMA of volume.</h4>
<p>
Getting the 10-day SMA stock volume is the same process as applying other indicators to data from Example 2. First, you should define a SelectionData class which performs the averaging. For this example, the following class will serve this purpose:
</p>
<div class="section-example-container">
<pre class="python">class SelectionData(object):
    def __init__(self, symbol, period):
        self.symbol = symbol
        self.volume = 0
        self.volume_ratio = 0
        self.sma = SimpleMovingAverage(period)

    def update(self, time, price, volume):
        self.volume = volume
        if self.sma.Update(time, volume):
            # get ratio of this volume bar vs previous 10 before it.
            self.volume_ratio = volume / self.sma.Current.Value 
</pre>
<pre class="csharp">private class SelectionData
{
    public readonly Symbol Symbol;
    public readonly SimpleMovingAverage VolumeSma;
    public decimal VolumeRatio;
    public SelectionData(Symbol symbol, int period)
    {
        Symbol = symbol;
        VolumeSma = new SimpleMovingAverage(period);
    }
    public bool Update(DateTime time, decimal value)
    {
        var ready = VolumeSma.Update(time, value);
        VolumeRatio = value / VolumeSma;
        return ready;
    }
}
</pre>
</div>
<p>
With this helper, we've defined a ratio of today's volume to the historical volumes. We can use this ratio to select assets that are above their 10-day simple moving average and sort the selection by the ones which have had the biggest jump since yesterday.
</p>
<p>We could use this Selection data like so:</p>
<div class="section-example-container">
<pre class="python">def CoarseFilterFunction(self, coarse):
        for c in coarse:
            if c.Symbol not in self.stateData:
                self.stateData[c.Symbol] = SelectionData(c.Symbol, 10)
            avg = self.stateData[c.Symbol]
            avg.update(c.EndTime, c.AdjustedPrice, c.DollarVolume)

        # filter the values of selectionData(sd) above SMA
        values = [sd for sd in self.stateData.values() if sd.volume &gt; sd.sma.Current.Value and sd.volume_ratio &gt; 0]
        
        # sort sd by the largest % jump in volume.
        values.sort(key=lambda sd: sd.volume_ratio, reverse=True)

        # return the top 10 symbol objects
        return [ sd.symbol for sd in values[:10] ]
</pre>
<pre class="csharp"> 
    IEnumerable&lt;Symbol&gt; MyCoarseFilterFunction(IEnumerable&lt;CoarseFundamental&gt; coarse) {
        var stocks = (from c in coarse
            let avg = _stateData.GetOrAdd(c.Symbol, sym =&gt; new SelectionData(10))
            where avg.Update(c.EndTime, c.Volume)
            where c.Volume &gt; avg.VolumeSma
            orderby avg.VolumeRatio descending
            select c.Symbol).Take(10).ToList();
        return stocks;
    }
</pre>
</div>


<h4>Example 4: Take top 10 "fastest moving" stocks with a 50-Day EMA &gt; 200 Day EMA.</h4>
<p>
Complex universe filters can be constructed using the SelectionData helper class pattern. We have implemented a full example of this case in Github, which you can view 
<span class="csharp"><a href="https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/EmaCrossUniverseSelectionAlgorithm.cs" target="_BLANK">here</a></span>
<span class="python"><a href="https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/EmaCrossUniverseSelectionAlgorithm.py" target="_BLANK">here</a></span>. We've also made a Boot Camp for this example, which you can do <a href="/learning/task/153/">here</a>. </p>