<p>We model the IB API by supporting several order types, order properties, and order updates. When you deploy live algorithms, you can <a href='/docs/v2/cloud-platform/live-trading/algorithm-control#03-Place-Manual-Trades'>place manual orders</a> through the IDE.</p>


<?php include(DOCS_RESOURCES."/brokerages/interactive-brokers/orders.php"); ?>


<h4>Fill Time</h4>
<p>IB has a 400 millisecond fill time for live orders.</p>

<h4>Brokerage Liquidation</h4>
<p>When IB liquidates part of your position, you receive an <a href='/docs/v2/writing-algorithms/trading-and-orders/order-events'>order event</a> that contains the <code>Brokerage Liquidation</code> message.</p>
