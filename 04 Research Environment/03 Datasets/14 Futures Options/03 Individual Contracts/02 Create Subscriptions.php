<p>Follow these steps to subscribe to individual Futures Option contracts:</p>

<ol>
<?
$additionalImports = "using QuantConnect.Data.Market;
using QuantConnect.Securities;
";
include(DOCS_RESOURCES."/datasets/research-environment/load-csharp-assemblies.php");
?>
    <li>Create a <code>QuantBook</code>.</li>
    <div class="section-example-container">
        <pre class="csharp">var qb = new QuantBook();</pre>
        <pre class="python">qb = QuantBook()</pre>
    </div>

    <li><a href="/docs/v2/research-environment/datasets/futures#03-Create-Subscriptions">Add the underlying Futures contract</a>.</li>
    <div class="section-example-container">
        <pre class="csharp">var future = qb.AddFuture(Futures.Indices.SP500EMini);
var startDate = new DateTime(2023, 12, 20);
var futuresContractSymbol = qb.FutureChainProvider.GetFutureContractList(future.Symbol, startDate)
    .OrderBy(s =&gt; s.ID.Date)
    .FirstOrDefault();
qb.AddFutureContract(futuresContractSymbol, fillForward: false);</pre>
        <pre class="python">future = qb.add_future(Futures.Indices.SP_500_E_MINI)
start_date = datetime(2023, 12, 20)
futures_contract_symbol = sorted(
  qb.future_chain_provider.get_future_contract_list(future.symbol, start_date), 
  key=lambda s: s.id.date
)[0]
qb.add_future_contract(futures_contract_symbol, fill_forward=False)</pre>
    </div>
    <p>To view the available underlying Futures in the US Future Options dataset, see <a href="/docs/v2/writing-algorithms/datasets/algoseek/us-future-options#07-Supported-Assets">Supported Assets</a>.</p>

    <li><a href='/docs/v2/research-environment/initialization#02-Set-Dates'>Set the start date</a> to a date in the past that you want to use as the analysis date.</li>
    <div class="section-example-container">
      <pre class="csharp">qb.SetStartDate(futuresContractSymbol.ID.Date.AddDays(-5));</pre>
      <pre class="python">qb.set_start_date(futures_contract_symbol.id.date - timedelta(5))</pre>
    </div>
    <p>The method that you call in the next step returns data on all the contracts that were tradable on this date.</p>

  	<li>Call the <code class='csharp'>OptionChain</code><code class='python'>option_chain</code> method with the underlying Futures contract <code>Symbol</code>.</li>
  	<div class="section-example-container">
  		<pre class="csharp">var chain = qb.OptionChain(futuresContractSymbol);</pre>
  		<pre class="python">chain = qb.option_chain(futures_contract_symbol, flatten=True).data_frame</pre>
  	</div>
    <p>
      This method returns an <code>OptionChain</code> object, which represent an entire chain of Option contracts for a single underlying security.
      <span class='python'>
        You can even format the chain data into a DataFrame where each row in the DataFrame represents a single contract.
      </span>
    </p>

    <li>Sort and filter the data to select the specific Futures Options contract(s) you want to analyze.</li>
    <div class="section-example-container">
      <pre class="csharp">// Select a contract.
var expiry = chain.Select(contract => contract.Expiry).Min();
var fopContractSymbol = chain
    .Where(contract => 
        // Select call contracts with the closest expiry.
        contract.Expiry == expiry && 
        contract.Right == OptionRight.Call
    )
    // Select the contract with a strike price near the middle.
    .OrderBy(contract => contract.Strike)
    .ToList()[150]
    // Get the Symbol of the target contract.
    .Symbol;</pre>
      <pre class="python"># Select a contract.
expiry = chain.expiry.min()
fop_contract_symbol = chain[
    # Select call contracts with the closest expiry.
    (chain.expiry == expiry) & 
    (chain.right == OptionRight.CALL)
    # Select the contract with a strike price near the middle.
].sort_values('strike').index[150]</pre>
    </div>

    <li>Call the <code class="csharp">AddFutureOptionContract</code><code class="python">add_future_option_contract</code> method with an <code>OptionContract</code> Symbol and disable fill-forward.</li>
	<div class="section-example-container">
		<pre class="csharp">var optionContract = qb.AddFutureOptionContract(fopContractSymbol, fillForward: false);</pre>
		<pre class="python">option_contract = qb.add_future_option_contract(fop_contract_symbol, fill_forward=False)</pre>
	</div>
    <p>Disable fill-forward because there are only a few <code>OpenInterest</code> data points per day.</p>
</ol>
