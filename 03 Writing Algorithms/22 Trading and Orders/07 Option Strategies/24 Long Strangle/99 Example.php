<script type="text/x-mathjax-config">
    MathJax.Hub.Config({tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}});
</script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-AMS-MML_HTMLorMML">
</script>

<p>The following table shows the price details of the assets in the algorithm at Option expiration (2017-04-22):</p>

<table class="table qc-table" id="payoff-table">
<thead>
<tr><th>Asset</th><th>Price ($)</th><th>Strike ($)</th></tr>
</thead>
<tbody>
<tr><td>Call</td><td>8.80</td><td>835.00</td></tr>
<tr><td>Put</td><td>9.50</td><td>832.50</td></tr>
<tr><td>Underlying Equity at expiration</td><td>843.19</td><td>-</td></tr>
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
C^{OTM}_T &amp; = &amp; (S_T - K^{C})^{+}\\
&amp; = &amp; (843.19-835.00)^{+}\\
&amp; = &amp; 8.19\\
P^{OTM}_T &amp; = &amp; (K^{P} - S_T)^{+}\\
&amp; = &amp; (832.50-843.19)^{+}\\
&amp; = &amp; 0\\
P_T &amp; = &amp; (C^{OTM}_T + P^{OTM}_T - C^{OTM}_0 - P^{OTM}_0)\times m - fee\\
&amp; = &amp; (8.19+0-8.80-9.50)\times100-2.00\times2\\
&amp; = &amp; -1013
\end{array}
$$<br>

<p>So, the strategy losses $1,013.</p>

<?
$optionStrategyName = "a long straddle";
$pythonBacktestHash = "546a7e1f6f0ba85d0394e2b147ff43aa" ;
$csharpBacktestHash = "126b5aeb4be7a8bc2e15e10f54ee4894" ;
include(DOCS_RESOURCES."/trading-and-orders/option-strategy-embedded-backtest.php"); 
?>