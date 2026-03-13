<p>The expected loss of a portfolio beyond the Value at Risk threshold, also known as Conditional Value at Risk (CVaR).</p>

<p>Assuming normally distributed returns, it is calculated as</p>

$$
\text{TVaR}_{\alpha} = \mu + \sigma \times \frac{\phi[\Phi^{-1}(\alpha)]}{1 - \alpha}
$$

<p>where $\mu$ is the mean return, $\sigma$ is the standard deviation, $\alpha$ is the confidence level, $\phi$ is the standard normal density function, and $\Phi^{-1}$ is the inverse of the standard normal cumulative distribution function.</p>

<?php if (!empty($includeScript)) { ?>
<script type="text/x-mathjax-config">
    MathJax.Hub.Config({tex2jax: {inlineMath: [['$','$'], ['\(','\)']]}});
</script>
<script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-AMS-MML_HTMLorMML">
</script>
<?php } ?>
