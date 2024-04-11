<p>To perform <a href="/docs/v2/writing-algorithms/securities/asset-classes/equity-options/greeks-and-implied-volatility/key-concepts#05-Volatility-Smoothing">IV smoothing</a> on the underlying IV, you can call the <code>SetSmoothingFunction</code> method of the <code>ImpliedVolatility</code> property. 
Note that you must use the mirror-contract constructor. Refer to <a href="/docs/v2/writing-algorithms/securities/asset-classes/equity-options/greeks-and-implied-volatility/option-indicators#03-Implied-Volatility">Implied Volatility</a> for details</p>

<div class="section-example-container">
    <pre class="csharp">// example: take average of the call-put pair
_<?=$typeName?>.ImpliedVolatility.SetSmoothingFunction((iv, mirrorIv) => (iv + mirrorIv) * 0.5m);</pre>
    <pre class="python"># example: take average of the call-put pair
self.<?=$typeName?>.implied_volatility.set_smoothing_function(lambda iv, mirror_iv: (iv + mirror_iv) * 0.5)</pre>
</div>
