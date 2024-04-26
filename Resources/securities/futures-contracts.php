<p><code>FuturesContract</code> objects represent the data of a single Futures contract in the market. They have the following properties:</p>
<div data-tree='QuantConnect.Data.Market.FuturesContract'></div>

<p>To get the Futures contracts in the <code>Slice</code>, use the <code class="csharp">Contracts</code><code class="python">contracts</code> property of the <code>FuturesChain</code>.</p>

<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    if (slice.FuturesChains.TryGetValue(<?=$cSharpMemberName?>.Canonical, out var chain))
    {
        if (chain.Contracts.TryGetValue(<?=$cSharpMemberName?>, out var contract))
        {
            var price = contract.LastPrice;
        }
    }
}

public void OnData(FuturesChains futuresChains)
{
    if (futuresChains.TryGetValue(<?=$cSharpMemberName?>.Canonical, out var chain))
    {
        if (chain.Contracts.TryGetValue(<?=$cSharpMemberName?>, out var contract))
        {
            var price = contract.LastPrice;
        }
    }
}</pre>
    <pre class='python'>def on_data(self, slice: Slice) -> None:
    chain = slice.FuturesChains.get(<?=$pythonMemberName?>.Canonical)
    if chain:
        contract = chain.Contracts.get(<?=$pythonMemberName?>)
        if contract:
            price = contract.LastPrice</pre>
</div>   
