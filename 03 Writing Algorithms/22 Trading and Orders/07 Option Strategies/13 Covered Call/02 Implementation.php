<p>Follow these steps to implement the covered call strategy:</p>

<ol>
    <li>In the <code class="csharp">Initialize</code><code class="python">initialize</code> method, set the start date, end date, starting cash, and <a href="/docs/v2/writing-algorithms/universes/equity-options">Options universe</a>.</li>
    <div class="section-example-container">
        <pre class="csharp">private Symbol _call, _symbol;

public override void Initialize()
{
    SetStartDate(2014, 1, 1);
    SetEndDate(2014, 3, 1);
    SetCash(100000);
    UniverseSettings.Asynchronous = true;
    var option = AddOption("IBM");
    _symbol = option.Symbol;
    option.SetFilter(-3, 3, 0, 31);
}</pre>
        <pre class="python">def initialize(self) -&gt; None:
    self.set_start_date(2014, 1, 1)
    self.set_end_date(2014, 3, 1)
    self.set_cash(100000)
    self.universe_settings.asynchronous = True
    option = self.add_option("IBM")
    self._symbol = option.symbol
    option.set_filter(-3, 3, 0, 31)
    self.call = None</pre>
    </div>
  
    <li>In the <code class="csharp">OnData</code><code class="python">on_data</code> method, select the Option contract.</li>
    <div class="section-example-container">
        <pre class="csharp">public override void OnData(Slice slice)
{
    if (_call != null &amp;&amp; Portfolio[_call].Invested) return;

    if (!slice.OptionChains.TryGetValue(_symbol, out var chain)) return;

    // Find ATM call with the farthest expiry
    var expiry = chain.Max(x =&gt; x.Expiry);
    var atmCall = chain
        .Where(x =&gt; x.Right == OptionRight.Call &amp;&amp; x.Expiry == expiry)
        .OrderBy(x =&gt; Math.Abs(x.Strike - chain.Underlying.Price))
        .FirstOrDefault();</pre>
        <pre class="python">def on_data(self, slice: Slice) -&gt; None:
    if self.call and self.portfolio[self.call].invested:
        return

    chain = slice.option_chains.get(self._symbol)
    if not chain:
        return

    # Find ATM call with the farthest expiry
    expiry = max([x.expiry for x in chain])
    call_contracts = sorted([x for x in chain
        if x.right == Optionright.call and x.expiry == expiry],
        key=lambda x: abs(chain.underlying.price - x.strike))

    if not call_contracts:
        return

    atm_call = call_contracts[0]</pre>
</div>

    <li>In the <code class="csharp">OnData</code><code class="python">on_data</code> method, call the <code>OptionStrategies.CoveredCall</code> method and then submit the order.</li>
    <div class="section-example-container">
        <pre class="csharp">var coveredCall = OptionStrategies.CoveredCall(_symbol, atmCall.Strike, expiry);
Buy(coveredCall, 1);

_call = atmCall.Symbol;</pre>
        <pre class="python">covered_call = OptionStrategies.covered_call(self._symbol, atm_call.strike, expiry)
self.buy(covered_call, 1)

self.call = atm_call.symbol</pre>
    </div>
<?php 
$methodNames = array("Buy");
include(DOCS_RESOURCES."/trading-and-orders/option-strategy-extra-args.php"); 
?>
    
</ol>
