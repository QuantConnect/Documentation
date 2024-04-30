<p>Follow these steps to implement the short strangle strategy:</p>

<ol>
    <li>In the <code class="csharp">Initialize</code><code class="python">initialize</code> method, set the start date, end date, cash, and <a href="/docs/v2/writing-algorithms/universes/equity-options">Option universe</a>.</li>
    <div class="section-example-container">
        <pre class="csharp">private Symbol _symbol;

public override void Initialize()
{
    SetStartDate(2017, 4, 1);
    SetEndDate(2017, 4, 30);
    SetCash(100000);
    UniverseSettings.Asynchronous = true;
    var option = AddOption("GOOG");
    _symbol = option.Symbol;
    option.SetFilter(-5, 5, 0, 30);
}</pre>
        <pre class="python">def initialize(self) -&gt; None:
    self.set_start_date(2017, 4, 1)
    self.set_end_date(2017, 4, 30)
    self.set_cash(100000)
    self.universe_settings.asynchronous = True
    option = self.add_option("GOOG")
    self._symbol = option.symbol
    option.set_filter(-5, 5, 0, 30)</pre>
    </div>

    <li>In the <code class="csharp">OnData</code><code class="python">on_data</code> method, select the expiration date and strike prices of the contracts in the strategy legs.</li>
    <div class="section-example-container">
        <pre class="csharp">public override void OnData(Slice slice)
{
    if (Portfolio.Invested ||
        !slice.OptionChains.TryGetValue(_symbol, out var chain))
    { 
        return;
    }

    // Find options with the farthest expiry
    var expiry = chain.Max(contract =&gt; contract.Expiry);
    var contracts = chain.Where(contract =&gt; contract.Expiry == expiry).ToList();

    // Order the OTM calls by strike to find the nearest to ATM
    var callContracts = contracts
        .Where(contract =&gt; contract.Right == OptionRight.Call &amp;&amp;
            contract.Strike &gt; chain.Underlying.Price)
        .OrderBy(contract =&gt; contract.Strike).ToArray();
    if (callContracts.Length == 0) return;

    // Order the OTM puts by strike to find the nearest to ATM
    var putContracts = contracts
        .Where(contract =&gt; contract.Right == OptionRight.Put &amp;&amp;
            contract.Strike &lt; chain.Underlying.Price)
        .OrderByDescending(contract =&gt; contract.Strike).ToArray();
    if (putContracts.Length == 0) return;

    var callStrike = callContracts[0].Strike;
    var putStrike = putContracts[0].Strike;
}</pre>
        <pre class="python">def on_data(self, slice: Slice) -&gt; None:
    if self.Portfolio.Invested:
        return

    chain = slice.OptionChains.get(self._symbol)
    if not chain:
        return

    # Find options with the farthest expiry
    expiry = max([x.Expiry for x in chain])
    contracts = [contract for contract in chain if contract.Expiry == expiry]
     
    # Order the OTM calls by strike to find the nearest to ATM
    call_contracts = sorted([contract for contract in contracts
        if contract.Right == OptionRight.Call and
            contract.Strike > chain.Underlying.Price],
        key=lambda x: x.Strike)
    if not call_contracts:
        return
        
    # Order the OTM puts by strike to find the nearest to ATM
    put_contracts = sorted([contract for contract in contracts
        if contract.Right == OptionRight.Put and
           contract.Strike < chain.Underlying.Price],
        key=lambda x: x.Strike, reverse=True)
    if not put_contracts:
        return

    call_strike = call_contracts[0].Strike
    put_strike = put_contracts[0].Strike</pre>
    </div>

    <li>In the <code class="csharp">OnData</code><code class="python">on_data</code> method, call the <code>OptionStrategies.Strangle</code> method and then submit the order.</li>
    <div class="section-example-container">
        <pre class="csharp">var shortStrangle = OptionStrategies.ShortStrangle(_symbol, callStrike, putStrike, expiry);
Buy(shortStrangle, 1);</pre>
        <pre class="python">short_strangle = OptionStrategies.short_strangle(self._symbol, call_strike, put_strike, expiry)
self.buy(short_strangle, 1)</pre>
    </div>

<?
$methodNames = array("Buy");
include(DOCS_RESOURCES."/trading-and-orders/option-strategy-extra-args.php"); 
?>
</ol>