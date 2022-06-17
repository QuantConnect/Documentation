<p>When a company does a stock split, the number of shares each shareholder owns increases and the price of each share decreases. When a company does a reverse stock split, the number of shares each shareholder owns decreases and the price of each share increases. A company may perform a stock split or a reverse stock split to adjust the price of their stock so that more investors trade it and the liquidity increases.</p>

<p>When a stock split or reverse stock split occurs for an Equity in your algorithm, LEAN sends a <code>Split</code> object to the <code>OnData</code> method. </code>Split</code> objects have the following properties:</p>
<div data-tree='QuantConnect.Data.Market.Split'></div>

<p>You receive <code>Split</code> objects when a split is in the near future and when it occurs. To know if the split occurs in the near future or now, check the <code>Type</code> property.</p>
<?php echo file_get_contents(DOCS_RESOURCES."/enumerations/split_type.html"); ?>

<p>To get the <code>Split</code> objects in the <code>Slice</code>, index the <code>Splits</code> property of the <code>Slice</code> with the security <code>Symbol</code>. The <code>Slice</code> may not contain data for your <code>Symbol</code>. To avoid issues, check if the <code>Split</code> contains data for your security before you index the <code>Split</code> with the security <code>Symbol</code>.</p>

<div class="section-example-container">
        <pre class="csharp">if (data.Splits.ContainsKey(_symbol))
{
    var split = data.Splits[_symbol];
}</pre>
        <pre class="python">if self.symbol in data.Splits:
    split = data.Splits[self.symbol]</pre>
</div>

<p>LEAN stores the data for stock splits in factor files. To view some example factor files, see the <a rel='nofollow' target="_blank" href='https://github.com/QuantConnect/Lean/tree/master/Data/equity/usa/factor_files'>LEAN GitHub repository</a>.</p>

<p>In backtests, your algorithm receives <code>Split</code> objects at midnight. In live trading, your algorithm receives <code>Split</code> objects when the factor files are ready.</p>

<p>If you backtest without <code>Raw</code> data normalization mode, the splits are factored into the price and volume. If you backtest with <code>Raw</code> data normalization or trade live, your positions automatically adjust based on the <code>SplitFactor</code> when a split occurs. If the post-split quantity isn't a valid lot size, LEAN credits the remaining value to your cash book in your account currency. </p>

<p>If you hold an Option contract for an underlying Equity when a split occurs, it closes your Option contract position.</p>



