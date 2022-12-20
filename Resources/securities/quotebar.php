<?php

$getQuoteBarText = function($securityName, $pythonVariable, $cSharpVariable) 
{
    echo "
<p><code>QuoteBar</code> objects are bars that consolidate NBBO quotes from the exchanges. They contain the open, high, low, and close prices of the bid and ask. The <code>Open</code>, <code>High</code>, <code>Low</code>, and <code>Close</code> properties of the <code>QuoteBar</code> object are the mean of the respective bid and ask prices. If the bid or ask portion of the <code>QuoteBar</code> has no data, the <code>Open</code>, <code>High</code>, <code>Low</code>, and <code>Close</code> properties of the <code>QuoteBar</code> copy the values of either the <code>Bid</code> or <code>Ask</code> instead of taking their mean.</p>

<img src='https://cdn.quantconnect.com/docs/i/dataformat-quotebar.png' class='img-responsive'>
<p><code>QuoteBar</code> objects have the following properties:</p>
<div data-tree='QuantConnect.Data.Market.QuoteBar'></div>

    
<p>To get the <code>QuoteBar</code> objects in the <code>Slice</code>, index the <code>QuoteBars</code> property of the <code>Slice</code> with the {$securityName} <code>Symbol</code>. If the {$securityName} doesn't actively get quotes or you are in the same time step as when you added the {$securityName} subscription, the <code>Slice</code> may not contain data for your <code>Symbol</code>. To avoid issues, check if the <code>Slice</code> contains data for your {$securityName} before you index the <code>Slice</code> with the {$securityName} <code>Symbol</code>.</p>
    
<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    if (slice.QuoteBars.ContainsKey({$cSharpVariable}))
    {
        var quoteBar = slice.QuoteBars[{$cSharpVariable}];
    }
}

public void OnData(QuoteBars quoteBars)
{
    if (quoteBars.ContainsKey({$cSharpVariable}))
    {
        var quoteBar = quoteBars[{$cSharpVariable}];
    }
}
</pre>
    <pre class='python'>def OnData(self, slice: Slice) -> None:
    if {$pythonVariable} in slice.QuoteBars:
        quote_bar = slice.QuoteBars[{$pythonVariable}]</pre>
</div>

<p>You can also iterate through the <code>QuoteBars</code> dictionary. The keys of the dictionary are the <code>Symbol</code> objects and the values are the <code>QuoteBar</code> objects.</p>
<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    foreach (var kvp in slice.QuoteBars)
    {
        var symbol = kvp.Key;
        var quoteBar = kvp.Value;
    }
}

public void OnData(QuoteBars quoteBars)
{
    foreach (var kvp in quoteBars)
    {
        var symbol = kvp.Key;
        var quoteBar = kvp.Value;
    }
}</pre>
    <pre class='python'>def OnData(self, slice: Slice) -> None:
    for symbol, quote_bar in slice.QuoteBars.items():
        pass</pre>
</div>

<p><code>QuoteBar</code> objects let LEAN incorporate spread costs into your <a href='/docs/v2/writing-algorithms/reality-modeling/trade-fills/key-concepts'>simulated trade fills</a> to make backtest results more realistic.</p>
";

}
?>

