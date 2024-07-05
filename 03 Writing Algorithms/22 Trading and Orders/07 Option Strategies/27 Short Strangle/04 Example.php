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
<tr><td>Call</td><td>8.00</td><td>835.00</td></tr>
<tr><td>Put</td><td>7.40</td><td>832.50</td></tr>
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
P_T &amp; = &amp; (-C^{OTM}_T - P^{OTM}_T + C^{OTM}_0 + P^{OTM}_0)\times m - fee\\
&amp; = &amp; (-8.19-0+8.00+7.40)\times100-2.00\times2\\
&amp; = &amp; 719
\end{array}
$$<br>

<p>So, the strategy gains $719.</p>

<?
$optionStrategyName = "a short straddle";
$pythonBacktestHash = "5e1918d3717bf9adc9a797695d2bd518" ;
$csharpBacktestHash = "50ec754950edc4d94d0b441d86540989" ;
include(DOCS_RESOURCES."/trading-and-orders/option-strategy-embedded-backtest.php"); 
?>