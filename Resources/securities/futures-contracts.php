<p>
    <code>FuturesContract</code> objects represent the data of a single Futures contract in the market.
    To get the Futures contracts in the <code>Slice</code>, use the <code class="csharp">Contracts</code><code class="python">contracts</code> property of the <code>FuturesChain</code>.
</p>

<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    // Try to get the FutureChain using the canonical symbol
    if (slice.FuturesChains.TryGetValue(<?=$cSharpMemberName?>.Canonical, out var chain))
    {
        // Get individual contract data
        if (chain.Contracts.TryGetValue(<?=$cSharpMemberName?>, out var contract))
        {
            var price = contract.LastPrice;
        }
    }
}

// // Using this overload will only handle any FutureChains object received
public void OnData(FuturesChains futuresChains)
{
    // Try to get the FutureChain using the canonical symbol
    if (futuresChains.TryGetValue(<?=$cSharpMemberName?>.Canonical, out var chain))
    {
        // Get individual contract data
        if (chain.Contracts.TryGetValue(<?=$cSharpMemberName?>, out var contract))
        {
            var price = contract.LastPrice;
        }
    }
}</pre>
    <pre class='python'>def on_data(self, slice: Slice) -> None:
    # Try to get the FutureChain using the canonical symbol
    chain = slice.future_chains.get(<?=$pythonMemberName?>.canonical)
    if chain:
        # Get individual contract data (None if not contained)
        contract = chain.contracts.get(<?=$pythonMemberName?>)
        if contract:
            price = contract.last_price</pre>
</div>   
