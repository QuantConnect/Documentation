<p>Follow these steps to implement the protective call strategy:</p>

<ol>
    <li>In the <code>Initialize</code> method, set the start date, end date, cash, and <a href="/docs/v2/writing-algorithms/universes/equity-options">Options universe</a>.</li>
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
        <pre class="python">def Initialize(self) -&gt; None:
    self.SetStartDate(2014, 1, 1)
    self.SetEndDate(2014, 3, 1)
    self.SetCash(100000)
    self.UniverseSettings.Asynchronous = True
    option = self.AddOption("IBM")
    self.symbol = option.Symbol
    option.SetFilter(-3, 3, 0, 31)
    self.call = None</pre>
    </div>
  
    <li>In the <code>OnData</code> method, select the Option contract.</li>
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
        <pre class="python">def OnData(self, slice: Slice) -&gt; None:
    if self.call and self.Portfolio[self.call].Invested:
        return

    chain = slice.OptionChains.get(self.symbol)
    if not chain:
        return

    # Find ATM call with the farthest expiry
    expiry = max([x.Expiry for x in chain])
    call_contracts = sorted([x for x in chain
        if x.Right == OptionRight.Call and x.Expiry == expiry],
        key=lambda x: abs(chain.Underlying.Price - x.Strike))

    if not call_contracts:
        return

    atm_call = call_contracts[0]</pre>
</div>

    <li>In the <code>OnData</code> method, call the <code>OptionStrategies.ProtectiveCall</code> method and then submit the order.</li>
    <div class="section-example-container">
        <pre class="csharp">var protectiveCall = OptionStrategies.ProtectiveCall(_symbol, atmCall.Strike, expiry);
Buy(protectiveCall, 1);

_call = atmCall.Symbol;</pre>
        <pre class="python">protective_call = OptionStrategies.ProtectiveCall(self.symbol, atm_call.Strike, expiry)
self.Buy(protective_call, 1)

self.call = atm_call.Symbol</pre>
    </div>

<?php 
$methodNames = array("Buy");
include(DOCS_RESOURCES."/trading-and-orders/option-strategy-extra-args.php"); 
?>
</ol>
