<p>Follow these steps to implement the long call calendar spread strategy:</p>

<ol>
    <li>In the <code>Initialize</code> method, set the start date, end date, cash, and <a href="/docs/v2/writing-algorithms/universes/equity-options">Option universe</a>.</li>
    <div class="section-example-container">
        <pre class="csharp">private Symbol _symbol;

public override void Initialize()
{
    SetStartDate(2017, 2, 1);
    SetEndDate(2017, 2, 19);
    SetCash(500000);
    UniverseSettings.Asynchronous = true;
    var option = AddOption("GOOG", Resolution.Minute);
    _symbol = option.Symbol;
    option.SetFilter(universe =&gt; universe.IncludeWeeklys().Strikes(-1, 1).Expiration(0, 62));
}</pre>
        <pre class="python">def initialize(self) -&gt; None:
    self.set_start_date(2017, 2, 1)
    self.set_end_date(2017, 2, 19)
    self.set_cash(500000)
    self.universe_settings.asynchronous = True
    option = self.add_option("GOOG", Resolution.minute)
    self.symbol = option.symbol
    option.set_filter(lambda universe: universe.include_weeklys().strikes(-1, 1).expiration(0, 62))</pre>
    </div>

    <li>In the <code>OnData</code> method, select the expiration and strikes of the contracts in the strategy legs.</li>
    <div class="section-example-container">
        <pre class="csharp">public override void OnData(Slice slice)
{
    if (Portfolio.Invested) return;

    // Get the OptionChain
    var chain = slice.OptionChains.get(_symbol, null);
    if (chain == null || chain.Count() == 0) return;

    // Get the ATM strike
    var atmStrike = chain.OrderBy(x =&gt; Math.Abs(x.Strike - chain.Underlying.Price)).First().Strike;

    // Select the ATM call Option contracts
    var calls = chain.Where(x =&gt; x.Strike == atmStrike &amp;&amp; x.Right == OptionRight.Call);
    if (calls.Count() == 0) return;

    // Select the near and far expiry dates
    var expiries = calls.Select(x =&gt; x.Expiry).OrderBy(x =&gt; x);
    var nearExpiry = expiries.First();
    var farExpiry = expiries.Last();</pre>
        <pre class="python">def on_data(self, slice: Slice) -&gt; None:
    if self.portfolio.invested: return

    # Get the OptionChain
    chain = slice.option_chains.get(self.symbol, None)
    if not chain: return

    # Get the ATM strike
    atm_strike = sorted(chain, key=lambda x: abs(x.strike - chain.underlying.price))[0].strike

    # Select the ATM call Option contracts
    calls = [i for i in chain if i.strike == atm_strike and i.right == OptionRight.call]
    if len(calls) == 0: return

    # Select the near and far expiry dates
    expiries = sorted([x.expiry for x in calls])
    near_expiry = expiries[0]
    far_expiry = expiries[-1]</pre>
    </div>

    <li>In the <code>OnData</code> method, call the <code>OptionStrategies.CallCalendarSpread</code> method and then submit the order.</li>
    <div class="section-example-container">
        <pre class="csharp">var optionStrategy = OptionStrategies.CallCalendarSpread(_symbol, atmStrike, nearExpiry, farExpiry);
Buy(optionStrategy, 1);</pre>
        <pre class="python">option_strategy = OptionStrategies.call_calendar_spread(self.symbol, atm_strike, near_expiry, far_expiry)
self.buy(option_strategy, 1)</pre>
    </div>

<?php 
$methodNames = array("Buy");
include(DOCS_RESOURCES."/trading-and-orders/option-strategy-extra-args.php"); 
?>

</ol>
