<?php

$getTickText = function($securityName, $pythonVariable, $cSharpVariable) 
{
    echo "
<p><code>Tick</code> objects represent a single trade or quote at a moment in time. A trade tick is a record of a transaction for the {$securityName}. A quote tick is an offer to buy or sell the {$securityName} at a specific price. <code>Tick</code> objects have the following properties:</p>
<div data-tree='QuantConnect.Data.Market.Tick'></div>

<p>Trade ticks have a non-zero value for the <code>Quantity</code> and <code>Price</code> properties, but they have a zero value for the <code>BidPrice</code>, <code>BidSize</code>, <code>AskPrice</code>, and <code>AskSize</code> properties. Quote ticks have non-zero values for <code>BidPrice</code> and <code>BidSize</code> properties or have non-zero values for <code>AskPrice</code> and <code>AskSize</code> properties. To check if a tick is a trade or a quote, use the <code>TickType</code> property.</p>

<p> In backtests, LEAN groups ticks into one millisecond buckets. In live trading, LEAN groups ticks into ~70-millisecond buckets. To get the <code>Tick</code> objects in the <code>Slice</code>, index the <code>Ticks</code> property of the <code>Slice</code> with a <code>Symbol</code>. If the {$securityName} doesn't actively trade or you are in the same time step as when you added the {$securityName} subscription, the <code>Slice</code> may not contain data for your <code>Symbol</code>. To avoid issues, check if the <code>Slice</code> contains data for your {$securityName} before you index the <code>Slice</code> with the {$securityName} <code>Symbol</code>.</p>

<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    if (slice.Ticks.ContainsKey({$cSharpVariable}))
    {
        var ticks = slice.Ticks[{$cSharpVariable}];
        foreach (var tick in ticks)
        {
            //
        }
    }
}

public void OnData(Ticks ticks)
{
    if (ticks.ContainsKey({$cSharpVariable}))
    {
        foreach (var tick in ticks[{$cSharpVariable}])
        {
            //
        }
    }
}
</pre>
    <pre class='python'>def OnData(self, slice: Slice) -> None:
    if {$pythonVariable} in slice.Ticks:
        ticks = slice.Ticks[{$pythonVariable}]
        for tick in ticks:
            pass</pre>
</div>

<p>You can also iterate through the <code>Ticks</code> dictionary. The keys of the dictionary are the <code>Symbol</code> objects and the values are the <code class='csharp'>List&lt;Tick&gt;</code><code class='python'>list[Tick]</code> objects.</p>
<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    foreach (var kvp in slice.Ticks)
    {
        var symbol = kvp.Key;
        var ticks = kvp.Value;
        foreach (var tick in ticks)
        {
            //
        }
    }
}

public void OnData(Ticks ticks)
{
    foreach (var kvp in ticks)
    {
        var symbol = kvp.Key;
        foreach (var tick in kvp.Value)
        {
            //
        }
    }
}</pre>
    <pre class='python'>def OnData(self, slice: Slice) -> None:
    for symbol, ticks in slice.Ticks.items():
        for tick in ticks:
            pass</pre>
</div>

<p>Tick data is raw and unfiltered, so it can contain bad ticks that skew your trade results. For example, some ticks come from dark pools, which aren't tradable. We recommend you only use tick data if you understand the risks and are able to perform your own online tick filtering.</p>
";

}
?>
