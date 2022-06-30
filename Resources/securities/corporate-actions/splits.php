<?php

$getSplitsText = function($dataNormalizationModeLink, $factorFilesLink) 
{
    echo "
<p>When a company does a stock split, the number of shares each shareholder owns increases and the price of each share decreases. When a company does a reverse stock split, the number of shares each shareholder owns decreases and the price of each share increases. A company may perform a stock split or a reverse stock split to adjust the price of their stock so that more investors trade it and the liquidity increases.</p>

<p>When a stock split or reverse stock split occurs for an Equity in your algorithm, LEAN sends a <code>Split</code> object to the <code>OnData</code> method. </code>Split</code> objects have the following properties:</p>
<div data-tree='QuantConnect.Data.Market.Split'></div>

<p>You receive <code>Split</code> objects when a split is in the near future and when it occurs. To know if the split occurs in the near future or now, check the <code>Type</code> property.</p>

<p>If you backtest without the <code>Raw</code> <a href='{$dataNormalizationModeLink}'>data normalization mode</a>, the splits are factored into the price and volume. If you backtest with the <code>Raw</code> data normalization mode or trade live, when a split occurs, LEAN automatically adjusts your positions based on the <code>SplitFactor</code>. If the post-split quantity isn't a valid <a href='/docs/v2/writing-algorithms/securities/properties#50-Symbol-Properties'>lot size</a>, LEAN credits the remaining value to your <a href='/docs/v2/writing-algorithms/portfolio/cashbook'>cashbook</a> in your account currency. If you have indicators in your algorithm, <a href='/docs/v2/writing-algorithms/indicators/key-concepts#09-Reset-Indicators'>reset your indicators</a> when splits occur so that the data in your indicators account for the price adjustments that the splits cause.</p>

<p>To get the <code>Split</code> objects in the <code>Slice</code>, index the <code>Splits</code> property of the <code>Slice</code> with the security <code>Symbol</code>. The <code>Slice</code> may not contain data for your <code>Symbol</code>. To avoid issues, check if the <code>Splits</code> property contains data for your security before you index it with the security <code>Symbol</code>.</p>

<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    if (slice.Splits.ContainsKey(_symbol))
    {
        var split = slice.Splits[_symbol];
    }
}

public void OnData(Splits splits)
{
    if (splits.ContainsKey(_symbol))
    {
        var split = splits[_symbol];
    }
}
</pre>
    <pre class='python'>def OnData(self, slice: Slice) -> None:
    split = slice.Splits.get(self.symbol)
    if split:
        pass</pre>
</div>

<p>You can also iterate through the <code>Splits</code> dictionary. The keys of the dictionary are the <code>Symbol</code> objects and the values are the <code>Split</code> objects.</p>
<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    foreach (var kvp in slice.Splits)
    {
        var symbol = kvp.Key;
        var split = kvp.Value;
    }
}

public void OnData(Splits splits)
{
    foreach (var kvp in splits)
    {
        var symbol = kvp.Key;
        var split = kvp.Value;
    }
}</pre>
    <pre class='python'>def OnData(self, slice: Slice) -> None:
    for symbol, split in slice.Splits.items():
        pass</pre>
</div>


<p>LEAN stores the data for stock splits in factor files. To view some example factor files, see the <a rel='nofollow' target='_blank' href='{$factorFilesLink}'>LEAN GitHub repository</a>. In backtests, your algorithm receives <code>Split</code> objects at midnight. In live trading, your algorithm receives <code>Split</code> objects when the factor files are ready.</p>


<p>If you hold an Option contract for an underlying Equity when a split occurs, LEAN closes your Option contract position.</p>  
";
}

?>
