<p><code>FuturesChain</code> objects represent an entire chain of contracts for a single underlying Future. They have the following properties:</p>
<div data-tree='QuantConnect.Data.Market.FuturesChain'></div>    

<p>To get the <code>FuturesChain</code>, index the <code class="csharp">FuturesChains</code><code class="python">futures_chains</code> property of the <code>Slice</code> with the continuous contract <code>Symbol</code>.</p>

<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    if (slice.FuturesChains.TryGetValue(<?=$cSharpMemberName?>.Canonical, out var chain))
    {
        var contracts = chain.Contracts;
    }
}
</pre>
    <pre class='python'>def on_data(self, slice: Slice) -> None:
    chain = slice.futures_chains.get(<?=$pythonMemberName?>.canonical)
    if chain:
        contracts = chain.contracts</pre>
</div>

<p>You can also loop through the <code class="csharp">FuturesChains</code><code class="python">futures_chains</code> property to get each <code>FuturesChain</code>.</p>
<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    foreach (var kvp in slice.FuturesChains)
    {
        var continuousContractSymbol = kvp.Key;
        var chain = kvp.Value;
        var contracts = chain.Contracts;
    }
}

public void OnData(FuturesChains futuresChains)
{
    foreach (var kvp in futuresChains)
    {
        var continuousContractSymbol = kvp.Key;
        var chain = kvp.Value;
        var contracts = chain.Contracts;
    }
}</pre>
    <pre class='python'>def on_data(self, slice: Slice) -> None:
    for continuous_contract_symbol, chain in slice.futures_chains.items():
        contracts = chain.contracts</pre>
</div>

