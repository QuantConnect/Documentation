<p>Follow these steps to subscribe to a Future security:</p>

<ol>
<?
$additionalImports = "using QuantConnect.Securities.Future;
";
include(DOCS_RESOURCES."/datasets/research-environment/load-csharp-assemblies.php");
?>
    <li>Create a <code>QuantBook</code>.</li>
    <div class="section-example-container">
        <pre class="csharp">var qb = new QuantBook();</pre>
        <pre class="python">qb = QuantBook()</pre>
    </div>
    
    <li>Call the <code class="csharp">AddFuture</code><code class="python">add_future</code> method with a ticker.</li>
    <div class="section-example-container">
        <pre class="csharp">var future = qb.AddFuture(Futures.Indices.SP500EMini);</pre>
        <pre class="python">future = qb.add_future(Futures.Indices.SP_500_E_MINI)</pre>
    </div>
    <p>To view the supported assets in the US Futures dataset, see <a href='/docs/v2/writing-algorithms/datasets/algoseek/us-futures#08-Supported-Assets'>Supported Assets</a>.</p>

    <li><a href='/docs/v2/research-environment/initialization#02-Set-Dates'>Set the start date</a> to a date in the past that you want to use as the analysis date.</li>
    <div class="section-example-container">
        <pre class="csharp">qb.SetStartDate(2024, 1, 1);</pre>
        <pre class="python">qb.set_start_date(2024, 1, 1)</pre>
    </div>
    <p>The method that you call in the next step returns data on all the contracts that were tradable on this date.</p>

    <li>Call the <code class='csharp'>FutureChain</code><code class='python'>future_chain</code> method with the <code>Symbol</code> of the continuous Futures contract.</li>
    <div class="section-example-container">
        <pre class="csharp">// Get the Futures contracts that were tradable on January 1st, 2024.
var chain = qb.OptionChain(future.Symbol);</pre>
        <pre class="python"># Get the Futures contracts that were tradable on January 1st, 2024.
chain = qb.future_chain(future.symbol, flatten=True)</pre>
    </div>

    <p>
        This method returns a <code>FutureChain</code> object, which represent an entire chain of Futures contracts.
        <span class='python'>You can even format the chain data into a DataFrame where each row in the DataFrame represents a single contract.</span>
    </p>

    <li>Sort and filter the data to select the specific contract(s) you want to analyze.</li>
    <div class="section-example-container">
        <pre class="csharp">// Select a contract.
var contractSymbol = chain
    .Where(contract => 
        // Select contracts that have at least 1 week before expiry.
        contract.Expiry > Time.AddDays(7)
    )
    // Select the contract with the largest open interest.
    .OrderByDescending(contract => contract.OpenInterest)
    .First()
    // Get the Symbol of the target contract.
    .Symbol;</pre>
        <pre class="python"># Get the contracts available to trade (in DataFrame format).
chain = chain.data_frame

# Select a contract.
contract_symbol = chain[
    # Select contracts that have at least 1 week before expiry.
    chain.expiry > self.time + timedelta(7)
    # Select the contract with the largest open interest.
].sort_values('openinterest').index[-1]</pre>
    </div>


    <li>Call the <code class="csharp">AddFutureContract</code><code class="python">add_future_contract</code> method with a <code>FutureContract</code> <code>Symbol</code> and disable fill-forward.</li>
    <div class="section-example-container">
        <pre class="csharp">qb.AddFutureContract(contractSymbol, fillForward: false);</pre>
        <pre class="python">qb.add_future_contract(contract_symbol, fill_forward=False)</pre>
    </div>
    <p>Disable fill-forward because there are only a few <code>OpenInterest</code> data points per day.</p>
</ol>
