<p>To use your volatility model as the <a href='/docs/v2/writing-algorithms/reality-modeling/options-models/pricing#96-What-Is-Implied-Volatility3F'>inital guess for the implied volatility</a>, warm up the volatility model of the underlying security. If you subscribe to all the Options in the <code class="csharp">Initialize</code><code class="python">initialize</code> method, set a <a href="/docs/v2/writing-algorithms/historical-data/warm-up-periods">warm-up period</a> to warm up their volatility models. The warm-up period should provide the volatility models with enough data to compute their values.</p>

<div class="section-example-container">
    <pre class="csharp">public override void Initialize()
{
    // For default 30-day SD of return as volatility, you need 30+1 trading day to warm up
    SetWarmUp(31, Resolution.Daily);
}

public override void OnData(Slice slice)
{
    // Only take the warmed-up volatility value into account for accuracy issue
    if (IsWarmingUp) return;
}</pre>
    <pre class="python">def initialize(self) -&gt; None:
    # For default 30-day SD of return as volatility, you need 30+1 trading day to warm up
    self.set_warm_up(30, Resolution.DAILY)

def on_data(self, slice: Slice) -&gt; None:
    # Only take the warmed-up volatility value into account for accuracy issue
    if self.is_warming_up:
        return</pre>
</div>

<p>If you have a dynamic universe of underlying assets and add Option contracts to your algorithm with the <code class="csharp">AddOptionContract</code><code class="python">add_option_contract</code>, <code class="csharp">AddIndexOptionContract</code><code class="python">add_index_option_contract</code>, or <code class="csharp">AddFutureOptionContract</code><code class="python">add_future_option_contract</code>  methods, warm up the volatility model when the underlying asset enters your universe. We recommend you do this inside a <a href='/docs/v2/writing-algorithms/initialization#07-Set-Security-Initializer'>security initializer</a>.</p>


<?php

$overwriteCodePy = "if security.type == SecurityType.EQUITY:  # Underlying asset type
            security.volatility_model = StandardDeviationOfReturnsVolatilityModel(30)
            trade_bars = self._algorithm.history[TradeBar](security.symbol, 30, Resolution.DAILY)
            for trade_bar in trade_bars:
                security.volatility_model.update(security, trade_bar)";
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
