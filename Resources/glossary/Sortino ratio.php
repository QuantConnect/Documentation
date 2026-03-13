<p>A measure of the risk-adjusted return, developed by Frank Sortino. Unlike the Sharpe ratio, the Sortino ratio only penalizes returns falling below a user-specified target or required rate of return.</p>

<p>It is calculated as</p>

$$
S = \frac{R_p - R_f}{\sigma_d}
$$

<p>where $R_p$ is the portfolio return, $R_f$ is the risk-free rate, and $\sigma_d$ is the downside deviation (the standard deviation of negative returns only). By using downside deviation, the Sortino ratio does not penalize upside volatility.</p>

<?php if (!empty($includeScript)) { ?>
<script type="text/x-mathjax-config">
    MathJax.Hub.Config({tex2jax: {inlineMath: [['$','$'], ['\(','\)']]}});
</script>
<script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-AMS-MML_HTMLorMML">
</script>
<?php } ?>
