<script type="text/x-mathjax-config">
    MathJax.Hub.Config({tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}});
</script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-AMS-MML_HTMLorMML">
</script>
<p>The following table shows the price details of the assets in the algorithm:</p>

<table class="table qc-table" id="payoff-table">
<thead>
<tr><th>Asset</th><th>Price ($)</th><th>Strike ($)</th></tr>
</thead>
<tbody>
<tr><td>Call</td><td>3.35</td><td>185.00</td></tr>
<tr><td>Underlying Equity at expiration</td><td>190.01</td><td>-</td></tr>
</tbody>
</table>

<style>
#payoff-table td:nth-child(2), 
#payoff-table th:nth-child(2), 
#payoff-table td:nth-child(3), 
#payoff-table th:nth-child(3) {
    text-align: right;
}
</style>

<p>Therefore, the payoff is</p>
$$
\begin{array}{rcll}
C^{K}_T &amp; = &amp; (S_T - K)^{+}\\
&amp; = &amp; (190.01 - 185)^{+}\\
&amp; = &amp; 5.01\\
P_T &amp; = &amp; (C^{K}_0 - C^{K}_T)\times m - fee\\
&amp; = &amp; (3.35 - 5.01)\times m - fee\\
&amp; = &amp; -1.66 \times 100 - 2\\
&amp; = &amp; -167
\end{array}
$$
<br>
<p>So, the strategy loses $167.</p>

<?
$optionStrategyName = "a naked call";
$pythonBacktestHash = "5bd322fd0b49580b221946de87e6e99a" ;
$csharpBacktestHash = "7c9299e0c62c4adacebc41acb63a771c" ;
include(DOCS_RESOURCES."/trading-and-orders/option-strategy-embedded-backtest.php"); 
?>