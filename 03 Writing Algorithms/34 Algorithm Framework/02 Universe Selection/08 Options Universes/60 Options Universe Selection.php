<p>The <code>OptionUniverseSelectionModel</code> selects all the available contracts for the Equity Options, Index Options, and Future Options you specify. To use this model, provide a <code>refreshInterval</code> and a selector function. The <code>refreshInterval</code><code></code> defines how frequently LEAN calls the selector function. The selector function receives a <code class="csharp">DateTime</code><code class="python">datetime</code> object that represents the current Coordinated Universal Time (UTC) and returns a list of <code>Symbol</code> objects. The <code>Symbol</code> objects you return from the selector function are the Options of the universe.</p>

<div class="section-example-container">
	<pre class="csharp">AddUniverseSelection(new OptionUniverseSelectionModel(refreshInterval, optionChainSymbolSelector));</pre>
	<pre class="python">from Selection.OptionUniverseSelectionModel import OptionUniverseSelectionModel 

self.AddUniverseSelection(OptionUniverseSelectionModel(refreshInterval, optionChainSymbolSelector))</pre>
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
            <td><code>refreshInterval</code></td>
	    <td><code class="csharp">TimeSpan</code><code class="python">timedelta</code></td>
            <td>Time interval between universe refreshes</td>
            <td></td>
        </tr>
        <tr>
            <td><code>optionChainSymbolSelector</code></td>
	    <td><code class="csharp">Func&lt;DateTime, IEnumerable&lt;Symbol&gt;&gt;</code><code class="python">Callable[[datetime], List[Symbol]]</code></td>
            <td>A function that selects the Option symbols<br></td>
            <td></td>
        </tr>
        <tr>
            <td><code>universeSettings</code></td>
	    <td><code>UniverseSettings</code></td>
            <td>Universe settings define attributes of created subscriptions, such as their resolution and the minimum time in universe before they can be removed</td>
            <td><code class="csharp">null</code><code class="python">None</code></td>
        </tr>
    </tbody>
</table>

<p>If you don't provide a <code>universeSettings</code> argument, the <code>algorithm.UniverseSettings</code> is used by default.</p>

<div class="section-example-container">
	<pre class="csharp">public override void Initialize()
{
    AddUniverseSelection(
        new OptionUniverseSelectionModel(TimeSpan.FromDays(1), SelectOptionChainSymbols)
    );
}

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
    var futureContractSymbols = FutureChainProvider.GetFutureContractList(futureSymbol, Time);
    foreach (var symbol in futureContractSymbols)
    {
        yield return QuantConnect.Symbol.CreateCanonicalOption(symbol);
    }
}</pre>
	<pre class="python">from Selection.OptionUniverseSelectionModel import OptionUniverseSelectionModel 

def Initialize(self) -&gt; None:
    universe = OptionUniverseSelectionModel(timedelta(days=1), self.select_option_chain_symbols)
    self.SetUniverseSelection(universe)

def select_option_chain_symbols(self, utc_time: datetime) -&gt; List[Symbol]:
    # Equity Options example:
    #tickers = ["SPY", "QQQ", "TLT"]
    #return [Symbol.Create(ticker, SecurityType.Option, Market.USA) for ticker in tickers]

    # Index Options example:
    #tickers = ["VIX", "SPX"]
    #return [Symbol.Create(ticker, SecurityType.IndexOption, Market.USA) for ticker in tickers]

    # Future Options example:
    future_symbol = Symbol.Create(Futures.Indices.SP500EMini, SecurityType.Future, Market.CME)
    future_contract_symbols = self.FutureChainProvider.GetFutureContractList(future_symbol, self.Time)
    return [Symbol.CreateCanonicalOption(symbol) for symbol in future_contract_symbols]</pre>
</div>

<p>This model uses the default Option filter, which selects all of the available Option contracts at the current time step. To use a different filter for the contracts, subclass the <code>OptionUniverseSelectionModel</code> and define a <code>Filter</code> method. The <code>Filter</code> method accepts and returns an <code>OptionFilterUniverse</code> object to select the Option contracts. The following table describes the methods of the <code>OptionFilterUniverse</code> class:</p>

<? include(DOCS_RESOURCES."/universes/option/option-filter-universe.html"); ?>

<p>Depending on how you define the contract filter, LEAN may call it once a day or at every time step.</p> 

<p>To move the selection functions outside of the algorithm class, create a universe selection model that inherits the <code>OptionUniverseSelectionModel</code> class.</p>

<div class="section-example-container">
	<pre class="csharp">// In Initialize
AddUniverseSelection(new EarliestExpiringAtTheMoneyCallOptionUniverseSelectionModel(this));

// Outside of the algorithm class
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
        var futureContractSymbols = algorithm.FutureChainProvider.GetFutureContractList(futureSymbol, algorithm.Time);
        foreach (var symbol in futureContractSymbols)
        {
            yield return QuantConnect.Symbol.CreateCanonicalOption(symbol);
        }
    }

    protected override OptionFilterUniverse Filter(OptionFilterUniverse filter)
    {
        return filter.Strikes(-1, -1).Expiration(0, 7).CallsOnly().OnlyApplyFilterAtMarketOpen();
    }
}
</pre>
	<pre class="python">from Selection.OptionUniverseSelectionModel import OptionUniverseSelectionModel 

# In Initialize
self.AddUniverseSelection(EarliestExpiringAtTheMoneyCallOptionUniverseSelectionModel(self))

# Outside of the algorithm class
class EarliestExpiringAtTheMoneyCallOptionUniverseSelectionModel(OptionUniverseSelectionModel):
    def __init__(self, algorithm):
        self.algo = algorithm
        super().__init__(timedelta(1), self.select_option_chain_symbols)
    
    def select_option_chain_symbols(self, utc_time: datetime) -> List[Symbol]:
        # Equity Options example:
        #tickers = ["SPY", "QQQ", "TLT"]
        #return [Symbol.Create(ticker, SecurityType.Option, Market.USA) for ticker in tickers]

        # Index Options example:
        #tickers = ["VIX", "SPX"]
        #return [Symbol.Create(ticker, SecurityType.IndexOption, Market.USA) for ticker in tickers]

        # Future Options example:
        future_symbol = Symbol.Create(Futures.Indices.SP500EMini, SecurityType.Future, Market.CME)
        future_contract_symbols = self.algo.FutureChainProvider.GetFutureContractList(future_symbol, self.algo.Time)
        return [Symbol.CreateCanonicalOption(symbol) for symbol in future_contract_symbols]

    def Filter(self, option_filter_universe: OptionFilterUniverse) -> OptionFilterUniverse:
        return option_filter_universe.Strikes(-1, -1).Expiration(0, 7).CallsOnly().OnlyApplyFilterAtMarketOpen()</pre>
</div>

<?
$assetClass = "Option";
include(DOCS_RESOURCES."/universes/option/filter-caveats.php");
?>

<p>To override the default <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/options-models/pricing'>pricing model</a> of the Options, <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/options-models/pricing#04-Set-Models'>set a pricing model</a> in a security initializer.</p>

<? include(DOCS_RESOURCES."/reality-modeling/volatility-model.html"); ?>

<p>To view the implementation of this model, see the <span class="csharp"><a target="_blank" rel="nofollow" href="https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Selection/OptionUniverseSelectionModel.cs">LEAN GitHub repository</a></span><span class="python"><a target="_blank" rel="nofollow" href="https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Selection/OptionUniverseSelectionModel.py">LEAN GitHub repository</a></span>.</p>
