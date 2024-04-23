<p>Follow these steps to subscribe to an Index Option security:</p>

<ol>
<?
$additionalImports = "using QuantConnect.Securities.Index;
using QuantConnect.Securities.IndexOption;
";
include(DOCS_RESOURCES."/datasets/research-environment/load-csharp-assemblies.php");
?>
    <li>Instantiate a <code>QuantBook</code>.</li>
    <div class="section-example-container">
        <pre class="csharp">var qb = new QuantBook();</pre>
        <pre class="python">qb = QuantBook()</pre>
    </div>
    <li>Call the <code class="csharp">AddIndex</code><code class="python">add_index</code> method with a ticker and resolution.</li>
    <div class="section-example-container">
        <pre class="csharp">var indexSymbol = qb.AddIndex("SPX", Resolution.Minute).Symbol;</pre>
        <pre class="python">index_symbol = qb.add_index("SPX", Resolution.MINUTE).symbol</pre>
    </div>
    <p>To view the available indices, see <a href="/docs/v2/writing-algorithms/datasets/algoseek/us-index-options#07-Supported-Assets">Supported Assets</a>.</p>
    <p>If you do not pass a resolution argument, <code class="csharp">Resolution.Minute</code><code class="python">Resolution.MINUTE</code> is used by default. <br></p>
    <li>Call the <code class="csharp">AddIndexOption</code><code class="python">add_index_option</code> method with the underlying <code>Index</code> <code>Symbol</code> and, if you want non-standard Index Options, the <a href='/docs/v2/writing-algorithms/datasets/algoseek/us-index-options#07-Supported-Assets'>target Option ticker</a>.</li>
    <div class="section-example-container">
        <pre class="csharp">var option = qb.AddIndexOption(indexSymbol);</pre>
        <pre class="python">option = qb.add_index_option(index_symbol)</pre>
    </div>
    <li><span class='qualifier'>(Optional)</span> Set a <a href='/docs/v2/writing-algorithms/universes/index-options#03-Filter-Contracts'>contract filter</a>.</li>
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
</ol>

<p>If you want historical data on individual contracts and their <code>OpenInterest</code>, follow these steps to subscribe to individual Index Option contracts:</p>
<ol>
    <li>Call the <code>GetOptionsContractList</code> method with the underlying <code>Index</code> <code>Symbol</code> and a <code class="python">datetime</code><code class="csharp">DateTime</code>.</li>
    <div class="section-example-container">
        <pre class="csharp">var startDate = new DateTime(2021, 12, 31);

// Standard contracts
var canonicalSymbol = QuantConnect.Symbol.CreateCanonicalOption(indexSymbol, Market.USA, "?SPX");
var contractSymbols = qb.OptionChainProvider.GetOptionContractList(canonicalSymbol, startDate);
            
// Weekly contracts
var weeklyCanonicalSymbol = QuantConnect.Symbol.CreateCanonicalOption(indexSymbol, "SPXW", Market.USA, "?SPXW");
var weeklyContractSymbols = qb.OptionChainProvider.GetOptionContractList(weeklyCanonicalSymbol, startDate)
    .Where(s => OptionSymbol.IsWeekly(s));</pre>
<pre class="python">start_date = datetime(2021, 12, 31)

# Standard contracts
canonical_symbol = Symbol.create_canonical_option(index_symbol, Market.USA, "?SPX")
contract_symbols = qb.option_chain_provider.get_option_contract_list(canonical_symbol, start_date)

# Weekly contracts
weekly_canonical_symbol = Symbol.create_canonical_option(index_symbol, "SPXW", Market.USA, "?SPXW")
weekly_contract_symbols = qb.option_chain_provider.get_option_contract_list(weekly_canonical_symbol, start_date)
weekly_contract_symbols = [s for s in weekly_contract_symbols if OptionSymbol.is_weekly(s)]</pre>
    </div>
    <p>This method returns a list of <code>Symbol</code>&nbsp;objects that reference the Option contracts that were trading at the given time. If you set a contract filter with <code>SetFilter</code>, it doesn't affect the results of <code>GetOptionContractList</code>.</p>
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
                 <td><code>ID.Date</code></td>
                 <td>The expiration date of the contract.</td>
            </tr>
            <tr>
                 <td><code>ID.StrikePrice</code></td>
                 <td>The strike price of the contract.</td>
            </tr>
            <tr>
                 <td><code>ID.OptionRight</code></td>
                 <td>
                     The contract type. The <code>OptionRight</code> enumeration has the following members:
                     <div data-tree="QuantConnect.OptionRight"></div>
                  </td>
            </tr>
            <tr>
                 <td><code>ID.OptionStyle</code></td>
                 <td>
                     The contract style. The <code>OptionStyle</code> enumeration has the following members:
                     <div data-tree="QuantConnect.OptionStyle"></div>
                  </td>
            </tr>
        </tbody>
    </table>

    <div class="section-example-container">
      <pre class="csharp">// Standard contracts
var contractSymbol = contractSymbols.Where(s => 
    s.ID.OptionRight == OptionRight.Call &&
    s.ID.StrikePrice == 4460 &&
    s.ID.Date == new DateTime(2022, 4, 14)).First();

// Weekly contracts
var weeklyContractSymbol = weeklyContractSymbols.Where(s => 
    s.ID.OptionRight == OptionRight.Call &&
    s.ID.StrikePrice == 4460 &&
    s.ID.Date == new DateTime(2021, 12, 31)).First();</pre>
	    <pre class="python"># Standard contracts
contract_symbol = [s for s in contract_symbols 
    if s.id.option_right == OptionRight.CALL 
        and s.id.strike_price == 4460 
        and s.id.date == datetime(2022, 4, 14)][0]

# Weekly contracts
weekly_contract_symbol = [s for s in weekly_contract_symbols 
    if s.id.option_right == OptionRight.CALL 
        and s.id.strike_price == 4460 
        and s.id.date == datetime(2021, 12, 31)][0]</pre>
    </div>

	<li>Call the <code>AddIndexOptionContract </code> method with an <code>OptionContract</code> <code>Symbol</code> and disable fill-forward.</li>
    <div class="section-example-container">
      <pre class="csharp">qb.AddIndexOptionContract(contractSymbol, fillForward: false);</pre>
	    <pre class="python">qb.add_index_option_contract(contract_symbol, fill_forward = False)</pre>
    </div>
  <p>Disable fill-forward because there are only a few <code>OpenInterest</code> data points per day.</p>
</ol>
