<p>Follow these steps to subscribe to an Equity Option security:</p>

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
        <pre class="csharp">var equitySymbol = qb.AddEquity("SPY", dataNormalizationMode: DataNormalizationMode.Raw).Symbol;</pre>
        <pre class="python">equity_symbol = qb.add_equity("SPY", data_normalization_mode=DataNormalizationMode.RAW).symbol</pre></div><div class="csharp section-example-container">
    </div>
    <p>To view the supported underlying assets in the US Equity Options dataset, see the <a href="/datasets/algoseek-us-equity-options/explorer">Data Explorer</a>.</p>
    <li>Call the <code class="csharp">AddOption</code><code class="python">add_option</code> method with the underlying Equity <code>Symbol</code>.</li>
    <div class="section-example-container">
        <pre class="csharp">var option = qb.AddOption(equitySymbol);</pre>
        <pre class="python">option = qb.add_option(equity_symbol)</pre>
    </div>
    <li><span class='qualifier'>(Optional)</span> Set a <a href="/docs/v2/writing-algorithms/universes/equity-options#03-Filter-Contracts">contract filter</a>.</li>
    <div class="section-example-container">
        <pre class="csharp">option.set_filter(-1, 1, 0, 90);</pre>
        <pre class="python">option.set_filter(-1, 1, 0, 90)</pre>
    </div>
    <p>The filter determines which contracts the <code>OptionHistory</code> method returns. If you don't set a filter, the default filter selects the contracts that have the following characteristics:</p>
<ul>
	<li>Standard type (exclude weeklys)</li>
	<li>Within 1 strike price of the underlying asset price</li>
	<li>Expire within 31 days</li>
</ul>
    <li><span class='qualifier'>(Optional)</span> Set the <a href='/docs/v2/writing-algorithms/reality-modeling/options-models/pricing'>price model</a>.</li>
    <div class="section-example-container">
        <pre class="csharp">option.price_model = OptionPriceModels.binomial_cox_ross_rubinstein();</pre>
        <pre class="python">option.price_model = OptionPriceModels.binomial_cox_ross_rubinstein()</pre>
    </div>

</ol>

<p>If you want historical data on individual contracts and their <code>OpenInterest</code>, follow these steps to subscribe to the individual Equity Option contracts:</p>
<ol>
    <li>Call the <code>GetOptionsContractList</code> method with the underlying <code>Equity</code> <code>Symbol</code> and a <code class="python">datetime</code><code class="csharp">DateTime</code>.</li>
    <div class="section-example-container">
        <pre class="csharp">var startDate = new DateTime(2021, 12, 31);
var contractSymbols = qb.OptionChainProvider.GetOptionContractList(equitySymbol, startDate);</pre>
    <pre class="python">start_date = datetime(2021, 12, 31)
contract_symbols = qb.option_chain_provider.get_option_contract_list(equity_symbol, start_date)</pre>
    </div>
    <p>This method returns a list of <code>Symbol</code> objects that reference the Option contracts that were trading at the given time. If you set a contract filter with <code>SetFilter</code>, it doesn't affect the results of <code>GetOptionsContractList</code>.</p>
    <li>Select the <code>Symbol</code> of the <code>OptionContract</code>&nbsp;object(s) for which you want to get historical data.</li>
    <p>To filter and select contracts, you can use the following properties of each&nbsp;<code>Symbol</code> object:</p>
    <table class="qc-table table">
        <thead>
            <tr>
                <th>Property</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                 <td><code class="csharp">ID.Date</code><code class="python">id.date</code></td>
                 <td>The expiration date of the contract.</td>
            </tr>
            <tr>
                 <td><code class="csharp">ID.StrikePrice</code><code class="python">id.strike_price</code></td>
                 <td>The strike price of the contract.</td>
            </tr>
            <tr>
                 <td><code class="csharp">ID.OptionRight</code><code class="python">id.option_right</code></td>
                 <td>
                     The contract type. The <code>OptionRight</code> enumeration has the following members:
                     <div data-tree="QuantConnect.OptionRight"></div>
                  </td>
            </tr>
            <tr>
                 <td><code class="csharp">ID.OptionStyle</code><code class="python">id.option_style</code></td>
                 <td>
                     The contract style. The <code>OptionStyle</code> enumeration has the following members:
                     <div data-tree="QuantConnect.OptionStyle"></div>
                  </td>
            </tr>
        </tbody>
    </table>

    <div class="section-example-container">
      <pre class="csharp">var contractSymbol = contractSymbols.Where(s =&gt; 
    s.ID.OptionRight == OptionRight.Call &amp;&amp;
    s.ID.StrikePrice == 477 &amp;&amp;
    s.ID.Date == new DateTime(2022, 1, 21)).First();</pre>
	    <pre class="python">contract_symbol = [s for s in contract_symbols 
    if s.id.option_right == OptionRight.CALL 
        and s.id.strike_price == 477 
        and s.id.date == datetime(2022, 1, 21)][0]</pre>
    </div>
	<li>Call the <code class="csharp">AddOptionContract</code><code class="python">add_option_contract</code>  method with an <code>OptionContract</code> <code>Symbol</code> and disable fill-forward.</li>
    <div class="section-example-container">
      <pre class="csharp">var optionContract = qb.AddOptionContract(contractSymbol, fillForward: false);</pre>
      <pre class="python">option_contract = qb.add_option_contract(contract_symbol, fill_forward = False)</pre>
    </div>
  <p>Disable fill-forward because there are only a few <code>OpenInterest</code> data points per day.</p>
    <li><span class='qualifier'>(Optional)</span> Set the <a href='/docs/v2/writing-algorithms/reality-modeling/options-models/pricing'>price model</a>.</li>
    <div class="section-example-container">
        <pre class="csharp">optionContract.PriceModel = OptionPriceModels.BinomialCoxRossRubinstein();</pre>
        <pre class="python">option_contract.price_model = OptionPriceModels.binomial_cox_ross_rubinstein()</pre>
    </div>
</ol>
