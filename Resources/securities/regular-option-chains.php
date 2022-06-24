<?php
$getRegularOptionChainsText = function($cSharpMemberName, $pythonMemberName, $cSharpVariableame, $pythonVariableName)
{
    echo "
<p>To get the <code>OptionChain</code>, index the <code>OptionChains</code> property of the <code>Slice</code> with the canonical <code>Symbol</code>.</p>

<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    if (slice.OptionChains.TryGetValue({$cSharpMemberName}, out var chain))
    {
        //
    }
}

public void OnData(OptionChains optionChains)
{
    if (optionChains.TryGetValue({$cSharpMemberName}, out var chain))
    {
        //
    }
}</pre>
    <pre class='python'>def OnData(self, slice: Slice) -> None:
    chain = slice.OptionChains.get({$pythonMemberName})
    if chain:
        pass</pre>
</div>

<p>You can also loop through the <code>OptionChains</code> property to get each <code>OptionChain</code>.</p>
<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    foreach (var kvp in slice.OptionChains)
    {
        var {$cSharpVariableame} = kvp.Key;
        var chain = kvp.Value;
    }
}

public void OnData(OptionChains optionChains)
{
    foreach (var kvp in optionChains)
    {
        var {$cSharpVariableame} = kvp.Key;
        var chain = kvp.Value;
    }
}</pre>
    <pre class='python'>def OnData(self, slice: Slice) -> None:
    for {$pythonVariableName}, chain in slice.OptionChains.items():
        pass</pre>
</div>";

}

?>
