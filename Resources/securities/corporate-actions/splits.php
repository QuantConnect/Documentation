<p>When a company does a stock split, the number of shares each shareholder owns increases and the price of each share decreases. When a company does a reverse stock split, the number of shares each shareholder owns decreases and the price of each share increases. A company may perform a stock split or a reverse stock split to adjust the price of their stock so that more investors trade it and the liquidity increases.</p>

<p>When a stock split or reverse stock split occurs for an Equity in your algorithm, LEAN sends a <code>Split</code> object to the <code class="csharp">OnData</code><code class="python">on_data</code> method. </code>Split</code> objects have the following properties:</p>
<div data-tree='QuantConnect.Data.Market.Split'></div>

<p>You receive <code>Split</code> objects when a split is in the near future and when it occurs. To know if the split occurs in the near future or now, check the <code class="csharp">Type</code><code class="python">type</code> property.</p>

<p>If you backtest without the <code class="csharp">Raw</code><code class="python">RAW</code> <a href='<?=$dataNormalizationModeLink?>'>data normalization mode</a>, the splits are factored into the price and volume. If you backtest with the <code class="csharp">Raw</code><code class="python">RAW</code> data normalization mode or trade live, when a split occurs, LEAN automatically adjusts your positions based on the <code class="csharp">SplitFactor</code><code class="python">split_factor</code>. If the post-split quantity isn't a valid <a href='/docs/v2/writing-algorithms/securities/properties#50-Symbol-Properties'>lot size</a>, LEAN credits the remaining value to your <a href='/docs/v2/writing-algorithms/portfolio/cashbook'>cashbook</a> in your account currency. If you have indicators in your algorithm, <a href='/docs/v2/writing-algorithms/indicators/key-concepts#10-Reset-Indicators'>reset and warm-up your indicators with ScaledRaw data</a> when splits occur so that the data in your indicators account for the price adjustments that the splits cause.</p>

<p>To get the <code>Split</code> objects, index the <code class="csharp">Splits</code><code class="python">splits</code> object with the security <code class="csharp">Symbol</code><code class="python">symbol</code>. The <code class="csharp">Splits</code><code class="python">splits</code> object may not contain data for your <code>Symbol</code>. To avoid issues, check if the <code class="csharp">Splits</code><code class="python">splits</code> object contains data for your security before you index it with the security <code>Symbol</code>.</p>

<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    if (slice.Splits.ContainsKey(_symbol))
    {
        var split = slice.Splits[_symbol];
    }
}

public override void OnSplits(Splits splits)
{
    if (splits.ContainsKey(_symbol))
    {
        var split = splits[_symbol];
    }
}
</pre>
    <pre class='python'>def on_data(self, slice: Slice) -> None:
    split = slice.splits.get(self._symbol)
    if split:
        pass


def on_splits(self, splits: Splits) -> None:
    split = splits.get(self._symbol)
    if split:
        pass</pre>
</div>

<p>You can also iterate through the <code class="csharp">Splits</code><code class="python">splits</code> dictionary. The keys of the dictionary are the <code>Symbol</code> objects and the values are the <code>Split</code> objects.</p>
<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    foreach (var kvp in slice.Splits)
    {
        var symbol = kvp.Key;
        var split = kvp.Value;
    }
}

public override void OnSplits(Splits splits)
{
    foreach (var kvp in splits)
    {
        var symbol = kvp.Key;
        var split = kvp.Value;
    }
}</pre>
    <pre class='python'>def on_data(self, slice: Slice) -> None:
    for symbol, split in slice.splits.items():
        pass

def on_splits(self, splits: Splits) -> None:
    for symbol, split in splits.items():
        pass</pre>
</div>


<p>LEAN stores the data for stock splits in factor files. To view some example factor files, see the <a rel='nofollow' target='_blank' href='<?=$factorFilesLink?>'>LEAN GitHub repository</a>. In backtests, your algorithm receives <code>Split</code> objects at midnight. In live trading, your algorithm receives <code>Split</code> objects when the factor files are ready.</p>


<p>If you hold an Option contract for an underlying Equity when a split occurs, LEAN closes your Option contract position.</p>

<? include(DOCS_RESOURCES."/securities/corporate-actions/splits-order.html"); ?>
