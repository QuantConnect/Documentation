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
<tr><td>Put</td><td>1.37</td><td>185.00</td></tr>
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
P^{K}_T &amp; = &amp; (K - S_T)^{+}\\
&amp; = &amp; (185 - 190.01)^{+}\\
&amp; = &amp; 0.0\\
P_T &amp; = &amp; (P^{K}_0 - P^{K}_T)\times m - fee\\
&amp; = &amp; (1.37 - 0.0)\times m - fee\\
&amp; = &amp; 1.37 \times 100 - 1\\
&amp; = &amp; 136
\end{array}
$$
<br>
<p>So, the strategy gains $136.</p>

<?
$optionStrategyName = "a naked put";
$pythonBacktestHash = "66b70758a7fa2e0685d081ab6d6921b3" ;
$csharpBacktestHash = "e3654011cfc55607efdb6cabba7bb2ef" ;
include(DOCS_RESOURCES."/trading-and-orders/option-strategy-embedded-backtest.php"); 
?>