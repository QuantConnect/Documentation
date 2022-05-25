<?php echo file_get_contents(DOCS_RESOURCES."/securities/symbol_properties.html"); ?>
<p>
The <code>SymbolProperties</code> is a property of the <code>Security</code> object that provides three important values for calculating the order quantity: <code>LotSize</code>, <code>MinimumOrderSize</code>, and <code>MinimumPriceVariation</code>:
</p>

<div class="section-example-container">
	<pre class="csharp">var symbolProperties = Securities["BTCUSD"].SymbolProperties;
var lotSize = symbolProperties.LotSize;
var minimumOrderSize = symbolProperties.MinimumOrderSize;
var minimumPriceVariation = symbolProperties.MinimumPriceVariation;
	</pre>
	<pre class="python">symbol_properties = self.Securities["BTCUSD"].SymbolProperties
lot_size = symbol_properties.LotSize
minimum_order_size = symbol_properties.MinimumOrderSize
minimum_price_variation = symbol_properties.MinimumPriceVariation
	</pre>
</div>
<p>
These properties are used by the algorithm to prevent invalid orders from being placed or by helper methods to calculate order quantities for a given target.
</p>