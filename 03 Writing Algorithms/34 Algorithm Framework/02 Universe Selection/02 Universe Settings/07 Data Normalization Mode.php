<p>The <code class="csharp">DataNormalizationMode</code><code class="python">data_normalization_mode</code> setting is an enumeration that defines how historical data is adjusted. This setting is only applicable for US Equities and Futures.

<p>In the case of US Equities, the data normalization mode affects how historical data is adjusted for <a href='/docs/v2/writing-algorithms/securities/asset-classes/us-equity/corporate-actions'>corporate actions</a>. To view all the available options, see <a href='/docs/v2/writing-algorithms/securities/asset-classes/us-equity/requesting-data#11-Data-Normalization'>Data Normalization</a>. To change the data normalization mode, in the <a href='/docs/v2/writing-algorithms/initialization'><span class="csharp">Initialize</span><span class="python">initialize</span></a> method, adjust the algorithm's <code class="csharp">UniverseSettings</code><code class="python">universe_settings</code> before you create the Universe Selection model.</p>

<p>In the case of Futures, the data normalization mode affects how historical data of two contracts is stitched together to form the <a href='/docs/v2/writing-algorithms/universes/futures#12-Continous-Contracts'>continuous contract</a>. To view all the available options, see <a href='/docs/v2/writing-algorithms/securities/asset-classes/futures/requesting-data/individual-contracts#09-Data-Normalization'>Data Normalization</a>.</p>

<p>The default value is <code class="csharp">DataNormalizationMode.Adjusted</code><code class="python">DataNormalizationMode.ADJUSTED</code>. To change the data normalization mode, in the <a href='/docs/v2/writing-algorithms/initialization'>Initialize</a> method, adjust the algorithm's <code>UniverseSettings</code> before you create the Universe Selection model.</p>

<div class="section-example-container">
	<pre class="csharp">UniverseSettings.DataNormalizationMode = DataNormalizationMode.Raw;

var tickers = new[] {"MSTR", "MSFT", "IBM"};
var symbols = tickers.Select(ticker =&gt; QuantConnect.Symbol.Create(ticker, SecurityType.Equity, Market.USA));
AddUniverseSelection(new ManualUniverseSelectionModel(symbols));</pre>
	<pre class="python">self.universe_settings.data_normalization_mode = DataNormalizationMode.RAW

tickers = ["MSTR", "MSFT", "IBM"]
symbols = [ Symbol.create(ticker, SecurityType.EQUITY, Market.USA) for ticker in tickers]
self.add_universe_selection(ManualUniverseSelectionModel(symbols))</pre>
</div>