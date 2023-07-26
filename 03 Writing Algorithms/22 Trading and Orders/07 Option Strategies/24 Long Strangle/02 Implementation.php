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

    var option = AddOption("GOOG");
    _symbol = option.Symbol;
    option.SetFilter(-5, 5, 0, 30);
}</pre>
        <pre class="python">def Initialize(self) -&gt; None:
    self.SetStartDate(2017, 4, 1)
    self.SetEndDate(2017, 4, 30)
    self.SetCash(100000)
        
    option = self.AddOption("GOOG")
    self.symbol = option.Symbol
    option.SetFilter(-5, 5, 0, 30)</pre>
    </div>

    <li>In the <code>OnData</code> method, select the expiration date and strike prices of the contracts in the strategy legs.</li>
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
        <pre class="python">def OnData(self, slice: Slice) -&gt; None:
    if self.Portfolio.Invested:
        return

    chain = slice.OptionChains.get(self.symbol)
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

    <li>In the <code>OnData</code> method, call the <code>OptionStrategies.Strangle</code> method and then submit the order.</li>
    <div class="section-example-container">
        <pre class="csharp">var longStrangle = OptionStrategies.Strangle(_symbol, callStrike, putStrike, expiry);
Buy(longStrangle, 1);</pre>
        <pre class="python">long_strangle = OptionStrategies.Strangle(self.symbol, call_strike, put_strike, expiry)
self.Buy(long_strangle, 1)</pre>
    </div>

<?
$methodNames = array("Buy");
include(DOCS_RESOURCES."/trading-and-orders/option-strategy-extra-args.php"); 
?>
</ol>