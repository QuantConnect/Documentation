<p>Follow these steps to implement the put butterfly strategy:</p>

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

    <li>In the <code>OnData</code> method, select strikes and expiration date of the contracts in the strategy legs.</li>
    <div class="section-example-container">
        <pre class="csharp">public override void OnData(Slice slice)
{
    if (Portfolio.Invested) return;

    // Get the OptionChain
    var chain = slice.OptionChains.get(_symbol, null);
    if (chain == null || chain.Count() == 0) return;

    // Select an expiry date
    var expiry = chain.OrderByDescending(x =&gt; x.Expiry).First().Expiry;
    
    // Select the put contracts that expire on the selected date
    var puts = chain.Where(x =&gt; x.Expiry == expiry &amp;&amp; x.Right == OptionRight.Put);
    if (puts.Count() == 0) return;

    // Sort the put contracts by their strike prices
    var putStrikes = puts.Select(x =&gt; x.Strike).OrderBy(x =&gt; x);

    // Get the ATM strike price
    var atmStrike = puts.OrderBy(x =&gt; Math.Abs(x.Strike - chain.Underlying.Price)).First().Strike;

    // Get the distance between lowest strike price and ATM strike, and highest strike price and ATM strike 
    // Get the lower value as the spread distance as equidistance is needed for both sides
    var spread = Math.Min(Math.Abs(putStrikes.First() - atmStrike), Math.Abs(putStrikes.Last() - atmStrike));

    // Select the strike prices of the strategy legs
    var itmStrike = atmStrike + spread;
    var otmStrike = atmStrike - spread;</pre>
        <pre class="python">def OnData(self, slice: Slice) -&gt; None:
    if self.Portfolio.Invested: return

    # Get the OptionChain
    chain = slice.OptionChains.get(self.symbol, None)
    if not chain: return

    # Select an expiry date
    expiry = sorted(chain, key = lambda x: x.Expiry, reverse=True)[0].Expiry
    
    # Select the put contracts that expire on the selected date
    puts = [i for i in chain if i.Expiry == expiry and i.Right == OptionRight.Put]
    if len(puts) == 0: return

    # Sort the put contracts by their strike prices
    put_strikes = sorted([x.Strike for x in puts])

    # Get the ATM strike price
    atm_strike = sorted(puts, key=lambda x: abs(x.Strike - chain.Underlying.Price))[0].Strike

    # Get the distance between lowest strike price and ATM strike, and highest strike price and ATM strike. 
    # Get the lower value as the spread distance as equidistance is needed for both sides
    spread = min(abs(put_strikes[0] - atm_strike), abs(put_strikes[-1] - atm_strike))

    # Select the strike prices of the strategy legs
    itm_strike = atm_strike + spread
    otm_strike = atm_strike - spread</pre>
    </div>

    <li>In the <code>OnData</code> method, call the <code>OptionStrategies.PutButterfly</code> method and then submit the order.</li>
    <div class="section-example-container">
        <pre class="csharp">var optionStrategy = OptionStrategies.PutButterfly(_symbol, itmStrike, atmStrike, otmStrike, expiry);
Buy(optionStrategy, 1);    // if long put butterfly
Sell(optionStrategy, 1);   // if short put butterfly<br></pre>
        <pre class="python">option_strategy = OptionStrategies.PutButterfly(self.symbol, itm_strike, atm_strike, otm_strike, expiry)
self.Buy(option_strategy, 1)    # if long put butterfly
self.Sell(option_strategy, 1)   # if short put butterfly</pre>
    </div>

<?php 
$methodNames = array("Buy", "Sell");
include(DOCS_RESOURCES."/trading-and-orders/option-strategy-extra-args.php"); 
?>
    
</ol>
