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
<tr><td>Call</td><td>19.6</td><td>835.00</td></tr>
<tr><td>Put</td><td>21.4</td><td>835.00</td></tr>
<tr><td>Underlying Equity at early (2017-05-15) call assignment</td><td>932.22</td><td>-</td></tr>
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
&amp; = &amp; 99.01\\
P^{ATM}_T &amp; = &amp; (K^{P} - S_T)^{+}\\
&amp; = &amp; (835.00-934.01)^{+}\\
&amp; = &amp; 0\\
P_T &amp; = &amp; (-C^{ATM}_T - P^{ATM}_T + C^{ATM}_0 + P^{ATM}_0)\times m - fee\\
&amp; = &amp; (-99.01-0+19.6+21.4)\times 100 - 2.00\\
&amp; = &amp; -5803
\end{array}
$$<br>

<p>So, the strategy loses $5,277. The early assigment doesn't influence the payoff.</p>

<?
$optionStrategyName = "a short straddle";
$pythonBacktestHash = "31f30d2a842c6cf2e398e3ec8be85a57" ;
$csharpBacktestHash = "fbbc80c0ae7217a6ab626738d1c59e30" ;
include(DOCS_RESOURCES."/trading-and-orders/option-strategy-embedded-backtest.php"); 
?>