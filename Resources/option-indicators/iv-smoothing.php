<p>
    The default <a href="<?=$ivLink?>">IV smoothing</a> method uses the one contract in the pair that's at-the-money or out-of-money to calculate the IV.
    To change the smoothing function, pass a <code class="csharp">mirrorOption</code><code class="python">mirror_option</code> argument to the indicator method or constructor and then call the <code class="csharp">SetSmoothingFunction</code><code class="python">set_smoothing_function</code> method of the <code class='csharp'>ImpliedVolatility</code><code class='python'>implied_volatility</code> property of the indicator.
</p>
    
<div class="section-example-container">
    <pre class="csharp">// Example: Average IV of the call-put pair.
<?=$typeName?>Indicator.ImpliedVolatility.SetSmoothingFunction((iv, mirrorIv) => (iv + mirrorIv) * 0.5m);</pre>
    <pre class="python"># Example: Average IV of the call-put pair.
<?=$typeName?>_indicator.implied_volatility.set_smoothing_function(lambda iv, mirror_iv: (iv + mirror_iv) * 0.5)</pre>
</div>

