<? include(DOCS_RESOURCES."/securities/data-definitions/greeks-and-iv.html"); ?>

<p>Follow these steps to get the Greeks and IV data:</p>

<ol>
    <li>Create the mirror contract <code>Symbol</code>.</li>
    <div class="section-example-container">
        <pre class="csharp">var mirrorContractSymbol = Symbol.CreateOption(
    optionContract.Underlying.Symbol, 
    <?=$contractNameC?>.ID.Market, 
    optionContract.Style, 
    optionContract.Right == OptionRight.Put ? OptionRight.Call : OptionRight.Put,
    optionContract.StrikePrice, 
    optionContract.Expiry
);</pre>
        <pre class="python">mirror_contract_symbol = Symbol.create_option(
    option_contract.underlying.symbol, <?=$contractNamePy?>.id.market, option_contract.style, 
    OptionRight.Call if option_contract.right == OptionRight.PUT else OptionRight.PUT,
    option_contract.strike_price, option_contract.expiry
)</pre>
    </div>
    
    <li>Set up the <a href='/docs/v2/writing-algorithms/reality-modeling/risk-free-interest-rate/key-concepts'>risk free interest rate</a>, <a href='/docs/v2/writing-algorithms/reality-modeling/dividend-yield/key-concepts'>dividend yield</a>, and <a href='/docs/v2/writing-algorithms/reality-modeling/options-models/pricing'>Option pricing</a> models.</li>
    <p>In <a href='/research/16977/greeks-and-iv-implementation/p1'>our research</a>, we found the Forward Tree model to be the best pricing model for indicators.</p>
    <div class="section-example-container">
        <pre class="python">risk_free_rate_model = qb.risk_free_interest_rate_model
dividend_yield_model = DividendYieldProvider(<?=$underlyingNamePy?>)
option_model = OptionPricingModelType.FORWARD_TREE</pre>
        <pre class="csharp">var riskFreeRateModel = qb.RiskFreeInterestRateModel;
var dividendYieldModel = new DividendYieldProvider(<?=$underlyingNameC?>);
var optionModel = OptionPricingModelType.ForwardTree;</pre>
    </div>

    <li>Define a method to return the <a href="/docs/v2/writing-algorithms/securities/asset-classes/equity-options/greeks-and-implied-volatility/indicators">IV & Greeks indicator</a> values for each contract.</li>
    <div class="section-example-container">
        <pre class="python">def greeks_and_iv(contracts, period, risk_free_rate_model, dividend_yield_model, option_model):
    # Get the call and put contract.
    call, put = sorted(contracts, key=lambda s: s.id.option_right)
    
    def get_values(indicator_class, contract, mirror_contract):
        return qb.indicator_history(
            indicator_class(contract, risk_free_rate_model, dividend_yield_model, mirror_contract, option_model), 
            [contract, mirror_contract, contract.underlying], 
            period
        ).data_frame.current

    return pd.DataFrame({
        'iv_call': get_values(ImpliedVolatility, call, put),
        'iv_put': get_values(ImpliedVolatility, put, call),
        'delta_call': get_values(Delta, call, put),
        'delta_put': get_values(Delta, put, call),
        'gamma_call': get_values(Gamma, call, put),
        'gamma_put': get_values(Gamma, put, call),
        'rho_call': get_values(Rho, call, put),
        'rho_put': get_values(Rho, put, call),
        'vega_call': get_values(Vega, call, put),
        'vega_put': get_values(Vega, put, call),
        'theta_call': get_values(Theta, call, put),
        'theta_put': get_values(Theta, put, call),
    })</pre>
        <pre class="csharp">Dictionary&lt;string, IndicatorHistory&gt; GreeksAndIV(List&lt;Symbol&gt; contracts, int period)
{
    // Get the call and put contract.
    var sortedSymbols = contracts.OrderBy(s => s.ID.OptionRight).ToArray();
    var call = sortedSymbols[0];
    var put = sortedSymbols[1];
    
    IndicatorHistory GetValues(OptionIndicatorBase indicator)
    {
        // Use both contracts and the underlying to update the indicator and get its value.
        return qb.IndicatorHistory(indicator, new[] { call, put, call.Underlying }, period);
    }

    // Get the values of all the IV and Greek indicators.
    return new Dictionary&lt;string, IndicatorHistory&gt;
    {
        {"IVCall", GetValues(new ImpliedVolatility(call, riskFreeRateModel, dividendYieldModel, put, optionModel))},
        {"IVPut", GetValues(new ImpliedVolatility(put, riskFreeRateModel, dividendYieldModel, call, optionModel))},
        {"DeltaCall", GetValues(new Delta(call, riskFreeRateModel, dividendYieldModel, put, optionModel))},
        {"DeltaPut", GetValues(new Delta(put, riskFreeRateModel, dividendYieldModel, call, optionModel))},
        {"GammaCall", GetValues(new Gamma(call, riskFreeRateModel, dividendYieldModel, put, optionModel))},
        {"GammaPut", GetValues(new Gamma(put, riskFreeRateModel, dividendYieldModel, call, optionModel))},
        {"VegaCall", GetValues(new Vega(call, riskFreeRateModel, dividendYieldModel, put, optionModel))},
        {"VegaPut", GetValues(new Vega(put, riskFreeRateModel, dividendYieldModel, call, optionModel))},
        {"ThetaCall", GetValues(new Theta(call, riskFreeRateModel, dividendYieldModel, put, optionModel))},
        {"ThetaPut", GetValues(new Theta(put, riskFreeRateModel, dividendYieldModel, call, optionModel))},
        {"RhoCall", GetValues(new Rho(call, riskFreeRateModel, dividendYieldModel, put, optionModel))},
        {"RhoPut", GetValues(new Rho(put, riskFreeRateModel, dividendYieldModel, call, optionModel))}
    };
}</pre>
    </div>

    <li>Call the preceding method and display the results.</li>
    <div class="section-example-container">
        <pre class="python">greeks_and_iv([<?=$contractNamePy?>, mirror_contract_symbol], 15, risk_free_rate_model, dividend_yield_model, option_model)</pre>
        <pre class="csharp">foreach (var (key, indicatorHistory) in GreeksAndIV(new List&lt;Symbol&gt; {<?=$contractNameC?>, mirrorContractSymbol}, 15))
{
    foreach (var dataPoint in indicatorHistory)
    {
        Console.WriteLine($"{dataPoint.EndTime} - {key}: {dataPoint.Current.Value}");
    }
}</pre>
    </div>
</ol>

<img class='python docs-image' alt='DataFrame result of the preceding code snippets, containing the greeks and IV history.' src='<?=$imgLinkPy?>'>
<p class='python'>The DataFrame can have NaN entries if there is no data for the contracts or the underlying asset at a moment in time.</p>
