<p>Follow these steps to subscribe to an Equity Option universe:</p>

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
        <pre class="csharp">// Set the contract filter to select contracts that have the strike price 
// within 1 strike level and expire within 90 days.
option.set_filter(-1, 1, 0, 90);</pre>
        <pre class="python"># Set the contract filter to select contracts that have the strike price 
# within 1 strike level and expire within 90 days.
option.set_filter(-1, 1, 0, 90)</pre>
    </div>
    <p>The filter determines which contracts the <code class="csharp">OptionHistory</code><code class="python">option_history</code> method returns. If you don't set a filter, the default filter selects the contracts that have the following characteristics:</p>
<ul>
	<li>Standard type (exclude weeklys)</li>
	<li>Within 1 strike price of the underlying asset price</li>
	<li>Expire within 31 days</li>
</ul>
    <li><span class='qualifier'>(Optional)</span> Set the <a href='/docs/v2/writing-algorithms/reality-modeling/options-models/pricing'>price model</a>.</li>
    <div class="section-example-container">
        <pre class="csharp">// Use the Cox, Ross, and Rubinstein Binomial model to price the Options.
option.price_model = OptionPriceModels.binomial_cox_ross_rubinstein();</pre>
        <pre class="python"># Use the Cox, Ross, and Rubinstein Binomial model to price the Options.
option.price_model = OptionPriceModels.binomial_cox_ross_rubinstein()</pre>
    </div>
</ol>
