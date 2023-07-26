<script type="text/x-mathjax-config">
    MathJax.Hub.Config({tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}});
</script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-AMS-MML_HTMLorMML">
</script>

<p>The following table shows the price details of the assets in the algorithm at Option expiration (2017-05-20):</p>

<table class="table qc-table" id="payoff-table">
<thead>
<tr><th>Asset</th><th>Price ($)</th><th>Strike ($)</th></tr>
</thead>
<tbody>
<tr><td>Call</td><td>22.30</td><td>835.00</td></tr>
<tr><td>Put</td><td>23.90</td><td>835.00</td></tr>
<tr><td>Underlying Equity at expiration</td><td>934.01</td><td>-</td></tr>
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
C^{ATM}_T &amp; = &amp; (S_T - K^{C})^{+}\\
&amp; = &amp; (934.01-835.00)^{+}\\
&amp; = &amp; 98.99\\
P^{ATM}_T &amp; = &amp; (K^{P} - S_T)^{+}\\
&amp; = &amp; (835.00-934.01)^{+}\\
&amp; = &amp; 0\\
P_T &amp; = &amp; (C^{ATM}_T + P^{ATM}_T - C^{ATM}_0 - P^{ATM}_0)\times m - fee\\
&amp; = &amp; (98.99+0-22.3-23.9)\times100-1.00\times2\\
&amp; = &amp; 5277
\end{array}
$$<br>

<p>So, the strategy gains $5,277.</p>

<p>The following algorithm implements a long straddle Option strategy:</p>

<?
$optionStrategyName = "a long straddle";
$pythonBacktestHash = "26487a7d8b247490e6ae365a9344c17a" ;
$csharpBacktestHash = "f52ce1b96d83d7e41f40139e429e220d" ;
include(DOCS_RESOURCES."/trading-and-orders/option-strategy-embedded-backtest.php"); 
?>