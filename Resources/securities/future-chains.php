<p>
    <code>FuturesChain</code> objects represent an entire chain of contracts for a single underlying Future. 
    To get the <code>FuturesChain</code>, index the <code class="csharp">FuturesChains</code><code class="python">futures_chains</code> property of the <code>Slice</code> with the continuous contract <code>Symbol</code>.
</p>

<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    // Try to get the FutureChain using the canonical symbol
    if (slice.FuturesChains.TryGetValue(<?=$cSharpMemberName?>.Canonical, out var chain))
    {
        // Get all contracts if the FutureChain contains any member
        var contracts = chain.Contracts;
    }
}
</pre>
    <pre class='python'>def on_data(self, slice: Slice) -> None:
    # Try to get the FutureChain using the canonical symbol (None if no FutureChain return)
    chain = slice.futures_chains.get(<?=$pythonMemberName?>.canonical)
    if chain:
        # Get all contracts if the FutureChain contains any member
        contracts = chain.contracts</pre>
</div>

<p>You can also loop through the <code class="csharp">FuturesChains</code><code class="python">futures_chains</code> property to get each <code>FuturesChain</code>.</p>
<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    // Iterate all received Canonical Symbol-FutureChain key-value pairs
    foreach (var kvp in slice.FuturesChains)
    {
        var continuousContractSymbol = kvp.Key;
        var chain = kvp.Value;
        var contracts = chain.Contracts;
    }
}

// Using this overload will only handle any FutureChains object received
public void OnData(FuturesChains futuresChains)
{
    // Iterate all received Canonical Symbol-FutureChain key-value pairs
    foreach (var kvp in futuresChains)
    {
        var continuousContractSymbol = kvp.Key;
        var chain = kvp.Value;
        var contracts = chain.Contracts;
    }
}</pre>
    <pre class='python'>def on_data(self, slice: Slice) -> None:
    # Iterate all received Canonical Symbol-FutureChain key-value pairs
    for continuous_contract_symbol, chain in slice.futures_chains.items():
        contracts = chain.contracts</pre>
</div>

<p><code>FuturesChain</code> objects have the following properties:</p>
<div data-tree='QuantConnect.Data.Market.FuturesChain'></div>    
