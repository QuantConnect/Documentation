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
    UniverseSettings.Asynchronous = true;
    var option = AddOption("GOOG");
    _symbol = option.Symbol;
    option.SetFilter(-1, 1, 30, 60);
}</pre>
        <pre class="python">def Initialize(self) -&gt; None:
    self.SetStartDate(2017, 4, 1)
    self.SetEndDate(2017, 6, 30)
    self.SetCash(100000)
    self.UniverseSettings.Asynchronous = True 
    option = self.AddOption("GOOG")
    self.symbol = option.Symbol
    option.SetFilter(-1, 1, 30, 60)</pre>
    </div>

    <li>In the <code>OnData</code> method, select the expiration date and strike price of the contracts in the strategy legs.</li>
    <div class="section-example-container">
        <pre class="csharp">public override void OnData(Slice slice)
{
    if (Portfolio.Invested || 
        !slice.OptionChains.TryGetValue(_symbol, out var chain))
    {
        return;
    }

    // Find ATM options with the nearest expiry
    var expiry = chain.Min(contract =&gt; contract.Expiry);
    var contracts = chain.Where(contract =&gt; contract.Expiry == expiry)
                         .OrderBy(contract =&gt; Math.Abs(contract.Strike - chain.Underlying.Price))
                         .ToArray();

    if (contracts.Length < 2) return;</pre>
        <pre class="python">def OnData(self, slice: Slice) -&gt; None:
    if self.Portfolio.Invested:
        return

    chain = slice.OptionChains.get(self.symbol, None)
    if not chain:
        return

    # Find ATM options with the nearest expiry
    expiry = min([x.Expiry for x in chain])
    contracts = sorted([x for x in chain if x.Expiry == expiry],
        key=lambda x: abs(chain.Underlying.Price - x.Strike))

    if len(contracts) < 2:
        return</pre>
    </div>

    <li>In the <code>OnData</code> method, call the <code>OptionStrategies.Straddle</code> method and then submit the order.</li>
    <div class="section-example-container">
        <pre class="csharp">var longStraddle = OptionStrategies.Straddle(_symbol, contracts[0].Strike, expiry);
Buy(longStraddle, 1);<br></pre>
        <pre class="python">long_straddle = OptionStrategies.Straddle(self.symbol, contracts[0].Strike, expiry)
self.Buy(long_straddle, 1)</pre>
    </div>

<?
$methodNames = array("Buy");
include(DOCS_RESOURCES."/trading-and-orders/option-strategy-extra-args.php"); 
?>
</ol>