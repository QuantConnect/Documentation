<p>A dividend is a payment that a company gives to shareholders to distribute profits. When a dividend payment occurs for an Equity in your algorithm, LEAN sends a <code>Dividend</code> object to the <code>OnData</code> method. <code>Dividend</code> objects have the following properties:</p>
<div data-tree="QuantConnect.Data.Market.Dividend"></div>

<p>If you backtest with the <code>Adjusted</code> or <code>TotalReturn</code> data normalization mode, the dividends are factored into the price. If you backtest with the other data normalization modes or trade live, when a dividend payment occurs, LEAN automatically adds the payment amount to your cashbook. If you have indicators in your algorithm, <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/indicators/key-concepts#09-Reset-Indicators'>reset your indicators</a> when dividend payments occur so that the data in your indicators account for the price adjustments that the dividend causes.</p>

<p>To get the <code>Dividend</code> objects in the <code>Slice</code>, index the <code>Dividends</code> property of the <code>Slice</code> with the security <code>Symbol</code>. The <code>Slice</code> may not contain data for your <code>Symbol</code>. To avoid issues, check if the <code>Dividends</code> property contains data for your security before you index it with the security <code>Symbol</code>.</p>

<div class="section-example-container">
        <pre class="csharp">if (data.Dividends.ContainsKey(_symbol))
{
    var dividend = data.Dividends[_symbol];
}</pre>
        <pre class="python">if self.symbol in data.Dividends:
    dividend = data.Dividends[self.symbol]</pre>
    </div>

<p>For a full example, see the <a rel='nofollow' target='_blank' class='csharp' href='https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/DividendAlgorithm.cs'>DividendAlgorithm</a><a rel='nofollow' target='_blank' href='https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/DividendAlgorithm.py' class='python'>DividendAlgorithm</a> in the LEAN GitHub repository.</p>
