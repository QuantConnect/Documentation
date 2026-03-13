<p>The probability that the estimated Sharpe ratio of an algorithm is greater than a benchmark.</p>

<p>It is calculated as</p>

\[ P\left(\hat{SR} > SR^{\ast}\right) = CDF\left(\frac{(\hat{SR} - SR^{\ast})\sqrt{n-1}}{\sqrt{1 - \hat{\gamma}_{3}\hat{SR} + \frac{\hat{\gamma}_{4}-1}{4}\hat{SR}^{2}}}\right) \]

<p>where $SR^{\ast}$ is the Sharpe ratio of the benchmark, $\hat{SR}$ is the Sharpe ratio of the algorithm, $n$ is the number of trading days, $\hat{\gamma}_{3}$ is the skewness of the algorithm's returns, $\hat{\gamma}_{4}$ is the kurtosis of the algorithm's returns, and $CDF$ is the normal cumulative distribution function. For more information about the PSR, see <a rel='nofollow' target='_blank' href='https://papers.ssrn.com/sol3/papers.cfm?abstract_id=1821643'>Bailey and L&oacute;pez de Prado (2012)</a>.</p>

<?php if (!empty($includeScript)) { ?>
<script type="text/x-mathjax-config">
    MathJax.Hub.Config({tex2jax: {inlineMath: [['$','$'], ['\(','\)']]}});
</script>
<script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-AMS-MML_HTMLorMML">
</script>
<?php } ?>
