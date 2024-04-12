<? include(DOCS_RESOURCES."/universes/settings/extended-market-hours.php"); ?> 

<p>To enable extended market hours, in the <a href='/docs/v2/writing-algorithms/initialization'>Initialize</a> method, adjust the algorithm's <code>UniverseSettings</code> before you create the Universe Selection model.</p> 

<div class="section-example-container">
    <pre class="csharp">UniverseSettings.ExtendedMarketHours = true;
var tickers = new[] {"SPY", "QQQ", "IWM"};
var symbols = tickers.Select(ticker =&gt; QuantConnect.Symbol.Create(ticker, SecurityType.Equity, Market.USA));
AddUniverseSelection(new ManualUniverseSelectionModel(symbols));</pre>
    <pre class="python">self.universe_settings.extended_market_hours = True
tickers = ["SPY", "QQQ", "IWM"]
symbols = [ Symbol.create(ticker, SecurityType.EQUITY, Market.USA) for ticker in tickers]
self.add_universe_selection(ManualUniverseSelectionModel(symbols))</pre>
</div>
