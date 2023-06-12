<p>Follow these steps to implement the long straddle strategy:</p>

<ol>
    <li>In the <code>Initialize</code> method, set the start date, end date, cash, and <a href="/docs/v2/writing-algorithms/universes/equity-options">Option universe</a>.</li>
    <div class="section-example-container">
        <pre class="csharp">private Symbol _symbol;

public override void Initialize()
{
    SetStartDate(2017, 4, 1);
    SetEndDate(2017, 6, 30);
    SetCash(100000);

    var option = AddOption("GOOG", Resolution.Minute);
    _symbol = option.Symbol;
    option.SetFilter(-1, 1, TimeSpan.FromDays(30), TimeSpan.FromDays(60));
}</pre>
        <pre class="python">def Initialize(self) -&gt; None:
    self.SetStartDate(2017, 4, 1)
    self.SetEndDate(2017, 6, 30)
    self.SetCash(100000)
    
    option = self.AddOption("GOOG", Resolution.Minute)
    self.symbol = option.Symbol
    option.SetFilter(-1, 1, timedelta(30), timedelta(60))</pre>
    </div>

    <li>In the <code>OnData</code> method, select the expiration date and strike price of the contracts in the strategy legs.</li>
    <div class="section-example-container">
        <pre class="csharp">public override void OnData(Slice slice)
{
    if (Portfolio.Invested) return;

    // Get the OptionChain
    var chain = slice.OptionChains.get(_symbol, null);
    if (chain == null || chain.Count() == 0) return;

    // Select an expiration date
    var expiry = chain.OrderBy(contract =&gt; contract.Expiry).Last().Expiry;

    // Select the ATM strike price
    var strike = chain.Where(contract =&gt; contract.Expiry == expiry)
                      .Select(contract =&gt; contract.Strike)
                      .OrderBy(strike =&gt; Math.Abs(strike - chain.Underlying.Price))
                      .First();</pre>
        <pre class="python">def OnData(self, slice: Slice) -&gt; None:
    if self.Portfolio.Invested: return

    # Get the OptionChain
    chain = slice.OptionChains.get(self.symbol, None)
    if not chain: return

    # Select an expiration date
    expiry = sorted(chain, key=lambda contract: contract.Expiry, reverse=True)[0].Expiry

    # Select the ATM strike price
    strikes = [contract.Strike for contract in chain if contract.Expiry == expiry]
    strike = sorted(strikes, key=lambda strike: abs(strike - chain.Underlying.Price))[0]</pre>
    </div>

    <li>In the <code>OnData</code> method, call the <code>OptionStrategies.Straddle</code> method and then submit the order.</li>
    <div class="section-example-container">
        <pre class="csharp">var optionStrategy = OptionStrategies.Straddle(_symbol, strike, expiry);
Buy(optionStrategy, 1);<br></pre>
        <pre class="python">option_strategy = OptionStrategies.Straddle(self.symbol, strike, expiry)
self.Buy(option_strategy, 1)</pre>
    </div>

<?php 
$methodNames = array("Buy");
include(DOCS_RESOURCES."/trading-and-orders/option-strategy-extra-args.php"); 
?>

</ol>
