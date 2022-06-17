<p>When a company does a stock split, the number of shares each shareholder owns increases and the price of each share decreases. When a company does a reverse stock split, the number of shares each shareholder owns decreases and the price of each share increases. A company may perform a stock split or a reverse stock split to adjust the price of their stock so that more investors trade it and the liquidity increases.</p>

<p>When a stock split or reverse stock split occurs for an Equity in your algorithm, LEAN sends a <code>Split</code> object to the <code>OnData</code> method. </code>Split</code> objects have the following properties:</p>
<div data-tree='QuantConnect.Data.Market.Split'></div>

<p>You receive <code>Split</code> objects when a split is in the near future and when it occurs. To know if the split occurs in the near future or now, check the <code>Type</code> property.</p>
<?php echo file_get_contents(DOCS_RESOURCES."/enumerations/split_type.html"); ?>

<p>To get the <code>Split</code> objects in the <code>Slice</code>, index the <code>Splits</code> property of the <code>Split</code> with the security <code>Symbol</code>. The <code>Slice</code> may not contain data for your <code>Symbol</code>. To avoid issues, check if the <code>Split</code> contains data for your security before you index the <code>Split</code> with the security <code>Symbol</code>.</p>

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



- Splits are handled by the engine depeding on the data normalization mode or whether the algorithm is running in live mode
<br>&nbsp;&nbsp; - Raw data and live mode:
<br>&nbsp;&nbsp; - It's information is used by the engine to adjust the quantity of the positions accordingly ("SplitOccurred")
<br>&nbsp;&nbsp; - If the quantity is not a valid lot size, the remaining value is credited to your account currency.
<br>- Other mode with backtesting:
<br>&nbsp;&nbsp; - The splts are factored into the price and volume.<br>

Splits close all options positions. <br>

-By default, data is split adjusted. When using Raw data, splits are applied directly to your portfolio quantity<br>



