<p>You can manually subscribe to data for specific assets or use <a href="/docs/v2/writing-algorithms/universes/key-concepts">universes</a> to choose groups of assets. The <a href="https://www.quantconnect.com/datasets">Dataset Market</a> provides <!--?php echo file_get_contents(DOCS_RESOURCES."/kpis/dataset-size.php"); ?--> of data.</p>

<h4>Asset Data</h4>

<p>To subscribe to asset data, call one of the asset subscription methods (for example, the <code>AddEquity</code> method). Each asset class has its own method to create subscriptions. For more information about how to create subscriptions for each asset class, see <a href="https://www.quantconnect.com/docs/v2/writing-algorithms/asset-classes/key-concepts">Asset Classes</a>.</p>

<div class="section-example-container">
	<pre class="all">// Complete Add Equity API - Including Default Parameters:
AddEquity(string ticker, Resolution resolution = Resolution.Minute, string market = Market.USA, bool fillDataForward = true, decimal leverage = 0m, bool extendedMarketHours = false)

//Complete Add Forex API - Including Default Parameters:
AddForex(string ticker, Resolution resolution = Resolution.Minute, string market = Market.OANDA, bool fillDataForward = true, decimal leverage = 0m)
</pre>
</div>

<div class="section-example-container">
	<pre class="csharp">AddEquity("AAPL"); // Add Apple 1 minute bars (minute by default).
AddForex("EURUSD", Resolution.Second); // Add EURUSD 1 second bars.
</pre>
	<pre class="python">self.AddEquity("SPY")  # Default to minute bars
self.AddForex("EURUSD", Resolution.Second) # Set second bars.
</pre>
</div>

<p>In live trading, you define the securities you want, but LEAN also gets the securities in your live portfolio and sets their resolution to the lowest resolution of the subscriptions you made. For example, if you create subscriptions in your algorithm for securities with Second, Minute, and Hour resolutions, the assets in your live portfolio are given a resolution of Second.</p>

<h4>Fundamental and Alternative Data</h4>

<p>To add alternative datasets to an algorithm, call the <code>AddData</code> method. For full examples, in the <a href="/docs/v2/writing-algorithms/datasets/overview">Datasets</a> chapter, select a dataset and see the <span class="page-section-name">Requesting Data</span> section.</p>

<h4>Custom Data</h4>

<p>To add custom custom data in an algorithm, call the <code>AddData</code> method. For more information about custom data, see <a href="https://www.quantconnect.com/docs/v2/writing-algorithms/importing-data/key-concepts">Importing Data</a>.</p>

<h4>Limitations</h4>
<p>There is no official limit to how much data
	you can add to an algorithm, but there are practical resource limitations. <br></p>
