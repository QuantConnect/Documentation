<?
$orderType = "contingent orders";
$brokerageNameCs = "QuantConnectBrokerage"; $brokerageNamePy = "QUANTCONNECT_BROKERAGE";
include(DOCS_RESOURCES."/order-types/brokerage-restrictions.php");
?>
<p>Brokerages that support contingent orders can limit the contingency types, the number of orders in a set, the securities in a set, combo order members, and chains. If the brokerage model doesn't support a set of contingent orders, LEAN invalidates every order in the set.</p>
