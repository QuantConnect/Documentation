<p>When you update or cancel an order, LEAN returns an <code>OrderReponse</code> object, which have the following attributes:</p>
<div data-tree="QuantConnect.Orders.OrderResponse"></div>

<p>If your order changes fail, check the <code>ErrorCode</code> or <code>ErrorMessage</code>. For more information about specific order errors, see the <a href="/docs/v2/writing-algorithms/trading-and-orders/order-errors#20-Order-Response-Error-Reference">Order Response Error Reference</a>.</p>

<p>To get most recent order response, call the <code>GetMostRecentOrderResponse</code> method.</p>
<div class="section-example-container">
<pre class="csharp">var response = ticket.GetMostRecentOrderResponse();</pre>
<pre class="python">response = ticket.GetMostRecentOrderResponse()</pre>
</div>
