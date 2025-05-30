<p>The <code>OpenInterestFutureUniverseSelectionModel</code> is an extension of the <code>FutureUniverseSelectionModel</code> that selects the contract with the greatest open interest on a daily basis.</p>

<div class="section-example-container">
    <pre class="csharp">// Enable asynchronous universe settings for faster performance.
UniverseSettings.Asynchronous = true;
// Add an OpenInterestFutureUniverseSelectionModel for E-mini S&P 500 Futures, incorporating contracts with high 
// open interest into the trading universe.
AddUniverseSelection(
    new OpenInterestFutureUniverseSelectionModel(
        this, 
        utcTime => new[] { QuantConnect.Symbol.Create(Futures.Indices.SP500EMini, SecurityType.Future, Market.CME) }
    )
);</pre>
    <pre class="python"># Enable asynchronous universe settings for faster performance.
self.universe_settings.asynchronous = True
# Add an OpenInterestFutureUniverseSelectionModel for E-mini S&P 500 Futures, incorporating contracts with high 
# open interest into the trading universe.
self.add_universe_selection(
    OpenInterestFutureUniverseSelectionModel(
        self, 
        lambda utc_time: [Symbol.create(Futures.Indices.SP500E_MINI, SecurityType.FUTURE, Market.CME)]
    )
)</pre>
</div>

<p>The following table describes the arguments the model accepts:</p>

<table class="qc-table table" id='OpenInterestFutureUniverseSelectionModel-args-table'>
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
            <td><code>algorithm</code></td>
	    <td><code>IAlgorithm</code></td>
            <td>Algorithm</td>
            <td></td>
        </tr>
        <tr>
            <td><code class="csharp">futureChainSymbolSelector</code><code class="python">future_chain_symbol_selector</code></td>
	    <td>
		    <code class="csharp">Func&lt;DateTime, IEnumerable&lt;Symbol&gt;&gt;</code>
		    <code class="python">Callable[[datetime], list[Symbol]]</code>
	    </td>
            <td>A function that selects the Future symbols for a given Coordinated Universal Time (UTC). To view the supported assets in the US Futures dataset, see <a href='/docs/v2/writing-algorithms/datasets/algoseek/us-futures#09-Supported-Assets'>Supported Assets</a>.<br></td>
            <td></td>
        </tr>
        <tr>
            <td><code class="csharp">chainContractsLookupLimit</code><code class="python">chain_contracts_lookup_limit</code></td>
	    <td>
		    <code class='csharp'>int?</code>
		    <code class='python'>int/None</code>
	    </td>
            <td>Limit on how many contracts to query for open interest</td>
            <td>6</td>
        </tr>
        <tr>
            <td><code class="csharp">resultsLimit</code><code class="python">results_limit</code></td>
	    <td>
		    <code class='csharp'>int?</code>
		    <code class='python'>int/None</code>
	    </td>
            <td>Limit on how many contracts will be part of the universe</td>
            <td>1</td>
        </tr>
    </tbody>
</table>

<style>
#OpenInterestFutureUniverseSelectionModel-args-table td:last-child, 
#OpenInterestFutureUniverseSelectionModel-args-table th:last-child {
    text-align: right;
}
</style>

<p>The following example shows how to define the Future chain Symbol selector as an isolated method:</p>

<div class="section-example-container">
    <pre class="csharp">// In the Initialize method, define the universe settings and add a universe.
public override void Initialize()
{
    UniverseSettings.Asynchronous = true;  
    AddUniverseSelection(
        new OpenInterestFutureUniverseSelectionModel(this, SelectFutureChainSymbols)
    );
}

// Define the selection function, which returns Symbol objects.
private static IEnumerable&lt;Symbol&gt; SelectFutureChainSymbols(DateTime utcTime)
{
    return new[] {
        QuantConnect.Symbol.Create(Futures.Indices.SP500EMini, SecurityType.Future, Market.CME),
        QuantConnect.Symbol.Create(Futures.Metals.Gold, SecurityType.Future, Market.COMEX)
    };
}

</pre>
    <pre class="python"># In the Initialize method, define the universe settings and add a universe.
def initialize(self) -&gt; None:
    self.universe_settings.asynchronous = True
    self.add_universe_selection(
        OpenInterestFutureUniverseSelectionModel(self, self.select_future_chain_symbols)
    )

# Define the selection function, which returns Symbol objects.
def select_future_chain_symbols(self, utc_time: datetime) -&gt; list[Symbol]:
    return [ 
        Symbol.create(Futures.Indices.SP500E_MINI, SecurityType.FUTURE, Market.CME),
        Symbol.create(Futures.Metals.GOLD, SecurityType.FUTURE, Market.COMEX)
    ]</pre>
</div>

<p>To move the Future chain Symbol selector outside of the algorithm class, create a universe selection model that inherits the <code>OpenInterestFutureUniverseSelectionModel</code> class.</p>

<div class="section-example-container">
    <pre class="csharp">// In the Initialize method, define the universe settings and add a universe.
UniverseSettings.Asynchronous = true;
AddUniverseSelection(new GoldOpenInterestFutureUniverseSelectionModel(this));

// Outside of the algorithm class, define the universe selection model.
class GoldOpenInterestFutureUniverseSelectionModel : OpenInterestFutureUniverseSelectionModel
{
    public GoldOpenInterestFutureUniverseSelectionModel(QCAlgorithm algorithm, 
        int? chainContractsLookupLimit = 6, int? resultsLimit = 1)
        : base(algorithm, SelectFutureChainSymbols, chainContractsLookupLimit, resultsLimit) {}

    private static IEnumerable&lt;Symbol&gt; SelectFutureChainSymbols(DateTime utcTime)
    {
        return new List&lt;Symbol&gt; { 
            QuantConnect.Symbol.Create(Futures.Metals.Gold, SecurityType.Future, Market.COMEX) 
        };
    }
}</pre>
    <pre class='python'># In the Initialize method, define the universe settings and add a universe.
self.universe_settings.asynchronous = True
self.add_universe_selection(GoldOpenInterestFutureUniverseSelectionModel(self))
    
# Outside of the algorithm class, define the universe selection model.
class GoldOpenInterestFutureUniverseSelectionModel(OpenInterestFutureUniverseSelectionModel):
    def __init__(self, algorithm: QCAlgorithm, chain_contracts_lookup_limit: int=6, results_limit: int=1):
        super().__init__(algorithm, self.select_future_chain_symbols, chain_contracts_lookup_limit, results_limit)

    def select_future_chain_symbols(self, utcTime: datetime) -> list[Symbol]:
        return [Symbol.Create(Futures.Metals.GOLD, SecurityType.FUTURE, Market.COMEX)]</pre>
</div>

<?
include(DOCS_RESOURCES."/algorithm-framework/continuous-futures.html");
?>
	
<p>To view the implementation of this model, see the <a target="_blank" rel="nofollow" href="https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Selection/OpenInterestFutureUniverseSelectionModel.cs">LEAN GitHub repository</a>.</p>
