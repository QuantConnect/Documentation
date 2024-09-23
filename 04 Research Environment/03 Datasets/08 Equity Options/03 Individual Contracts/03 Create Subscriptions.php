<p>Follow these steps to subscribe to individual Equity Option contracts:</p>

<ol>
<?
$additionalImports = "using QuantConnect.Securities.Option;
";
include(DOCS_RESOURCES."/datasets/research-environment/load-csharp-assemblies.php");
?>
    <li>Create a <code>QuantBook</code>.</li>
    <div class="section-example-container">
        <pre class="csharp">var qb = new QuantBook();</pre>
        <pre class="python">qb = QuantBook()</pre>
    </div>

    <li>Subscribe to the underlying Equity with raw data normalization and save a reference to the Equity&nbsp;<span style="color: rgb(220, 67, 67);">Symbol</span>.</li>
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
chain = qb.option_chain(underlying_symbol)</pre>
    </div>
    <p>
      <span class='python'>
          This method returns a <code>DataHistory[OptionUniverse]</code> object, which you can format into a DataFrame or iterate through.
          Each row in the DataFrame and each <code>OptionUniverse</code> object represents a single contract.
      </span>
      <span class='csharp'>This method returns a collection of <code>OptionUniverse</code> objects, where each object represents a contract.</span>
    </p>

    <li>Sort and filter the data to select the specific contract(s) you want to analyze.</li>
    <div class="section-example-container">
      <pre class="csharp">// Select a contract.
var expiry = chain.Select(contract => contract.ID.Date).Min();
var contractSymbol = chain
    .Where(contract => 
        // Select call contracts with the closest expiry.
        contract.ID.Date == expiry && 
        contract.ID.OptionRight == OptionRight.Call &&
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
expiry = chain.id.map(lambda id: id.date).min()
delta = chain.greeks.map(lambda greeks: greeks.delta)
contract_id = chain[
    # Select call contracts with the closest expiry.
    chain.id.map(lambda id: id.date == expiry) & 
    chain.id.map(lambda id: id.option_right == OptionRight.CALL) &
    # Select contracts with a 0.3-0.7 delta.
    (delta > 0.3) &
    (delta < 0.7)
    # Select the contract with the largest open interest.
].sort_values('openinterest').iloc[-1]['id']

# Convert the security Id to a Symbol.
contract_symbol = self.symbol(str(contract_id))</pre>
    </div>
  <p><code>OptionUniverse</code> objects have the following properties:</p>
  <div data-tree='QuantConnect.Data.UniverseSelection.OptionUniverse'></div>

	<li>Call the <code class="csharp">AddOptionContract</code><code class="python">add_option_contract</code> method with an <code>OptionContract</code> <code>Symbol</code> and disable fill-forward.</li>
    <div class="section-example-container">
      <pre class="csharp">// Subscribe to the target contract.
var optionContract = qb.AddOptionContract(contractSymbol, fillForward: false);</pre>
      <pre class="python"># Subscribe to the target contract.
option_contract = qb.add_option_contract(contract_symbol, fill_forward = False)</pre>
    </div>
  <p>Disable fill-forward because there are only a few <code>OpenInterest</code> data points per day.</p>

  <li><span class='qualifier'>(Optional)</span> Set the <a href='/docs/v2/writing-algorithms/reality-modeling/options-models/pricing'>price model</a>.</li>
  <div class="section-example-container">
      <pre class="csharp">// Use the Cox, Ross, and Rubinstein Binomial Option price model.
optionContract.PriceModel = OptionPriceModels.BinomialCoxRossRubinstein();</pre>
      <pre class="python"># Use the Cox, Ross, and Rubinstein Binomial Option price model.
option_contract.price_model = OptionPriceModels.binomial_cox_ross_rubinstein()</pre>
  </div>
</ol>
