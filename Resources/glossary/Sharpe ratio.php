<p>A measure of the risk-adjusted return, developed by William Sharpe.</p>

<p>It is calculated as</p>

$$
SR = \frac{E[R_p - R_b]}{\sigma_p}
$$

<p>where $R_p$ is the return of the portfolio, $R_b$ is the return of the benchmark, and $\sigma_p$ is the standard deviation of the portfolio's excess returns. By default, LEAN uses a 0% risk-free rate, so $R_b = 0$. For more information about the Sharpe ratio, see <a rel='nofollow' target='_blank' href='https://web.stanford.edu/~wfsharpe/art/sr/sr.htm'>Sharpe (1994)</a>.</p>

<?php if (!empty($includeScript)) { ?>
<script type="text/x-mathjax-config">
    MathJax.Hub.Config({tex2jax: {inlineMath: [['$','$'], ['\(','\)']]}});
</script>
<script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-AMS-MML_HTMLorMML">
</script>
<?php } ?>
