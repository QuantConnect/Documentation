<p>
  There are several ways to get the implied volatility and Greeks of contracts.
  The values in the universe filter function are pre-calculated, daily values.
</p>

<div class="section-example-container">
    <pre class="csharp">// TODO</pre>
    <pre class="python"># TODO</pre>
</div>

<p>
  To override the default <a href="/docs/v2/writing-algorithms/reality-modeling/options-models/pricing">pricing model</a> of the Option, <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/options-models/pricing#03-Set-Models'>set a pricing model</a>.
  If you set a price model, it sets the price model of the individual contracts in the universe.
</p>

<div class="section-example-container">
    <pre class="csharp">// Set PriceModel property to use the Crank-Nicolson finite-difference model to price the Options.
option.PriceModel = OptionPriceModels.crank_nicolson_fd();</pre>
    <pre class="python">// Set price_model field to use the Crank-Nicolson finite-difference model to price the Options.
option.price_model = OptionPriceModels.crank_nicolson_fd()</pre>
</div>

<? include(DOCS_RESOURCES."/reality-modeling/volatility-model.html"); ?>
