<p>The <code>OptionUniverseSelectionModel</code> selects all the available contracts for the Equity Options, Index Options, and Future Options you specify. To use this model, provide a <code class="csharp">refreshInterval</code><code class="python">refresh_interval</code> and a selector function. The <code class="csharp">refreshInterval</code><code class="python">refresh_interval</code> defines how frequently LEAN calls the selector function. The selector function receives a <code class="csharp">DateTime</code><code class="python">datetime</code> object that represents the current Coordinated Universal Time (UTC) and returns a list of <code>Symbol</code> objects. The <code>Symbol</code> objects you return from the selector function are the Options of the universe.</p>

<div class="section-example-container">
	<pre class="csharp">// Run universe selection asynchronously to speed up your algorithm. 
// In this case, you can't rely on the method or algorithm state between filter calls.
UniverseSettings.Asynchronous = true;
// Add a universe of SPY Options.
AddUniverseSelection(
    new OptionUniverseSelectionModel(
        // Refresh the universe daily.
        TimeSpan.FromDays(1), 
        _ => new [] { QuantConnect.Symbol.Create("SPY", SecurityType.Option, Market.USA) }
    )
);</pre>
	<pre class="python">from Selection.OptionUniverseSelectionModel import OptionUniverseSelectionModel 

# Run universe selection asynchronously to speed up your algorithm. 
# In this case, you can't rely on the method or algorithm state between filter calls.
self.universe_settings.asynchronous = True
# Add a universe of SPY Options.
self.set_universe_selection(
    OptionUniverseSelectionModel(
        # Refresh the universe daily.
        timedelta(1), lambda _: [Symbol.create("SPY", SecurityType.OPTION, Market.USA)]
    )
)</pre>
</div>

<p>The following table describes the arguments the model accepts:</p>

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
            <td><code class="csharp">refreshInterval</code><code class="python">refresh_interval</code></td>
	    <td><code class="csharp">TimeSpan</code><code class="python">timedelta</code></td>
            <td>Time interval between universe refreshes</td>
            <td></td>
        </tr>
        <tr>
            <td><code class="csharp">optionChainSymbolSelector</code><code class="python">option_chain_symbol_selector</code></td>
	    <td><code class="csharp">Func&lt;DateTime, IEnumerable&lt;Symbol&gt;&gt;</code><code class="python">Callable[[datetime], list[Symbol]]</code></td>
            <td>A function that selects the Option symbols<br></td>
            <td></td>
        </tr>
        <tr>
            <td><code class="csharp">universeSettings</code><code class="python">universe_settings</code></td>
	    <td><code>UniverseSettings</code></td>
            <td>The <a href="/docs/v2/writing-algorithms/algorithm-framework/universe-selection/universe-settings">universe settings</a>. If you don't provide an argument, the model uses the <code class="csharp">algorithm.UniverseSettings</code><code class="python">algorithm.universe_settings</code> by default.</td>
            <td><code class="csharp">null</code><code class="python">None</code></td>
        </tr>
    </tbody>
</table>

<p>The following example shows how to define the Option chain Symbol selector as an isolated method:</p>

<div class="section-example-container">
	<pre class="csharp">// In the Initialize method, add the OptionUniverseSelectionModel with a custom selection function.
public override void Initialize()
{
    AddUniverseSelection(
        new OptionUniverseSelectionModel(TimeSpan.FromDays(1), SelectOptionChainSymbols)
    );
}

// Define the selection function.
private IEnumerable&lt;Symbol&gt; SelectOptionChainSymbols(DateTime utcTime)
{
    // Equity Options example:
    //var tickers = new[] {"SPY", "QQQ", "TLT"};
    //return tickers.Select(ticker =&gt; QuantConnect.Symbol.Create(ticker, SecurityType.Option, Market.USA));

    // Index Options example:
    //var tickers = new[] {"VIX", "SPX"};
    //return tickers.Select(ticker =&gt; QuantConnect.Symbol.Create(ticker, SecurityType.IndexOption, Market.USA));

    // Future Options example:
    var futureSymbol = QuantConnect.Symbol.Create(Futures.Indices.SP500EMini, SecurityType.Future, Market.CME);
    foreach (var contract in FuturesChain(futureSymbol))
    {
        yield return QuantConnect.Symbol.CreateCanonicalOption(contract.Symbol);
    }
}</pre>
	<pre class="python">from Selection.OptionUniverseSelectionModel import OptionUniverseSelectionModel 

# In the initialize method, add the OptionUniverseSelectionModel with a custom selection function.
def initialize(self) -&gt; None:
    self.add_universe_selection(
        OptionUniverseSelectionModel(timedelta(days=1), self.select_option_chain_symbols)
    )

# Define the selection function.
def select_option_chain_symbols(self, utc_time: datetime) -&gt; list[Symbol]:
    # Equity Options example:
    #tickers = ["SPY", "QQQ", "TLT"]
    #return [Symbol.create(ticker, SecurityType.OPTION, Market.USA) for ticker in tickers]

    # Index Options example:
    #tickers = ["VIX", "SPX"]
    #return [Symbol.create(ticker, SecurityType.INDEX_OPTION, Market.USA) for ticker in tickers]

    # Future Options example:
    future_symbol = Symbol.create(Futures.Indices.SP_500_E_MINI, SecurityType.FUTURE, Market.CME)
    return [Symbol.create_canonical_option(contract.symbol) for contract in self.futures_chain(future_symbol)]</pre>
</div>

<p>This model uses the default Option filter, which selects all of the available Option contracts at the current time step. To use a different filter for the contracts, subclass the <code>OptionUniverseSelectionModel</code> and define a <code class="csharp">Filter</code><code class="csharp">filter</code> method. The <code class="csharp">Filter</code><code class="csharp">filter</code> method accepts and returns an <code>OptionFilterUniverse</code> object to select the Option contracts. The following table describes the methods of the <code>OptionFilterUniverse</code> class:</p>

<? include(DOCS_RESOURCES."/universes/option/option-filter-universe.html"); ?>

<p>The contract filter runs at the first time step of each day.</p>

<p>To move the Option chain Symbol selector outside of the algorithm class, create a universe selection model that inherits the <code>OptionUniverseSelectionModel</code> class.</p>

<div class="section-example-container">
	<pre class="csharp">// In the Initialize method, define the universe settings and add data.
UniverseSettings.Asynchronous = true;
AddUniverseSelection(new EarliestExpiringAtTheMoneyCallOptionUniverseSelectionModel(this));

// Outside of the algorithm class, define the universe selection model.
class EarliestExpiringAtTheMoneyCallOptionUniverseSelectionModel : OptionUniverseSelectionModel
{
    public EarliestExpiringAtTheMoneyCallOptionUniverseSelectionModel(QCAlgorithm algorithm)
            : base(TimeSpan.FromDays(1), utcTime => SelectOptionChainSymbols(algorithm, utcTime)) {}
    
    private static IEnumerable&lt;Symbol&gt; SelectOptionChainSymbols(QCAlgorithm algorithm, DateTime utcTime)
    {
        // Equity Options example:
        //var tickers = new[] {"SPY", "QQQ", "TLT"};
        //return tickers.Select(ticker =&gt; QuantConnect.Symbol.Create(ticker, SecurityType.Option, Market.USA));

        // Index Options example:
        //var tickers = new[] {"VIX", "SPX"};
        //return tickers.Select(ticker =&gt; QuantConnect.Symbol.Create(ticker, SecurityType.IndexOption, Market.USA));

        // Future Options example:
        var futureSymbol = QuantConnect.Symbol.Create(Futures.Indices.SP500EMini, SecurityType.Future, Market.CME);
        foreach (var contract in algorithm.FuturesChain(futureSymbol))
        {
            yield return QuantConnect.Symbol.CreateCanonicalOption(contract.Symbol);
        }
    }
    
    // Create a filter to select contracts that have the strike price within 1 strike level and expire within 7 days.
    protected override OptionFilterUniverse Filter(OptionFilterUniverse filter)
    {
        return filter.Strikes(-1, -1).Expiration(0, 7).CallsOnly();
    }
}
</pre>
	<pre class="python"># In the initialize method, define the universe settings and add data.
self.universe_settings.asynchronous = True
self.add_universe_settings(EarliestExpiringAtTheMoneyCallOptionUniverseSelectionModel(self))

# Outside of the algorithm class, define the universe selection model.
class EarliestExpiringAtTheMoneyCallOptionUniverseSelectionModel(OptionUniverseSelectionModel):
    def __init__(self, algorithm):
        self.algo = algorithm
        super().__init__(timedelta(1), self.select_option_chain_symbols)
    
    def select_option_chain_symbols(self, utc_time: datetime) -> list[Symbol]:
        # Equity Options example:
        #tickers = ["SPY", "QQQ", "TLT"]
        #return [Symbol.create(ticker, SecurityType.OPTION, Market.USA) for ticker in tickers]

        # Index Options example:
        #tickers = ["VIX", "SPX"]
        #return [Symbol.create(ticker, SecurityType.INDEX_OPTION, Market.USA) for ticker in tickers]

        # Future Options example:
        future_symbol = Symbol.create(Futures.Indices.SP_500_E_MINI, SecurityType.FUTURE, Market.CME)
        return [Symbol.create_canonical_option(contract.symbol) for contract in self.algo.futures_chain(future_symbol)]
        
    # Create a filter to select contracts that have the strike price within 1 strike level and expire within 7 days.
    def Filter(self, option_filter_universe: OptionFilterUniverse) -> OptionFilterUniverse:
        return option_filter_universe.strikes(-1, -1).expiration(0, 7).calls_only()</pre>
</div>

<?
$assetClass = "Option";
include(DOCS_RESOURCES."/universes/option/filter-caveats.php");
?>

<p>To override the default <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/options-models/pricing'>pricing model</a> of the Options, <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/options-models/pricing#03-Set-Models'>set a pricing model</a> in a security initializer.</p>

<? include(DOCS_RESOURCES."/reality-modeling/volatility-model.html"); ?>

<p>To view the implementation of this model, see the <span class="csharp"><a target="_blank" rel="nofollow" href="https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Selection/OptionUniverseSelectionModel.cs">LEAN GitHub repository</a></span><span class="python"><a target="_blank" rel="nofollow" href="https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Selection/OptionUniverseSelectionModel.py">LEAN GitHub repository</a></span>.</p>
