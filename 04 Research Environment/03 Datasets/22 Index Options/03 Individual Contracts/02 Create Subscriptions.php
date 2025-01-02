<p>Follow these steps to subscribe to individual Index Option contracts:</p>

<ol>
<?
$additionalImports = "using QuantConnect.Data.Market;
using QuantConnect.Securities.Index;
using QuantConnect.Securities.IndexOption;
";
include(DOCS_RESOURCES."/datasets/research-environment/load-csharp-assemblies.php");
?>
    <li>Create a <code>QuantBook</code>.</li>
    <div class="section-example-container">
        <pre class="csharp">var qb = new QuantBook();</pre>
        <pre class="python">qb = QuantBook()</pre>
    </div>

    <li><a href='/docs/v2/research-environment/datasets/indices#03-Create-Subscriptions'>Add the underlying Index</a>.</li>
    <div class="section-example-container">
        <pre class="csharp">var underlyingSymbol = qb.AddIndex("SPX", Resolution.Minute).Symbol;</pre>
        <pre class="python">underlying_symbol = qb.add_index("SPX", Resolution.MINUTE).symbol</pre>
    </div>
    <p>To view the available Indices, see <a href="/docs/v2/writing-algorithms/datasets/algoseek/us-index-options#08-Supported-Assets">Supported Assets</a>.</p>
    <p>If you do not pass a resolution argument, <code class="csharp">Resolution.Minute</code><code class="python">Resolution.MINUTE</code> is used by default. <br></p>

    <li><a href='/docs/v2/research-environment/initialization#02-Set-Dates'>Set the start date</a> to a date in the past that you want to use as the analysis date.</li>
    <div class="section-example-container">
      <pre class="csharp">qb.SetStartDate(2024, 1, 1);</pre>
      <pre class="python">qb.set_start_date(2024, 1, 1)</pre>
    </div>
    <p>The method that you call in the next step returns data on all the contracts that were tradable on this date.</p>


    <li>Call the <code class='csharp'>OptionChain</code><code class='python'>option_chain</code> method with the underlying <code>Index</code> <code>Symbol</code>.</li>
    <div class="section-example-container">
        <pre class="csharp">// Get the Option contracts that were tradable on January 1st, 2024.
//   Option A: Standard contracts.
var chain = qb.OptionChain(
    QuantConnect.Symbol.CreateCanonicalOption(underlyingSymbol, Market.USA, "?SPX")
);

//   Option B: Weekly contracts.
//var chain = qb.OptionChain(
//    QuantConnect.Symbol.CreateCanonicalOption(underlyingSymbol, "SPXW", Market.USA, "?SPXW")
//);</pre>
    <pre class="python"># Get the Option contracts that were tradable on January 1st, 2024.
#   Option A: Standard contracts.
chain = qb.option_chain(
    Symbol.create_canonical_option(underlying_symbol, Market.USA, "?SPX"), flatten=True
).data_frame

#  Option B: Weekly contracts.
#chain = qb.option_chain(
#    Symbol.create_canonical_option(underlying_symbol, "SPXW", Market.USA, "?SPXW"), flatten=True
#).data_frame</pre>
    </div>
    <p>
      This method returns an <code>OptionChain</code> object, which represent an entire chain of Option contracts for a single underlying security.
      <span class='python'>
        You can even format the chain data into a DataFrame where each row in the DataFrame represents a single contract.
      </span>
    </p>

    <li>Sort and filter the data to select the specific contract(s) you want to analyze.</li>
    <div class="section-example-container">
      <pre class="csharp">// Select a contract.
var expiry = chain.Select(contract => contract.Expiry).Min();
var contractSymbol = chain
    .Where(contract => 
        // Select call contracts with the closest expiry.
        contract.Expiry == expiry && 
        contract.Right == OptionRight.Call &&
        // Select contracts with a 0.3-0.7 delta.
        contract.Greeks.Delta > 0.3m && 
        contract.Greeks.Delta < 0.7m
    )
    // Select the contract with the largest open interest.
    .OrderByDescending(contract => contract.OpenInterest)
    .First()
    // Get the Symbol of the target contract.
    .Symbol;</pre>
      <pre class="python"># Select a contract.
expiry = chain.expiry.min()
contract_symbol = chain[
    # Select call contracts with the closest expiry.
    (chain.expiry == expiry) & 
    (chain.right == OptionRight.CALL) &
    # Select contracts with a 0.3-0.7 delta.
    (chain.delta > 0.3) &
    (chain.delta < 0.7)
    # Select the contract with the largest open interest.
].sort_values('openinterest').index[-1]</pre>
    </div>

  <li>Call the <code class='csharp'>AddIndexOptionContract</code><code class='python'>add_index_option_contract</code> method with an <code>OptionContract</code> <code>Symbol</code> and disable fill-forward.</li>
    <div class="section-example-container">
      <pre class="csharp">var optionContract = qb.AddIndexOptionContract(contractSymbol, fillForward: false);</pre>
      <pre class="python">option_contract = qb.add_index_option_contract(contract_symbol, fill_forward=False)</pre>
    </div>
  <p>Disable fill-forward because there are only a few <code>OpenInterest</code> data points per day.</p>
</ol>

