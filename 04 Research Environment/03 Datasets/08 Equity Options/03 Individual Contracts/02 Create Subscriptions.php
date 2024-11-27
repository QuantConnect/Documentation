<p>Follow these steps to subscribe to individual Equity Option contracts:</p>

<ol>
<?
$additionalImports = "using QuantConnect.Data.Market;
using QuantConnect.Securities.Option;
";
include(DOCS_RESOURCES."/datasets/research-environment/load-csharp-assemblies.php");
?>
    <li>Create a <code>QuantBook</code>.</li>
    <div class="section-example-container">
        <pre class="csharp">var qb = new QuantBook();</pre>
        <pre class="python">qb = QuantBook()</pre>
    </div>

    <li><a href='/docs/v2/research-environment/datasets/us-equity#03-Create-Subscriptions'>Add the underlying Equity</a> with raw <a href='/docs/v2/research-environment/datasets/us-equity#07-Data-Normalization'>data normalization</a>.</li>
    <div class="section-example-container">
        <pre class="csharp">var underlyingSymbol = qb.AddEquity("SPY", dataNormalizationMode: DataNormalizationMode.Raw).Symbol;</pre>
        <pre class="python">underlying_symbol = qb.add_equity("SPY", data_normalization_mode=DataNormalizationMode.RAW).symbol</pre></div><div class="csharp section-example-container">
    </div>
    <p>To view the supported underlying assets in the US Equity Options dataset, see the <a href="/datasets/algoseek-us-equity-options/explorer">Data Explorer</a>.</p>

    <li><a href='/docs/v2/research-environment/initialization#02-Set-Dates'>Set the start date</a> to a date in the past that you want to use as the analysis date.</li>
    <div class="section-example-container">
      <pre class="csharp">qb.SetStartDate(2024, 1, 1);</pre>
      <pre class="python">qb.set_start_date(2024, 1, 1)</pre>
    </div>
    <p>The method that you call in the next step returns data on all the contracts that were tradable on this date.</p>

    <li>Call the <code class='csharp'>OptionChain</code><code class='python'>option_chain</code> method with the underlying <code>Equity</code> <code>Symbol</code>.</li>
    <div class="section-example-container">
        <pre class="csharp">// Get the Option contracts that were tradable on January 1st, 2024.
var chain = qb.OptionChain(underlyingSymbol);</pre>
    <pre class="python"># Get the Option contracts that were tradable on January 1st, 2024.
chain = qb.option_chain(underlying_symbol, flatten=True)</pre>
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
      <pre class="python"># Get the contracts available to trade (in DataFrame format).
chain = chain.data_frame

# Select a contract.
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

	<li>Call the <code class="csharp">AddOptionContract</code><code class="python">add_option_contract</code> method with an <code>OptionContract</code> <code>Symbol</code> and disable fill-forward.</li>
    <div class="section-example-container">
      <pre class="csharp">// Subscribe to the target contract.
var optionContract = qb.AddOptionContract(contractSymbol, fillForward: false);</pre>
      <pre class="python"># Subscribe to the target contract.
option_contract = qb.add_option_contract(contract_symbol, fill_forward=False)</pre>
    </div>
  <p>Disable fill-forward because there are only a few <code>OpenInterest</code> data points per day.</p>
</ol>
