<p>LEAN models buying power and margin calls to ensure your algorithm stays within the margin requirements.</p>

<h4>Leverage</h4>
<p>In backtests, the default leverage is 1. To change the amount of leverage you can use for a security, pass a leverage argument to the <code>AddOptionContract</code> method.</p>

<div class="section-example-container">
    <pre class="csharp">_symbol = AddOptionContract(contractSymbol, leverage: 2).Symbol;</pre>
    <pre class="python">self.symbol = AddOptionContract(contract_symbol, leverage=1).Symbol</pre>
</div>

<p>In live trading, the brokerage determines how much leverage you may use. For more information about the leverage they provide, see <a href='/docs/v2/our-platform/live-trading/brokerages'>Brokerages</a>.</p>


<?php include(DOCS_RESOURCES."/brokerages/margin-calls.html"); ?>
