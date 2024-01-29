<p>The <code>FutureUniverseSelectionModel</code> selects all the contracts for a set of Futures you specify. To use this model, provide a <code>refreshInterval</code> and a selector function. The <code>refreshInterval</code><code></code> defines how frequently LEAN calls the selector function. The selector function receives a <code class="csharp">DateTime</code><code class="python">datetime</code> object that represents the current Coordinated Universal Time (UTC) and returns a list of <code>Symbol</code> objects. The <code>Symbol</code> objects you return from the selector function are the Futures of the universe.</p>

<div class="section-example-container">
	<pre class="csharp">UniverseSettings.Asynchronous = true;
AddUniverseSelection(
    new FutureUniverseSelectionModel(
        TimeSpan.FromDays(1), 
        _ => new List&lt;Symbol&gt; {{ QuantConnect.Symbol.Create(Futures.Indices.SP500EMini, SecurityType.Future, Market.CME) }}
    )
);</pre>
	<pre class="python">from Selection.FutureUniverseSelectionModel import FutureUniverseSelectionModel

self.UniverseSettings.Asynchronous = True
self.AddUniverseSelection(
    FutureUniverseSelectionModel(
        timedelta(1), 
        lambda _: [Symbol.Create(Futures.Indices.SP500EMini, SecurityType.Future, Market.CME)]
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
            <td><code>refreshInterval</code></td>
	    <td><code class="csharp">TimeSpan</code><code class="python">timedelta</code></td>
            <td>Time interval between universe refreshes</td>
            <td></td>
        </tr>
        <tr>
            <td><code>futureChainSymbolSelector</code></td>
	    <td><code class="csharp">Func&lt;DateTime, IEnumerable&lt;Symbol&gt;&gt;</code><code class="python">Callable[[datetime], List[Symbol]]</code></td>
            <td>A function that selects the Future symbols for a given Coordinated Universal Time (UTC). To view the supported assets in the US Futures dataset, see <a href='/docs/v2/writing-algorithms/datasets/algoseek/us-futures#07-Supported-Assets'>Supported Assets</a>.</td>
            <td></td>
        </tr>
        <tr>
            <td><code>universeSettings</code></td>
	    <td><code>UniverseSettings</code></td>
            <td>The <a href="/docs/v2/writing-algorithms/algorithm-framework/universe-selection/universe-settings">universe settings</a>. If you don't provide an argument, the model uses the <code>algorithm.UniverseSettings</code> by default.</td>
            <td><code class="csharp">null</code><code class="python">None</code></td>
        </tr>
    </tbody>
</table>

<p>The following example shows how to define the Future chain Symbol selector as an isolated method:</p>

<div class="section-example-container">
    <pre class="csharp">public override void Initialize()
{
    AddUniverseSelection(
        new FutureUniverseSelectionModel(TimeSpan.FromDays(1), SelectFutureChainSymbols)
    );
}

private static IEnumerable&lt;Symbol&gt; SelectFutureChainSymbols(DateTime utcTime)
{
    return new[] {
        QuantConnect.Symbol.Create(Futures.Indices.SP500EMini, SecurityType.Future, Market.CME),
        QuantConnect.Symbol.Create(Futures.Metals.Gold, SecurityType.Future, Market.COMEX)
    };
}</pre>
    <pre class="python">from Selection.FutureUniverseSelectionModel import FutureUniverseSelectionModel

def Initialize(self) -&gt; None:
    self.SetUniverseSelection(
        FutureUniverseSelectionModel(timedelta(days=1), self.select_future_chain_symbols)
    )

def select_future_chain_symbols(self, utc_time: datetime) -&gt; List[Symbol]:
    return [ 
        Symbol.Create(Futures.Indices.SP500EMini, SecurityType.Future, Market.CME),
        Symbol.Create(Futures.Metals.Gold, SecurityType.Future, Market.COMEX)
    ]</pre>
</div>

<p>This model uses the default Future contract filter, which doesn't select any Futures contracts. To use a different filter, subclass the <code>FutureUniverseSelectionModel</code> and define a <code>Filter</code> method. The <code>Filter</code> method accepts and returns a <code>FutureFilterUniverse</code> object to select the Futures contracts. The following table describes the filter methods of the <code>FutureFilterUniverse</code> class:</p>

<? echo file_get_contents(DOCS_RESOURCES."/universes/future/future-filter-universe.html");?>
	
<p>The contract filter runs at the first time step of each day.</p>

<p>To move the Future chain Symbol selector and the contract selection function outside of the algorithm class, create a universe selection model that inherits the FundamentalUniverseSelectionModel class and override its Select method.</p>

<div class="section-example-container">
	<pre class="csharp">// In Initialize
UniverseSettings.Asynchronous = true;
AddUniverseSelection(new FrontMonthFutureUniverseSelectionModel());

// Outside of the algorithm class
class FrontMonthFutureUniverseSelectionModel : FutureUniverseSelectionModel
{
    public FrontMonthFutureUniverseSelectionModel()
        : base(TimeSpan.FromDays(1), SelectFutureChainSymbols) {}

    private static IEnumerable&lt;Symbol&gt; SelectFutureChainSymbols(DateTime utcTime)
    {
        return new List&lt;Symbol&gt; {
            QuantConnect.Symbol.Create(Futures.Indices.SP500EMini, SecurityType.Future, Market.CME),
            QuantConnect.Symbol.Create(Futures.Metals.Gold, SecurityType.Future, Market.COMEX)
        };
    }

    protected override FutureFilterUniverse Filter(FutureFilterUniverse filter)
    {
        return filter.FrontMonth();
    }
}</pre>
	<pre class="python">from Selection.FutureUniverseSelectionModel import FutureUniverseSelectionModel

# In Initialize
self.UniverseSettings.Asynchronous = True
self.AddUniverseSelection(FrontMonthFutureUniverseSelectionModel())

# Outside of the algorithm class
class FrontMonthFutureUniverseSelectionModel(FutureUniverseSelectionModel):
    def __init__(self) -> None:
        super().__init__(timedelta(1), self.select_future_chain_symbols)

    def select_future_chain_symbols(self, utc_time: datetime) -> List[Symbol]:
        return [ 
            Symbol.Create(Futures.Indices.SP500EMini, SecurityType.Future, Market.CME),
            Symbol.Create(Futures.Metals.Gold, SecurityType.Future, Market.COMEX) 
        ]

    def Filter(self, filter: FutureFilterUniverse) -> FutureFilterUniverse:
        return filter.FrontMonth()</pre>
</div>

<?
$assetClass = "Future";
include(DOCS_RESOURCES."/universes/option/filter-caveats.php");

include(DOCS_RESOURCES."/algorithm-framework/continuous-futures.html");
?>



<p>To view the implementation of this model, see the <span class="csharp"><a target="_blank" rel="nofollow" href="https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Selection/FutureUniverseSelectionModel.cs">LEAN GitHub repository</a></span><span class="python"><a target="_blank" rel="nofollow" href="https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Selection/FutureUniverseSelectionModel.py">LEAN GitHub repository</a></span>.</p>
