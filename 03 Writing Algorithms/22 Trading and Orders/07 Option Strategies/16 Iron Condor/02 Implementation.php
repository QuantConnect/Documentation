<p>Follow these steps to implement the long iron condor strategy:</p>

<ol>
    <li>In the <code class="csharp">Initialize</code><code class="python">initialize</code> method, set the start date, end date, cash, and <a href="/docs/v2/writing-algorithms/universes/equity-options">Option universe</a>.</li>
    <div class="section-example-container">
        <pre class="csharp">private Symbol _symbol;

public override void Initialize()
{
    SetStartDate(2017, 2, 1);
    SetEndDate(2017, 3, 1);
    SetCash(500000);
    UniverseSettings.Asynchronous = true;
    var option = AddOption("GOOG");
    _symbol = option.Symbol;
    option.SetFilter(universe =&gt; universe.IncludeWeeklys().Strikes(-15, 15).Expiration(0, 40));
}</pre>
        <pre class="python">def initialize(self) -&gt; None:
    self.set_start_date(2017, 2, 1)
    self.set_end_date(2017, 3, 1)
    self.set_cash(500000)
    self.universe_settings.asynchronous = True
    option = self.add_option("GOOG")
    self._symbol = option.symbol
    option.set_filter(lambda universe: universe.include_weeklys().strikes(-15, 15).expiration(0, 40))</pre>
    </div>

    <li>In the <code class="csharp">OnData</code><code class="python">on_data</code> method, select the contracts in the strategy legs.</li>
    <div class="section-example-container">
        <pre class="csharp">public override void OnData(Slice slice)
{
    if (Portfolio[_symbol.Underlying].Invested)
    {
        Liquidate();
    }

    if (Portfolio.Invested || !IsMarketOpen(_symbol)) return;

    if (!slice.OptionChains.TryGetValue(_symbol, out var chain)) return;

    // Find put and call contracts with the farthest expiry
    var expiry = chain.Max(x =&gt; x.Expiry);
    var contracts = chain.Where(x =&gt; x.Expiry == expiry).OrderBy(x => x.Strike);

    var putContracts = contracts.Where(x =&gt; x.Right == OptionRight.Put).ToArray();
    var callContracts = contracts.Where(x =&gt; x.Right == OptionRight.Call).ToArray();

    if (putContracts.Length &lt; 10 || putContracts.Length &lt; 10) return;

    // Select the strategy legs
    var farPut = putContracts[0];
    var nearPut = putContracts[10];
    var nearCall = callContracts[^10];
    var farCall = callContracts[^1];

    // Select the strikes in the strategy legs
    var farPutStrike = farPut.Strike;
    var nearPutStrike = nearPut.Strike;
    var nearCallStrike = nearCall.Strike;
    var farCallStrike = farCall.Strike;</pre>
        <pre class="python">def on_data(self, slice: Slice) -&gt; None:
    if self.portfolio[self.symbol.underlying].invested:
        self.liquidate()

    if self.portfolio.invested or not self.is_market_open(self._symbol):
        return

    chain = slice.option_chains.get(self._symbol)
    if not chain:
        return

    # Find put and call contracts with the farthest expiry       
    expiry = max([x.expiry for x in chain])
    chain = sorted([x for x in chain if x.expiry == expiry], key = lambda x: x.strike)

    put_contracts = [x for x in chain if x.right == OptionRight.PUT]
    call_contracts = [x for x in chain if x.right == OptionRight.CALL]

    if len(call_contracts) &lt; 10 or len(put_contracts) &lt; 10:
        return

    # Select the strategy legs
    far_put = put_contracts[0]
    near_put = put_contracts[10]
    near_call = call_contracts[-10]
    far_call = call_contracts[-1]

    # Select the strikes in the strategy legs
    far_put_strike = far_put.strike
    near_put_strike = near_put.strike
    near_call_strike = near_call.strike
    far_call_strike = far_call.strike</pre>
    </div>
</ol>

<h4>Using Helper strategies</h4>
<ol>
    <li>In the <code class="csharp">OnData</code><code class="python">on_data</code> method, call the <code class="csharp">OptionStrategies.IronCondor</code><code class="python">OptionStrategies.iron_condor</code> method and then submit the order.</li>
    <div class="section-example-container">
        <pre class="csharp">var ironCondor = OptionStrategies.IronCondor(
    _symbol, 
    farPutStrike,
    nearPutStrike,
    nearCallStrike,
    nearCallStrike,
    expiry);

Buy(ironCondor, 2);</pre>
        <pre class="python">iron_condor = OptionStrategies.iron_condor(
    self.symbol, 
    far_put_strike,
    near_put_strike,
    near_call_strike,
    far_call_strike,
    expiry)

self.buy(iron_condor, 2)</pre>
    </div>
<?php 
$methodNames = array("Buy", "Sell");
include(DOCS_RESOURCES."/trading-and-orders/option-strategy-extra-args.php"); 
?>
    
</ol>

<h4>Using Combo Orders</h4>
<ol>
    <li>In the <code class="csharp">OnData</code><code class="python">on_data</code> method, create <code>Leg</code> and call the <a href="/docs/v2/writing-algorithms/trading-and-orders/order-types/combo-market-orders">Combo Market Order</a>/<a href="/docs/v2/writing-algorithms/trading-and-orders/order-types/combo-limit-orders">Combo Limit Order</a>/<a href="/docs/v2/writing-algorithms/trading-and-orders/order-types/combo-leg-limit-orders">Combo Leg Limit Order</a> to submit the order.</li>
    <div class="section-example-container">
        <pre class="csharp">var legs = new List&lt;Leg&gt;()
    {
        Leg.Create(farPut.Symbol, -1),
        Leg.Create(nearPut.Symbol, 1),
        Leg.Create(farCall.Symbol, -1),
        Leg.Create(nearCall.Symbol, 1)
    };
ComboMarketOrder(legs, 1);</pre>
        <pre class="python">legs = [
    Leg.create(far_put.symbol, -1),
    Leg.create(near_put.symbol, 1),
    Leg.create(far_call.symbol, -1),
    Leg.create(near_call.symbol, 1)
]
self.combo_market_order(legs, 1)</pre>
    </div>

</ol>
