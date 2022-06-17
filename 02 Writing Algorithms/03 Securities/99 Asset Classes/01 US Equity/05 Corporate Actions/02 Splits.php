<p>When a company does a stock split, it increases the number of shares each shareholder owns and decreases the price of each share. When a company does a reverse stock split, it decreases the number of shares each shareholder owns and increases the price of each share. A company may perform a stock split or a reverse stock split to adjust the price of their stock so that more investors trade it and the liquidity increases.</p>

<p>When a stock split or reverse stock split occurs for an Equity in your algorithm, LEAN sends a <code>Split</code> object to the <code>OnData</code> method. </code>Split</code> objects have the following properties:</p>
<div data-tree='QuantConnect.Data.Market.Split'></div>

<p>To check if a <code>Split</code> is a regular stock split or a reverse stock split, use its <code>Type</code> property.</p>
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




<div>- In backtesting, they are streamed to your algorithm in a Slice object that is emitted at midnight</div><div>- Splits are handled by the engine depeding on the data normalization mode or whether the algorithm is running in live mode<br>&nbsp;&nbsp; - Raw data and live mode:<br>&nbsp;&nbsp; - It's information is used by the engine to adjust the quantity of the positions accordingly ("SplitOccurred")<br>&nbsp;&nbsp; - If the quantity is not a valid lot size, the remaining value is credited to your account currency.<br>
- Other mode with backtesting:<br>&nbsp;&nbsp; - The splts are factored into the price and volume.<br>

Splits close all options positions. <br></div>

<div>-By default, data is split adjusted. When using Raw data, splits are applied directly to your portfolio quantity<br></div>

<div>&nbsp;&nbsp;&nbsp; - Type<br></div><div>-Show values for SplitType enum</div><div>&nbsp;&nbsp;&nbsp;&nbsp; - Warning</div><div>&nbsp;&nbsp;&nbsp;&nbsp; - SplitOccurred</div>
-Data saved in factor files<br></div><div></div>



