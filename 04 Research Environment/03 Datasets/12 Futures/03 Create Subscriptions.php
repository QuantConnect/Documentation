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
    <li>Call the <code>AddFuture</code> method with a ticker, resolution, and <a href="/docs/v2/writing-algorithms/universes/futures#12-Continous-Contracts">contract rollover settings</a>.</li>
    <div class="section-example-container">
        <pre class="csharp">var future = qb.AddFuture(Futures.Indices.SP500EMini, Resolution.Minute,
                dataNormalizationMode: DataNormalizationMode.BackwardsRatio,
                dataMappingMode: DataMappingMode.LastTradingDay,
                contractDepthOffset: 0);</pre>
        <pre class="python">future = qb.add_future(Futures.Indices.SP500E_MINI, Resolution.MINUTE,
                data_normalization_mode = DataNormalizationMode.BACKWARDS_RATIO,
                data_mapping_mode = DataMappingMode.LAST_TRADING_DAY,
                contract_depth_offset = 0)</pre>
    </div>
    <p>To view the available tickers in the US Futures dataset, see <a href='/docs/v2/writing-algorithms/datasets/algoseek/us-futures#07-Supported-Assets'>Supported Assets</a>.</p>
    <p>If you omit any of the arguments after the ticker, see the following table for their default values:</p>
    <table class="qc-table table">
        <thead>
            <tr>
                <th>Argument</th>
                <th>Default Value</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><code>resolution</code></td>
                <td><code>Resolution.Minute</code></td>
            </tr>
            <tr>
                <td><code>dataNormalizationMode</code></td>
                <td><code>DataNormalizationMode.Adjusted</code></td>
            </tr>
            <tr>
                <td><code>dataMappingMode</code></td>
                <td><code>DataMappingMode.OpenInterest</code></td>
            </tr>
            <tr>
                <td><code>contractDepthOffset</code></td>
                <td>0</td>
            </tr>
        </tbody>
    </table>

    <li><span class='qualifier'>(Optional)</span> Set a <a href='/docs/v2/writing-algorithms/universes/futures#13-Filter-Contracts'>contract filter</a>.</li>
    <div class="section-example-container">
        <pre class="csharp">future.set_filter(0, 90);</pre>
        <pre class="python">future.set_filter(0, 90)</pre>
    </div>
    <p>If you don't call the <code>SetFilter</code> method, the <code>FutureHistory</code> method won't return historical data.</p>
</ol>

<p>If you want historical data on individual contracts and their <code>OpenInterest</code>, follow these steps to subscribe to individual Future contracts:</p>
<ol>
  <li>Call the <code>GetFuturesContractList</code> method with the underlying <code>Future</code> <code>Symbol</code> and a <code class="python">datetime</code><code class="csharp">DateTime</code>.</li>
	<div class="section-example-container">
		<pre class="csharp">var startDate = new DateTime(2021,12,20);
var symbols = qb.FutureChainProvider.GetFutureContractList(future.Symbol, startDate);</pre>
		<pre class="python">start_date = datetime(2021,12,20)
symbols = qb.future_chain_provider.get_future_contract_list(future.symbol, start_date)</pre>
	</div>
	<p>This method returns a list of <code>Symbol</code> objects that reference the Future contracts that were trading at the given time. If you set a contract filter with <code>SetFilter</code>, it doesn't affect the results of <code>GetFutureContractList</code>.</p>
	<li>Select the <code>Symbol</code> of the <code>FutureContract</code>&nbsp;object(s) for which you want to get historical data.</li>
	<p>For example, select the  <code>Symbol</code> of the contract with the closest expiry.<br></p>
	<div class="section-example-container">
		<pre class="csharp">var contractSymbol = symbols.OrderBy(s =&gt; s.ID.Date).FirstOrDefault();</pre>
		<pre class="python">contract_symbol = sorted(symbols, key=lambda s: s.id.date)[0]</pre>
	</div>
	<li>Call the <code>AddFutureContract</code> method with an <code>FutureContract</code> <code>Symbol</code> and disable fill-forward.</li>
	<div class="section-example-container">
		<pre class="csharp">qb.AddFutureContract(contractSymbol, fillForward: false);</pre>
		<pre class="python">qb.add_future_contract(contract_symbol, fill_forward = False)</pre>
	</div>
	<p>Disable fill-forward because there are only a few <code>OpenInterest</code> data points per day.</p>
</ol>
