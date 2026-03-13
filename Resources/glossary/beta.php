<p>The scale and direction of an algorithm's returns relative to movements in the underlying benchmark.</p>

<p>It is calculated as</p>

$$
\beta = \frac{\text{Cov}(R_p,\, R_b)}{\text{Var}(R_b)}
$$

<p>where $R_p$ is the portfolio return and $R_b$ is the benchmark return. A beta of 1 indicates the algorithm moves in lockstep with the benchmark, a beta greater than 1 indicates amplified movements, and a negative beta indicates inverse movements.</p>

<?php if (!empty($includeScript)) { ?>
<script type="text/x-mathjax-config">
    MathJax.Hub.Config({tex2jax: {inlineMath: [['$','$'], ['\(','\)']]}});
</script>
<script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-AMS-MML_HTMLorMML">
</script>
<?php } ?>
