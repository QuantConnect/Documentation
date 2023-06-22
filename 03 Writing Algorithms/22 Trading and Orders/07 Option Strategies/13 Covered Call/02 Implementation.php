<p>Follow these steps to implement the covered call strategy:</p>

<ol>
    <li>In the <code>Initialize</code> method, set the start date, end date, starting cash, and <a href="/docs/v2/writing-algorithms/universes/equity-options">Options universe</a>.</li>
    <div class="section-example-container">
        <pre class="csharp">private Symbol _call, _symbol;

public override void Initialize()
{
    SetStartDate(2014, 1, 1);
    SetEndDate(2014, 3, 1);
    SetCash(100000);

    var option = AddOption("IBM");
    _symbol = option.Symbol;
    option.SetFilter(-3, 3, 0, 31);
}</pre>
        <pre class="python">def Initialize(self) -&gt; None:
    self.SetStartDate(2014, 1, 1)
    self.SetEndDate(2014, 3, 1)
    self.SetCash(100000)
    
    option = self.AddOption("IBM")
    self.symbol = option.Symbol
    self.call = None

    option.SetFilter(-3, 3, 0, 31)</pre>
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

    <li>In the <code>OnData</code> method, call the <code>OptionStrategies.CoveredCall</code> method and then submit the order.</li>
    <div class="section-example-container">
        <pre class="csharp">var coveredCall = OptionStrategies.CoveredCall(_symbol, atmCall.Strike, expiry);
Buy(coveredCall, 1);

_call = atmCall.Symbol;</pre>
        <pre class="python">covered_call = OptionStrategies.CoveredCall(self.symbol, atm_call.Strike, expiry)
self.Buy(covered_call, 1)

self.call = atm_call.Symbol</pre>
    </div>
<?php 
$methodNames = array("Buy");
include(DOCS_RESOURCES."/trading-and-orders/option-strategy-extra-args.php"); 
?>
    
</ol>
