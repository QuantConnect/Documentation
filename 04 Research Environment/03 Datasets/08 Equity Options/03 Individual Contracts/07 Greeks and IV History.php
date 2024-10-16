<? include(DOCS_RESOURCES."/securities/data-definitions/greeks-and-iv.html"); ?>

<p>Follow these steps to get the Greeks and IV data:</p>

<ol>
    <li>Create the mirror contract <code>Symbol</code>.</li>
    <div class="section-example-container">
        <pre class="csharp">var mirrorContractSymbol = Symbol.CreateOption(
    optionContract.Underlying.Symbol, 
    contractSymbol.ID.Market, 
    optionContract.Style, 
    optionContract.Right == OptionRight.Put ? OptionRight.Call : OptionRight.Put,
    optionContract.StrikePrice, 
    optionContract.Expiry
);</pre>
        <pre class="python">mirror_contract_symbol = Symbol.create_option(
    option_contract.underlying.symbol, contract_symbol.id.market, option_contract.style, 
    OptionRight.Call if option_contract.right == OptionRight.PUT else OptionRight.PUT,
    option_contract.strike_price, option_contract.expiry
)</pre>
    </div>
    
    <li>Set up the <a href='/docs/v2/writing-algorithms/reality-modeling/risk-free-interest-rate/key-concepts'>risk free interest rate</a>, <a href='/docs/v2/writing-algorithms/reality-modeling/dividend-yield/key-concepts'>dividend yield</a>, and <a href='/docs/v2/writing-algorithms/reality-modeling/options-models/pricing'>Option pricing</a> models.</li>
    <div class="section-example-container">
        <pre class="python">risk_free_rate_model = qb.risk_free_interest_rate_model
dividend_yield_model = DividendYieldProvider(underlying_symbol)
option_model = OptionPricingModelType.FORWARD_TREE</pre>
        <pre class="csharp">var riskFreeRateModel = qb.RiskFreeInterestRateModel;
var dividendYieldModel = new DividendYieldProvider(underlyingSymbol);
var optionModel = OptionPricingModelType.ForwardTree;</pre>
    </div>

    <li>Define a method to return the <a href="/docs/v2/writing-algorithms/securities/asset-classes/equity-options/greeks-and-implied-volatility/indicators">IV & Greeks indicator</a> values for each contract.</li>
    <div class="section-example-container">
        <pre class="python">def get_indicators(contracts, period):

    call, put = sorted(contracts, key=lambda s: s.id.option_right)
    
    def get_value(indicator):
        return qb.indicator_history(indicator, [call, put, underlying_symbol], period).data_frame.current

    return pd.DataFrame({
        'iv_call': get_value(ImpliedVolatility(call, risk_free_rate_model, dividend_yield_model, put, option_model)),
        'iv_put': get_value(ImpliedVolatility(put, risk_free_rate_model, dividend_yield_model, call, option_model)),
        'delta_call': get_value(Delta(call, risk_free_rate_model, dividend_yield_model, put, option_model)),
        'delta_put': get_value(Delta(put, risk_free_rate_model, dividend_yield_model, call, option_model)),
        'gamma_call': get_value(Gamma(call, risk_free_rate_model, dividend_yield_model, put, option_model)),
        'gamma_put': get_value(Gamma(put, risk_free_rate_model, dividend_yield_model, call, option_model)),
        'rho_call': get_value(Rho(call, risk_free_rate_model, dividend_yield_model, put, option_model)),
        'rho_put': get_value(Rho(put, risk_free_rate_model, dividend_yield_model, call, option_model)),
        'vega_call': get_value(Vega(call, risk_free_rate_model, dividend_yield_model, put, option_model)),
        'vega_put': get_value(Vega(put, risk_free_rate_model, dividend_yield_model, call, option_model)),
        'theta_call': get_value(Theta(call, risk_free_rate_model, dividend_yield_model, put, option_model)),
        'theta_put': get_value(Theta(put, risk_free_rate_model, dividend_yield_model, call, option_model)),
    })</pre>
        <pre class="csharp">Dictionary&lt;string, IndicatorHistory&gt; GetIndicators(List&lt;Symbol&gt; contracts, int period)
{
    // Get the call and put contract.
    var sortedSymbols = contracts.OrderBy(s => s.ID.OptionRight).ToArray();
    var call = sortedSymbols[0];
    var put = sortedSymbols[1];
    
    IndicatorHistory GetValue(OptionIndicatorBase indicator)
    {
        // Use both contracts and the underlying to update the indicator and get its value.
        return qb.IndicatorHistory(indicator, new[] { call, put, underlyingSymbol }, period);
    }

    // Get the values of all the IV and Greek indicators.
    return new Dictionary&lt;string, IndicatorHistory&gt;
    {
        {"IVCall", GetValue(new ImpliedVolatility(call, riskFreeRateModel, dividendYieldModel, put, optionModel))},
        {"IVPut", GetValue(new ImpliedVolatility(put, riskFreeRateModel, dividendYieldModel, call, optionModel))},
        {"DeltaCall", GetValue(new Delta(call, riskFreeRateModel, dividendYieldModel, put, optionModel))},
        {"DeltaPut", GetValue(new Delta(put, riskFreeRateModel, dividendYieldModel, call, optionModel))},
        {"GammaCall", GetValue(new Gamma(call, riskFreeRateModel, dividendYieldModel, put, optionModel))},
        {"GammaPut", GetValue(new Gamma(put, riskFreeRateModel, dividendYieldModel, call, optionModel))},
        {"VegaCall", GetValue(new Vega(call, riskFreeRateModel, dividendYieldModel, put, optionModel))},
        {"VegaPut", GetValue(new Vega(put, riskFreeRateModel, dividendYieldModel, call, optionModel))},
        {"ThetaCall", GetValue(new Theta(call, riskFreeRateModel, dividendYieldModel, put, optionModel))},
        {"ThetaPut", GetValue(new Theta(put, riskFreeRateModel, dividendYieldModel, call, optionModel))},
        {"RhoCall", GetValue(new Rho(call, riskFreeRateModel, dividendYieldModel, put, optionModel))},
        {"RhoPut", GetValue(new Rho(put, riskFreeRateModel, dividendYieldModel, call, optionModel))}
    };
}</pre>
    </div>

    <li>Call the preceding method and display the results.</li>
    <div class="section-example-container">
        <pre class="python">get_indicators([contract_symbol, mirror_contract_symbol], 10)</pre>
        <pre class="csharp">foreach (var (key, indicatorHistory) in GetIndicators(new List<Symbol> {contractSymbol, mirrorContractSymbol}, 10))
{
    foreach (var dataPoint in indicatorHistory)
    {
        Console.WriteLine($"{dataPoint.EndTime} - {key}: {dataPoint.Current.Value}");
    }
}</pre>
    </div>
</ol>

