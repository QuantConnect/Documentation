<p>To get the <code>OptionChain</code>, index the <code class="csharp">OptionChains</code><code class="python">option_chains</code> property of the <code>Slice</code> with the canonical <code>Symbol</code>.</p>

<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    if (slice.OptionChains.TryGetValue(<?=$cSharpMemberName?>, out var chain))
    {
        var contracts = chain.Contracts;
    }
}</pre>
    <pre class='python'>def on_data(self, slice: Slice) -> None:
    chain = slice.option_chains.get(<?=$pythonMemberName?>)
    if chain:
        contracts = chain.contracts</pre>
</div>

<p>You can also loop through the <code class="csharp">OptionChains</code><code class="python">option_chains</code> property to get each <code>OptionChain</code>.</p>
<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    foreach (var kvp in slice.OptionChains)
    {
        var <?=$cSharpVariableName?> = kvp.Key;
        var chain = kvp.Value;
        var contracts = chain.Contracts;
    }
}</pre>
    <pre class='python'>def on_data(self, slice: Slice) -> None:
    for <?=$pythonVariableName?>, chain in slice.option_chains.items():
        contracts = chain.contracts</pre>
</div>