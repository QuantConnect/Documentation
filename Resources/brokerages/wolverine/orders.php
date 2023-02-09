
<h4>Order Types</h4>
<p><?= $cloudPlatform ? "Our Wolverine Execution Services integration" : "The <code>WolverineBrokerageModel</code>" ?> supports <a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/market-orders'>market orders</a>.</p>

<h4>Updates</h4>
<p><?= $writingAlgorithms ? "The <code>WolverineBrokerageModel</code> doesn't support" : "We model the Wolverine Execution Services API by not supporting" ?> order updates.</p>

<h4>Extended Market Hours</h4>
<p><?= $cloudPlatform ? "Wolverine Execution Services" : "The <code>WolverineBrokerageModel</code>" ?> doesn't support extended market hours trading. If you place an order outside of regular trading hours, the order is invalid.</p>

