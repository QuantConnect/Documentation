<?php
$getFutureChainsText = function($pythonMemberName, $cSharpMemberName)
{
    echo "
<p><code>FuturesChain</code> objects represent an entire chain of contracts for a single underlying Future. They have the following properties:</p>
<div data-tree='QuantConnect.Data.Market.FuturesChain'></div>    

<p>To get the <code>FuturesChain</code>, index the <code>FuturesChains</code> property of the <code>Slice</code> with the continuous contract <code>Symbol</code>.</p>

<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    if (slice.FuturesChains.TryGetValue({$cSharpMemberName}.Canonical, out var chain))
    {
        //
    }
}
</pre>
    <pre class='python'>def OnData(self, slice: Slice) -> None:
    chain = slice.FuturesChains.get({$pythonMemberName}.Canonical)
    if chain:
        pass</pre>
</div>



<p>You can also loop through the <code>FuturesChains</code> property to get each <code>FuturesChain</code>.</p>
<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    foreach (var kvp in slice.FuturesChains)
    {
        var continuousContractSymbol = kvp.Key;
        var chain = kvp.Value;
    }
}

public void OnData(FuturesChains futuresChains)
{
    foreach (var kvp in futuresChains)
    {
        var continuousContractSymbol = kvp.Key;
        var chain = kvp.Value;
    }
}</pre>
    <pre class='python'>def OnData(self, slice: Slice) -> None:
    for continuous_contract_symbol, chain in slice.FuturesChains.items():
        pass</pre>
</div>
";
}

?>
