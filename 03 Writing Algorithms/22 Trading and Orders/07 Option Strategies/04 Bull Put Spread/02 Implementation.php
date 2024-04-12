<p>Follow these steps to implement the bull put spread strategy:</p>

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
        <pre class="python">def initialize(self) -&gt; None:
    self.set_start_date(2017, 2, 1)
    self.set_end_date(2017, 3, 5)
    self.set_cash(500000)
    self.universe_settings.asynchronous = True
    option = self.add_option("GOOG", Resolution.MINUTE)
    self.symbol = option.symbol
    option.set_filter(lambda universe: universe.include_weeklys().strikes(-15, 15).expiration(0, 31))</pre>
    </div>

    <li>In the <code>OnData</code> method, select the expiration and strikes of the contracts in the strategy legs.</li>
    <div class="section-example-container">
        <pre class="csharp">public override void OnData(Slice slice)
{
    if (Portfolio.Invested) return;

    // Get the OptionChain
    var chain = slice.OptionChains.get(_symbol, null);
    if (chain.Count() == 0) return;

    // Get the furthest expiration date of the contracts
    var expiry = chain.OrderByDescending(x =&gt; x.Expiry).First().Expiry;
    
    // Select the put Option contracts with the furthest expiry
    var puts = chain.Where(x =&gt; x.Expiry == expiry &amp;&amp; x.Right == OptionRight.Put);
    if (puts.Count() == 0) return;

    // Select the ITM and OTM contract strikes from the remaining contracts
    var putStrikes = puts.Select(x =&gt; x.Strike).OrderBy(x =&gt; x);
    var itmStrike = putStrikes.Last();
    var otmStrike = putStrikes.First();</pre>
        <pre class="python">def on_data(self, slice: Slice) -&gt; None:
    if self.portfolio.invested: return

    # Get the OptionChain
    chain = slice.option_chains.get(self.symbol, None)
    if not chain: return

    # Get the furthest expiration date of the contracts
    expiry = sorted(chain, key = lambda x: x.expiry, reverse=True)[0].expiry
    
    # Select the put Option contracts with the furthest expiry
    puts = [i for i in chain if i.expiry == expiry and i.right == OptionRight.put]
    if len(puts) == 0: return

    # Select the ITM and OTM contract strikes from the remaining contracts
    put_strikes = sorted([x.strike for x in puts])
    otm_strike = put_strikes[0]
    itm_strike = put_strikes[-1]</pre>
    </div>

    <li>In the <code>OnData</code> method, call the <code>OptionStrategies.BullPutSpread</code> method and then submit the order.</li>
    <div class="section-example-container">
        <pre class="csharp">var optionStrategy = OptionStrategies.BullPutSpread(_symbol, itmStrike, otmStrike, expiry);
Buy(optionStrategy, 1);<br></pre>
        <pre class="python">option_strategy = OptionStrategies.bull_put_spread(self.symbol, itm_strike, otm_strike, expiry)
self.buy(option_strategy, 1)</pre>
    </div>

<?php 
$methodNames = array("Buy");
include(DOCS_RESOURCES."/trading-and-orders/option-strategy-extra-args.php"); 
?>

</ol>
