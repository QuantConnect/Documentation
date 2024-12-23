<p>
  To get historical data for a specific date range, call <code class='python'>history</code><code class='csharp'>History</code> method with start and end <code class='python'>datetime</code><code class='csharp'>DateTime</code> objects. 
  The <code class='csharp'>DateTime</code><code class='python'>datetime</code> objects you provide are based in the <a href='<?=$writingAlgorithms ? "/docs/v2/writing-algorithms/initialization#12-Set-Time-Zone" : "/docs/v2/research-environment/initialization#04-Set-Time-Zone" ?>'><?=$writingAlgorithms ? "algorithm" : "notebook" ?> time zone</a>.
</p>

<div class="section-example-container">
    <pre class="csharp">// Get the Symbol of an asset.
var symbol = AddEquity("SPY").Symbol;
// Get the daily-resolution TradeBar data of the asset during 2020.
var history = History&lt;TradeBar&gt;(symbol, new DateTime(2020, 1, 1), new DateTime(2021, 1, 1), Resolution.Daily);</pre>
    <pre class="python"># Get the Symbol of an asset.
symbol = self.add_equity('SPY').symbol
# Get the daily-resolution TradeBar data of the asset during 2020.
history = self.history(TradeBar, symbol, datetime(2020, 1, 1), datetime(2021, 1, 1), Resolution.DAILY)</pre>
</div>

<img src='https://cdn.quantconnect.com/i/tu/history-request-date-ranges.png' class='python docs-image' alt='DataFrame of daily OHLCV data for SPY during 2020.'>

<p>
  If there is no data for the date range you request, the result is empty.
</p>
