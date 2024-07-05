<p>You can <a href="/docs/v2/writing-algorithms/universes/equity-options#03-Filter-Contracts">filter the option universe</a> to include only the option contracts required to form the <?=$strategy?> strategy in the <code>OptionChain</code>. It allows much less contracts being subscribed, hence significantly speeding up the algorithm.</p>

<div class="section-example-container">
    <pre class="csharp">private Symbol _symbol;

public override void Initialize()
{
    var option = AddOption("SPY");
    _symbol = option.Symbol;
    option.SetFilter(u => 
        u.IncludeWeeklys()
        .Strike(-5, 5)
        .Expiration(0, 30)
        .<?=$csharpMethod?>);
}

public override void OnData(Slice slice)
{
    if (!slice.OptionChains.TryGetValue(_symbol, out var chain)) return;

    // You will only receive the <?=$nContracts?> contracts needed to construct a <?=$strategy?> option strategy.
    // ...
}</pre>
    <pre class="python">def initialize(self) -&gt; None:
    option = self.add_option("SPY")
    self._symbol = option.symbol
    option.set_filter(lambda u: \
        u.include_weeklys()\
        .strike(-5, 5)\
        .expiration(0, 30)\
        .<?=$pythonMethod?>)

def on_data(self, slice: Slice) -&gt; None:
    chain = slice.option_chains.get(self._symbol, None)
    if not chain:
        return
    
    # You will only receive the <?=$nContracts?> contracts needed to construct a <?=$strategy?> option strategy.
    # ...</pre>
</div>
