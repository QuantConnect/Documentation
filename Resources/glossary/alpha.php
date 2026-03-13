<p>The excess return of an algorithm relative to its benchmark after adjusting for risk. Alpha measures the value added by the algorithm's strategy beyond what could be explained by exposure to market movements.</p>

<p>It is calculated as</p>

$$
\alpha = R_p - \left[ R_f + \beta \left( R_b - R_f \right) \right]
$$

<p>where $R_p$ is the portfolio return, $R_f$ is the risk-free rate, $\beta$ is the portfolio's beta, and $R_b$ is the benchmark return. A positive alpha indicates the algorithm outperformed its risk-adjusted benchmark.</p>

<?php if (!empty($includeScript)) { ?>
<script type="text/x-mathjax-config">
    MathJax.Hub.Config({tex2jax: {inlineMath: [['$','$'], ['\(','\)']]}});
</script>
<script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-AMS-MML_HTMLorMML">
</script>
<?php } ?>
