<p>Follow these steps to implement the long straddle strategy:</p>

<ol>
    <li>In the <code class="csharp">Initialize</code><code class="python">initialize</code> method, set the start date, end date, cash, and <a href="/docs/v2/writing-algorithms/universes/equity-options">Option universe</a>.</li>
    <div class="section-example-container">
        <pre class="csharp">private Symbol _symbol;

public override void Initialize()
{
    SetStartDate(2017, 4, 1);
    SetEndDate(2017, 6, 30);
    SetCash(100000);
    UniverseSettings.Asynchronous = true;
    var option = AddOption("GOOG");
    _symbol = option.Symbol;
    option.SetFilter(-1, 1, 30, 60);
}</pre>
        <pre class="python">def initialize(self) -&gt; None:
    self.set_start_date(2017, 4, 1)
    self.set_end_date(2017, 6, 30)
    self.set_cash(100000)
    self.universe_settings.asynchronous = True 
    option = self.add_option("GOOG")
    self._symbol = option.symbol
    option.set_filter(-1, 1, 30, 60)</pre>
    </div>
</ol>

<h4>Using Helper strategies</h4>
<ol>
    <li>In the <code class="csharp">OnData</code><code class="python">on_data</code> method, select the expiration date and strike price of the contracts in the strategy legs.</li>
    <div class="section-example-container">
        <pre class="csharp">public override void OnData(Slice slice)
{
    if (Portfolio.Invested || 
        !slice.OptionChains.TryGetValue(_symbol, out var chain))
    {
        return;
    }

    // Find ATM options with the nearest expiry
    var expiry = chain.Min(contract =&gt; contract.Expiry);
    var contracts = chain.Where(contract =&gt; contract.Expiry == expiry)
                         .OrderBy(contract =&gt; Math.Abs(contract.Strike - chain.Underlying.Price))
                         .ToArray();

    if (contracts.Length < 2) return;</pre>
        <pre class="python">def on_data(self, slice: Slice) -&gt; None:
    if self.Portfolio.Invested:
        return

    chain = slice.OptionChains.get(self._symbol, None)
    if not chain:
        return

    # Find ATM options with the nearest expiry
    expiry = min([x.Expiry for x in chain])
    contracts = sorted([x for x in chain if x.Expiry == expiry],
        key=lambda x: abs(chain.Underlying.Price - x.Strike))

    if len(contracts) < 2:
        return</pre>
    </div>

    <li>In the <code class="csharp">OnData</code><code class="python">on_data</code> method, call the <code class="csharp">OptionStrategies.Straddle</code><code class="python">OptionStrategies.straddle</code> method and then submit the order.</li>
    <div class="section-example-container">
        <pre class="csharp">var longStraddle = OptionStrategies.Straddle(_symbol, contracts[0].Strike, expiry);
Buy(longStraddle, 1);<br></pre>
        <pre class="python">long_straddle = OptionStrategies.straddle(self._symbol, contracts[0].strike, expiry)
self.buy(long_straddle, 1)</pre>
    </div>

<?
$methodNames = array("Buy");
include(DOCS_RESOURCES."/trading-and-orders/option-strategy-extra-args.php"); 
?>

</ol>

<h4>Using Combo Orders</h4>
<ol>
    <li>In the <code class="csharp">OnData</code><code class="python">on_data</code> method, select the strategy legs.</li>
    <div class="section-example-container">
        <pre class="csharp">public override void OnData(Slice slice)
{
    if (Portfolio.Invested || 
        !slice.OptionChains.TryGetValue(_symbol, out var chain))
    {
        return;
    }

    // Find ATM options with the nearest expiry
    var expiry = chain.Min(contract =&gt; contract.Expiry);
    var contracts = chain.Where(contract =&gt; contract.Expiry == expiry)
                         .OrderBy(contract =&gt; Math.Abs(contract.Strike - chain.Underlying.Price));

    if (contracts.Length < 2) return;

    var atmCall = contracts.Single(x =&gt; x.Right == OptionRight.Call);
    var atmPut = contracts.Single(x =&gt; x.Right == OptionRight.Put);</pre>
        <pre class="python">def on_data(self, slice: Slice) -&gt; None:
    if self.Portfolio.Invested:
        return

    chain = slice.OptionChains.get(self._symbol, None)
    if not chain:
        return

    # Find ATM options with the nearest expiry
    expiry = min([x.Expiry for x in chain])
    contracts = sorted([x for x in chain if x.Expiry == expiry],
        key=lambda x: abs(chain.Underlying.Price - x.Strike))

    if len(contracts) < 2:
        return
    
    atm_call = [x for x in contracts if x.right == OptionRight.CALL][0]
    atm_put = [x for x in contracts if x.right == OptionRight.PUT][0]</pre>
    </div>

    <li>In the <code class="csharp">OnData</code><code class="python">on_data</code> method, create <code>Leg</code> and call the <a href="/docs/v2/writing-algorithms/trading-and-orders/order-types/combo-market-orders">Combo Market Order</a>/<a href="/docs/v2/writing-algorithms/trading-and-orders/order-types/combo-limit-orders">Combo Limit Order</a>/<a href="/docs/v2/writing-algorithms/trading-and-orders/order-types/combo-leg-limit-orders">Combo Leg Limit Order</a> to submit the order.</li>
    <div class="section-example-container">
        <pre class="csharp">var legs = new List&lt;Leg&gt;()
    {
        Leg.Create(atmCall.Symbol, 1),
        Leg.Create(atmPut.Symbol, 1)
    };
ComboMarketOrder(legs, 1);</pre>
        <pre class="python">legs = [
    Leg.create(atm_call.symbol, 1),
    Leg.create(atm_put.symbol, 1)
]
self.combo_market_order(legs, 1)</pre>
    </div>

</ol>
