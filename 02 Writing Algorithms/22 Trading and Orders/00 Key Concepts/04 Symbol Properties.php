<?php echo file_get_contents(DOCS_RESOURCES."/securities/symbolproperties.html"); ?>

<p>

</p>

<div class="section-example-container">
	<pre class="csharp">var symbolProperties = Securities["BTCUSD"].SymbolProperties;
var lotSize = symbolProperties.LotSize;
var minimumOrderSize = symbolProperties.MinimumOrderSize;
var minimumPriceVariation = symbolProperties.MinimumPriceVariation;
	</pre>
	<pre class="python">symbol_properties = Securities["BTCUSD"].SymbolProperties
lot_size = symbol_properties.LotSize
minimum_order_size = symbol_properties.MinimumOrderSize
minimum_price_variation = symbol_properties.MinimumPriceVariation
	</pre>
</div>

