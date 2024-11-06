<p>To get the <code>OptionChain</code>, index the <code class="csharp">OptionChains</code><code class="python">option_chains</code> property of the <code>Slice</code> with the canonical <code>Symbol</code>.</p>

<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    // Try to get the OptionChain using the canonical symbol
    if (slice.OptionChains.TryGetValue(<?=$cSharpMemberName?>, out var chain))
    {
        // Get all contracts if the OptionChain contains any member
        var contracts = chain.Contracts;
    }
}</pre>
    <pre class='python'>def on_data(self, slice: Slice) -> None:
    # Try to get the OptionChain using the canonical symbol (None if no OptionChain return)
    chain = slice.option_chains.get(<?=$pythonMemberName?>)
    if chain:
        # Get all contracts if the OptionChain contains any member
        contracts = chain.contracts</pre>
</div>

<p>You can also loop through the <code class="csharp">OptionChains</code><code class="python">option_chains</code> property to get each <code>OptionChain</code>.</p>
<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    // Iterate all received Canonical Symbol-OptionChain key-value pairs
    foreach (var kvp in slice.OptionChains)
    {
        var <?=$cSharpVariableName?> = kvp.Key;
        var chain = kvp.Value;
        var contracts = chain.Contracts;
    }
}</pre>
    <pre class='python'>def on_data(self, slice: Slice) -> None:
    # Iterate all received Canonical Symbol-OptionChain key-value pairs
    for <?=$pythonVariableName?>, chain in slice.option_chains.items():
        contracts = chain.contracts</pre>
</div>