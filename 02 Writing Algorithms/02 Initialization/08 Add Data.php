<p>You can subscribe to asset, fundamental, alternative, and custom data. The <a href="https://www.quantconnect.com/datasets">Dataset Market</a> provides <?php echo file_get_contents(DOCS_RESOURCES."/kpis/dataset-size.php"); ?> of data that you can easily import into your algorithms.</p>

<h4>Asset Data</h4>

<p>To subscribe to asset data, call one of the asset subscription methods like <code>AddEquity</code> or <code>AddForex</code>. Each asset class has its own method to create subscriptions. For more information about how to create subscriptions for each asset class, see <a href="https://www.quantconnect.com/docs/v2/writing-algorithms/asset-classes">Asset Classes</a>.</p>

<div class="section-example-container">
	<pre class="csharp">AddEquity("AAPL"); // Add Apple 1 minute bars (minute by default)
AddForex("EURUSD", Resolution.Second); // Add EURUSD 1 second bars
</pre>
	<pre class="python">self.AddEquity("SPY")  # Add Apple 1 minute bars (minute by default)
self.AddForex("EURUSD", Resolution.Second) # Add EURUSD 1 second bars
</pre>
</div>

<p>In live trading, you define the securities you want, but LEAN also gets the securities in your live portfolio and sets their resolution to the lowest resolution of the subscriptions you made. For example, if you create subscriptions in your algorithm for securities with Second, Minute, and Hour resolutions, the assets in your live portfolio are given a resolution of Second.</p>

<h4>Alternative Data</h4>

<p>To add alternative datasets to an algorithm, call the <code>AddData</code> method. For full examples, in the <a href="/docs/v2/writing-algorithms/datasets/overview">Datasets</a> chapter, select a dataset and see the <span class="page-section-name">Requesting Data</span> section.</p>

<h4>Custom Data</h4>

<p>To add custom data to an algorithm, call the <code>AddData</code> method. For more information about custom data, see <a href="https://www.quantconnect.com/docs/v2/writing-algorithms/importing-data/key-concepts">Importing Data</a>.</p>

<h4>Limitations</h4>
<p>There is no official limit to how much data	you can add to an algorithm, but there are practical resource limitations. Each security subscription requires about 5MB of RAM, so larger machines let you run algorithms with bigger universes. For more information about our cloud nodes, see <a href='/docs/v2/our-platform/organizations/resources'>Resources</a>.</p>
