<p>To use your volatility model as the <a href='/docs/v2/writing-algorithms/reality-modeling/options-models/pricing#03-What-Is-Implied-Volatility3F'>inital guess for the implied volatility</a>, warm up the volatility model of the underlying security. If you subscribe to all the Options in the <code class="csharp">Initialize</code><code class="python">initialize</code> method, set a <a href="/docs/v2/writing-algorithms/historical-data/warm-up-periods">warm-up period</a> to warm up their volatility models. The warm-up period should provide the volatility models with enough data to compute their values.</p>

<div class="section-example-container">
    <pre class="csharp">// In Initialize
SetWarmUp(30, Resolution.Daily);

// In OnData
if (IsWarmingUp) return;</pre>
    <pre class="python"># In Initialize
self.set_warm_up(30, Resolution.DAILY)

# In OnData
if self.is_warming_up:
    return</pre>
</div>

<p>If you have a dynamic universe of underlying assets and add Option contracts to your algorithm with the <code>AddOptionContract</code>, <code class="csharp">AddIndexOptionContract</code><code class="python">add_index_option_contract</code>, or <code class="csharp">AddFutureOptionContract</code><code class="python">add_future_option_contract</code>  methods, warm up the volatility model when the underlying asset enters your universe. We recommend you do this inside a <a href='/docs/v2/writing-algorithms/initialization#07-Set-Security-Initializer'>security initializer</a>.</p>


<?php

$overwriteCodePy = "if security.Type == SecurityType.Equity:  # Underlying asset type
            security.VolatilityModel = StandardDeviationOfReturnsVolatilityModel(30)
            trade_bars = self.algorithm.History[TradeBar](security.Symbol, 30, Resolution.Daily)
            for trade_bar in trade_bars:
                security.VolatilityModel.Update(security, trade_bar)";
$overwriteCodeC = "if (security.Type == SecurityType.Equity) // Underlying asset type
        {
            security.VolatilityModel = new StandardDeviationOfReturnsVolatilityModel(30);
            foreach (var tradeBar in _algorithm.History(security.Symbol, 30, Resolution.Daily))
            {
                security.VolatilityModel.Update(security, tradeBar);
            }
        }";
$comment = "and warm up the volatility model";
$saveAlgorithm = true;
include(DOCS_RESOURCES."/reality-modeling/brokerage-model-security-init.php");
?>
