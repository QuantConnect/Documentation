<p>Follow these steps to implement the long strangle strategy:</p>

<ol>
    <li>In the <code>Initialize</code> method, set the start date, end date, cash, and <a href="/docs/v2/writing-algorithms/universes/equity-options">Option universe</a>.</li>
    <div class="section-example-container">
        <pre class="csharp">private Symbol _symbol;

public override void Initialize()
{
    SetStartDate(2017, 4, 1);
    SetEndDate(2017, 4, 30);
    SetCash(100000);

    var option = AddOption("GOOG", Resolution.Minute);
    _symbol = option.Symbol;
    option.SetFilter(-5, 5, TimeSpan.FromDays(0), TimeSpan.FromDays(30));
}</pre>
        <pre class="python">def Initialize(self) -&gt; None:
    self.SetStartDate(2017, 4, 1)
    self.SetEndDate(2017, 4, 30)
    self.SetCash(100000)
    
    option = self.AddOption("GOOG", Resolution.Minute)
    self.symbol = option.Symbol
    option.SetFilter(-5, 5, timedelta(0), timedelta(30))</pre>
    </div>

    <li>In the <code>OnData</code> method, select the expiration date and strike prices of the contracts in the strategy legs.</li>
    <div class="section-example-container">
        <pre class="csharp">public override void OnData(Slice slice)
{
    if (Portfolio.Invested) return;

    // Get the OptionChain
    var chain = slice.OptionChains.get(_symbol, null);
    if (chain == null || chain.Count() == 0) return;

    // Select an expiration date
    var expiry = chain.OrderBy(contract =&gt; contract.Expiry).Last().Expiry;

    // Select the OTM call strike
    var callStrikes = chain.Where(contract =&gt; contract.Expiry == expiry 
                                              &amp;&amp; contract.Right == OptionRight.Call 
                                              &amp;&amp; contract.Strike &gt; chain.Underlying.Price)
                           .Select(contract =&gt; contract.Strike);
    if (callStrikes.Count() == 0) return;
    var callStrike = callStrikes.Min();

    // Select the OTM put strike
    var putStrikes = chain.Where(contract =&gt; contract.Expiry == expiry 
                                              &amp;&amp; contract.Right == OptionRight.Put 
                                              &amp;&amp; contract.Strike &lt; chain.Underlying.Price)
                          .Select(contract =&gt; contract.Strike);
    if (putStrikes.Count() == 0) return;
    var putStrike = putStrikes.Max();
}</pre>
        <pre class="python">def OnData(self, slice: Slice) -&gt; None:
    if self.Portfolio.Invested: return

    # Get the OptionChain
    chain = slice.OptionChains.get(self.symbol, None)
    if not chain: return

    # Select an expiration date
    expiry = sorted(chain, key=lambda contract: contract.Expiry, reverse=True)[0].Expiry

    # Select the OTM call strike
    strikes = [contract.Strike for contract in chain if contract.Expiry == expiry]
    call_strikes = [contract.Strike for contract in chain 
        if contract.Expiry == expiry 
        and contract.Right == OptionRight.Call
        and contract.Strike > chain.Underlying.Price]
    if len(call_strikes) == 0: return
    call_strike = min(call_strikes)

    # Select the OTM put strike
    put_strikes = [contract.Strike for contract in chain 
        if contract.Expiry == expiry 
        and contract.Right == OptionRight.Put
        and contract.Strike < chain.Underlying.Price]
    if len(put_strikes) == 0: return
    put_strike = max(put_strikes)</pre>
    </div>

    <li>In the <code>OnData</code> method, call the <code>OptionStrategies.Strangle</code> method and then submit the order.</li>
    <div class="section-example-container">
        <pre class="csharp">var optionStrategy = OptionStrategies.Strangle(_symbol, callStrike, putStrike, expiry);
Buy(optionStrategy, 1);</pre>
        <pre class="python">option_strategy = OptionStrategies.Strangle(self.symbol, call_strike, put_strike, expiry)
self.Buy(option_strategy, 1)</pre>
    </div>

<?php 
$methodNames = array("Buy");
include(DOCS_RESOURCES."/trading-and-orders/option-strategy-extra-args.php"); 
?>

</ol>
