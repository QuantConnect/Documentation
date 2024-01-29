<? include(DOCS_RESOURCES."/universes/settings/extended-market-hours.php"); ?> 

<p>To enable extended market hours, in the <a href='/docs/v2/writing-algorithms/initialization'>Initialize</a> method, adjust the algorithm's <code>UniverseSettings</code> before you create the Universe Selection model.</p> 

<div class="section-example-container">
    <pre class="csharp">UniverseSettings.ExtendedMarketHours = true;
var tickers = new[] {"SPY", "QQQ", "IWM"};
var symbols = tickers.Select(ticker =&gt; QuantConnect.Symbol.Create(ticker, SecurityType.Equity, Market.USA));
AddUniverseSelection(new ManualUniverseSelectionModel(symbols));</pre>
    <pre class="python">self.UniverseSettings.ExtendedMarketHours = True
tickers = ["SPY", "QQQ", "IWM"]
symbols = [ Symbol.Create(ticker, SecurityType.Equity, Market.USA) for ticker in tickers]
self.AddUniverseSelection(ManualUniverseSelectionModel(symbols))</pre>
</div>
