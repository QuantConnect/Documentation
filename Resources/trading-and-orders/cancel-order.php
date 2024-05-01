<p>To cancel a <?=$orderType?> order, call the <code class="csharp">Cancel</code><code class="python">cancel</code> method on the <code>OrderTicket</code>. If you don't have the order ticket, <a href='/docs/v2/writing-algorithms/trading-and-orders/order-management/transaction-manager#02-Get-a-Single-Order-Ticket'>get it from the transaction manager</a>. The <code class="csharp">Cancel</code><code class="python">cancel</code> method returns an <code>OrderResponse</code> object to signal the success or failure of the cancel request.</p>

<div class="section-example-container">
<pre class="csharp">var response = ticket.Cancel("Cancelled trade");
if (response.IsSuccess)
{
    Debug("Order successfully cancelled");
}</pre>
<pre class="python">response = ticket.cancel("Cancelled Trade")
if response.is_success:
    self.debug("Order successfully cancelled")</pre>
</div>

<p>When you cancel an order, LEAN creates a <code>CancelOrderRequest</code>, which have the following attributes:</p>
<div data-tree='QuantConnect.Orders.CancelOrderRequest'></div>

<p>To get the <code>CancelOrderRequest</code> for an order, call the <code class="csharp">CancelRequest</code><code class="python">cancel_order_request</code> method on the order ticket. The method returns <code class='csharp'>null</code><code class='python'>None</code> if the order hasn't been cancelled.</p>

<div class="section-example-container">
<pre class="csharp">var request = ticket.cancel_order_request();</pre>
<pre class="python">request = ticket.cancel_order_request()</pre>
</div>