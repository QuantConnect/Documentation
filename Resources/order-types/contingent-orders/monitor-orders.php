<p>Each order in the set has its own <a href="/docs/v2/writing-algorithms/trading-and-orders/order-management/order-tickets">order ticket</a>, which fills and closes independently. To get the relationships of an order, use the <code class="csharp">Contingency</code><code class="python">contingency</code> property of the order ticket.<? if ($hasParent) { ?> A child order that waits for its parent to fill has the <code class="csharp">OrderStatus.Submitted</code><code class="python">OrderStatus.SUBMITTED</code> status. To check if the brokerage still holds the order, use the <code class="csharp">IsWaitingForTrigger</code><code class="python">is_waiting_for_trigger</code> property of the contingency.<? } ?></p>

<div class="section-example-container">
<pre class="csharp">foreach (var ticket in tickets)
{
    Debug($"{ticket.OrderId} held: {ticket.Contingency.IsWaitingForTrigger}; Quantity filled: {ticket.QuantityFilled}");
}</pre>
<pre class="python">for ticket in tickets:
    self.debug(f"{ticket.order_id} held: {ticket.contingency.is_waiting_for_trigger}; Quantity filled: {ticket.quantity_filled}")</pre>
</div>

<p>The <code class="csharp">Contingency</code><code class="python">contingency</code> property is an <code>OrderContingency</code> object, which has the following attributes:</p>
<div data-tree='QuantConnect.Orders.OrderContingency'></div>

<p>The <code class="csharp">Links</code><code class="python">links</code> property holds a <code>ContingencyLink</code> object for each relationship of the order, which has the following attributes:</p>
<div data-tree='QuantConnect.Orders.ContingencyLink'></div>

<p>The <code class="csharp">GetOpenOrdersRemainingQuantity</code><code class="python">get_open_orders_remaining_quantity</code> method of the <a href='/docs/v2/writing-algorithms/trading-and-orders/order-management/transaction-manager#06-Get-Remaining-Order-Quantity'>transaction manager</a> excludes the orders that wait for their parent to fill.<? if ($hasSiblings) { ?> At most one order of an OCO or OUO set fills, so the method includes only the largest remaining quantity of each set.<? } ?></p>
