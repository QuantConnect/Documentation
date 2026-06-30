<p>
	In local deployments, <a href='/docs/v2/writing-algorithms/universes/key-concepts'>universe selection</a> is available with the <?=$dataProviderName?> data provider if you download the data from the <a href='/datasets'>Dataset Market</a>. 
	The dataset listings show how to download the universe selection data with the CLI. 
	For live trading, you'll need to periodically download the new data from QuantConnect Cloud, which you can automate with Python scripts. 
	For example, the following tutorials explain how to download historical data and download daily updates:
</p>

<ul>
	<li><a href='/docs/v2/lean-cli/datasets/quantconnect/us-equity-coarse-fundamental'>US Equity Coarse Universe</a></li>	
	<li><a href='/docs/v2/lean-cli/datasets/quantconnect/equity-options#02-Prerequisites'>US Equity Option Universe</a></li>
	<li><a href='/docs/v2/lean-cli/datasets/quantconnect/us-etf-constituents'>US ETF Constituents</a></li>
	<li><a href='/docs/v2/lean-cli/datasets/quantconnect/index-options#02-Prerequisites'>US Index Option Universe</a></li>
</ul>

<p>In cloud deployments, QuantConnect Cloud provides the universe selection datasets.</p>
