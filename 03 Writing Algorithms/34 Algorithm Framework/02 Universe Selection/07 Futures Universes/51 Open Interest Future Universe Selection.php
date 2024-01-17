<p>The <code>OpenInterestFutureUniverseSelectionModel</code> is an extension of the <code>FutureUniverseSelectionModel</code> that selects the contract with the greatest open interest on a daily basis.</p>

<div class="section-example-container">
    <pre class="csharp">AddUniverseSelection(new OpenInterestFutureUniverseSelectionModel(algorithm, futureChainSymbolSelector));</pre>
    <pre class="python">self.AddUniverseSelection(OpenInterestFutureUniverseSelectionModel(algorithm, futureChainSymbolSelector));</pre>
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
            <td><code>futureChainSymbolSelector</code></td>
	    <td>
		    <code class="csharp">Func&lt;DateTime, IEnumerable&lt;Symbol&gt;&gt;</code>
		    <code class="python">Callable[[datetime], List[Symbol]]</code>
	    </td>
            <td>A function that selects the Future symbols for a given Coordinated Universal Time (UTC). To view the supported assets in the US Futures dataset, see <a href='/docs/v2/writing-algorithms/datasets/algoseek/us-futures#07-Supported-Assets'>Supported Assets</a>.<br></td>
            <td></td>
        </tr>
        <tr>
            <td><code>chainContractsLookupLimit</code></td>
	    <td>
		    <code class='csharp'>int?</code>
		    <code class='python'>int/NoneType</code>
	    </td>
            <td>Limit on how many contracts to query for open interest</td>
            <td>6</td>
        </tr>
        <tr>
            <td><code>resultsLimit</code></td>
	    <td>
		    <code class='csharp'>int?</code>
		    <code class='python'>int/NoneType</code>
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

<div class="section-example-container">
    <pre class="csharp">public override void Initialize()
{
    var symbols = new[] {
        QuantConnect.Symbol.Create(Futures.Indices.SP500EMini, SecurityType.Future, Market.CME),
        QuantConnect.Symbol.Create(Futures.Metals.Gold, SecurityType.Future, Market.COMEX)
    }; 
    AddUniverseSelection(new OpenInterestFutureUniverseSelectionModel(this, utcTime =&gt; symbols));
}</pre>
    <pre class='python'>def Initialize(self):
    symbols = [
        Symbol.Create(Futures.Indices.SP500EMini, SecurityType.Future, Market.CME),
        Symbol.Create(Futures.Metals.Gold, SecurityType.Future, Market.COMEX)
    ]
    universe = OpenInterestFutureUniverseSelectionModel(self, lambda utc_time: symbols)
    self.AddUniverseSelection(universe)</pre>
</div>

<p>To move the selection functions outside of the algorithm class, create a universe selection model that inherits the <code>OpenInterestFutureUniverseSelectionModel</code> class.</p>

<div class="section-example-container">
    <pre class="csharp">// In Initialize
AddUniverseSelection(new GoldOpenInterestFutureUniverseSelectionModel(this));

// Outside of the algorithm class
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
    <pre class='python'># In Initialize
self.AddUniverseSelection(GoldOpenInterestFutureUniverseSelectionModel(self))
    
# Outside of the algorithm class
class GoldOpenInterestFutureUniverseSelectionModel(OpenInterestFutureUniverseSelectionModel):
    def __init__(self, algorithm: QCAlgorithm, chainContractsLookupLimit: int = 6, resultsLimit: int = 1):
        super().__init__(algorithm, self.select_future_chain_symbols, chainContractsLookupLimit, resultsLimit)

    def select_future_chain_symbols(self, utcTime: datetime) -> List[Symbol]:
        return [Symbol.Create(Futures.Metals.Gold, SecurityType.Future, Market.COMEX)]</pre>
</div>

<?
include(DOCS_RESOURCES."/algorithm-framework/continuous-futures.html");
?>
	
<p>To view the implementation of this model, see the <a target="_blank" rel="nofollow" href="https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Selection/OpenInterestFutureUniverseSelectionModel.cs">LEAN GitHub repository</a>.</p>
