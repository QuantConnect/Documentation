<p>Follow these steps to implement the protective put strategy:</p>

<ol>
    <li>In the <code>Initialize</code> method, set the start date, end date, starting cash, and <a href="/docs/v2/writing-algorithms/universes/equity-options">Options universe</a>.</li>
    <div class="section-example-container">
        <pre class="csharp">private Symbol _put, _symbol;

public override void Initialize()
{
    SetStartDate(2014, 1, 1);
    SetEndDate(2014, 3, 1);
    SetCash(100000);
    UniverseSettings.Asynchronous = true;
    var option = AddOption("IBM");
    _symbol = option.Symbol;
    option.SetFilter(-3, 3, 0, 31);
}</pre>
        <pre class="python">def initialize(self) -&gt; None:
    self.set_start_date(2014, 1, 1)
    self.set_end_date(2014, 3, 1)
    self.set_cash(100000)
    self.universe_settings.asynchronous = True
    option = self.add_option("IBM")
    self.symbol = option.symbol
    option.set_filter(-3, 3, 0, 31)
    self.put = None</pre>
    </div>
  
    <li>In the <code>OnData</code> method, select the Option contract.</li>
    <div class="section-example-container">
        <pre class="csharp">public override void OnData(Slice slice)
{
    if (_put != null && Portfolio[_put].Invested) return;

    if (!slice.OptionChains.TryGetValue(_symbol, out var chain)) return;

    // Find ATM put with the farthest expiry
    var expiry = chain.Max(x =&gt; x.Expiry);
    var atmPut = chain
        .Where(x =&gt; x.Right == OptionRight.Put && x.Expiry == expiry)
        .OrderBy(x =&gt; Math.Abs(x.Strike - chain.Underlying.Price))
        .FirstOrDefault();</pre>
        <pre class="python">def on_data(self, slice: Slice) -&gt; None:
    if self.put and self.portfolio[self.put].invested:
        return

    chain = slice.option_chains.get(self.symbol)
    if not chain:
        return

    # Find ATM put with the farthest expiry
    expiry = max([x.expiry for x in chain])
    put_contracts = sorted([x for x in chain
        if x.right == OptionRight.PUT and x.expiry == expiry],
        key=lambda x: abs(chain.underlying.price - x.strike))

    if not put_contracts:
        return

    atm_put = put_contracts[0]</pre>
</div>

    <li>In the <code>OnData</code> method, call the <code>OptionStrategies.ProtectivePut</code> method and then submit the order.</li>
    <div class="section-example-container">
        <pre class="csharp">var protectivePut = OptionStrategies.ProtectivePut(_symbol, atmPut.Strike, expiry);
Buy(protectivePut, 1);

_put = atmPut.Symbol;</pre>
        <pre class="python">protective_put = OptionStrategies.protective_put(self.symbol, atm_put.strike, expiry)
self.buy(protective_put, 1)

self.put = atm_put.symbol</pre>
    </div>
    
<?php 
$methodNames = array("Buy");
include(DOCS_RESOURCES."/trading-and-orders/option-strategy-extra-args.php"); 
?>
    
</ol>
