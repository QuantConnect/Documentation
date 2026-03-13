<p><span class='qualifier'>(Call Option)</span> The strike price minus the price of an asset if the price is below the strike price, otherwise zero.</p>

<p>It is calculated as</p>

$$
OTM_{\text{call}} = \max(K - S,\, 0)
$$

<p><span class='qualifier'>(Put Option)</span> The price of an asset minus the strike price if the price is above the strike price, otherwise zero.</p>

<p>It is calculated as</p>

$$
OTM_{\text{put}} = \max(S - K,\, 0)
$$

<p>where $S$ is the current price of the underlying asset and $K$ is the strike price of the Option contract.</p>

<?php if (!empty($includeScript)) { ?>
<script type="text/x-mathjax-config">
    MathJax.Hub.Config({tex2jax: {inlineMath: [['$','$'], ['\(','\)']]}});
</script>
<script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-AMS-MML_HTMLorMML">
</script>
<?php } ?>
