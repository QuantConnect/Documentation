<p>To add a universe of Index Option contracts, in the <code class="csharp">Initialize</code><code class="python">initialize</code> method, call the <code class="csharp">AddIndexOption</code><code class="python">add_index_option</code> method. This method returns an <code>Option</code> object, which contains the canonical <code class="csharp">Symbol</code><code class="python">symbol</code>. You can't trade with the canonical Option <code class="csharp">Symbol</code><code class="python">symbol</code>, but save a reference to it so you can easily access the Option contracts in the <a href='/docs/v2/writing-algorithms/securities/asset-classes/index-options/handling-data#04-Option-Chains'>OptionChain</a> that LEAN passes to the <code class="csharp">OnData</code><code class="python">on_data</code> method. The method to create the universe depends on if the Index Options you want require a target ticker.</p>

<h4>Create Non-Standard Universes</h4>

<p>
	Non-standard Option contracts are popular among Option traders. 
	To create a universe of non-standard Index Options like weekly VIX contracts, pass the index Symbol and target Option ticker to the <code class="csharp">AddIndexOption</code><code class="python">add_index_option</code> method.
</p>

<div class="section-example-container">
    <pre class="csharp">public class WeeklyIndexOptionAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    public override void Initialize()
    {
        var underlying = AddIndex("VIX").Symbol;
        var option = AddIndexOption(underlying, "VIXW");
        option.SetFilter(universe => universe.IncludeWeeklys().Expiration(0, 7).Delta(0.35m, 0.75m));
        _symbol = option.Symbol;
    }

    public override void OnData(Slice data)
    {
        if (data.OptionChains.TryGetValue(_symbol, out var chain))
        {
            var contract = chain.OrderBy(x => x.Expiry).ThenBy(x => x.Greeks.Delta).FirstOrDefault();
        }
    }
}</pre>
    <pre class="python">class WeeklyIndexOptionAlgorithm(QCAlgorithm):

    def initialize(self):
        underlying = self.add_index("VIX").symbol
        option = self.add_index_option(underlying, "VIXW")
        option.set_filter(lambda universe: universe.include_weeklys().expiration(0, 7).delta(0.35, 0.75))
        self._symbol = option.symbol

    def on_data(self, data):
        chain = data.option_chains.get(self._symbol)
        if chain:
            contract = sorted(chain, key=lambda x: (x.expiry, x.greeks.delta))[0]</pre>
</div>

<p>The following table describes the <code class="csharp">AddIndexOption</code><code class="python">add_index_option</code> method arguments for non-standard universes:</p>
<table class="qc-table table">
    <thead>
        <tr>
            <th>Argument</th>
            <th>Data Type</th>
            <th>Description</th>
            <th>Default Value</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>underlying</code></td>
	        <td><code>Symbol</code></td>
            <td>The underlying Index <code>Symbol</code>. To view the supported indices, see <a href='/docs/v2/writing-algorithms/datasets/algoseek/us-index-options#08-Supported-Assets'>Supported Assets</a>.</td>
            <td></td>
        </tr>
        <tr>
            <td><code class="csharp">targetOption</code><code class="python">target_option</code></td>
	        <td><code class="csharp">string</code><code class="python">str</code></td>
            <td>The target Option ticker. To view the supported target Options, see <a href='/docs/v2/writing-algorithms/datasets/algoseek/us-index-options#08-Supported-Assets'>Supported Assets</a>.</td>
            <td></td>
        </tr>
        <tr>
            <td><code>resolution</code></td>
	        <td><code class="csharp">Resolution?</code><code class="python">Resolution/NoneType</code></td>
            <td>The resolution of the market data. To view the supported resolutions, see <a href='/docs/v2/writing-algorithms/securities/asset-classes/index-options/requesting-data/individual-contracts#03-Resolutions'>Resolutions</a>. The Index resolution must be less than or equal to the Index Option resolution. For example, if you set the Index resolution to minute, then you must set the Index Option resolution to minute, hour, or daily.</td>
            <td><code class="python">None</code><code class="csharp">null</code></td>
        </tr>
        <tr>
            <td><code>market</code></td>
	        <td><code class="csharp">string</code><code class="python">str</code></td>
            <td>The Index Option market.</td>
            <td><code>Market.USA</code></td>
        </tr>
        <tr>
            <td><code class="csharp">fillForward</code><code class="python">fill_forward</code></td>
	        <td><code>bool</code></td>
            <td>If true, the current slice contains the last available data even if there is no data at the current time.</td>
            <td><code class="python">True</code><code class="csharp">true</code></td>
        </tr>
    </tbody>
</table>
<p>If you add an Option universe for an underlying Index that you don't have a subscription for, LEAN automatically subscribes to the underlying Index.</p>

<h4>Create Standard Universes</h4>

<p>To create a universe of Index Options based on an index like VIX, SPX, or NDX, pass the index ticker to the <code class="csharp">AddIndexOption</code><code class="python">add_index_option</code> method.</p>

<div class="section-example-container">
    <pre class="csharp">var option = AddIndexOption("SPX");
option.SetFilter(universe => universe.Expiration(0, 60).Delta(0.35m, 0.75m););
 _symbol = option.Symbol;</pre>
    <pre class="python">
option = self.add_index_option("SPX")
option.set_filter(lambda universe: universe.expiration(0, 60).delta(0.35, 0.75))
self._symbol = option.symbol</pre>
</div>

<p>The following table describes the <code class="csharp">AddIndexOption</code><code class="python">add_index_option</code> method arguments for standard universes:</p>
<table class="qc-table table">
    <thead>
        <tr>
            <th>Argument</th>
            <th>Data Type</th>
            <th>Description</th>
            <th>Default Value</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>ticker</code></td>
	        <td><code class="csharp">string</code><code class="python">str</code></td>
            <td>The underlying Index ticker. To view the supported indices, see <a href='/docs/v2/writing-algorithms/datasets/algoseek/us-index-options#08-Supported-Assets'>Supported Assets</a>.</td>
            <td></td>
        </tr>
        <tr>
            <td><code>resolution</code></td>
	        <td><code class="csharp">Resolution?</code><code class="python">Resolution/NoneType</code></td>
            <td>The resolution of the market data. To view the supported resolutions, see <a href='/docs/v2/writing-algorithms/securities/asset-classes/index-options/requesting-data/individual-contracts#03-Resolutions'>Resolutions</a>.</td>
            <td><code class="python">None</code><code class="csharp">null</code></td>
        </tr>
        <tr>
            <td><code>market</code></td>
	        <td><code class="csharp">string</code><code class="python">str</code></td>
            <td>The Index Option market.</td>
            <td><code>Market.USA</code></td>
        </tr>
        <tr>
            <td><code class="csharp">fillForward</code><code class="python">fill_forward</code></td>
	        <td><code>bool</code></td>
            <td>If true, the current slice contains the last available data even if there is no data at the current time.</td>
            <td><code class="python">True</code><code class="csharp">true</code></td>
        </tr>
    </tbody>
</table>
<p>If you add an Option universe for an underlying Index that you don't have a subscription for, LEAN automatically subscribes to the underlying Index and sets its <a href='/docs/v2/writing-algorithms/securities/asset-classes/index/requesting-data#05-Fill-Forward'>fill forward</a> property to match that of the Index Option universe.</p>
