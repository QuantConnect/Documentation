<p>The largest peak to trough decline in an algorithm's equity curve.</p>

<p>It is calculated as</p>

$$
1 - \frac{v^{t \ge s}_{\text{min}}}{v^s_{\text{max}}}
$$

<p>where $v^s_{\text{max}}$ is the maximum equity value up to time $s$ and $v^{t \ge s}_{\text{min}}$ is the minimum equity value at time $t$ where $t \ge s$.</p>

<?php if (!empty($includeScript)) { ?>
<script type="text/x-mathjax-config">
    MathJax.Hub.Config({tex2jax: {inlineMath: [['$','$'], ['\(','\)']]}});
</script>
<script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-AMS-MML_HTMLorMML">
</script>
<?php } ?>
