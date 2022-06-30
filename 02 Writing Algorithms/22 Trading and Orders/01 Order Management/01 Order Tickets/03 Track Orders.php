<p>As the state or your order updates over time, your order ticket automatically updates. To track an order, you can check any of the preceding order ticket properties.</p>

<p>To get an order field, call the <code>Get</code> method with an <code>OrderField</code>.</p> 

<div class="section-example-container">
<pre class="csharp">var limitPrice = orderTicket.Get(OrderField.LimitPrice);</pre>
<pre class="python">limit_price = order_ticket.Get(OrderField.LimitPrice)</pre>
</div>

<p>The <code>OrderField</code> enumeration has the following members:</p>
<div data-tree="QuantConnect.Orders.OrderField"></div>


<p>In addition to using order tickets to track orders, you can receive <a href='/docs/v2/writing-algorithms/trading-and-orders/order-events'>order events</a> through the <code>OnOrderEvent</code> event handler.</p>
