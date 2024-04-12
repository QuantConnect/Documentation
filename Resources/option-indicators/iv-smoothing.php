<p>
    The default <a href="/docs/v2/writing-algorithms/securities/asset-classes/equity-options/greeks-and-implied-volatility/key-concepts#05-Volatility-Smoothing">IV smoothing</a> method uses the one contract in the pair that's at-the-money or out-of-money to calculate the IV.
    To change the smoothing function, pass a <code>mirrorOption</code> argument to the indicator method or constructor and then call the <code>SetSmoothingFunction</code> method of the <code>ImpliedVolatility</code> property of the indicator.
</p>
    
<div class="section-example-container">
    <pre class="csharp">// Example: average IV of the call-put pair.
_<?=$typeName?>.ImpliedVolatility.SetSmoothingFunction((iv, mirrorIv) => (iv + mirrorIv) * 0.5m);</pre>
    <pre class="python"># Example: average IV of the call-put pair.
self.<?=$typeName?>.ImpliedVolatility.SetSmoothingFunction(lambda iv, mirror_iv: (iv + mirror_iv) * 0.5)</pre>
</div>

<p>For more information about the IV smoothing function, see <a href="/docs/v2/writing-algorithms/securities/asset-classes/equity-options/greeks-and-implied-volatility/option-indicators#03-Implied-Volatility">Implied Volatility</a>.</p>
