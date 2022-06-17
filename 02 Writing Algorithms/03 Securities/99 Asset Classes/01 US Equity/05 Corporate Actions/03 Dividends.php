<p>A dividend is a payment that a company gives to shareholders to distribute profits. When a dividend payment occurs for an Equity in your algorithm, LEAN sends a <code>Dividend</code> object to the <code>OnData</code> method. <code>Dividend</code> objects have the following properties:</p>
<div data-tree="QuantConnect.Data.Market.Dividend"></div>

<p>By default, data is adjusted for dividends. If you use the <code>Raw</code> or <code>SplitAdjusted</code> data normalization mode, LEAN adds dividend payments to your cashbook.</p>

<p>To get the <code>Dividend</code> objects in the <code>Slice</code>, index the <code>Dividends</code> property of the <code>Slice</code> with the security <code>Symbol</code>. The <code>Slice</code> may not contain data for your <code>Symbol</code>. To avoid issues, check if the <code>Dividends</code> property contains data for your security before you index the <code>Dividends</code> property with the security <code>Symbol</code>.</p>

<div class="section-example-container">
        <pre class="csharp">if (data.Dividends.ContainsKey(_symbol))
{
    var dividend = data.Dividends[_symbol];
}</pre>
        <pre class="python">if self.symbol in data.Dividends:
    dividend = data.Dividends[self.symbol]</pre>
    </div>

<p>For a full example, see the <a rel='nofollow' target='_blank' class='csharp' href='https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/DividendAlgorithm.cs'>DividendAlgorithm</a><a rel='nofollow' target='_blank' href='https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/DividendAlgorithm.py' class='python'>DividendAlgorithm</a> in the LEAN GitHub repository.</p>