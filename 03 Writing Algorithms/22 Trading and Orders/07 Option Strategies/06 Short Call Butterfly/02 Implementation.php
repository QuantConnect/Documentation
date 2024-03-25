<p>Follow these steps to implement the short call butterfly strategy:</p>

<ol>
    <li>In the <code>Initialize</code> method, set the start date, end date, cash, and <a href="/docs/v2/writing-algorithms/universes/equity-options">Option universe</a>.</li>
    <div class="section-example-container">
        <pre class="csharp">private Symbol _symbol;

public override void Initialize()
{
    SetStartDate(2017, 2, 1);
    SetEndDate(2017, 3, 5);
    SetCash(500000);
    UniverseSettings.Asynchronous = true;
    var option = AddOption("GOOG", Resolution.Minute);
    _symbol = option.Symbol;
    option.SetFilter(universe =&gt; universe.IncludeWeeklys().Strikes(-15, 15).Expiration(0, 31));
}</pre>
        <pre class="python">def Initialize(self) -&gt; None:
    self.SetStartDate(2017, 2, 1)
    self.SetEndDate(2017, 3, 5)
    self.SetCash(500000)
    self.UniverseSettings.Asynchronous = True
    option = self.AddOption("GOOG", Resolution.Minute)
    self.symbol = option.Symbol
    option.SetFilter(lambda universe: universe.IncludeWeeklys().Strikes(-15, 15).Expiration(0, 31))</pre>
    </div>

    <li>In the <code>OnData</code> method, select the expiration and strikes of the contracts in the strategy legs.</li>
    <div class="section-example-container">
        <pre class="csharp">public override void OnData(Slice slice)
{
    if (Portfolio.Invested) return;

    // Get the OptionChain
    var chain = slice.OptionChains.get(_symbol, null);
    if (chain == null || chain.Count() == 0) return;

    // Get the furthest expiry date of the contracts
    var expiry = chain.OrderByDescending(x =&gt; x.Expiry).First().Expiry;
    
    // Select the call Option contracts with the furthest expiry
    var calls = chain.Where(x =&gt; x.Expiry == expiry &amp;&amp; x.Right == OptionRight.Call);
    if (calls.Count() == 0) return;

    // Get the strike prices of the all the call Option contracts
    var callStrikes = calls.Select(x =&gt; x.Strike).OrderBy(x =&gt; x);

    // Get the ATM strike price
    var atmStrike = calls.OrderBy(x =&gt; Math.Abs(x.Strike - chain.Underlying.Price)).First().Strike;

    // Get the strike prices for the contracts not ATM
    var spread = Math.Min(Math.Abs(callStrikes.First() - atmStrike), Math.Abs(callStrikes.Last() - atmStrike));
    var itmStrike = atmStrike - spread;
    var otmStrike = atmStrike + spread;</pre>
        <pre class="python">def OnData(self, slice: Slice) -&gt; None:
    if self.Portfolio.Invested: return

    # Get the OptionChain
    chain = slice.OptionChains.get(self.symbol, None)
    if not chain: return

    # Get the furthest expiry date of the contracts
    expiry = sorted(chain, key = lambda x: x.Expiry, reverse=True)[0].Expiry
    
    # Select the call Option contracts with the furthest expiry
    calls = [i for i in chain if i.Expiry == expiry and i.Right == OptionRight.Call]
    if len(calls) == 0: return

    # Get the strike prices of the all the call Option contracts
    call_strikes = sorted([x.Strike for x in calls])

    # Get the ATM strike price
    atm_strike = sorted(calls, key=lambda x: abs(x.Strike - chain.Underlying.Price))[0].Strike

    # Get the strike prices for the contracts not ATM
    spread = min(abs(call_strikes[0] - atm_strike), abs(call_strikes[-1] - atm_strike))
    itm_strike = atm_strike - spread
    otm_strike = atm_strike + spread</pre>
    </div>

    <li>In the <code>OnData</code> method, call the <code>OptionStrategies.ShortButterflyCall</code> method and then submit the order.</li>
    <div class="section-example-container">
        <pre class="csharp">var optionStrategy = OptionStrategies.ShortButterflyCall(_symbol, otmStrike, atmStrike, itmStrike, expiry);
Buy(optionStrategy, 1);</pre>
        <pre class="python">option_strategy = OptionStrategies.ShortButterflyCall(self.symbol, otm_strike, atm_strike, itm_strike, expiry)
self.Buy(option_strategy, 1)</pre>
    </div>

<?php 
$methodNames = array("Buy");
include(DOCS_RESOURCES."/trading-and-orders/option-strategy-extra-args.php"); 
?>

</ol>
