<p>The <code class="csharp">SymbolProperties</code><code class="python">symbol_properties</code> are a property of the <code>Security</code> object. LEAN uses some of the <code class="csharp">SymbolProperties</code><code class="python">symbol_properties</code> to prevent invalid orders, and to <a href='/docs/v2/writing-algorithms/trading-and-orders/position-sizing'>calculate order quantities</a> for a given target. </p>

<p><code>SymbolProperties</code> objects have the following properties:</p>
<div data-tree='QuantConnect.Securities.SymbolProperties'></div>

<p>To get the <code class="csharp">SymbolProperties</code><code class="python">symbol_properties</code>, use the property on the <code>Security</code> object.</p>

<div class="section-example-container">
	<pre class="csharp">var symbolProperties = Securities["BTCUSD"].SymbolProperties;
var lotSize = symbolProperties.LotSize;
var minimumOrderSize = symbolProperties.MinimumOrderSize;
var minimumPriceVariation = symbolProperties.MinimumPriceVariation;</pre>
	<pre class="python">symbol_properties = self.securities["BTCUSD"].symbol_properties
lot_size = symbol_properties.lot_size
minimum_order_size = symbol_properties.minimum_order_size
minimum_price_variation = symbol_properties.minimum_price_variation</pre>
</div>

<p>LEAN uses the <code class="csharp">MinimumPriceVariation</code><code class="python">minimum_price_variation</code> to round the <code class="csharp">LimitPrice</code><code class="python">limit_price</code>, <code class="csharp">StopPrice</code><code class="python">stop_price</code>, and the <code class="csharp">TriggerPrice</code><code class="python">trigger_price</code>.</p>