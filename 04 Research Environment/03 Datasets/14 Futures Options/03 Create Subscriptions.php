<p>Follow these steps to subscribe to a Futures Option contract:</p>

<ol>
<?
$additionalImports = "using QuantConnect.Securities.Future;
using QuantConnect.Securities.FutureOption;
";
include(DOCS_RESOURCES."/datasets/research-environment/load-csharp-assemblies.php");
?>
    <li>Create a <code>QuantBook</code>.</li>
    <div class="section-example-container">
        <pre class="csharp">var qb = new QuantBook();</pre>
        <pre class="python">qb = QuantBook()</pre>
    </div>

    <li><a href="/docs/v2/research-environment/datasets/futures#03-Create-Subscriptions">Subscribe to a Futures contract</a>.</li>
    <div class="section-example-container">
        <pre class="csharp">var future = qb.AddFuture(Futures.Indices.SP500EMini, Resolution.Minute);
var startDate = new DateTime(2021,12,20);
var futuresContractSymbols = qb.FutureChainProvider.GetFutureContractList(future.Symbol, startDate);
var futuresContractSymbol = futuresContractSymbols.OrderBy(s =&gt; s.ID.Date).FirstOrDefault();
qb.AddFutureContract(futuresContractSymbol, fillForward: false);</pre>
        <pre class="python">future = qb.add_future(Futures.indices.SP500E_MINI, Resolution.MINUTE)
start_date = datetime(2021,12,20)
futures_contract_symbols = qb.future_chain_provider.get_future_contract_list(future.symbol, start_date)
futures_contract_symbol = sorted(futures_contract_symbols, key=lambda s: s.id.date)[0]
qb.add_future_contract(futures_contract_symbol, fill_forward = False)</pre>
    </div>
    <p>To view the available underlying Futures in the US Future Options dataset, see <a href="/docs/v2/writing-algorithms/datasets/algoseek/us-future-options#06-Supported-Assets">Supported Assets</a>.</p>

    <li><span class='qualifier'>(Optional)</span> Set a <a href='/docs/v2/writing-algorithms/universes/future-options#03-Filter-Contracts'>contract filter</a>.</li>
<div class="section-example-container">
        <pre class="csharp">qb.AddFutureOption(future.Symbol, optionFilterUniverse =&gt; optionFilterUniverse.Strikes(-1, 1));</pre>
        <pre class="python">qb.add_future_option(future.symbol, lambda option_filter_universe: option_filter_universe.strikes(-1, 1))</pre>
    </div>
<p>The filter determines which contracts the <code>OptionHistory</code> method returns. If you don't set a filter, the default filter selects the contracts that have the following characteristics:</p>
<ul>
	<li>Standard type (exclude weeklys)</li>
	<li>Within 1 strike price of the underlying asset price</li>
	<li>Expire within 31 days</li>
</ul>

</ol>

<p>If you want historical data on individual contracts and their <code>OpenInterest</code>, follow these steps to subscribe to the individual Futures Option contracts:</p>
<ol>
	<li>Call the <code>GetOptionsContractList</code> method with the underlying Futures Contract <code>Symbol</code> and a <code class="python">datetime</code> <code class="csharp">DateTime</code> object.</li>
	<div class="section-example-container">
		<pre class="csharp">var fopContractSymbols = qb.OptionChainProvider.GetOptionContractList(futuresContractSymbol, startDate);</pre>
		<pre class="python">fop_contract_symbols = qb.option_chain_provider.get_option_contract_list(futures_contract_symbol, start_date)</pre>
	</div>
    <p>This method returns a list of <code>Symbol</code> objects that reference the Option contracts that were trading for the underlying Future contract at the given time. If you set a contract filter with <code>SetFilter</code>, it doesn't affect the results of <code>GetOptionContractList</code>.</p>

    <li>Select the Symbol of the <code>OptionContract</code> object(s) for which you want to get historical data.</li>
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
		<pre class="csharp">var closestExpiry = fopContractSymbols.Select(c =&gt; c.ID.Date).Min();
var fopContractSymbol = fopContractSymbols
    .Where(c =&gt; c.ID.Date == closestExpiry &amp;&amp; c.ID.OptionRight == OptionRight.Call)
    .OrderBy(c =&gt; c.ID.StrikePrice)
    .FirstOrDefault();</pre>
		<pre class="python">closest_expiry = min([c.id.date for c in fop_contract_symbols])
calls = [c for c in fop_contract_symbols if c.id.date == closest_expiry and c.id.option_right == OptionRight.CALL]
fop_contract_symbol = sorted(calls, key=lambda c: c.id.strike_price)[0]</pre>
	</div>

    <li>Call the <code>AddFutureOptionContract</code>&nbsp;method with an <code>OptionContract</code> Symbol and disable fill-forward.</li>
	<div class="section-example-container">
		<pre class="csharp">var optionContract = qb.AddFutureOptionContract(fopContractSymbol, fillForward: false);</pre>
		<pre class="python">option_contract = qb.add_future_option_contract(fop_contract_symbol, fill_forward = False)</pre>
	</div>
    <p>Disable fill-forward because there are only a few <code>OpenInterest</code> data points per day.</p>
    
    <li><span class='qualifier'>(Optional)</span> Set the <a href='/docs/v2/writing-algorithms/reality-modeling/options-models/pricing'>price model</a>.</li>
    <div class="section-example-container">
        <pre class="csharp">optionContract.PriceModel = OptionPriceModels.BinomialCoxRossRubinstein();</pre>
        <pre class="python">option_contract.price_model = OptionPriceModels.binomial_cox_ross_rubinstein()</pre>
    </div>
</ol>
