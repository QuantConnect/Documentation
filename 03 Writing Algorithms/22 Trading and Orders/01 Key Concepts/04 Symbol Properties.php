<p>The <code>SymbolProperties</code> are a property of the <code>Security</code> object. LEAN uses some of the <code>SymbolProperties</code> to prevent invalid orders, and to <a href='/docs/v2/writing-algorithms/trading-and-orders/position-sizing'>calculate order quantities</a> for a given target. </p>

<p><code>SymbolProperties</code> objects have the following properties:</p>
<div data-tree='QuantConnect.Securities.SymbolProperties'></div>

<p>To get the <code>SymbolProperties</code>, use the property on the <code>Security</code> object.</p>

<div class="section-example-container">
	<pre class="csharp">var symbolProperties = Securities["BTCUSD"].SymbolProperties;
var lotSize = symbolProperties.LotSize;
var minimumOrderSize = symbolProperties.MinimumOrderSize;
var minimumPriceVariation = symbolProperties.MinimumPriceVariation;</pre>
	<pre class="python">symbol_properties = self.Securities["BTCUSD"].SymbolProperties
lot_size = symbol_properties.LotSize
minimum_order_size = symbol_properties.MinimumOrderSize
minimum_price_variation = symbol_properties.MinimumPriceVariation</pre>
</div>

<p>LEAN uses the <code>MinimumPriceVariation</code> to round the <code>LimitPrice</code>, <code>StopPrice</code>, and the <code>TriggerPrice</code>.</p>