<p>Follow these steps to implement the put calendar spread strategy:</p>

<ol>
    <li>In the <code>Initialize</code> method, set the start date, end date, cash, and <a href="/docs/v2/writing-algorithms/universes/equity-options">Option universe</a>.</li>
    <div class="section-example-container">
        <pre class="csharp">private Symbol _symbol;

public override void Initialize()
{
    SetStartDate(2017, 2, 1);
    SetEndDate(2017, 2, 19);
    SetCash(500000);

    var option = AddOption("GOOG", Resolution.Minute);
    _symbol = option.Symbol;
    option.SetFilter(universe =&gt; universe.IncludeWeeklys()
                                         .Strikes(-1, 1)
                                         .Expiration(TimeSpan.FromDays(0), TimeSpan.FromDays(62)));
}</pre>
        <pre class="python">def Initialize(self) -&gt; None:
    self.SetStartDate(2017, 2, 1)
    self.SetEndDate(2017, 2, 19)
    self.SetCash(500000)

    option = self.AddOption("GOOG", Resolution.Minute)
    self.symbol = option.Symbol
    option.SetFilter(self.UniverseFunc)

def UniverseFunc(self, universe: OptionFilterUniverse) -&gt; OptionFilterUniverse:
    return universe.Strikes(-1, 1).Expiration(timedelta(0), timedelta(62))</pre>
    </div>

    <li>In the <code>OnData</code> method, select the strike price and expiration dates of the contracts in the strategy legs.</li>
    <div class="section-example-container">
        <pre class="csharp">public override void OnData(Slice slice)
{
    if (Portfolio.Invested) return;

    // Get the OptionChain
    var chain = slice.OptionChains.get(_symbol, null);
    if (chain == null || chain.Count() == 0) return;

    // Get the ATM strike price
    var atmStrike = chain.OrderBy(x =&gt; Math.Abs(x.Strike - chain.Underlying.Price)).First().Strike;

    // Select the ATM put contracts
    var puts = chain.Where(x =&gt; x.Strike == atmStrike &amp;&amp; x.Right == OptionRight.Put);
    if (puts.Count() == 0) return;

    // Select the near and far expiration dates
    var expiries = puts.Select(x =&gt; x.Expiry).OrderBy(x =&gt; x);
    var nearExpiry = expiries.First();
    var farExpiry = expiries.Last();</pre>
        <pre class="python">def OnData(self, slice: Slice) -&gt; None:
    if self.Portfolio.Invested: return

    # Get the OptionChain
    chain = slice.OptionChains.get(self.symbol, None)
    if not chain: return

    # Get the ATM strike price
    atm_strike = sorted(chain, key=lambda x: abs(x.Strike - chain.Underlying.Price))[0].Strike

    # Select the ATM put contracts
    puts = [i for i in chain if i.Strike == atm_strike and i.Right == OptionRight.Put]
    if len(puts) == 0: return

    # Select the near and far expiration dates
    expiries = sorted([x.Expiry for x in puts], key = lambda x: x)
    near_expiry = expiries[0]
    far_expiry = expiries[-1]</pre>
    </div>

    <li>In the <code>OnData</code> method, call the <code>OptionStrategies.PutCalendarSpread</code> method and then submit the order.</li>
    <div class="section-example-container">
        <pre class="csharp">var optionStrategy = OptionStrategies.PutCalendarSpread(_symbol, atmStrike, nearExpiry, farExpiry);
Buy(optionStrategy, 1);    // if long put calendar spread
Sell(optionStrategy, 1);   // if short put calendar spread</pre>
        <pre class="python">option_strategy = OptionStrategies.PutCalendarSpread(self.symbol, atm_strike, near_expiry, far_expiry)
self.Buy(option_strategy, 1)    # if long put calendar spread
self.Sell(option_strategy, 1)   # if short put calendar spread</pre>
    </div>

<?php 
$methodNames = array("Buy", "Sell");
include(DOCS_RESOURCES."/trading-and-orders/option-strategy-extra-args.php"); 
?>

</ol>
