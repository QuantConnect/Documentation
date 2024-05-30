<p>Follow these steps to implement the long put butterfly strategy:</p>

<ol>
    <li>In the <code class="csharp">Initialize</code><code class="python">initialize</code> method, set the start date, end date, cash, and <a href="/docs/v2/writing-algorithms/universes/equity-options">Option universe</a>.</li>
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
    self._symbol = option.symbol
    option.set_filter(lambda universe: universe.include_weeklys().strikes(-15, 15).expiration(0, 31))</pre>
    </div>
</ol>

<h4>Using Helper strategies</h4>
<ol>
    <li>In the <code class="csharp">OnData</code><code class="python">on_data</code> method, select strikes and expiration date of the contracts in the strategy legs.</li>
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
        <pre class="python">def on_data(self, slice: Slice) -&gt; None:
    if self.portfolio.invested: return

    # Get the OptionChain
    chain = slice.option_chains.get(self._symbol, None)
    if not chain: return

    # Select an expiry date
    expiry = sorted(chain, key = lambda x: x.expiry, reverse=True)[0].expiry
    
    # Select the put contracts that expire on the selected date
    puts = [i for i in chain if i.expiry == expiry and i.right == OptionRight.PUT]
    if len(puts) == 0: return

    # Sort the put contracts by their strike prices
    put_strikes = sorted([x.strike for x in puts])

    # Get the ATM strike price
    atm_strike = sorted(puts, key=lambda x: abs(x.strike - chain.underlying.price))[0].strike

    # Get the distance between lowest strike price and ATM strike, and highest strike price and ATM strike. 
    # Get the lower value as the spread distance as equidistance is needed for both sides
    spread = min(abs(put_strikes[0] - atm_strike), abs(put_strikes[-1] - atm_strike))

    # Select the strike prices of the strategy legs
    itm_strike = atm_strike + spread
    otm_strike = atm_strike - spread</pre>
    </div>

    <li>In the <code class="csharp">OnData</code><code class="python">on_data</code> method, call the <code class="csharp">OptionStrategies.ButterflyPut</code><code class="python">OptionStrategies.butterfly_put</code> method and then submit the order.</li>
    <div class="section-example-container">
        <pre class="csharp">var optionStrategy = OptionStrategies.ButterflyPut(_symbol, itmStrike, atmStrike, otmStrike, expiry);
Buy(optionStrategy, 1);</pre>
        <pre class="python">option_strategy = OptionStrategies.butterfly_put(self._symbol, itm_strike, atm_strike, otm_strike, expiry)
self.buy(option_strategy, 1)</pre>
    </div>

<?php 
$methodNames = array("Buy");
include(DOCS_RESOURCES."/trading-and-orders/option-strategy-extra-args.php"); 
?>

</ol>

<h4>Using Combo Orders</h4>
<ol>
    <li>In the <code class="csharp">OnData</code><code class="python">on_data</code> method, select the strategy legs.</li>
    <div class="section-example-container">
        <pre class="csharp">public override void OnData(Slice slice)
{
    if (Portfolio.Invested) return;

    // Get the OptionChain
    var chain = slice.OptionChains.get(_symbol, null);
    if (chain.Count() == 0) return;

    // Select the call Option contracts with the furthest expiry
    var expiry = chain.OrderByDescending(x =&gt; x.Expiry).First().Expiry;    
    var puts = chain.Where(x =&gt; x.Expiry == expiry &amp;&amp; x.Right == OptionRight.Put);
    if (puts.Count() == 0) return;

    // Select the ATM, ITM and OTM contract strike prices from the remaining contracts
    var atmPut = puts.OrderBy(x =&gt; Math.Abs(x.Strike - chain.Underlying.Price)).First();
    var itmPut = puts.OrderBy(x =&gt; x.Strike).SkipLast(1).Last();
    var otmPut = puts.Single(x =&gt; x.Strike == atmPut.Strike * 2 - itmPut.Strike);</pre>
        <pre class="python">def on_data(self, slice: Slice) -&gt; None:
    if self.portfolio.invested: return

    # Get the OptionChain
    chain = slice.option_chains.get(self._symbol, None)
    if not chain: return

    # Get the furthest expiry date of the contracts
    expiry = sorted(chain, key = lambda x: x.expiry, reverse=True)[0].expiry
    
    # Select the call Option contracts with the furthest expiry
    puts = [i for i in chain if i.expiry == expiry and i.right == OptionRight.PUT]
    if len(puts) == 0: return

    # Select the ATM, ITM and OTM contract strike prices from the remaining contracts
    atm_put = sorted(puts, key=lambda x: abs(x.strike - chain.underlying.price))[0]
    itm_put = sorted(puts, key=lambda x: x.strike)[-2]
    otm_put = [x for x in puts if x.strike == atm_put.strike * 2 - itm_put.strike][0]</pre>
    </div>

    <li>In the <code class="csharp">OnData</code><code class="python">on_data</code> method, create <code>Leg</code> and call the <a href="/docs/v2/writing-algorithms/trading-and-orders/order-types/combo-market-orders">Combo Market Order</a>/<a href="/docs/v2/writing-algorithms/trading-and-orders/order-types/combo-limit-orders">Combo Limit Order</a>/<a href="/docs/v2/writing-algorithms/trading-and-orders/order-types/combo-leg-limit-orders">Combo Leg Limit Order</a> to submit the order.</li>
    <div class="section-example-container">
        <pre class="csharp">var legs = new List&lt;Leg&gt;()
    {
        Leg.Create(atmPut.Symbol, -2),
        Leg.Create(itmPut.Symbol, 1),
        Leg.Create(otmPut.Symbol, 1)
    };
ComboMarketOrder(legs, 1);</pre>
        <pre class="python">legs = [
    Leg.create(atm_put.symbol, -2),
    Leg.create(itm_put.symbol, 1),
    Leg.create(otm_put.symbol, 1)
]
self.combo_market_order(legs, 1)</pre>
    </div>

</ol>
