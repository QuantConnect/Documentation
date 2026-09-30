<?
$orderType = "contingent orders";
$brokerageNameCs = "QuantConnectBrokerage"; $brokerageNamePy = "QUANTCONNECT_BROKERAGE";
include(DOCS_RESOURCES."/order-types/brokerage-restrictions.php");
?>
<p>The following brokerages support contingent orders:</p>

<ul>
    <li><a href='/docs/v2/writing-algorithms/reality-modeling/brokerages/supported-models/alpaca#03-Orders'>Alpaca</a></li>
    <li><a href='/docs/v2/writing-algorithms/reality-modeling/brokerages/supported-models/binance#03-Orders'>Binance</a></li>
    <li><a href='/docs/v2/writing-algorithms/reality-modeling/brokerages/supported-models/charles-schwab#03-Orders'>Charles Schwab</a></li>
    <li><a href='/docs/v2/writing-algorithms/reality-modeling/brokerages/supported-models/interactive-brokers#03-Orders'>Interactive Brokers</a></li>
    <li><a href='/docs/v2/writing-algorithms/reality-modeling/brokerages/supported-models/tradestation#03-Orders'>TradeStation</a></li>
</ul>

<p>Brokerages that support contingent orders can limit the contingency types, the number of orders in a set, the securities in a set, combo order members, and chains. If the brokerage model doesn't support a set of contingent orders, LEAN invalidates every order in the set.</p>
